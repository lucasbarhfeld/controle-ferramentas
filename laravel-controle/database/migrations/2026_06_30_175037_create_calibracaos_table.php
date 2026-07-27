<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calibracoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('equipamento_id')
                ->constrained('equipamentos')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->date('data_calibragem');

            $table->string('resultado')->default('Aprovado');

            $table->text('observacoes')->nullable();

            $table->timestamp('data_registro')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calibracoes');
    }
};