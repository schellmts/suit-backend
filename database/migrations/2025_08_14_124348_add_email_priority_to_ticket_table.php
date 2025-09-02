<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove a restrição 'CHECK' antiga da coluna 'priority'
        DB::statement('ALTER TABLE ticket DROP CONSTRAINT ticket_priority_check');

        // Adiciona uma nova restrição 'CHECK' com os valores atualizados
        DB::statement("ALTER TABLE ticket ADD CONSTRAINT ticket_priority_check
            CHECK (priority IN ('1', '2', '3', '4', '5', '6'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverte a alteração, removendo o valor '6' da restrição
        DB::statement('ALTER TABLE ticket DROP CONSTRAINT ticket_priority_check');

        DB::statement("ALTER TABLE ticket ADD CONSTRAINT ticket_priority_check
            CHECK (priority IN ('1', '2', '3', '4', '5'))");
    }
};
