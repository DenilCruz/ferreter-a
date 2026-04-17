<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Usuarios - Ferretería Guisella</title>
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
            margin: 0; padding: 24px; padding-top: 88px;
            background: linear-gradient(160deg, var(--bg) 0%, #dbeafe 100%);
            color: var(--text); min-height: 100vh;
        }
        .topbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 10;
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
        
        .action-buttons { display: flex; gap: 12px; margin-bottom: 24px; }
        .btn-action {
            background: var(--surface); color: var(--accent); border: 2px solid var(--accent);
            padding: 12px 24px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem; transition: all 0.2s;
        }
        .btn-action:hover, .btn-action.active { background: var(--accent); color: #fff; }
        
        .form-container { background: var(--surface); padding: 24px; border-radius: var(--radius); margin-bottom: 28px; box-shadow: var(--shadow); border: 1px solid rgba(15, 23, 42, 0.06); }
        .form-container h3 { margin: 0 0 18px; font-size: 1.1rem; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; align-items: start; }
        .field { display: flex; flex-direction: column; gap: 6px; }
        .form-grid input, .form-grid select { padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; width: 100%; }
        .btn-save { background: linear-gradient(180deg, var(--accent-hover), var(--accent)); color: #fff; border: none; padding: 10px 18px; border-radius: 8px; cursor: pointer; font-weight: 600; align-self: end; }
        
        .catalog { background: var(--surface); padding: 24px; border-radius: var(--radius); box-shadow: var(--shadow); border: 1px solid rgba(15, 23, 42, 0.06); }
        .catalog h2 { margin: 0 0 8px; font-size: 1.25rem; }
        .table-wrap { overflow-x: auto; margin-top: 12px; }
        table { width: 100%; border-collapse: collapse; font-size: 0.95rem; }
        th { background: #334155; color: #fff; padding: 12px; text-align: left; }
        td { border-bottom: 1px solid #e2e8f0; padding: 12px; }
        tr:hover td { background: #f8fafc; }
        
        .badge { background: #e2e8f0; color: #475569; padding: 4px 8px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
        
        .d-none { display: none !important; }
        .error-text { color: #b91c1c; font-size: 0.8rem; margin: 0; }
        .alert { border-radius: 10px; padding: 12px 14px; margin-bottom: 14px; font-size: 0.9rem; }
        .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* Estilos de tabla del Estudio (Mini Modal/Box) */
        .study-box { background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px dashed #cbd5e1; margin-top: 15px; }
        .task-list { list-style: none; padding: 0; margin: 0; }
        .task-list li { background: white; padding: 10px; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-bottom: 8px; border-left: 3px solid var(--accent); }
    </style>
</head>
<body>
    <div class="topbar">
        <span>Hola, {{ Auth::user()->name }}</span>
        <a href="{{ url('/') }}">Ir a Inventario</a>
        <a href="{{ url('/trabajos') }}">Trabajos Asignados</a>
        <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>
    </div>

    <div class="wrap">
        <h1>Gestión de Personal y Usuarios</h1>
        <p class="subtitle">Directorio de recursos humanos, administradores y clientes.</p>

        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('success_eliminar')) <div class="alert alert-success">{{ session('success_eliminar') }}</div> @endif
        @if(session('error_general')) <div class="alert alert-error">{{ session('error_general') }}</div> @endif
        @if($errors->any()) <div class="alert alert-error">{{ $errors->first() }}</div> @endif

        @can('admin')
        <div class="action-buttons">
            <button class="btn-action" id="btn-toggle-estudio" onclick="toggleForm('estudio')">Estudio Téc. (Ver Info)</button>
            <button class="btn-action" id="btn-toggle-modificar" onclick="toggleForm('modificar')">Modificar Perfil</button>
            <button class="btn-action" id="btn-toggle-eliminar" onclick="toggleForm('eliminar')" style="color: var(--danger); border-color: var(--danger);">Borrar Acceso</button>
        </div>

        <!-- FORMULARIO ESTUDIO -->
        <div class="form-container d-none" id="container-estudio">
            <h3>Estudio Analítico de Usuario</h3>
            <p id="estudio-error-msg" class="error-text d-none" style="margin-bottom: 12px;"></p>
            <div class="form-grid">
                <div class="field">
                    <label>Carnet ID a estudiar:</label>
                    <input type="text" id="ci-estudio" placeholder="Ingrese CI de la persona..." autocomplete="off">
                </div>
            </div>
            
            <div id="study-results" class="study-box d-none">
                <h4 style="margin: 0 0 10px 0; color:var(--accent);">Ficha Personal: <span id="study-name"></span></h4>
                <p style="margin: 5px 0; font-size: 0.9rem;"><strong>Correo Contacto:</strong> <span id="study-mail"></span></p>
                <p style="margin: 5px 0; font-size: 0.9rem;"><strong>Tipo Perfil:</strong> <span id="study-tipo"></span></p>
                
                <h4 style="margin: 15px 0 10px 0; color:#334155;">Historial de Roles / Asignaciones Activas:</h4>
                <ul class="task-list" id="study-tasks">
                    <!-- Javascript rellenará aquí -->
                </ul>
            </div>
        </div>

        <!-- FORMULARIO MODIFICAR -->
        <div class="form-container d-none" id="container-modificar">
            <h3>Modificar Perfil Operativo</h3>
            
            <div class="form-grid" style="margin-bottom: 10px;">
                <div class="field" style="grid-column: 1 / -1;">
                    <label>Buscar Carnet por Modificar:</label>
                    <input type="text" id="ci-modificar" placeholder="ID de la persona a editar..." style="max-width: 300px;">
                    <span id="modificar-error-msg" class="error-text d-none"></span>
                </div>
            </div>
            <form id="form-modificar" action="#" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid" style="padding-top:10px; border-top: 1px solid #e2e8f0;">
                    <div class="field"><label>Nombre</label><input type="text" id="modnombre" name="nombre" required></div>
                    <div class="field"><label>Apellido</label><input type="text" id="modapellido" name="apellido" required></div>
                    <div class="field"><label>Teléfono</label><input type="text" id="modtelefono" name="telefono"></div>
                    <div class="field">
                        <label>Sexo</label>
                        <select id="modsexo" name="sexo">
                            <option value="M">Masculino (M)</option>
                            <option value="F">Femenino (F)</option>
                        </select>
                    </div>
                    <div class="field"><label>Correo Electrónico (Auth)</label><input type="email" id="modcorreo" name="correo" required></div>
                    <div class="field"><label>Domicilio</label><input type="text" id="moddomicilio" name="domicilio"></div>
                    <div class="field"><label>Tipo de Persona</label><input type="text" id="modtipo" name="tipoPersona" required></div>
                    <button type="submit" class="btn-save" style="margin-top: 15px;">Actualizar</button>
                </div>
            </form>
        </div>

        <!-- FORMULARIO ELIMINAR -->
        <div class="form-container d-none" id="container-eliminar" style="border-color: #fca5a5; background-color: #fef2f2;">
            <h3 style="color: var(--danger);">Eliminar usuario irrevocablemente</h3>
            <form id="form-eliminar" action="#" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-grid">
                    <div class="field">
                        <input type="text" id="ci-eliminar" name="ci" placeholder="Digita el CI de la persona" required style="border-color: #fca5a5;">
                        <span id="eliminar-error-msg" class="error-text d-none"></span>
                    </div>
                    <div class="field"><input type="text" id="delnombre" placeholder="Sujeto (Autocompletado)" readonly style="background:#f1f5f9; cursor:not-allowed;"></div>
                    <button type="submit" class="btn-logout" onclick="return confirm('¿Confirmas borrar esta alma tanto del Motor de Tienda como del Motor Auth?');" style="grid-column: 1 / -1; margin-top:10px; width: 100%;">Destruir Cuenta</button>
                </div>
            </form>
        </div>
        @endcan

        <div class="catalog">
            <h2>Directorio General</h2>
            <div class="table-wrap">
                @if($usuarios->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>CI (Identidad)</th>
                                <th>Nombre Completo</th>
                                <th>Teléfono</th>
                                <th>Correo Sincronizado</th>
                                <th>Rol Social</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $u)
                            <tr>
                                <td><span class="badge">{{ $u->ci }}</span></td>
                                <td><strong>{{ $u->nombre }} {{ $u->apellido }}</strong></td>
                                <td style="color:var(--muted)">{{ $u->telefono ?? 'S/R' }}</td>
                                <td>{{ $u->correo }}</td>
                                <td>{{ $u->tipoPersona }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p style="color:var(--muted); font-weight:500;">No hay base de usuarios instalada.</p>
                @endif
            </div>
        </div>
    </div>

<script>
    function toggleForm(tipo) {
        document.getElementById('container-estudio').classList.add('d-none');
        document.getElementById('container-modificar').classList.add('d-none');
        document.getElementById('container-eliminar').classList.add('d-none');
        document.querySelectorAll('.btn-action').forEach(b => b.classList.remove('active'));

        document.getElementById('container-' + tipo).classList.remove('d-none');
        document.getElementById('btn-toggle-' + tipo).classList.add('active');
    }

    // LÓGICA DEL ESTUDIO (SHOW)
    document.getElementById('ci-estudio').addEventListener('input', function() {
        clearTimeout(window.estudioTimeout);
        let errorMsg = document.getElementById('estudio-error-msg');
        let studyBox = document.getElementById('study-results');
        errorMsg.classList.add('d-none');
        studyBox.classList.add('d-none');
        
        let ci = this.value;
        if (!ci) return;

        window.estudioTimeout = setTimeout(() => {
            fetch('/api/usuario/' + ci)
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        let u = data.usuario;
                        document.getElementById('study-name').textContent = u.nombre + ' ' + (u.apellido || '');
                        document.getElementById('study-mail').textContent = u.correo;
                        document.getElementById('study-tipo').textContent = u.tipoPersona;
                        
                        let taskList = document.getElementById('study-tasks');
                        taskList.innerHTML = '';
                        if(data.detalles_estudio_asignaciones && data.detalles_estudio_asignaciones.length > 0) {
                            data.detalles_estudio_asignaciones.forEach(asig => {
                                let li = document.createElement('li');
                                li.innerHTML = `<strong>${asig.rol.nombre}:</strong> Iniciado ${asig.fechaInicio}. <span class="badge">${asig.estado}</span>`;
                                taskList.appendChild(li);
                            });
                        } else {
                            taskList.innerHTML = '<li style="border-left:3px solid #cbd5e1; color:#64748b;">El sistema indica que este usuario no tiene historial de labores asignadas.</li>';
                        }

                        studyBox.classList.remove('d-none');
                    } else {
                        errorMsg.textContent = 'Individuo no hallado en la base de datos empresarial.';
                        errorMsg.classList.remove('d-none');
                    }
                });
        }, 500);
    });

    // LÓGICA DE MODIFICAR (UPDATE)
    document.getElementById('ci-modificar').addEventListener('input', function() {
        clearTimeout(window.modTimeout);
        let errorMsg = document.getElementById('modificar-error-msg');
        errorMsg.classList.add('d-none');
        
        let ci = this.value;
        if (!ci) return;

        window.modTimeout = setTimeout(() => {
            fetch('/api/usuario/' + ci)
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        let u = data.usuario;
                        document.getElementById('modnombre').value = u.nombre;
                        document.getElementById('modapellido').value = u.apellido || '';
                        document.getElementById('modtelefono').value = u.telefono || '';
                        document.getElementById('modsexo').value = u.sexo || 'M';
                        document.getElementById('modcorreo').value = u.correo;
                        document.getElementById('moddomicilio').value = u.domicilio || '';
                        document.getElementById('modtipo').value = u.tipoPersona || '';
                        
                        // Forzar el endpoint correcto en el formulario
                        document.getElementById('form-modificar').action = "{{ url('usuarios') }}/" + encodeURIComponent(ci);
                    } else {
                        errorMsg.textContent = 'Ese carnet no está en las listas.';
                        errorMsg.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    console.error("Fetch error:", err);
                    errorMsg.textContent = 'Error de conexión o datos no válidos.';
                    errorMsg.classList.remove('d-none');
                });
        }, 500); 
    });

    // LÓGICA DE ELIMINAR (DESTROY)
    document.getElementById('ci-eliminar').addEventListener('input', function() {
        clearTimeout(window.delTimeout);
        let errorMsg = document.getElementById('eliminar-error-msg');
        errorMsg.classList.add('d-none');
        
        let ci = this.value;
        if (!ci) { document.getElementById('delnombre').value = ''; return; }

        window.delTimeout = setTimeout(() => {
            fetch('/api/usuario/' + ci)
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        let u = data.usuario;
                        document.getElementById('delnombre').value = 'Objetivo Activo: ' + u.nombre + ' ' + u.correo;
                        document.getElementById('form-eliminar').action = "{{ url('usuarios') }}/" + ci;
                    } else {
                        errorMsg.textContent = 'Persona fantasma.';
                        errorMsg.classList.remove('d-none');
                        document.getElementById('delnombre').value = '';
                    }
                });
        }, 500); 
    });
</script>
</body>
</html>
