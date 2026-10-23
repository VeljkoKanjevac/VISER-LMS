<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_codes', function (Blueprint $table) {
            $table->dropForeign(['offer_id']);
            $table->dropColumn('offer_id');

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('referral_codes', function (Blueprint $table) {
            $table->dropUnique(['user_id']);

            $table->foreignId('offer_id')
                ->constrained()
                ->cascadeOnDelete();
        });
    }
};
