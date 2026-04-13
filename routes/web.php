<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// 1. Ruta principal: Muestra el inventario al entrar a 127.0.0.1:8000
Route::get('/', [ProductoController::class, 'index']);

// 2. Ruta del Dashboard de Breeze (Protegida por login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. Rutas de perfil de Breeze (Protegidas por login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// 4. Rutas para tus Casos de Uso (CRUD completo)
Route::resource('productos', ProductoController::class);
Route::resource('usuarios', UsuarioController::class);