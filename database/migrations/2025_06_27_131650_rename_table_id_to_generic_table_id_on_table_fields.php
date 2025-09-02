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
        Schema::table('table_fields', function (Blueprint $table) {
            $table->renameColumn('table_id', 'generic_table_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_fields', function (Blueprint $table) {
            $table->renameColumn('generic_table_id', 'table_id');

        });
    }
};
