
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('offer_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('referral_code_id')
                ->nullable()
                ->constrained()
                ->restrictOnDelete();

            $table->unsignedInteger('regular_amount');

            $table->unsignedInteger('discount_amount')->default(0);

            $table->unsignedInteger('amount');

            $table->unsignedInteger('referral_commission_amount')->default(0);

            $table->string('status')->default('pending');

            $table->text('admin_note')->nullable();

            $table->timestamp('confirmed_at')->nullable();

            $table->timestamp('rejected_at')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_requests');
    }
};
