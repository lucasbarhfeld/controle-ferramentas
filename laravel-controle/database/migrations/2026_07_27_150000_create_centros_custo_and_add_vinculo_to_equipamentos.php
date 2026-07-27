<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('centros_custo', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nome')->nullable();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::table('equipamentos', function (Blueprint $table) {
            $table->string('tipo_vinculacao')->default('sem_responsavel')->after('status');
            $table->foreignId('centro_custo_id')
                ->nullable()
                ->after('usuario_responsavel_id')
                ->constrained('centros_custo')
                ->nullOnDelete();
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE equipamentos MODIFY periodo_calibragem_dias INT NOT NULL DEFAULT 360');
        }

        $centrosPorCodigo = [];
        $agora = now();

        DB::table('equipamentos')
            ->leftJoin('users', 'users.id', '=', 'equipamentos.usuario_responsavel_id')
            ->select('equipamentos.id', 'equipamentos.usuario_responsavel_id', 'users.name as responsavel_nome')
            ->orderBy('equipamentos.id')
            ->each(function (object $equipamento) use (&$centrosPorCodigo, $agora): void {
                if (! $equipamento->usuario_responsavel_id) {
                    DB::table('equipamentos')
                        ->where('id', $equipamento->id)
                        ->update(['tipo_vinculacao' => 'sem_responsavel']);

                    return;
                }

                $nome = trim((string) $equipamento->responsavel_nome);
                $normalizado = Str::of($nome)->lower()->ascii()->squish()->toString();
                $compacto = preg_replace('/[^a-z0-9]+/', '', $normalizado);

                if (in_array($normalizado, ['armario coletivo', 'armario de uso coletivo'], true)) {
                    DB::table('equipamentos')
                        ->where('id', $equipamento->id)
                        ->update([
                            'tipo_vinculacao' => 'armario_coletivo',
                            'usuario_responsavel_id' => null,
                        ]);

                    return;
                }

                if (preg_match('/^(?:cc|centrodecusto)(\d+)$/', $compacto, $matches)) {
                    $codigo = 'CC ' . $matches[1];

                    if (! isset($centrosPorCodigo[$codigo])) {
                        $centroExistente = DB::table('centros_custo')->where('codigo', $codigo)->first();

                        $centrosPorCodigo[$codigo] = $centroExistente?->id
                            ?? DB::table('centros_custo')->insertGetId([
                                'codigo' => $codigo,
                                'nome' => null,
                                'descricao' => 'Criado automaticamente a partir de um vínculo existente.',
                                'ativo' => true,
                                'created_at' => $agora,
                                'updated_at' => $agora,
                            ]);
                    }

                    DB::table('equipamentos')
                        ->where('id', $equipamento->id)
                        ->update([
                            'tipo_vinculacao' => 'centro_custo',
                            'usuario_responsavel_id' => null,
                            'centro_custo_id' => $centrosPorCodigo[$codigo],
                        ]);

                    return;
                }

                DB::table('equipamentos')
                    ->where('id', $equipamento->id)
                    ->update(['tipo_vinculacao' => 'usuario']);
            });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE equipamentos MODIFY periodo_calibragem_dias INT NOT NULL DEFAULT 60');
        }

        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('centro_custo_id');
            $table->dropColumn('tipo_vinculacao');
        });

        Schema::dropIfExists('centros_custo');
    }
};
