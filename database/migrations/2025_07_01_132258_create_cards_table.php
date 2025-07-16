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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade')->comment('Empresa dona do card');
            $table->foreignId('board_id')->constrained('boards')->onDelete('cascade')->comment('Quadro dono do card');
            $table->foreignId('column_id')->constrained('columns')->onDelete('cascade')->comment('Coluna onde o card está');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Usuário responsável pelo card');
            $table->string('title')->comment('Título da tarefa');
            $table->text('description')->nullable()->comment('Descrição da tarefa');
            $table->integer('position')->default(0)->comment('Ordem do card na coluna');
            $table->date('due_date')->nullable()->comment('Data de vencimento da tarefa');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium')->comment('Prioridade do card');
            $table->string('color')->default('#FFFFFF')->comment('Cor do card');
            $table->string('icon')->nullable()->comment('Ícone do card');
            $table->boolean('is_archived')->default(false)->comment('Indica se o card está arquivado');
            $table->string('slug')->unique()->comment('Slug único do card');
            $table->string('background_image')->nullable()->comment('Imagem de fundo do card');
            $table->boolean('is_template')->default(false)->comment('Indica se o card é um modelo');
            $table->boolean('is_read_only')->default(false)->comment('Indica se o card é somente leitura');
            $table->boolean('is_collaborative')->default(false)->comment('Indica se o card é colaborativo');
            $table->boolean('is_public')->default(false)->comment('Indica se o card é público');
            $table->boolean('is_private')->default(false)->comment('Indica se o card é privado');
            $table->softDeletes()->comment('Exclusão suave');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
