<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductVariantController extends ApiController
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'string|max:255',
            'price' => '',
            'type' => 'string|max:255',
            'quantity' => ''
        ]);

        $variant = ProductVariant::create([
            'sku' => $request->sku,
            'price' => $request->price
        ]);

        $product->variants()->save($variant);
        $variant->inventories()->save([
            'type' => $request->type,
            'quantity' => $request->quantity
        ]);

        return $this->respondSuccess(ProductResource::make($product));
    }
}
