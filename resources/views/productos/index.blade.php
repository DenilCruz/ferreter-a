<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ferretería Guisella - Inventario</title>
    <style>
        :root {
            --bg: #e8eef3;
            --surface: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
            --accent: #0f766e;
            --accent-hover: #0d9488;
            --danger: #dc2626;
            --radius: 12px;
            --shadow: 0 1px 2px rgba(15, 23, 42, 0.06), 0 8px 24px rgba(15, 23, 42, 0.08);
        }
        * { box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            padding: 24px;
            padding-top: 88px;
            background: linear-gradient(160deg, var(--bg) 0%, #dbeafe 100%);
            color: var(--text);
            min-height: 100vh;
        }
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            padding: 14px 24px;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: var(--shadow);
        }
        .topbar a, .topbar button {
            font-size: 0.9rem;
        }
        .topbar a {
            text-decoration: none;
            color: var(--accent);
            font-weight: 600;
        }
        .topbar a:hover { color: var(--accent-hover); }
        .topbar span { color: var(--muted); font-weight: 600; }
        .btn-logout {
            background: var(--danger);
            color: #fff;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
        }
        .btn-logout:hover { filter: brightness(1.05); }
        .wrap { max-width: 1100px; margin: 0 auto; }
        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0 0 8px;
            color: var(--text);
        }
        .subtitle { color: var(--muted); margin: 0 0 28px; font-size: 0.95rem; }
        .form-container {
            background: var(--surface);
            padding: 24px;
            border-radius: var(--radius);
            margin-bottom: 28px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }
        .form-container h3 {
            margin: 0 0 18px;
            font-size: 1.1rem;
            color: var(--text);
        }
        .form-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        .form-grid input, .form-grid select {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.9rem;
            min-width: 120px;
        }
        .form-grid input:focus, .form-grid select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.2);
        }
        .btn-save {
            background: linear-gradient(180deg, var(--accent-hover), var(--accent));
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 2px 6px rgba(15, 118, 110, 0.35);
        }
        .btn-save:hover { filter: brightness(1.06); }
        .catalog {
            background: var(--surface);
            padding: 24px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }
        .catalog h2 { margin: 0 0 8px; font-size: 1.25rem; }
        .catalog .hint { color: var(--muted); margin: 0 0 20px; font-size: 0.9rem; }
        /* Árbol (nodo.blade.php) */
        .tree-details { margin-left: 4px; margin-bottom: 8px; }
        .tree-summary {
            cursor: pointer;
            font-weight: 600;
            padding: 10px 14px;
            background: linear-gradient(180deg, #f8fafc, #f1f5f9);
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            list-style: none;
        }
        .tree-summary::-webkit-details-marker { display: none; }
        .tree-summary::before { content: "▸ "; color: var(--accent); font-size: 0.85em; }
        details[open] > .tree-summary::before { content: "▾ "; }
        .tree-inner {
            padding-left: 16px;
            border-left: 2px solid #cbd5e1;
            margin: 10px 0 0 8px;
        }
        .tree-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 0.9rem;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .tree-table th {
            background: #334155;
            color: #fff;
            padding: 10px 12px;
            text-align: left;
            font-weight: 600;
        }
        .tree-table td {
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 12px;
            background: #fafafa;
        }
        .tree-table tr:last-child td { border-bottom: none; }
    </style>
</head>
<body>
    <div class="topbar">
        @auth
            <span>Hola, {{ Auth::user()->name }}</span>
            <a href="{{ url('/dashboard') }}">Mi perfil</a>
            <a href="{{ url('/bitacora') }}">Bitácora</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                @csrf
                <button type="submit" class="btn-logout">Cerrar sesión</button>
            </form>
        @else
            <a href="{{ route('login') }}">Iniciar sesión</a>
            <a href="{{ route('register') }}">Registrarse</a>
        @endauth
    </div>

    <div class="wrap">
        <h1>Inventario</h1>
        <p class="subtitle">Ferretería Guisella — catálogo y altas de producto</p>

        <div class="form-container">
            <h3>Agregar producto</h3>
            <form action="{{ route('productos.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <input type="number" name="idproducto" placeholder="ID producto" required>
                    <input type="text" name="nombre" placeholder="Nombre" required>
                    <input type="text" name="descripcion" placeholder="Descripción">
                    <input type="number" step="0.01" name="precio" placeholder="Precio (Bs)" required>
                    <input type="number" name="cantidad" placeholder="Stock inicial" required>
                    <input type="number" name="id_marca" placeholder="ID marca" required>
                    <select name="id_categoria" required>
                        <option value="">— Categoría —</option>
                        @foreach($categorias_formulario as $cat)
                            <option value="{{ $cat->idcategoria }}">
                                {{ $cat->id_categoria_padre ? '— ' : '' }}{{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-save">Guardar</button>
                </div>
            </form>
        </div>

        <div class="catalog">
            <h2>Catálogo</h2>
            <p class="hint">Despliega cada categoría para ver productos y subcategorías.</p>
            @foreach($categorias as $categoria)
                @include('productos.nodo', ['categoria' => $categoria])
            @endforeach
        </div>
    </div>
</body>
</html>
