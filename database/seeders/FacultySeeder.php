<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        Faculty::create([
            'name' => 'Visoka škola elektrotehnike i računarstva',
            'short_name' => 'VIŠER',
            'slug' => 'viser',
            'description' => null,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Faculty::create([
            'name' => 'Fakultet organizacionih nauka',
            'short_name' => 'FON',
            'slug' => 'fon',
            'description' => null,
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
