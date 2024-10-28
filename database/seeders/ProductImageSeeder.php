<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductImage;
use App\Models\Product;
class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        // Create a single image for each product
        foreach ($products as $product) {
            ProductImage::factory()->create([
                'product_id' => $product->id,
            ]);
        }
        
    }
}
