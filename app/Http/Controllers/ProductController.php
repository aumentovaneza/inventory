<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('pages.inventory');
    }

    /**
     * @param Request $request
     * @return mixed
     */
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

    /**
     * @param Product $product
     * @return mixed
     */
    public function show(Product $product)
    {
        return $this->respondSuccess(ProductResource::make($product));
    }
}
