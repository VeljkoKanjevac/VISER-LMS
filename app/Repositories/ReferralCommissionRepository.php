<?php

namespace App\Repositories;

use App\Models\ReferralCommission;

class ReferralCommissionRepository
{
    public function create(array $data): ReferralCommission
    {
        return ReferralCommission::create($data);
    }
}
