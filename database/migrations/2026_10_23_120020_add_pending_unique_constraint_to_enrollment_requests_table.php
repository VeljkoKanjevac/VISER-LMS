
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollment_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('pending_offer_id')
                ->nullable()
                ->storedAs(
                    "CASE WHEN status = 'pending' THEN offer_id ELSE NULL END"
                );
        });

        Schema::table('enrollment_requests', function (Blueprint $table) {
            $table->unique(
                ['user_id', 'pending_offer_id'],
                'enrollment_requests_unique_pending_offer'
            );
        });
    }

    public function down(): void
    {
        Schema::table('enrollment_requests', function (Blueprint $table) {
            $table->dropUnique(
                'enrollment_requests_unique_pending_offer'
            );
        });

        Schema::table('enrollment_requests', function (Blueprint $table) {
            $table->dropColumn('pending_offer_id');
        });
    }
};
