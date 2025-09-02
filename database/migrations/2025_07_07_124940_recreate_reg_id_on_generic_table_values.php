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
        Schema::table('generic_tables_values', function (Blueprint $table) {
            if (Schema::hasColumn('generic_tables_values', 'reg_id')) {
                $table->dropColumn('reg_id');
            }
        });

        Schema::table('generic_tables_values', function (Blueprint $table) {
            $table->unsignedInteger('reg_id')->default(1)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('generic_tables_values', function (Blueprint $table) {
            $table->dropColumn('reg_id');
        });
    }
};
