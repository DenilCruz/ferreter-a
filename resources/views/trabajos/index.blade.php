<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Roles y Trabajos - Ferretería Guisella</title>
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
            top: 0; left: 0; right: 0; z-index: 10;
            display: flex; flex-wrap: wrap; align-items: center; justify-content: flex-end; gap: 12px;
            padding: 14px 24px; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(15, 23, 42, 0.08); box-shadow: var(--shadow);
        }
        .topbar a { text-decoration: none; color: var(--accent); font-weight: 600; font-size: 0.9rem; }
        .topbar span { color: var(--muted); font-weight: 600; font-size: 0.9rem; }
        .btn-logout { background: var(--danger); color: #fff; border: none; padding: 8px 14px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.9rem; }
        
        .wrap { max-width: 1100px; margin: 0 auto; }
        h1 { font-size: 1.75rem; font-weight: 700; margin: 0 0 8px; }
        .subtitle { color: var(--muted); margin: 0 0 28px; }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }
        @media(min-width: 800px) {
            .dashboard-grid.admin-grid { grid-template-columns: 1fr 350px; }
        }

        .card { background: var(--surface); padding: 24px; border-radius: var(--radius); box-shadow: var(--shadow); }
        .card h2 { margin-top:0; font-size: 1.25rem; color: var(--accent); }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 0.95rem; }
        th { background: #334155; color: #fff; padding: 12px; text-align: left; }
        td { border-bottom: 1px solid #e2e8f0; padding: 12px; }
        tr:hover td { background: #f8fafc; }

        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 0.9rem; color: var(--text); }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; }
        .btn-save { background: linear-gradient(180deg, var(--accent-hover), var(--accent)); color: #fff; border: none; padding: 10px 18px; border-radius: 8px; cursor: pointer; font-weight: 600; width: 100%; }
        
        .alert { border-radius: 10px; padding: 12px 14px; margin-bottom: 14px; font-size: 0.9rem; }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        
        .badge { background: #e2e8f0; color: #475569; padding: 4px 8px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
        .badge.activo { background: #dcfce7; color: #166534; }
    </style>
</head>
<body>
    <div class="topbar">
        <span>Hola, {{ Auth::user()->name }}</span>
        <a href="{{ url('/') }}">Ir a Inventario</a>
        @can('admin')
        <a href="{{ url('/usuarios') }}">Directorio / Personal</a>
        @endcan
        <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>

    <div class="wrap">
        <h1>Centro de Asignaciones</h1>
        <p class="subtitle">Gestiona y revisa los trabajos y roles del personal.</p>

        @if(session('success_rol'))
            <div class="alert alert-success">{{ session('success_rol') }}</div>
        @endif
        @if(session('success_asignacion'))
            <div class="alert alert-success">{{ session('success_asignacion') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">Por favor revisa los datos ingresados e intenta otra vez.</div>
        @endif

        <div class="dashboard-grid {{ $isAdmin ? 'admin-grid' : '' }}">
            
            <div class="card">
                <h2>Tabla de Asignaciones</h2>
                @if($isAdmin)
                    <p style="font-size:0.9rem; color:var(--muted)">Visualizando todas las asignaciones de la empresa.</p>
                @else
                    <p style="font-size:0.9rem; color:var(--muted)">Visualizando únicamente los trabajos asignados para ti.</p>
                @endif

                <div class="table-wrap">
                    @if($asignaciones->count() > 0)
                        <table>
                            <thead>
                                <tr>
                                    <th>Rol/Trabajo</th>
                                    @if($isAdmin)<th>Empleado Asignado</th>@endif
                                    <th>Fec. Inicio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($asignaciones as $asignacion)
                                <tr>
                                    <td>
                                        <strong>{{ $asignacion->rol->nombre ?? 'N/A' }}</strong><br>
                                        <small style="color:var(--muted)">{{ $asignacion->rol->descripcion ?? '' }}</small>
                                    </td>
                                    @if($isAdmin)
                                    <td>
                                        {{ $asignacion->empleado->usuario->nombre ?? 'Desconocido' }} {{ $asignacion->empleado->usuario->apellido ?? '' }}<br>
                                        <small style="color:var(--muted)">CI: {{ $asignacion->ci_empleado }}</small>
                                    </td>
                                    @endif
                                    <td>{{ $asignacion->fechaInicio }}</td>
                                    <td><span class="badge {{ strtolower($asignacion->estado) == 'activo' ? 'activo' : '' }}">{{ $asignacion->estado }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div style="padding: 30px; text-align:center; background:#f8fafc; border-radius:8px; border:1px dashed #cbd5e1; margin-top:20px;">
                            <p style="color:var(--muted); font-weight:500;">No hay asignaciones registradas.</p>
                        </div>
                    @endif
                </div>
            </div>

            @can('admin')
            <div style="display:flex; flex-direction:column; gap:24px;">
                <div class="card">
                    <h2>Asignar Trabajo</h2>
                    <form action="{{ route('trabajos.asignar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Seleccionar Empleado</label>
                            <select name="ci_empleado" required>
                                <option value="">— Elegir Empleado —</option>
                                @foreach($empleados as $emp)
                                    <option value="{{ $emp->ci }}">
                                        {{ $emp->usuario->nombre ?? '' }} {{ $emp->usuario->apellido ?? '' }} (CI: {{ $emp->ci }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Designar Rol/Trabajo</label>
                            <select name="id_rol" required>
                                <option value="">— Elegir Trabajo —</option>
                                @foreach($rolesDisponibles as $r)
                                    <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-save">Guardar Asignación</button>
                    </form>
                </div>

                <div class="card" style="background:#f1f5f9; box-shadow:none; border:1px solid #e2e8f0;">
                    <h2>Registrar Nuevo Rol</h2>
                    <form action="{{ route('trabajos.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nombre del Trabajo</label>
                            <input type="text" name="nombre" placeholder="Ej: Gerente, Limpieza, Vendedor" required>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <input type="text" name="descripcion" placeholder="Breve descripción de las labores">
                        </div>
                        <button type="submit" class="btn-save" style="background:var(--accent);">Crear en Sistema</button>
                    </form>
                </div>
            </div>
            @endcan

        </div>
    </div>
</body>
</html>
