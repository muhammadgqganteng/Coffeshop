<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update admin
        Admin::updateOrCreate(
            ['email' => 'admin@coffeoshop.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        // Create or update test user
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'role' => 'user',
                'password' => bcrypt('password'),
            ]
        );

        // Create categories if not exist
        $categories = ['Coffee', 'Non-Coffee', 'Pastry'];
        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        // Get category IDs
        $coffeeId = Category::where('name', 'Coffee')->first()->id;
        $nonCoffeeId = Category::where('name', 'Non-Coffee')->first()->id;
        $pastryId = Category::where('name', 'Pastry')->first()->id;

        // Create products if not exist
        $products = [
            [
                'category_id' => $coffeeId,
                'name' => 'Espresso',
                'description' => 'Rich and bold espresso shot',
                'price' => 3.50,
                'image' => null,
            ],
            [
                'category_id' => $coffeeId,
                'name' => 'Cappuccino',
                'description' => 'Espresso with steamed milk and foam',
                'price' => 4.50,
                'image' => null,
            ],
            [
                'category_id' => $coffeeId,
                'name' => 'Latte',
                'description' => 'Espresso with steamed milk',
                'price' => 4.75,
                'image' => null,
            ],
            [
                'category_id' => $nonCoffeeId,
                'name' => 'Green Tea',
                'description' => 'Refreshing green tea',
                'price' => 3.00,
                'image' => null,
            ],
            [
                'category_id' => $nonCoffeeId,
                'name' => 'Hot Chocolate',
                'description' => 'Rich hot chocolate with whipped cream',
                'price' => 4.25,
                'image' => null,
            ],
            [
                'category_id' => $pastryId,
                'name' => 'Croissant',
                'description' => 'Buttery and flaky croissant',
                'price' => 2.75,
                'image' => null,
            ],
            [
                'category_id' => $pastryId,
                'name' => 'Blueberry Muffin',
                'description' => 'Fresh blueberry muffin',
                'price' => 3.25,
                'image' => null,
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }
    }
}
