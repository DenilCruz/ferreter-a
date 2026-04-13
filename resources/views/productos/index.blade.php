<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ferretería Guisella - Inventario</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
        .categoria-section { background: white; padding: 15px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #eee; padding: 10px; text-align: left; }
        th { background: #333; color: white; }
        .form-container { background: #e9ecef; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        input, select, button { padding: 8px; margin: 5px; }
    </style>
</head>
<body>
    <div style="position: absolute; top: 20px; right: 30px; background: #fff; padding: 10px 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        @auth
            <span style="margin-right: 15px; font-weight: bold;">Hola, {{ Auth::user()->name }}</span>
            <a href="{{ url('/dashboard') }}" style="margin-right: 15px; text-decoration: none; color: #333;">Mi Perfil</a>
            
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" style="background: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer;">Cerrar Sesión</button>
            </form>
        @else
            <a href="{{ route('login') }}" style="margin-right: 15px; text-decoration: none; color: #007bff; font-weight: bold;">Iniciar Sesión</a>
            <a href="{{ route('register') }}" style="text-decoration: none; color: #28a745; font-weight: bold;">Registrarse</a>
        @endauth
    </div>

    <h1>Gestión de Inventario - Ferretería</h1>

    <div class="form-container">
        <h3>Agregar Nuevo Producto</h3>
        <form action="{{ route('productos.store') }}" method="POST">
            @csrf
            <input type="number" name="idproducto" placeholder="ID Producto" required>
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="descripcion" placeholder="Descripción">
            <input type="number" step="0.01" name="precio" placeholder="Precio (Bs)" required>
            <input type="number" name="cantidad" placeholder="Stock Inicial" required>
            <input type="number" name="id_marca" placeholder="ID de la Marca" required>
            
            <select name="id_categoria" required>
                <option value="">-- Seleccionar Categoría --</option>
                @foreach($categorias_formulario as $cat)
                    <option value="{{ $cat->idcategoria }}">
                        {{ $cat->id_categoria_padre ? '--- ' : '' }}{{ $cat->nombre }}
                    </option>
                @endforeach
            </select>
            <button type="submit" style="background: green; color: white; border: none; cursor: pointer;">Guardar Producto</button>
        </form>
    </div>

    <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <h2>Catálogo de Inventario</h2>
        <p><i>Haga clic en las categorias para ver sus productos.</i></p>
        
        @foreach($categorias as $categoria)
            @include('productos.nodo', ['categoria' => $categoria])
        @endforeach
    </div>

</body>
</html>