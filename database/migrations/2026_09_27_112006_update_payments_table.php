<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('offer_id')
                ->after('user_id')
                ->constrained()
                ->restrictOnDelete();

            $table->unsignedInteger('regular_amount')
                ->after('offer_id');

            $table->unsignedInteger('discount_amount')
                ->default(0)
                ->after('regular_amount');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('offer_id');
            $table->dropColumn([
                'regular_amount',
                'discount_amount',
            ]);
        });
    }
};
