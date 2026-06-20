<?php

namespace Database\Seeders;

use App\Models\Category;
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
        $categoryIds = Category::query()->pluck('id')->toArray();

        Product::factory()
            ->count(100)
            ->sequence(fn() => ['category_id' => $categoryIds[array_rand($categoryIds)]])
            ->create();
    }
}
