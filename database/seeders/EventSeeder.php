<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        Event::create([
            'location'    => 'Damascus, Syria',
            'description' => 'This is a sample event for testing purposes. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'date'        => now()->toDateString(), // تاريخ اليوم
            'image'       => '1.png', // صورة ثابتة
        ]);
    }
}
