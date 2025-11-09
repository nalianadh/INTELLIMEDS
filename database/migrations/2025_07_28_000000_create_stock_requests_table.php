<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_requests', function (Blueprint $table) {
            $table->bigIncrements('request_id');

            $table->unsignedBigInteger('rq_requested_by')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // e.g. requester user_id
            $table->unsignedBigInteger('item_id');
            $table->integer('rq_quantity_requested');
            $table->integer('rq_qty_approved')->nullable();
            $table->string('rq_status')->default('Pending');
            $table->date('rq_date_requested');
            $table->date('rq_date_approved')->nullable();
            $table->unsignedBigInteger('rq_approved_by')->nullable();
            $table->text('rq_remarks')->nullable();

            // Foreign keys - updated to reference 'user_id' instead of 'id'
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('rq_approved_by')->references('user_id')->on('users')->onDelete('set null');
            $table->foreign('rq_requested_by')->references('user_id')->on('users')->onDelete('set null');

            $table->foreign('item_id')->references('item_id')->on('items')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_requests');
    }
}
