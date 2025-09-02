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
        Schema::table('ticket_rule_actions', function (Blueprint $table) {
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_rule_actions', function (Blueprint $table) {
            $table->dropColumn('account_id');
        });
    }
};
