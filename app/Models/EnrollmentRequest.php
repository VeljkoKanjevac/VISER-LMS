
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentRequest extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'offer_id',
        'referral_code_id',
        'regular_amount',
        'discount_amount',
        'amount',
        'referral_commission_amount',
        'status',
        'admin_note',
        'confirmed_at',
        'rejected_at',
    ];

    protected function casts(): array
    {
        return [
            'regular_amount' => 'integer',
            'discount_amount' => 'integer',
            'amount' => 'integer',
            'referral_commission_amount' => 'integer',
            'confirmed_at' => 'datetime',
            'rejected_at' => 'datetime',
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

    public function referralCode(): BelongsTo
    {
        return $this->belongsTo(ReferralCode::class);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function hasReferralCode(): bool
    {
        return $this->referral_code_id !== null;
    }
}
