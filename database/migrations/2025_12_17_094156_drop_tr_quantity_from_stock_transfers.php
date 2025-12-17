<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            if (Schema::hasColumn('stock_transfers', 'tr_quantity')) {
                $table->dropColumn('tr_quantity');
            }
        });
    }

    public function down()
    {
        Schema::table('stock_transfers', function (Blueprint $table) {
            $table->integer('tr_quantity')->default(0);
        });
    }
};
