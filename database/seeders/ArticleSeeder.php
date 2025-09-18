<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog\Article;
use App\Models\Paragraph;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        // جمل طويلة شوي باستخدام Lorem Ipsum
        $lorem = "Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                  Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                  Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";

        for ($i = 1; $i <= 12; $i++) {

            /** @var Article $article */
            $article = Article::create([
                'title'       => "Sample Article {$i}",
                'description' => $lorem . " Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
                'image'       => '1.png', // صورة ثابتة
            ]);

            // أضف 5 فقرات لكل مقالة
            for ($j = 1; $j <= 5; $j++) {
                Paragraph::create([
                    'article_id' => $article->id,
                    'title'      => "Paragraph {$j} for Article {$i}",
                    'body'       => $lorem . " " . $lorem, // نص طويل أكثر
                    'order'      => $j,
                    'image'       => '1.png', // صورة ثابتة

                ]);
            }
        }
    }
}
