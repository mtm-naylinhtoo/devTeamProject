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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Admin who is giving the feedback
            $table->unsignedBigInteger('task_detail_id'); // Task detail associated with the feedback
            $table->text('comment')->nullable();
            $table->tinyInteger('rating')->nullable(); // Assuming rating is an integer
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('task_detail_id')->references('id')->on('task_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
