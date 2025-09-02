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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid('network_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['authorized', 'unauthorized', 'blocked', 'unblocked']);
            $table->boolean('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('network_id')->references('id')->on('networks')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
