
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_commissions', function (Blueprint $table) {

            // Zaseban indeks za postojeći foreign key
            $table->index(
                'referral_code_id',
                'referral_commissions_referral_code_id_idx'
            );

        });

        Schema::table('referral_commissions', function (Blueprint $table) {

            // Uklanjamo ograničenje koje sprečava
            // više provizija za istog korisnika
            $table->dropUnique(
                'referral_commissions_referral_code_id_referred_user_id_unique'
            );

        });
    }

    public function down(): void
    {
        Schema::table('referral_commissions', function (Blueprint $table) {

            $table->unique(['referral_code_id', 'referred_user_id']);
        });

        Schema::table('referral_commissions', function (Blueprint $table) {

            $table->dropIndex('referral_commissions_referral_code_id_idx');
        });
    }
};
