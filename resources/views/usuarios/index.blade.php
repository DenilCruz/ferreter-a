@extends('layouts.ferreteria')

@section('title', 'Gestión de Usuarios - Ferretería Guisella')

@section('content')
        <h1>Gestión de Personal y Usuarios</h1>
        <p class="subtitle">Directorio de recursos humanos, administradores y clientes.</p>

        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('success_eliminar')) <div class="alert alert-success">{{ session('success_eliminar') }}</div> @endif
        @if(session('error_general')) <div class="alert alert-error">{{ session('error_general') }}</div> @endif
        @if($errors->any()) <div class="alert alert-error">{{ $errors->first() }}</div> @endif

        @can('admin')
        <div class="action-buttons">
            <button class="btn-action" id="btn-toggle-estudio">Estudio Téc. (Ver Info)</button>
            <button class="btn-action" id="btn-toggle-modificar">Modificar Perfil</button>
            <button class="btn-action danger" id="btn-toggle-eliminar">Borrar Acceso</button>
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
        <div class="form-container d-none danger" id="container-eliminar">
            <h3>Eliminar usuario irrevocablemente</h3>
            <form id="form-eliminar" action="#" method="POST">
                @csrf
                @method('DELETE')
                <div class="form-grid">
                    <div class="field">
                        <input type="text" id="ci-eliminar" name="ci" placeholder="Digita el CI de la persona" required style="border-color: #fca5a5;">
                        <span id="eliminar-error-msg" class="error-text d-none"></span>
                    </div>
                    <div class="field"><input type="text" id="delnombre" placeholder="Sujeto (Autocompletado)" readonly style="background:#f1f5f9; cursor:not-allowed;"></div>
                    <button type="submit" class="btn-logout" style="grid-column: 1 / -1; margin-top:10px; width: 100%;">Destruir Cuenta</button>
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
@endsection

@push('scripts')
<script src="{{ asset('js/ferreteria-usuarios.js') }}"></script>
@endpush
