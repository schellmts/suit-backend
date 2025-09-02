<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grouping_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grouping_id');
            $table->unsignedBigInteger('skill_id');
            $table->timestamps();

            $table->unique(['grouping_id', 'skill_id']);

            $table->foreign('grouping_id')->references('id')->on('groupings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grouping_skills');
    }
};
