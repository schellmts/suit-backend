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
        Schema::create('physical_person', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('account_id');
            $table->string('country_code', 10);
            $table->string('erp_physical_person_code', 50)->nullable();
            $table->integer('document_type1')->nullable();
            $table->string('document1', 50)->nullable();
            $table->integer('document_type2')->nullable();
            $table->string('document2', 50)->nullable();
            $table->string('passport', 50)->nullable();
            $table->dateTime('birth_date')->nullable();
            $table->string('name', 80);
            $table->string('city', 250)->nullable();
            $table->string('neighborhood', 250)->nullable();
            $table->string('street', 250)->nullable();
            $table->string('extra_info1', 512)->nullable();
            $table->string('postal_code', 50)->nullable();
            $table->string('city_code', 50)->nullable();
            $table->string('number', 50)->nullable();
            $table->string('extra_info2', 80)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email', 80)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'other'])->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physical_person');
    }
};
