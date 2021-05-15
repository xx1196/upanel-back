<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::factory()
            ->count(50)
            ->create();
        $categories = Category::all();

        $products->each(function (Product $product) use ($categories) {
            $product->categories()->attach(
                $categories->random(rand(1, 50))->pluck('id')->toArray()
            );
        });
    }
}
