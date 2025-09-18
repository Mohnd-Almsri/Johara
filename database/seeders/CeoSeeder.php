<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog\Ceo;

class CeoSeeder extends Seeder
{
    public function run(): void
    {
        // نص طويل لكل فقرة
        $paragraph = "Lorem ipsum dolor sit amet, consectetur adipiscing elit.
        Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.";

        /** @var Ceo $ceo */
        $ceo = Ceo::create([
            'name'        => 'John Doe',
            'paragraph_1' => $paragraph . " Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.",
            'paragraph_2' => $paragraph . " Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
            'paragraph_3' => $paragraph . " Curabitur pretium tincidunt lacus. Nulla gravida orci a odio.",
        ]);

        // إضافة 3 صور للمورف
        for ($i = 0; $i < 3; $i++) {
            $ceo->images()->create([
                'path' => '1.png',
                'type' => 'ceo',
            ]);
        }
    }
}
