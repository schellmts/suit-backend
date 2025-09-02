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
        Schema::create('ticket', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->integer('project_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('title');
            $table->text('body');
            $table->enum('status', ['1', '2', '3', '4', '5', '6'])->default('1'); //1- Open,  2 - In progress , 3-waiting Customer, 4 - Closed, 5-Resolved , 6 Canceled
            $table->enum('type', ['1', '2', '3', '4', '5']); //1- Bug, 2- Improvement , 3- Support, 4- Proposal/Project, 5- Suggestion
            $table->string('area_customer', 50);
            $table->string('category', 50);
            $table->string('subcategory', 50);
            $table->string('tags', 1250)->nullable();
            $table->enum('priority', ['1', '2', '3', '4', '5'])->nullable(); //1- Critical, 2- Urgent , 3- High, 4- Medium, 5- Low
            $table->string('assigned_area', 50)->nullable(); //aqui tem que ser uma lista de areas, criar listas
            $table->integer('accuracy_resolution')->nullable(); //avaliacao 1 a 10
            $table->integer('satisfaction_level')->nullable(); //avaliacao 1 a 10
            $table->string('obs_evaluation', 1250)->nullable();
            $table->string('user_ticket_id');
            $table->unsignedBigInteger('agent_id')->nullable(); // Responsável
            $table->string('email_cc', 250)->nullable(); // JSON com emails (tags)

            $table->dateTime('date_exp_first_interaction')->nullable(); // Previsão 1ª interação
            $table->dateTime('date_last_interaction')->nullable(); // Última interação
            $table->dateTime('date_exp_finish')->nullable(); // Previsão de finalização
            $table->dateTime('date_open')->nullable(); // Abertura
            $table->dateTime('date_finished')->nullable(); // Finalizado
            $table->dateTime('date_accept_customer')->nullable(); // Aceite cliente

            $table->unsignedBigInteger('group_id')->nullable(); // Grupo que pode interagir

            $table->unsignedTinyInteger('ticket_origin')->nullable();
            // 1 - Email, 2 - Telefone, 3 - MSG (W, T, M), 4 - Portal Cliente, 5 - Portal Interno

            $table->string('email_abertura_ticket', 250)->nullable(); // Email de abertura

            $table->decimal('ticket_budgeted_value', 10, 2)->nullable(); // Valor orçado

            $table->integer('ticket_hours_aprov')->nullable(); // Horas aprovadas
            $table->integer('ticket_hours_work')->nullable(); // Horas trabalhadas
            $table->integer('ticket_hours_lim')->nullable(); // Limite de horas

            $table->string('reserved_1')->nullable(); // Reservado
            $table->string('reserved_2')->nullable();
            $table->string('reserved_3')->nullable();
            $table->string('reserved_4')->nullable();
            $table->string('reserved_5')->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_prog', 250)->nullable();
            $table->string('created_prog', 250)->nullable();

            $table->unsignedBigInteger('related_ticket_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket');
    }
};
