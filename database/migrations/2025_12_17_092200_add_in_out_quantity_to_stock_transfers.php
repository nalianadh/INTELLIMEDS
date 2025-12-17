<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->integer('tr_in_quantity')->default(0)->after('item_id');
            $table->integer('tr_out_quantity')->default(0)->after('tr_in_quantity');
        });
    }

    public function down()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->dropColumn(['tr_in_quantity', 'tr_out_quantity']);
        });
    }
};
