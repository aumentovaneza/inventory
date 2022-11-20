<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends ApiController
{

    public function index()
    {
        $product = Product::with(['variants', 'variants'])->get();

        return $this->respondSuccess(ProductResource::collection($product));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $product = Product::create([
            'name' => $request->name
        ]);

        return $this->respondSuccess(ProductResource::collection($product));
    }

    public function show(Product $product)
    {
        return $this->respondSuccess(ProductResource::make($product));
    }
}
