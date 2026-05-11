<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────
// PÚBLICAS — accesibles sin login
// ─────────────────────────────────────────────────────────
Route::get('/', [ProductoController::class, 'index'])->name('inventario');
Route::get('/catalogo/producto/{id}', [ProductoController::class, 'showPublic'])->name('producto.show');

// Rutas de Carrito (Públicas y para Auth)
Route::get('/carrito', [CartController::class, 'index'])->name('carrito.index');
Route::post('/carrito/add', [CartController::class, 'add'])->name('carrito.add');
Route::post('/carrito/update', [CartController::class, 'update'])->name('carrito.update');
Route::post('/carrito/remove', [CartController::class, 'remove'])->name('carrito.remove');
Route::post('/carrito/clear', [CartController::class, 'clear'])->name('carrito.clear');

// ─────────────────────────────────────────────────────────
// AUTENTICADAS — requieren login
// ─────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Carrito Checkout
    Route::post('/carrito/checkout', [CartController::class, 'checkout'])->name('carrito.checkout');

    // Dashboard personal (Breeze)
    Route::get('/dashboard', function () {
        // Estadísticas para el Administrador
        $totalProductos = \App\Models\Producto::count();
        $totalUsuarios = \App\Models\Usuario::count();
        $stockBajo = \App\Models\Producto::where('cantidad', '<', 5)->count();
        $ultimasBitacoras = \App\Models\Bitacora::orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.index', compact('totalProductos', 'totalUsuarios', 'stockBajo', 'ultimasBitacoras'));
    })->name('dashboard');

    // Perfil de usuario (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Inventario: CRUD de productos (cualquier empleado autenticado)
    Route::get('/api/producto/{id}', [ProductoController::class, 'getProducto']);
    Route::resource('productos', ProductoController::class);

    // Trabajos y asignaciones (cualquier empleado autenticado, la vista filtra por rol)
    Route::get('/trabajos', [TrabajoController::class, 'index'])->name('trabajos.index');

    // ─────────────────────────────────────────────────────
    // SOLO ADMIN — requieren login + ser administrador
    // ─────────────────────────────────────────────────────
    Route::middleware('admin')->group(function () {

        // Gestión de usuarios / personal
        Route::get('/api/usuario/{ci}', [UsuarioController::class, 'getUsuario']);
        Route::resource('usuarios', UsuarioController::class);

        // Crear roles y asignar trabajos
        Route::post('/trabajos/rol', [TrabajoController::class, 'store'])->name('trabajos.store');
        Route::post('/trabajos/asignar', [TrabajoController::class, 'asignar'])->name('trabajos.asignar');

        // Bitácora de auditoría
        Route::get('/bitacora', function () {
            $registros = \App\Models\Bitacora::orderBy('created_at', 'desc')->get();
            return view('bitacora.index', compact('registros'));
        })->name('bitacora.index');
    });
});

require __DIR__.'/auth.php';