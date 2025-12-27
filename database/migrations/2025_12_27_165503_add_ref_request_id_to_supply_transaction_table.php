<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('supply_transaction', function (Blueprint $table) {
            $table->unsignedBigInteger('ref_request_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supply_transaction', function (Blueprint $table) {
            $table->dropColumn('ref_request_id');
        });
    }
};
