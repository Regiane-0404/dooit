<?php

use App\Http\Controllers\Api\WeatherController; // Import do WeatherController
use App\Http\Controllers\TarefaController; // Import do TarefaController
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Rotas Autenticadas
|--------------------------------------------------------------------------
| Todas as rotas aqui dentro exigem que o utilizador esteja logado.
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Rota do Dashboard
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Nossas rotas de TAREFAS
    Route::resource('tarefas', TarefaController::class);
    Route::patch('/tarefas/{tarefa}/toggle', [TarefaController::class, 'toggle'])->name('tarefas.toggle');

    // Nossa nova rota da API de METEOROLOGIA
    Route::get('/api/weather', [WeatherController::class, 'show'])->name('api.weather');
    Route::get('/api/news', [WeatherController::class, 'getNews'])->name('api.news');
});
/*
|--------------------------------------------------------------------------
| Ficheiros de Rotas Adicionais
|--------------------------------------------------------------------------
*/

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
