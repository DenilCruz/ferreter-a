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
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            align-items: start;
        }
        .field { display: flex; flex-direction: column; gap: 6px; }
        .form-grid input, .form-grid select {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.9rem;
            min-width: 0;
            width: 100%;
        }
        .field input.is-invalid, .field select.is-invalid {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
        }
        .error-text {
            color: #b91c1c;
            font-size: 0.8rem;
            margin: 0;
        }
        .alert {
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 14px;
            font-size: 0.9rem;
            border: 1px solid transparent;
        }
        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border-color: #fecaca;
        }
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border-color: #a7f3d0;
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
            align-self: end;
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
        .action-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }
        .btn-action {
            background: var(--surface);
            color: var(--accent);
            border: 2px solid var(--accent);
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
        }
        .btn-action:hover, .btn-action.active {
            background: var(--accent);
            color: #fff;
        }
        .d-none {
            display: none !important;
        }
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
        <h1>Ferretería Guisella — catálogo y altas de producto</h1>
        <p class="subtitle">Inventario</p>

        <div class="action-buttons">
            <button class="btn-action" id="btn-toggle-agregar" onclick="toggleForm('agregar')">Agregar producto</button>
            <button class="btn-action" id="btn-toggle-modificar" onclick="toggleForm('modificar')">Modificar producto</button>
            <button class="btn-action" id="btn-toggle-eliminar" onclick="toggleForm('eliminar')" style="color: var(--danger); border-color: var(--danger);">Eliminar producto</button>
        </div>

        <div class="form-container d-none" id="container-agregar">
            <h3>Agregar producto</h3>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->has('form'))
                <div class="alert alert-error">
                    {{ $errors->first('form') }}
                </div>
            @endif
            <form action="{{ route('productos.store') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <input type="number" name="idproducto" value="{{ old('idproducto') }}" placeholder="ID producto" class="@error('idproducto') is-invalid @enderror" required>
                        @error('idproducto')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <input type="text" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre" class="@error('nombre') is-invalid @enderror" required>
                        @error('nombre')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <input type="text" name="descripcion" value="{{ old('descripcion') }}" placeholder="Descripción" class="@error('descripcion') is-invalid @enderror">
                        @error('descripcion')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" placeholder="Precio (Bs)" class="@error('precio') is-invalid @enderror" required>
                        @error('precio')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <input type="number" name="cantidad" value="{{ old('cantidad') }}" placeholder="Stock inicial" class="@error('cantidad') is-invalid @enderror" required>
                        @error('cantidad')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <input type="number" name="id_marca" value="{{ old('id_marca') }}" placeholder="ID marca" class="@error('id_marca') is-invalid @enderror" required>
                        @error('id_marca')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <select name="id_categoria" class="@error('id_categoria') is-invalid @enderror" required>
                            <option value="">— Categoría —</option>
                            @foreach($categorias_formulario as $cat)
                                <option value="{{ $cat->idcategoria }}" {{ old('id_categoria') == $cat->idcategoria ? 'selected' : '' }}>
                                    {{ $cat->id_categoria_padre ? '— ' : '' }}{{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_categoria')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <input type="date" name="fechacaducidad" value="{{ old('fechacaducidad') }}" placeholder="Fecha Caducidad" class="@error('fechacaducidad') is-invalid @enderror">
                        @error('fechacaducidad')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <input type="number" name="idcolor" value="{{ old('idcolor') }}" placeholder="ID Color" class="@error('idcolor') is-invalid @enderror">
                        @error('idcolor')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <input type="number" name="idmedida" value="{{ old('idmedida') }}" placeholder="ID Medida" class="@error('idmedida') is-invalid @enderror">
                        @error('idmedida')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="field">
                        <input type="number" name="idvolumen" value="{{ old('idvolumen') }}" placeholder="ID Volumen" class="@error('idvolumen') is-invalid @enderror">
                        @error('idvolumen')
                            <p class="error-text">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn-save">Guardar</button>
                </div>
            </form>
        </div>

        <div class="form-container d-none" id="container-modificar">
            <h3>Modificar producto</h3>
            <p id="modificar-error-msg" class="error-text d-none" style="margin-bottom: 12px;"></p>
            @if ($errors->has('form_modificar'))
                <div class="alert alert-error">
                    {{ $errors->first('form_modificar') }}
                </div>
            @endif
            <form id="form-modificar" action="{{ url('productos') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <div class="field">
                        <input type="number" id="idproducto-modificar" name="idproducto" placeholder="ID producto a modificar" required>
                    </div>
                    <div class="field">
                        <input type="text" id="modnombre" name="nombre" placeholder="Nombre" required>
                    </div>
                    <div class="field">
                        <input type="text" id="moddescripcion" name="descripcion" placeholder="Descripción">
                    </div>
                    <div class="field">
                        <input type="number" step="0.01" id="modprecio" name="precio" placeholder="Precio (Bs)" required>
                    </div>
                    <div class="field">
                        <input type="number" id="modcantidad" name="cantidad" placeholder="Stock" required>
                    </div>
                    <div class="field">
                        <input type="number" id="modmarca" name="id_marca" placeholder="ID marca" required>
                    </div>
                    <div class="field">
                        <select id="modcategoria" name="id_categoria" required>
                            <option value="">— Categoría —</option>
                            @foreach($categorias_formulario as $cat)
                                <option value="{{ $cat->idcategoria }}">
                                    {{ $cat->id_categoria_padre ? '— ' : '' }}{{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <input type="date" id="modfechacaducidad" name="fechacaducidad" placeholder="Fecha Caducidad">
                    </div>
                    <div class="field">
                        <input type="number" id="modcolor" name="idcolor" placeholder="ID Color">
                    </div>
                    <div class="field">
                        <input type="number" id="modmedida" name="idmedida" placeholder="ID Medida">
                    </div>
                    <div class="field">
                        <input type="number" id="modvolumen" name="idvolumen" placeholder="ID Volumen">
                    </div>
                    <button type="submit" class="btn-save">Actualizar</button>
                </div>
            </form>
        </div>

        <div class="form-container d-none" id="container-eliminar" style="border-color: #fca5a5; background-color: #fef2f2;">
            <h3 style="color: var(--danger);">Eliminar producto</h3>
            <p id="eliminar-error-msg" class="error-text d-none" style="margin-bottom: 12px;"></p>
            @if ($errors->has('form_eliminar'))
                <div class="alert alert-error">
                    {{ $errors->first('form_eliminar') }}
                </div>
            @endif
            <form id="form-eliminar" action="{{ url('productos') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-grid">
                    <div class="field">
                        <input type="number" id="idproducto-eliminar" name="idproducto" placeholder="ID producto a eliminar" required style="border-color: #fca5a5;">
                    </div>
                    <div class="field">
                        <input type="text" id="elnombre" placeholder="Nombre (auto)" readonly style="background:#f1f5f9; cursor:not-allowed;">
                    </div>
                    <div class="field">
                        <input type="text" id="elprecio" placeholder="Precio (auto)" readonly style="background:#f1f5f9; cursor:not-allowed;">
                    </div>
                    <button type="submit" class="btn-logout" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.');" style="grid-column: 1 / -1; margin-top:10px; width: 100%;">Eliminar permanentemente</button>
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
<script>
    function toggleForm(tipo) {
        document.getElementById('container-agregar').classList.add('d-none');
        document.getElementById('container-modificar').classList.add('d-none');
        document.getElementById('container-eliminar').classList.add('d-none');
        document.getElementById('btn-toggle-agregar').classList.remove('active');
        document.getElementById('btn-toggle-modificar').classList.remove('active');
        document.getElementById('btn-toggle-eliminar').classList.remove('active');

        if (tipo !== 'ninguno') {
            document.getElementById('container-' + tipo).classList.remove('d-none');
            document.getElementById('btn-toggle-' + tipo).classList.add('active');
        }
    }

    // Al arrancar, si hay errores form, abrimos agregar. Si de form_modificar abrimos modificar.
    @if(session('success') && request()->has('eliminar'))
        // No abrir forms si se eliminó con exito
    @elseif(session('success') && !request()->has('modificar'))
        toggleForm('agregar');
    @elseif($errors->has('form'))
        toggleForm('agregar');
    @elseif($errors->has('form_modificar') || request()->has('modificar'))
        toggleForm('modificar');
    @elseif($errors->has('form_eliminar'))
        toggleForm('eliminar');
    @endif

    document.getElementById('idproducto-modificar').addEventListener('input', function() {
        clearTimeout(window.modificarTimeout);
        let errorMsg = document.getElementById('modificar-error-msg');
        errorMsg.classList.add('d-none');
        errorMsg.textContent = '';
        
        let id = this.value;
        if (!id) return;

        window.modificarTimeout = setTimeout(() => {
            fetch('/api/producto/' + id)
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        let p = data.producto;
                        document.getElementById('modnombre').value = p.nombre;
                        document.getElementById('moddescripcion').value = p.descripcion || '';
                        document.getElementById('modprecio').value = p.precio;
                        document.getElementById('modcantidad').value = p.cantidad;
                        document.getElementById('modmarca').value = p.id_marca;
                        document.getElementById('modcategoria').value = p.id_categoria;
                        document.getElementById('modfechacaducidad').value = p.fechacaducidad || '';
                        document.getElementById('modcolor').value = p.idcolor || '';
                        document.getElementById('modmedida').value = p.idmedida || '';
                        document.getElementById('modvolumen').value = p.idvolumen || '';
                        // Forzar el endpoint correcto en el formulario
                        document.getElementById('form-modificar').action = "{{ url('productos') }}/" + id + "?modificar=1";
                    } else {
                        errorMsg.textContent = 'Ese producto no existe.';
                        errorMsg.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    errorMsg.textContent = 'Error al buscar el producto.';
                    errorMsg.classList.remove('d-none');
                });
        }, 500); 
    });

    document.getElementById('idproducto-eliminar').addEventListener('input', function() {
        clearTimeout(window.eliminarTimeout);
        let errorMsg = document.getElementById('eliminar-error-msg');
        errorMsg.classList.add('d-none');
        errorMsg.textContent = '';
        
        let id = this.value;
        if (!id) {
            document.getElementById('elnombre').value = '';
            document.getElementById('elprecio').value = '';
            return;
        }

        window.eliminarTimeout = setTimeout(() => {
            fetch('/api/producto/' + id)
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        let p = data.producto;
                        document.getElementById('elnombre').value = p.nombre;
                        document.getElementById('elprecio').value = p.precio + ' Bs';
                        document.getElementById('form-eliminar').action = "{{ url('productos') }}/" + id + "?eliminar=1";
                    } else {
                        errorMsg.textContent = 'Ese producto no existe.';
                        errorMsg.classList.remove('d-none');
                        document.getElementById('elnombre').value = '';
                        document.getElementById('elprecio').value = '';
                    }
                })
                .catch(err => {
                    errorMsg.textContent = 'Error al buscar el producto.';
                    errorMsg.classList.remove('d-none');
                });
        }, 500); 
    });
</script>
</html>
