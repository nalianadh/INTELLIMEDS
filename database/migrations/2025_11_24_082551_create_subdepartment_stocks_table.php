<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubdepartmentStocksTable extends Migration
{
    public function up()
    {
        Schema::create('subdepartment_stocks', function (Blueprint $table) {
            $table->id();

            // Foreign key: User (sub_department role)
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

            // Foreign key: Item
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('cascade');

            // Updated naming
            $table->string('sd_batchNumber')->nullable();
            $table->date('sd_expiryDate')->nullable();
            $table->integer('sd_quantityInHand')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subdepartment_stocks');
    }
}
