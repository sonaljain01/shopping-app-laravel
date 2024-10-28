<?php

namespace Database\Factories;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Brand;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'category_id' => Category::inRandomOrder()->first()->id,
            'slug' => $this->faker->slug(),
            'sku' => $this->faker->ean8(),
            'track_qty' => $this->faker->boolean() ? 'Yes' : 'No',
            'qty' => $this->faker->numberBetween(1, 10),
            'brand_id' => Brand::inRandomOrder()->first()->id,
            'status' => $this->faker->randomElement([0, 1]),
            'is_featured' => $this->faker->boolean() ? 'Yes' : 'No',
            'compare_price' => $this->faker->randomFloat(2, 10, 500),
            'barcode' => $this->faker->ean8(),

        ];
    }
}
