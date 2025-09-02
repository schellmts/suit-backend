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
        Schema::table('ticket', function (Blueprint $table) {
            // 1. Primeiro, removemos a coluna antiga que tem o tipo incorreto (integer).
            // Isso também removerá os dados antigos que estão causando o conflito.
            $table->dropColumn('customer_id');
        });

        Schema::table('ticket', function (Blueprint $table) {
            // 2. Agora, adicionamos a coluna novamente com o tipo correto (uuid).
            // Usar ->after() é uma boa prática para manter a ordem das colunas no banco.
            $table->uuid('customer_id')->nullable()->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket', function (Blueprint $table) {
            // Lógica para reverter: remove a coluna uuid...
            $table->dropColumn('customer_id');
        });

        Schema::table('ticket', function (Blueprint $table) {
            // ...e adiciona a coluna integer de volta.
            $table->integer('customer_id')->nullable()->after('created_by');
        });
    }
};
