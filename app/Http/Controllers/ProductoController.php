<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Para el árbol de abajo (solo las raíces/padres)
        $categorias = \App\Models\Categoria::whereNull('id_categoria_padre')
                        ->with(['subcategorias', 'productos'])
                        ->get();
                        
        // 2. Para el formulario (absolutamente TODAS las categorías)
        $categorias_formulario = \App\Models\Categoria::all();

        // Pasamos ambas variables a la vista
        return view('productos.index', compact('categorias', 'categorias_formulario'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 2. Guardar datos: Recibe la info del formulario y crea el producto en MySQL
        \App\Models\Producto::create($request->all());
        
        // Recarga la página para que el usuario vea el nuevo producto en la lista
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}