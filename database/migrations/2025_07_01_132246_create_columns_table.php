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
        Schema::create('columns', function (Blueprint $table) {
             $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->nullable()->onDelete('cascade')->comment('Empresa dona da coluna');
            $table->foreignId('board_id')->constrained('boards')->nullable()->onDelete('cascade')->comment('Quadro dono da coluna');
            $table->string('name')->comment('Nome da coluna');
            $table->string('color')->default('#FFFFFF')->nullable()->comment('Cor da coluna');
            $table->integer('position')->default(0)->comment('Ordem da coluna dentro do quadro');
            $table->boolean('is_locked')->default(false)->comment('Indica se a coluna está bloqueada');
            $table->boolean('is_hidden')->default(false)->comment('Indica se a coluna está oculta');
            $table->string('slug')->unique()->nullable()->comment('Slug único da coluna');
            $table->string('description')->nullable()->comment('Descrição da coluna');
            $table->boolean('is_archived')->default(false)->comment('Indica se a coluna está arquivada');
            $table->boolean('is_template')->default(false)->comment('Indica se a coluna é um modelo');
            $table->boolean('is_read_only')->default(false)->comment('Indica se a coluna é somente leitura');
            $table->boolean('is_collaborative')->default(false)->comment('Indica se a coluna é colaborativa');
            $table->softDeletes()->comment('Exclusão suave');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('columns');
    }
};
