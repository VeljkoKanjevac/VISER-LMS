<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'offer_id',
        'regular_amount',
        'discount_amount',
        'amount',
        'note',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'regular_amount' => 'integer',
            'discount_amount' => 'integer',
            'amount' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function referralCommission(): HasOne
    {
        return $this->hasOne(ReferralCommission::class);
    }
}
