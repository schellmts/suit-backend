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
        Schema::create('grouping_cost_centers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grouping_id');
            $table->unsignedBigInteger('cost_center_id');
            $table->timestamps();

            $table->foreign('grouping_id')->references('id')->on('groupings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cost_center_id')->references('id')->on('cost_centers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grouping_cost_centers');
    }
};
