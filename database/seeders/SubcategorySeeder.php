<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     Category::factory()
    //         ->count(4)
    //         ->has(Subcategory::factory()->count(2))
    //         ->create();
    // }

    public function run()
{
    $bagsCategory = Category::where('name', 'Bags')->first();
    $beltsCategory = Category::where('name', 'Belts')->first();
    $walletsCategory = Category::where('name', 'Wallets')->first();
    $watchesCategory = Category::where('name', 'Watches')->first();
    $accessoriesCategory = Category::where('name', 'Accessories')->first();
    $salesCategory = Category::where('name', 'Sales')->first(); // Retrieve the 'Sales' category

    Subcategory::create(['name' => 'Gucci', 'category_id' => $bagsCategory->id]);
    Subcategory::create(['name' => 'Hugo Boss', 'category_id' => $beltsCategory->id]);
    Subcategory::create(['name' => 'Massimo Duti', 'category_id' => $walletsCategory->id]);
    Subcategory::create(['name' => 'Rolex', 'category_id' => $watchesCategory->id]);
    Subcategory::create(['name' => 'Prada', 'category_id' => $accessoriesCategory->id]);
}

}
