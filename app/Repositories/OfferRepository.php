<?php

namespace App\Repositories;

use App\Models\Offer;

class OfferRepository
{
    public function count(): int
    {
        return Offer::count();
    }
}
