<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Devices, gadgets, and electronic accessories.',
                'children' => [
                    ['name' => 'Smartphones'],
                    ['name' => 'Laptops'],
                    ['name' => 'Cameras']
                ]
            ],
            [
                'name' => 'Fashion',
                'description' => 'Clothing, shoes, and accessories.',
                'children' => [
                    ['name' => 'Men'],
                    ['name' => 'Women'],
                    ['name' => 'Kids']
                ]
            ],
            [
                'name' => 'Home & Living',
                'description' => 'Furniture, decor, and kitchenware.',
                'children' => [
                    ['name' => 'Furniture'],
                    ['name' => 'Decor'],
                    ['name' => 'Appliances']
                ]
            ],
        ];

        foreach ($categories as $order => $category) {
            $parent = Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'parent_id' => null,
                'is_active' => true,
                'order' => $order + 1,
            ]);
            foreach ($category['children'] as $childOrder => $child) {
                Category::create([
                    'name' => $child['name'],
                    'slug' => Str::slug($child['name']),
                    'description' => $child['name'] . ' products and accessories.',
                    'parent_id' => $parent->id,
                    'is_active' => true,
                    'order' => ($order + 1) * 10 + $childOrder + 1,
                ]);
            }
        }
    }
}
