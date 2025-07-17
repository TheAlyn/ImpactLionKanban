<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\CardController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('boards', BoardController::class)->names([
        'index' => 'boards.index',
        'create' => 'boards.create',
        'store' => 'boards.store',
        'show' => 'boards.show',
        'edit' => 'boards.edit',
        'update' => 'boards.update',
        'destroy' => 'boards.destroy',
    ]);

    // Rotas para gerenciar colunas dentro de um board
    Route::resource('columns', ColumnController::class)
        ->only(['store', 'update', 'destroy']);
    Route::get('boards/{board}/columns', [ColumnController::class,'index'])->name('boards.columns.index');

    Route::get('boards/{board}/cards',   [CardController::class,'index'])->name('boards.cards.index');
    // Rotas para gerenciar os cartoes dentro de uma coluna
    Route::resource('cards', CardController::class)
        ->only(['store', 'update', 'destroy']);
    // Rota para mover cartoes entre colunas
    Route::post('cards/{card}/move', [CardController::class, 'move'])->name('cards.move');

    // Minhas empresas - listagem para o usuário que pertence a elas
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');

    // Gerenciar empresas - só para admin
    Route::middleware('can:admin')->group(function () {
        Route::get('/companies/admin', [CompanyController::class, 'admin'])->name('companies.admin');
        Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
        Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
        // Mais rotas de editar, update, delete, se precisar
    });
});
