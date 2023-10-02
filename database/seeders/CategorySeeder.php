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
    // public function run(): void
    // {
    //     //
    //     Category::factory()->count(4)->create([
    //         "name"=>"Bags"
    //     ]);
    //     Category::factory()->create([
    //         "name"=>"Belts"
    //     ]);
    //     Category::factory()->create([
    //         "name"=>"Wallets"
    //     ]);
    //     Category::factory()->create([
    //         "name"=>"Watches"
    //     ]);
    //     Category::factory()->create([
    //         "name"=>"Accessories"
    //     ]);
    // }

    public function run()
    {
        Category::create(['name' => 'Bags']);
        Category::create(['name' => 'Belts']);
        Category::create(['name' => 'Wallets']);
        Category::create(['name' => 'Watches']);
        Category::create(['name' => 'Accessories']);

    }
}
