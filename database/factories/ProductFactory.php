<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Product::class;



    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(2),
            'sku' => $this->faker->unique()->ean13,
            'description' => $this->faker->paragraph,
            'long_description' => $this->faker->paragraphs(3, true),
            'image_src' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'discount' => $this->faker->randomFloat(2, 0, 20),
            'available' => $this->faker->boolean,
        ];
    }
}
