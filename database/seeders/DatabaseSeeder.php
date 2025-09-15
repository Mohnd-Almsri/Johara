<?php

namespace Database\Seeders;

use App\Models\About\About;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => hash::make('123123123'),
        ]);

//       $about = About::create([
//            'title' => 'About Us',
//            'body' => 'This is a brief description about the organization and its mission.'
//        ]);
//        $about->images()->create([
//            'path'=>'asdasd/asdasdas/dasdasd/asd'
//        ]);
//        $about->images()->create([
//            'path'=>'asdasd/asdasdas/dasdasd/asd'
//        ]);

    }
}
