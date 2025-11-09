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
        Schema::create('read_status', function (Blueprint $table) {
            $table->id();
            
            // 1. User ID (Who read the message)
            // Assuming your users table is 'users' and the primary key is 'id'
            $table->unsignedBigInteger('user_id'); 
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade'); // Assuming your User ID column is 'user_id'

            // 2. Polymorphic Columns (What message was read)
            // These two columns define the message that was read.
            // messageable_id: The ID of the message (from StockTransfer or StockRequest)
            // messageable_type: The model class name (e.g., App\Models\StockTransfer)
            $table->unsignedBigInteger('messageable_id');
            $table->string('messageable_type'); 

            // 3. Prevent duplicate 'read' entries
            // A user should only have one 'read' record per message.
            $table->unique(['user_id', 'messageable_id', 'messageable_type']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('read_status');
    }
};