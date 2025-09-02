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
        Schema::create('user_networks', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->uuid('network_id');
            $table->enum('type', ['owner', 'contributor', 'customer', 'supplier']);

            $table->unique(['user_id', 'network_id']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_networks');
    }
};
