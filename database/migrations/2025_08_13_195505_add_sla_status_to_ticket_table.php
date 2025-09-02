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
        DB::statement("ALTER TABLE ticket DROP CONSTRAINT IF EXISTS ticket_status_check");
        DB::statement("ALTER TABLE ticket ADD CONSTRAINT ticket_status_check CHECK (status IN ('1','2','3','4','5','6','7','8'))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE ticket DROP CONSTRAINT IF EXISTS ticket_status_check");
        DB::statement("ALTER TABLE ticket ADD CONSTRAINT ticket_status_check CHECK (status IN ('1','2','3','4','5','6'))");
    }

};
