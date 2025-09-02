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
        Schema::create('juridical_person', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('account_id');
            $table->string('country_code', 10);
            $table->integer('document_type1')->nullable();
            $table->string('document1', 50)->nullable();
            $table->integer('document_type2')->nullable();
            $table->string('document2', 50)->nullable();
            $table->integer('document_type3')->nullable();
            $table->string('document3', 50)->nullable();
            $table->dateTime('company_opening_date')->nullable();
            $table->string('city', 250)->nullable();
            $table->string('neighborhood', 250)->nullable();
            $table->string('street', 250)->nullable();
            $table->string('postal_code', 50)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email', 80)->nullable();
            $table->string('business_area', 100)->nullable();
            $table->string('corporate_name', 150)->nullable();
            $table->string('trade_name', 150)->nullable();
            $table->enum('company_type', ['ltda', 'sa', 'micro_enterprise', 'ngo'])->nullable();
            $table->string('number', 10)->nullable();
            $table->string('complement', 100)->nullable();
            $table->boolean('status')->default(true); // 0 = Inactive, 1 = Active
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
        Schema::dropIfExists('juridical_person');
    }
};
