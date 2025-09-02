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
        Schema::create('project_user_groupings', function (Blueprint $table) {
            $table->id();
            $table->uuid('project_id');
            $table->unsignedBigInteger('user_grouping_id');
            $table->timestamps();

            $table->unique(['project_id', 'user_grouping_id']);

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_grouping_id')->references('id')->on('user_groupings')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user_groupings');
    }
};
