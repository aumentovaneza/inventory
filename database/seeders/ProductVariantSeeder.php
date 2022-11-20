<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductVariant::truncate();

        $faker = fake();
        Model::unguard();

        $products = Product::all();

        foreach ($products as $product) {
            for($i=0; $i < 3; $i++){
                ProductVariant::create(['sku' => $faker->uuid, 'price' => $faker->numberBetween(1,20), 'product_id' => $product->id]);
            }
        }

        Model::reguard();
    }
}
