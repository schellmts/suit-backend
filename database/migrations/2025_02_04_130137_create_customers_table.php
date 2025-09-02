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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('account_id');
            $table->enum('type', ['individual', 'business']);
            $table->boolean('internal');
            $table->string('name');
            $table->string('document_number');
            $table->string('document_type');
            $table->string('email');
            $table->string('phone');
            $table->char('country', length: 2);
            $table->string('state')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->string('address_line');
            $table->string('timezone');
            $table->string('preferred_language');
            $table->json('metadata')->nullable();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
