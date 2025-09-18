<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Projects\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Residential Projects',
                'image' => '1.png', // صورة مشاريع سكنية
            ],
            [
                'name' => 'Commercial Projects',
                'image' => '1.png', // صورة مشاريع تجارية
            ],
            [
                'name' => 'Industrial Projects',
                'image' => '1.png', // صورة مشاريع صناعية
            ],
            [
                'name' => 'Landscape Design',
                'image' => '1.png', // صورة حدائق
            ],
            [
                'name' => 'Interior Design',
                'image' => '1.png', // صورة تصميم داخلي
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
