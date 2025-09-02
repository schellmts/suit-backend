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
        Schema::create('ticket_rule_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rule_group_id')->constrained('ticket_rule_groups')->onDelete('cascade');
            $table->foreignId('condition_type_id')->constrained('ticket_conditions')->onDelete('cascade');
            $table->foreignId('operator_id')->constrained('ticket_operators')->onDelete('cascade');
            $table->string('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_rule_conditions');
    }
};
