<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->create([
            'name' => 'Gucci Bag',
            'sku' => 'SKU001',
            'description' => 'Tiger edition',
            'long_description' => 'Long Description for Product 1',
            'category_id' => 1,
            // 'subcategory_id' => 1,
            'image_src' => "https://st2.depositphotos.com/3433891/6700/i/450/depositphotos_67007051-stock-photo-elegant-black-belt.jpg",
            'price' => '59.99',
            'discount' => '15.00',
            'available' => true,
        ]);

        Product::factory()->create([
            'name' => 'Leather Belt',
            'sku' => 'SKU002',
            'description' => 'Black Belt',
            'long_description' => 'Long Description for Product 2', // Updated description
            'category_id' => 2,
            // 'subcategory_id' => 2,
            'image_src' => "https://st2.depositphotos.com/3433891/6700/i/450/depositphotos_67007051-stock-photo-elegant-black-belt.jpg",
            'price' => '19.99',
            'discount' => '2.00',
            'available' => true,
        ]);

        Product::factory()->create([
            'name' => 'Rolex',
            'sku' => 'SKU003', // Unique SKU
            'description' => 'Diamond edition',
            'long_description' => 'Long Description for Product 3', // Updated description
            'category_id' => 3,
            // 'subcategory_id' => 4,
            'image_src' => "https://s.turbifycdn.com/aah/movadobaby/rolex-day-date-36-green-aventurine-dial-unisex-watch-128235-0068-5.jpg",
            'price' => '29999.99',
            'discount' => '180.00',
            'available' => true,
        ]);

        Product::factory()->create([
            'name' => 'Prada',
            'sku' => 'SKU004', // Unique SKU
            'description' => 'Black prada glasses',
            'long_description' => 'Long Description for Product 4', // Updated description
            'category_id' => 4,
            // 'subcategory_id' => 5,
            'image_src' => "https://assets.sunglasshut.com/is/image/LuxotticaRetail/8056597418478__STD__shad__qt.png?impolicy=SGH_bgtransparent&width=1000",
            'price' => '19.99',
            'discount' => '5.00',
            'available' => true,
        ]);
    }
}
