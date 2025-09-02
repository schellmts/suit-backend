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
        Schema::create('ticket_movement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('ticket_id')->constrained('ticket')->onDelete('cascade');
            $table->text('body');
            $table->string('email_cc', 250)->nullable();
            $table->enum('privacity', ['1', '2', '3']); //1- Public, 2- admin , 3- agents
            $table->enum('type', ['1', '2']); //1- Anotation, 2- move
            $table->integer('user_id')->nullable();
            $table->enum('origin', ['1', '2', '3']); //1- Client, 2- contact, 3- admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_movement');
    }
};
