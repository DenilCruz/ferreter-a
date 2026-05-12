<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TrabajoController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CategoriaController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────
// PÚBLICAS — accesibles sin login
// ─────────────────────────────────────────────────────────
Route::get('/', [ProductoController::class, 'index'])->name('inventario');
Route::get('/catalogo/producto/{id}', [ProductoController::class, 'showPublic'])->name('producto.show');

// Marcas públicas
Route::get('/marcas', [MarcaController::class, 'indexPublic'])->name('marcas.index');
Route::get('/marcas/{id}/productos', [MarcaController::class, 'productosPorMarca'])->name('marcas.productos');

// Categorías públicas
Route::get('/categorias', [CategoriaController::class, 'indexPublic'])->name('categorias.index');
Route::get('/categorias/{id}/productos', [CategoriaController::class, 'productosPorCategoria'])->name('categorias.productos');
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
        $isAdmin = \Illuminate\Support\Facades\Gate::allows('admin');

        $totalProductos = null;
        $totalUsuarios  = null;
        $stockBajo      = null;
        $ultimasBitacoras = collect();

        if ($isAdmin) {
            $totalProductos   = \App\Models\Producto::count();
            $totalUsuarios    = \App\Models\Usuario::count();
            $stockBajo        = \App\Models\Producto::where('cantidad', '<', 5)->count();
            $ultimasBitacoras = \App\Models\Bitacora::orderBy('created_at', 'desc')->take(5)->get();
        }

        return view('dashboard.index', compact('isAdmin', 'totalProductos', 'totalUsuarios', 'stockBajo', 'ultimasBitacoras'));
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

        // Admin: Marcas
        Route::get('/admin/marcas', [MarcaController::class, 'index'])->name('admin.marcas.index');
        Route::get('/admin/marcas/crear', [MarcaController::class, 'create'])->name('admin.marcas.create');
        Route::post('/admin/marcas', [MarcaController::class, 'store'])->name('admin.marcas.store');
        Route::get('/admin/marcas/{id}/editar', [MarcaController::class, 'edit'])->name('admin.marcas.edit');
        Route::put('/admin/marcas/{id}', [MarcaController::class, 'update'])->name('admin.marcas.update');
        Route::delete('/admin/marcas/{id}', [MarcaController::class, 'destroy'])->name('admin.marcas.destroy');

        // Admin: Categorías (Admin + Almacenero, la autorización se hace en el controlador)
        Route::get('/admin/categorias', [CategoriaController::class, 'index'])->name('admin.categorias.index');
        Route::get('/admin/categorias/crear', [CategoriaController::class, 'create'])->name('admin.categorias.create');
        Route::post('/admin/categorias', [CategoriaController::class, 'store'])->name('admin.categorias.store');
        Route::get('/admin/categorias/{id}/editar', [CategoriaController::class, 'edit'])->name('admin.categorias.edit');
        Route::put('/admin/categorias/{id}', [CategoriaController::class, 'update'])->name('admin.categorias.update');
        Route::delete('/admin/categorias/{id}', [CategoriaController::class, 'destroy'])->name('admin.categorias.destroy');

        // Gestión de usuarios / personal
        Route::get('/api/usuario/{ci}', [UsuarioController::class, 'getUsuario']);
        Route::resource('usuarios', UsuarioController::class);

        // Crear roles y asignar trabajos
        Route::post('/trabajos/rol', [TrabajoController::class, 'store'])->name('trabajos.store');
        Route::post('/trabajos/asignar', [TrabajoController::class, 'asignar'])->name('trabajos.asignar');
        Route::post('/trabajos/baja', [TrabajoController::class, 'darDeBaja'])->name('trabajos.baja');

        // Bitácora de auditoría
        Route::get('/bitacora', function () {
            $registros = \App\Models\Bitacora::orderBy('created_at', 'desc')->get();
            return view('bitacora.index', compact('registros'));
        })->name('bitacora.index');

        // Gestión de Cajas
        Route::get('/caja', [\App\Http\Controllers\Admin\CajaController::class, 'index'])->name('caja.index');
        Route::post('/caja/apertura', [\App\Http\Controllers\Admin\CajaController::class, 'apertura'])->name('caja.apertura');
        Route::post('/caja/corte/{caja}', [\App\Http\Controllers\Admin\CajaController::class, 'corte'])->name('caja.corte');
        Route::get('/caja/reporte/{caja}', [\App\Http\Controllers\Admin\CajaController::class, 'reporte'])->name('caja.reporte');
    });
});

require __DIR__.'/auth.php';