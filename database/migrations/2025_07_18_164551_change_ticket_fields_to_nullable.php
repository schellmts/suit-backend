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
        Schema::table('ticket', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->text('body')->nullable()->change();
            $table->string('area_customer', 50)->nullable()->change();
            $table->string('category', 50)->nullable()->change();
            $table->string('subcategory', 50)->nullable()->change();
            $table->string('user_ticket_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->text('body')->nullable(false)->change();
            $table->string('area_customer', 50)->nullable(false)->change();
            $table->string('category', 50)->nullable(false)->change();
            $table->string('subcategory', 50)->nullable(false)->change();
            $table->string('user_ticket_id')->nullable(false)->change();
        });
    }
};
