<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('generic_tables', function (Blueprint $table) {
            $table->unsignedInteger('seq_values')->default(0);
        });
    }

    public function down()
    {
        Schema::table('generic_tables', function (Blueprint $table) {
            $table->dropColumn('seq_values');
        });
    }
};
