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
        Schema::create('ticket_rule_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rule_group_id')->constrained('ticket_rule_groups')->onDelete('cascade');
            $table->foreignId('action_type_id')->constrained('ticket_actions')->onDelete('cascade');
            $table->string('action_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_rule_actions');
    }
};
