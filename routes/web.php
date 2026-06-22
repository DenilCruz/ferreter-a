<?php

use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────
// PÚBLICAS — accesibles sin login
// ─────────────────────────────────────────────────────────

// ─────────────────────────────────────────────────────────
// AUTENTICADAS — requieren login
// ─────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard personal (Breeze)
    Route::get('/dashboard', function () {
        $isAdmin = \Illuminate\Support\Facades\Gate::allows('admin');

        $totalProductos = null;
        $totalUsuarios  = null;
        $stockBajo      = null;
        $ultimasBitacoras = collect();

        if ($isAdmin) {
            $totalProductos   = \Modules\Inventory\Models\Producto::count();
            $totalUsuarios    = \Modules\Access\Models\Usuario::count();
            $stockBajo        = \Modules\Inventory\Models\Producto::where('cantidad', '<', 5)->count();
            $ultimasBitacoras = \Modules\Audit\Models\Bitacora::orderBy('created_at', 'desc')->take(5)->get();
        }

        return view('dashboard.index', compact('isAdmin', 'totalProductos', 'totalUsuarios', 'stockBajo', 'ultimasBitacoras'));
    })->name('dashboard');
});