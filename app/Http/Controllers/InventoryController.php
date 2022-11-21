<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\InventoryTrait;
use App\Models\Product;
use App\Models\ProductInventoryMovement;
use App\Models\ProductVariant;
use App\Resources\ProductResource;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryController extends ApiController
{
    use InventoryTrait;

    const PURCHASE = 'Purchase';
    const APPLICATION = 'Application';

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('pages.takeout-form');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param ProductVariant $variant
     * @return JsonResponse
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
     * @return Application|Factory|View
     */
    public function takeoutInventory(Request $request)
    {
        $request->validate([
            'quantity' => 'required',
            'product' => 'required'
        ]);

        $product = Product::findOrFail($request->product);
        $variants = $product->variants()
            ->orderBy('created_at', 'ASC')
            ->get();
        $applicationArray = [];
        $applicationQuantityLeft = $request->quantity;

        $currentInventoryQty = $this->getTotalProductInventory($product);

        //Check if the current product inventory is less than the requested application inventory
        if($currentInventoryQty < $request->quantity) {
            return view('pages.takeout-form')->with('error', [
                'code' => '400',
                'message'=>'The quantity to be applied exceeds the available quantity on hand'
            ]);
        }

        // Loop through the variants
        foreach ($variants as $variant) {

            // Get the total count of inventories per variant
            $variantCurrentTotalQty = $this->getTotalVariantInventory($variant);

            // Check if the current variant has inventory available, if not skip to the next available
            if($variantCurrentTotalQty !== 0) {
                // Check if the quantity to be applied still has remaining items to be deducted from
                // the available inventories
                if($applicationQuantityLeft !== 0) {
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
                        'variant_id' => $variant->id,
                        'product_sku' => $variant->sku
                    ];
                }
            }
        }

        return view('pages.takeout-form')
            ->with('applicationArray', $applicationArray);
    }
}
