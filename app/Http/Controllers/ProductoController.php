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
        // 1. Cargamos las categorías raíces con sus productos y subcategorías
        $categorias = \App\Models\Categoria::whereNull('id_categoria_padre')
                        ->with(['subcategorias', 'productos'])
                        ->get();
                        
        // 2. Cargamos todas las categorías para los select de los formularios
        $categorias_formulario = \App\Models\Categoria::all();

        // 3. DETERMINAR VISTA SEGÚN ROL
        // Administrador
        if (\Illuminate\Support\Facades\Gate::allows('admin')) {
            return view('inventario.roles.admin', compact('categorias', 'categorias_formulario'));
        }

        // Almacenero
        if (\Illuminate\Support\Facades\Gate::allows('almacenero')) {
            return view('inventario.roles.almacenero', compact('categorias', 'categorias_formulario'));
        }

        // Público / Cliente
        return view('inventario.roles.cliente', compact('categorias', 'categorias_formulario'));
    }

    /**
     * Muestra el formulario para crear un nuevo producto.
     */
    public function create()
    {
        $categorias_formulario = \App\Models\Categoria::all();
        $marcas = \App\Models\Marca::all();
        return view('inventario.create', compact('categorias_formulario', 'marcas'));
    }

    /**
     * Guarda el nuevo producto.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idproducto' => ['required', 'integer', 'unique:producto,idproducto'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'cantidad' => ['required', 'integer', 'min:0'],
            'id_marca' => ['required', 'integer'],
            'id_categoria' => ['required', 'integer'],
        ]);

        $producto = \App\Models\Producto::create($validated);
        
        \App\Models\Bitacora::registrar('INSERTAR', 'producto', $producto->idproducto, "Creación de producto: {$producto->nombre}");

        return redirect()->route('inventario')->with('success', '¡Producto creado con éxito!');
    }

    /**
     * Muestra el formulario para editar un producto.
     */
    public function edit($id)
    {
        $producto = \App\Models\Producto::where('idproducto', $id)->firstOrFail();
        $categorias_formulario = \App\Models\Categoria::all();
        $marcas = \App\Models\Marca::all();
        return view('inventario.edit', compact('producto', 'categorias_formulario', 'marcas'));
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
                'fechacaducidad' => ['nullable', 'date'],
                'id_color' => ['nullable', 'integer'],
                'id_medida' => ['nullable', 'integer'],
                'id_volumen' => ['nullable', 'integer'],
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

    public function showPublic($id)
    {
        // ElOrFail valida que exista y lanza 404 si no.
        $producto = \App\Models\Producto::with(['categoria', 'marca', 'color', 'medida', 'volumen'])
                        ->where('idproducto', $id)
                        ->firstOrFail();

        return view('inventario.producto-detalle', compact('producto'));
    }

    public function destroy(string $id)
    {
        try {
            $producto = \App\Models\Producto::where('idproducto', $id)->firstOrFail();
            $nombre = $producto->nombre;
            $producto->delete();

            // REGISTRO EN BITÁCORA
            \App\Models\Bitacora::registrar(
                'ELIMINAR',
                'producto',
                $id,
                "Se eliminó el producto: {$nombre}"
            );

            return redirect()->back()->with('success', 'Producto eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors([
                    'form_eliminar' => 'No se pudo eliminar el producto. Verifica que no existan otras referencias a este antes de eliminarlo.',
                ]);
        }
    }
}