<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\About\Team;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['name' => 'Ahmad Ali',       'role' => 'Architect'],
            ['name' => 'Sara Khaled',     'role' => 'Interior Designer'],
            ['name' => 'Mohammad Noor',   'role' => 'Project Manager'],
            ['name' => 'Lina Ahmad',      'role' => 'Civil Engineer'],
            ['name' => 'Omar Hasan',      'role' => 'Electrical Engineer'],
            ['name' => 'Huda Rami',       'role' => 'Landscape Designer'],
            ['name' => 'Fadi Salem',      'role' => 'Construction Supervisor'],
            ['name' => 'Rania Fares',     'role' => 'Structural Engineer'],
            ['name' => 'Yousef Taha',     'role' => 'Mechanical Engineer'],
            ['name' => 'Dana Ibrahim',    'role' => 'Procurement Specialist'],
            ['name' => 'Khaled Ziad',     'role' => 'Quality Control'],
            ['name' => 'Mona Samer',      'role' => 'Design Consultant'],
        ];

        foreach ($members as $member) {
            Team::create([
                'name'  => $member['name'],
                'role'  => $member['role'],
                'image' => '1.png', // صورة ثابتة لكل الأعضاء
            ]);
        }
    }
}
