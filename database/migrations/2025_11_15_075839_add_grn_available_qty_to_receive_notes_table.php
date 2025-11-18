<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('receive_notes', function (Blueprint $table) {
        $table->integer('grn_available_qty')->default(0)->after('grn_quantity_received');
    });

    // Update all existing rows
    DB::table('receive_notes')->update([
        'grn_available_qty' => DB::raw('grn_quantity_received')
    ]);
}
};
