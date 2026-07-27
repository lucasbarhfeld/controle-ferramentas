<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->string('faixa_uso')->nullable()->after('localizacao');
            $table->string('foto_path')->nullable()->after('faixa_uso');
        });
    }

    public function down(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropColumn(['faixa_uso', 'foto_path']);
        });
    }
};
