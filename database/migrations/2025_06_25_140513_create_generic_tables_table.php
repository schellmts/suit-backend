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
        Schema::create('generic_tables', function (Blueprint $table) {
            $table->id();
            $table->integer('reg_id');
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->string('table_code', 50);
            $table->string('table_desc', 100);
            $table->integer('table_values');
            $table->integer('seq_values'); //contador de reg_ids
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generic_tables');
    }
};
