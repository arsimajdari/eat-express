<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Category::factory()->create([
            "name"=>"Bags"
        ]);
        Category::factory()->create([
            "name"=>"Belts"
        ]);
        Category::factory()->create([
            "name"=>"Wallets"
        ]);
        Category::factory()->create([
            "name"=>"Watches"
        ]);
        Category::factory()->create([
            "name"=>"Accessories"
        ]);
    }
}
