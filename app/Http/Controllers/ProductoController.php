<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
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
        $validated = $request->validate(
            [
                'idproducto' => ['required', 'integer', 'min:1', 'unique:producto,idproducto'],
                'nombre' => ['required', 'string', 'max:255'],
                'descripcion' => ['nullable', 'string', 'max:500'],
                'precio' => ['required', 'numeric', 'min:0'],
                'cantidad' => ['required', 'integer', 'min:0'],
                'id_marca' => ['required', 'integer', 'exists:marca,id'],
                'id_categoria' => ['required', 'integer', 'exists:categoria,idcategoria'],
            ],
            [
                'idproducto.required' => 'El ID del producto es obligatorio.',
                'idproducto.integer' => 'El ID del producto debe ser un número entero.',
                'idproducto.min' => 'El ID del producto debe ser mayor a 0.',
                'idproducto.unique' => 'Ese ID de producto ya existe.',
                'nombre.required' => 'El nombre del producto es obligatorio.',
                'nombre.max' => 'El nombre no puede superar 255 caracteres.',
                'descripcion.max' => 'La descripción no puede superar 500 caracteres.',
                'precio.required' => 'El precio es obligatorio.',
                'precio.numeric' => 'El precio debe ser numérico.',
                'precio.min' => 'El precio no puede ser negativo.',
                'cantidad.required' => 'La cantidad es obligatoria.',
                'cantidad.integer' => 'La cantidad debe ser un número entero.',
                'cantidad.min' => 'La cantidad no puede ser negativa.',
                'id_marca.required' => 'El ID de marca es obligatorio.',
                'id_marca.integer' => 'El ID de marca debe ser un número entero.',
                'id_marca.exists' => 'La marca indicada no existe.',
                'id_categoria.required' => 'Debes seleccionar una categoría.',
                'id_categoria.integer' => 'La categoría debe ser un valor numérico válido.',
                'id_categoria.exists' => 'La categoría seleccionada no existe.',
            ]
        );

        try {
            $producto = \App\Models\Producto::create($validated);

            // REGISTRO EN BITÁCORA
            \App\Models\Bitacora::registrar(
                'INSERTAR',
                'producto',
                $producto->idproducto,
                "Se creó el producto: {$producto->nombre}"
            );

            return redirect()->back()->with('success', 'Producto registrado correctamente.');
        } catch (QueryException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'form' => 'No se pudo guardar el producto. Verifica los datos ingresados e intenta nuevamente.',
                ]);
        }
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

    public function update(Request $request, string $id)
    {
        $validated = $request->validate(
            [
                'nombre' => ['required', 'string', 'max:255'],
                'descripcion' => ['nullable', 'string', 'max:500'],
                'precio' => ['required', 'numeric', 'min:0'],
                'cantidad' => ['required', 'integer', 'min:0'],
                'id_marca' => ['required', 'integer', 'exists:marca,id'],
                'id_categoria' => ['required', 'integer', 'exists:categoria,idcategoria'],
            ],
            [
                'nombre.required' => 'El nombre del producto es obligatorio.',
                'precio.required' => 'El precio es obligatorio.',
                'cantidad.required' => 'La cantidad es obligatoria.',
                'id_marca.required' => 'El ID de marca es obligatorio.',
                'id_categoria.required' => 'Debes seleccionar una categoría.',
            ]
        );

        try {
            $producto = \App\Models\Producto::where('idproducto', $id)->firstOrFail();
            $producto->update($validated);

            // REGISTRO EN BITÁCORA
            \App\Models\Bitacora::registrar(
                'ACTUALIZAR',
                'producto',
                $producto->idproducto,
                "Se actualizó el producto: {$producto->nombre}"
            );

            return redirect()->back()->with('success', 'Producto actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'form_modificar' => 'No se pudo actualizar el producto. Verifica los datos ingresados e intenta nuevamente.',
                ]);
        }
    }

    public function getProducto($id)
    {
        $producto = \App\Models\Producto::where('idproducto', $id)->first();
        if ($producto) {
            return response()->json(['success' => true, 'producto' => $producto]);
        }
        return response()->json(['success' => false, 'message' => 'Producto no encontrado'], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}