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
        Schema::table('ticket_rule_conditions', function (Blueprint $table) {
            $table->enum('logic_operator', ['and', 'or'])->default('and')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_rule_conditions', function (Blueprint $table) {
            $table->dropColumn('logic_operator');
        });
    }
};
