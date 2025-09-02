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
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('user_manager');
            $table->uuid('customer_id');
            $table->unsignedBigInteger('user_customer_approver')->nullable();
            $table->string('name');
            $table->text('description');
            $table->enum('status', ['planning', 'in progress', 'paused', 'completed', 'suspended', 'canceled']);
            $table->boolean('manual');
            $table->boolean('with_warranty');
            $table->date('warranty_date')->nullable();
            $table->json('content');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_manager')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_customer_approver')->references('id')->on('users')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
