<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $viser = Faculty::where('slug', 'viser')->firstOrFail();

        Course::create([
            'faculty_id' => $viser->id,
            'study_year' => 1,
            'name' => 'Inženjerska matematika',
            'slug' => 'inzenjerska-matematika',
            'description' => null,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Course::create([
            'faculty_id' => $viser->id,
            'study_year' => 1,
            'name' => 'Elektrotehnika',
            'slug' => 'elektrotehnika',
            'description' => null,
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }
}
