<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Projects\Category;
use App\Models\Projects\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        // جيب كل الفئات
        $categories = Category::all();

        foreach ($categories as $category) {
            for ($i = 1; $i <= 10; $i++) {
                /** @var Project $project */
                $project = Project::create([
                    'name'               => "{$category->name} Project {$i}",
                    'main_description'   => 'Main description lorem ipsum…',
                    'second_description' => 'Second description lorem ipsum…',
                    'third_description'  => 'Third description lorem ipsum…',
                    'location'           => 'Damascus, SY',
                    'date'               => now()->toDateString(),
                    'contractor'         => 'ACME Co.',
                    'category_id'        => $category->id,
                    'details'            => [
                        ['title' => 'Area', 'text' => '250 m²'],
                        ['title' => 'Budget', 'text' => '$150k'],
                    ],
                    // صورة المشروع الأساسية
                    'mainImage'          => '1.png',
                ]);

                // 5 صور داخلية
                for ($k = 0; $k < 5; $k++) {
                    $project->images()->create([
                        'path' => '1.png',
                        'type' => 'interior',
                    ]);
                }

                // 5 صور خارجية
                for ($k = 0; $k < 5; $k++) {
                    $project->images()->create([
                        'path' => '1.png',
                        'type' => 'exterior',
                    ]);
                }
            }
        }
    }
}
