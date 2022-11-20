<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductInventoryMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ProductInventoryMovementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductInventoryMovement::truncate();

        Model::unguard();
        $products = Product::all();

        foreach ($products as $product) {
            foreach($product->variants as $variant) {
                for($i=0; $i < 3; $i++){
                    ProductInventoryMovement::create([
                        'product_id' => $product->id,
                        'product_variant_id' => $variant->id,
                        'type' => 'Purchase',
                        'quantity' => 10
                    ]);
                    ProductInventoryMovement::create([
                        'product_id' => $product->id,
                        'type' => 'Application',
                        'product_variant_id' => $variant->id,
                        'quantity' => 3
                    ]);
                }
            }
        }

        Model::reguard();
    }
}
