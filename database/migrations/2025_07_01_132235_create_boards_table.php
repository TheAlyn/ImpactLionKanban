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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nome do quadro');
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade')->comment('Empresa dona do quadro');
            $table->string('color')->default('#FFFFFF')->comment('Cor do quadro');
            $table->string('icon')->nullable()->comment('Ícone do quadro');
            $table->boolean('is_favorite')->default(false)->comment('Indica se o quadro é favorito');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Usuário dono do quadro');
            $table->string('slug')->unique()->comment('Identificador único do quadro');
            $table->string('description')->nullable()->comment('Descrição do quadro');
            $table->string('background_image')->nullable()->comment('Imagem de fundo do quadro');
            $table->boolean('is_public')->default(false)->comment('Indica se o quadro é público');
            $table->boolean('is_archived')->default(false)->comment('Indica se o quadro está arquivado');
            $table->boolean('is_template')->default(false)->comment('Indica se o quadro é um modelo');
            $table->boolean('is_read_only')->default(false)->comment('Indica se o quadro é somente leitura');
            $table->boolean('is_collaborative')->default(false)->comment('Indica se o quadro é colaborativo');
            $table->boolean('is_private')->default(false)->comment('Indica se o quadro é privado');
            $table->boolean('is_locked')->default(false)->comment('Indica se o quadro está bloqueado');
            $table->boolean('is_pinned')->default(false)->comment('Indica se o quadro está fixado');
            $table->boolean('is_hidden')->default(false)->comment('Indica se o quadro está oculto');
            $table->boolean('is_archived_by_user')->default(false)->comment('Indica se o quadro foi arquivado pelo usuário');
            $table->boolean('is_shared')->default(false)->comment('Indica se o quadro foi compartilhado');
            $table->boolean('is_synced')->default(false)->comment('Indica se o quadro está sincronizado');
            $table->boolean('is_trashed')->default(false)->comment('Indica se o quadro está na lixeira');
            $table->softDeletes()->comment('Data de exclusão suave do quadro');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
