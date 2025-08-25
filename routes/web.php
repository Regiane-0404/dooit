<?php

use App\Http\Controllers\TarefaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rota pública principal da aplicação.
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas
|--------------------------------------------------------------------------
|
| Todas as rotas dentro deste grupo só podem ser acedidas por utilizadores
| que tenham feito login.
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // A rota do Dashboard que já existia
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // A nossa nova linha que cria todas as rotas para as tarefas (listar, criar, etc.)
    Route::resource('tarefas', TarefaController::class);
});


/*
|--------------------------------------------------------------------------
| Ficheiros de Rotas Adicionais
|--------------------------------------------------------------------------
*/

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
