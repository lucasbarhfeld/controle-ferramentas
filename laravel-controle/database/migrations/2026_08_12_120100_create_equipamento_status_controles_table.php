<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipamento_status_controles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipamento_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('ultimo_status', 30);
            $table->timestamp('ultima_notificacao_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipamento_status_controles');
    }
};
