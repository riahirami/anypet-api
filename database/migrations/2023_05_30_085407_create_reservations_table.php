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
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->text('message')->nullable();
            $table->string('reservation_date')->nullable();
            $table->integer('status')->default('0');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
