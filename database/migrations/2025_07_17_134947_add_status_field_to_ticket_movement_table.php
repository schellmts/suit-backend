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
        Schema::table('ticket_movement', function (Blueprint $table) {
            $table->enum('status', ['1', '2', '3', '4', '5', '6'])->nullable(); //1- Open,  2 - In progress , 3-waiting Customer, 4 - Closed, 5-Resolved , 6 Canceled
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_movement', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
