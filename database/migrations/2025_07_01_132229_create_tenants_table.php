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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nome da empresa');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade'); // usuário dono da empresa
            $table->string('slug')->unique()->comment('Identificador único da empresa');
            $table->string('domain')->unique()->comment('Domínio da empresa');
            $table->string('logo')->nullable()->comment('Logo da empresa');
            $table->string('color')->default('#FFFFFF')->comment('Cor da empresa');
            $table->string('description')->nullable()->comment('Descrição da empresa');
            $table->boolean('is_active')->default(true)->comment('Indica se a empresa está ativa');
            $table->boolean('is_verified')->default(false)->comment('Indica se a empresa está verificada');
            $table->boolean('is_premium')->default(false)->comment('Indica se a empresa é premium');
            $table->boolean('is_archived')->default(false)->comment('Indica se a empresa está arquivada');
            $table->softDeletes()->comment('Data de exclusão suave da empresa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
