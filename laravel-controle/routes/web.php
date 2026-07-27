<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\EquipamentoImportController;
use App\Http\Controllers\CalibracaoController;
use App\Http\Controllers\CadastroController;
use App\Http\Controllers\CentroCustoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeuController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/me', [MeuController::class, 'index'])->name('me.index');

    Route::get('/equipamentos/exportar', [EquipamentoController::class, 'export'])
        ->name('equipamentos.export');

    Route::resource('equipamentos', EquipamentoController::class)
        ->except(['destroy']);

    Route::get('/calibracoes', [CalibracaoController::class, 'index'])
        ->name('calibracoes.index');

    Route::get('/calibracoes/create', [CalibracaoController::class, 'create'])
        ->name('calibracoes.create');

    Route::post('/calibracoes', [CalibracaoController::class, 'store'])
        ->name('calibracoes.store');

    Route::middleware('admin')->group(function () {
        Route::get('/cadastros', [CadastroController::class, 'index'])
            ->name('cadastros.index');

        Route::post('/equipamentos/importar', EquipamentoImportController::class)
            ->name('equipamentos.import');

        Route::delete('/equipamentos/{equipamento}', [EquipamentoController::class, 'destroy'])
            ->name('equipamentos.destroy');
    
        Route::resource('usuarios', UsuarioController::class)->except(['show']);

        Route::resource('centros-custo', CentroCustoController::class)
            ->parameters(['centros-custo' => 'centro_custo'])
            ->except(['show']);
    });
});

Route::get('/teste-admin', function () {
    return 'Área de administrador funcionando.';
})->middleware(['auth', 'admin']);

require __DIR__.'/auth.php';
