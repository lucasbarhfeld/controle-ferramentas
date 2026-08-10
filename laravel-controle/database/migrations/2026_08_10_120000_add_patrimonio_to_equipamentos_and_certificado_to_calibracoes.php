<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->string('patrimonio')->nullable()->after('codigo');
        });

        Schema::table('calibracoes', function (Blueprint $table) {
            $table->string('certificado')->nullable()->after('data_calibragem');
        });
    }

    public function down(): void
    {
        Schema::table('calibracoes', function (Blueprint $table) {
            $table->dropColumn('certificado');
        });

        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropColumn('patrimonio');
        });
    }
};
