<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\InventoryTrait;
use App\Models\Product;
use App\Models\ProductInventoryMovement;
use App\Models\ProductVariant;
use App\Resources\ProductResource;
use Illuminate\Http\Request;

class InventoryController extends ApiController
{
    use InventoryTrait;

    const PURCHASE = 'Purchase';
    const APPLICATION = 'Application';

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param ProductVariant $variant
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, ProductVariant $variant)
    {
        $request->validate([
            'type' => 'string|max:255',
            'quantity' => ''
        ]);

        ProductInventoryMovement::create([
            'product_id' => $variant->product->id,
            'product_variant_id' => $variant->id,
            'type' => $request->type,
            'quantity' => $request->quantity
        ]);

        return $this->respondSuccess(ProductResource::make($variant->product));
    }

    /**
     * Logic for taking out/application of inventory
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function takeoutInventory(Request $request, Product $product)
    {
        $request->validate([
            'applicationQuantity' => 'required',
        ]);

        $variants = $product->variants()
            ->orderBy('created_at', 'ASC')
            ->get();
        $applicationArray = [];
        $applicationQuantityLeft = $request->applicationQuantity;

        $currentInventoryQty = $this->getTotalProductInventory($product);

        if($currentInventoryQty < $request->applicationQuantity) {
            return $this->respondWithError(['The quantity to be applied exceeds the available quantity on hand']);
        }

        // Loop through the variants
        foreach ($variants as $variant) {
            // Get the total count of inventories per variant
            $variantCurrentTotalQty = $this->getTotalVariantInventory($variant);

            // Check if the current variant has inventory available, if not skip to the next available
            if($variantCurrentTotalQty === 0) {
                continue;
            } else {

                // Check if the quantity to be applied still has remaining items to be deducted from
                // the available inventories
                if($applicationQuantityLeft === 0) {
                    exit;
                } else {

                    // Getting the difference between the total qty of the variant and the
                    // current total qty to be applied so that we'd be able to check if all
                    // the qty to be applied is already served
                    $diff = $variantCurrentTotalQty - $applicationQuantityLeft;

                    if($diff < 0) {
                        $variantUpdatedTotalQty = $applicationQuantityLeft - abs($variantCurrentTotalQty - $applicationQuantityLeft);
                    } else {
                        $variantUpdatedTotalQty = $applicationQuantityLeft;
                    }

                    // Add an entry to the database for the inventory movement
                    ProductInventoryMovement::create([
                        'product_id' => $variant->product->id,
                        'product_variant_id' => $variant->id,
                        'type' => 'Application',
                        'quantity' => $variantUpdatedTotalQty
                    ]);

                    // Updating the qty left
                    $applicationQuantityLeft  = $applicationQuantityLeft - $variantUpdatedTotalQty;

                    // Placed inside the query to be used on the frontend
                    $applicationArray[] = [
                        'applied' => $variantUpdatedTotalQty,
                        'price' => $variant->price,
                        'variant_id' => $variant->id
                    ];
                }
            }
        }

        return $this->respondSuccess([
            'applicationArray' => $applicationArray,
            'product' => ProductResource::make($product)
        ]);
    }
}
