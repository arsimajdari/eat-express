<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     Subcategory::factory()->count(4)->has(
    //     Product::factory()
    //     ->count(4)->state(new Sequence(
    //         ['image_src' => "https://st2.depositphotos.com/3433891/6700/i/450/depositphotos_67007051-stock-photo-elegant-black-belt.jpg"],

    //         ['image_src' => "https://st2.depositphotos.com/3433891/6700/i/450/depositphotos_67007051-stock-photo-elegant-black-belt.jpg"],

    //         ['image_src' => "https://s.turbifycdn.com/aah/movadobaby/rolex-day-date-36-green-aventurine-dial-unisex-watch-128235-0068-5.jpg"],

    //         ['image_src' => "https://assets.sunglasshut.com/is/image/LuxotticaRetail/8056597418478__STD__shad__qt.png?impolicy=SGH_bgtransparent&width=1000"],
    //     )))
    //     ->create();

    // }

    // public function run()
    // {
    // $categories = Category::factory()->count(1)->create();
    // $subcategories = Subcategory::factory()->count(1)->create(['category_id' => $categories->first()->id]);

    // Product::factory(4)->create([
    //     'category_id' => $categories->first()->id,
    //     'subcategory_id' => $subcategories->first()->id,
    // ]);
    // }
    public function run()
    {
        // Manually specify categories and subcategories for each product

        // Bags category, Sales subcategory
        Product::factory(1)->create([
            'category_id' => Category::where('name', 'Bags')->first()->id,
            'subcategory_id' => Subcategory::where('name', 'Gucci')->first()->id,
            'image_src' => "https://image.harrods.com/gucci-ophidia-double-g-tote-bag_15371095_37633693_2048.jpg",

        ]);

        // Belts category, Sales subcategory
        Product::factory(1)->create([
            'category_id' => Category::where('name', 'Belts')->first()->id,
            'subcategory_id' => Subcategory::where('name', 'Hugo Boss')->first()->id,
            'image_src' => "https://st2.depositphotos.com/3433891/6700/i/450/depositphotos_67007051-stock-photo-elegant-black-belt.jpg",

        ]);

        // Wallets category, Sales subcategory
        Product::factory(1)->create([
            'category_id' => Category::where('name', 'Wallets')->first()->id,
            'subcategory_id' => Subcategory::where('name', 'Massimo Duti')->first()->id,
            'image_src' => "https://static.massimodutti.net/3/photos/2023/V/0/2/p/1602/464/800/1602464800_2_1_16.jpg",

        ]);

        // Watches category, Sales subcategory
        Product::factory(1)->create([
            'category_id' => Category::where('name', 'Watches')->first()->id,
            'subcategory_id' => Subcategory::where('name', 'Rolex')->first()->id,
            'image_src' => "https://s.turbifycdn.com/aah/movadobaby/rolex-day-date-36-green-aventurine-dial-unisex-watch-128235-0068-5.jpg",

        ]);

        // Accessories category, Sales subcategory
        Product::factory(1)->create([
            'category_id' => Category::where('name', 'Accessories')->first()->id,
            'subcategory_id' => Subcategory::where('name', 'Prada')->first()->id,
            'image_src' => "https://img.mytheresa.com/332/376/90/jpeg/catalog/product/03/P00819480.jpg",
        ]);
    }
}
