<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipamentos', function (Blueprint $table) {
            $table->id();

            $table->string('codigo')->unique();
            $table->string('nome');
            $table->string('fabricante')->nullable();
            $table->string('modelo')->nullable();
            $table->string('localizacao')->nullable();

            $table->string('status')->default('Ativo');

            $table->foreignId('usuario_responsavel_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('ultima_calibragem')->nullable();
            $table->integer('periodo_calibragem_dias')->default(60);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipamentos');
    }
};
