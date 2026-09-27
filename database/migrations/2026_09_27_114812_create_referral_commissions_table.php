<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_commissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('referral_code_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('referred_user_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('payment_id')
                ->constrained()
                ->restrictOnDelete();

            $table->unsignedInteger('amount');

            $table->string('status')->default('earned');

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->unique(
                ['referral_code_id', 'referred_user_id']
            );

            $table->unique('payment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_commissions');
    }
};
