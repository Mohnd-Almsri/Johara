<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\About\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Architectural Design',
                'description' => 'Professional design services for residential and commercial projects.',
                'image' => '1.png',
            ],
            [
                'name' => 'Construction Management',
                'description' => 'Complete project management from planning to execution.',
                'image' => '1.png',
            ],
            [
                'name' => 'Interior Design',
                'description' => 'Creative interior design solutions tailored to your style.',
                'image' => '1.png',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
