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
        Schema::create('table_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('table_id')->constrained('generic_tables')->onDelete('cascade');
            $table->integer('seq_field');
            $table->string('cod_field', 50);
            $table->string('description', 250);
            $table->string('default_value', 2500)->nullable();
            $table->unsignedBigInteger('list_field_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables_fields');
    }
};
