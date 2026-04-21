<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Trabajos Asignados') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            <!-- TÍTULO -->
            <div>
                <h2 class="text-xl font-bold mb-2">Centro de Asignaciones</h2>
                <p class="subtitle">Gestiona y revisa los trabajos y roles del personal.</p>
            </div>

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
    </div>
</x-app-layout>