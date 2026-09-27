<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Offer;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        $viser = Faculty::where('slug', 'viser')->firstOrFail();

        $matematika = Course::where('slug', 'inzenjerska-matematika')
            ->firstOrFail();

        $elektrotehnika = Course::where('slug', 'elektrotehnika')
            ->firstOrFail();

        $matematikaOffer = Offer::create([
            'faculty_id' => $viser->id,
            'name' => 'Inženjerska matematika',
            'slug' => 'inzenjerska-matematika',
            'description' => null,
            'price' => 8000,
            'referral_discount' => 1000,
            'referral_commission' => 1000,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $elektrotehnikaOffer = Offer::create([
            'faculty_id' => $viser->id,
            'name' => 'Elektrotehnika',
            'slug' => 'elektrotehnika',
            'description' => null,
            'price' => 8000,
            'referral_discount' => 1000,
            'referral_commission' => 1000,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $kompletOffer = Offer::create([
            'faculty_id' => $viser->id,
            'name' => 'Inženjerska matematika + Elektrotehnika',
            'slug' => 'inzenjerska-matematika-elektrotehnika',
            'description' => null,
            'price' => 14000,
            'referral_discount' => 1000,
            'referral_commission' => 1000,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        $matematikaOffer->courses()->attach($matematika->id);

        $elektrotehnikaOffer->courses()->attach($elektrotehnika->id);

        $kompletOffer->courses()->attach([
            $matematika->id,
            $elektrotehnika->id,
        ]);
    }
}
