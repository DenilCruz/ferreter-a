@extends('layouts.ferreteria')

@section('title', 'Roles y Trabajos - Ferretería Guisella')

@section('content')
<div class="animate-fade-up">
    <div class="page-header">
        <div>
            <h1 style="margin: 0;">Centro de Asignaciones</h1>
            <p class="subtitle" style="margin: 0;">Gestiona y revisa los trabajos y roles del personal.</p>
        </div>
    </div>

    @if(session('success_rol'))
        <div class="alert alert-success"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> {{ session('success_rol') }}</div>
    @endif
    @if(session('success_asignacion'))
        <div class="alert alert-success"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> {{ session('success_asignacion') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> Por favor revisa los datos ingresados e intenta otra vez.</div>
    @endif

    <div class="dashboard-grid {{ $isAdmin ? 'admin-grid' : '' }}">

        <div class="card" style="margin-bottom: 0;">
            <div style="margin-bottom: 24px;">
                <h2 style="margin: 0;">Tabla de Asignaciones</h2>
                @if($isAdmin)
                    <p class="muted" style="font-size:0.9rem; margin: 4px 0 0 0;">Visualizando todas las asignaciones de la empresa.</p>
                @else
                    <p class="muted" style="font-size:0.9rem; margin: 4px 0 0 0;">Visualizando únicamente los trabajos asignados para ti.</p>
                @endif
            </div>

            <div class="table-wrap">
                @if($asignaciones->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>Rol / Trabajo</th>
                                @if($isAdmin)<th>Empleado Asignado</th>@endif
                                <th>Fecha Inicio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asignaciones as $asignacion)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-main);">{{ $asignacion->rol->nombre ?? 'N/A' }}</div>
                                    <div style="font-size: 0.85rem;" class="muted">{{ $asignacion->rol->descripcion ?? '' }}</div>
                                </td>
                                @if($isAdmin)
                                <td>
                                    <div style="font-weight: 600;">{{ $asignacion->empleado->usuario->nombre ?? 'Desconocido' }} {{ $asignacion->empleado->usuario->apellido ?? '' }}</div>
                                    <div style="font-size: 0.85rem;" class="muted">CI: {{ $asignacion->ci_empleado }}</div>
                                </td>
                                @endif
                                <td>{{ $asignacion->fechaInicio }}</td>
                                <td>
                                    <span class="badge {{ strtolower($asignacion->estado) == 'activo' ? 'activo' : '' }}">{{ $asignacion->estado }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="padding:40px; text-align:center; background:#f8fafc; border-radius:var(--radius-sm); border:1px dashed var(--border);">
                        <p style="color:var(--text-muted); font-weight:500; font-size:1.1rem;">No hay asignaciones registradas.</p>
                    </div>
                @endif
            </div>
        </div>

        @can('admin')
        <div style="display:flex; flex-direction:column; gap:30px;">
            <div class="card" style="margin-bottom: 0;">
                <h2 style="display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><line x1="19" y1="8" x2="19" y2="14"></line><line x1="22" y1="11" x2="16" y2="11"></line></svg>
                    Asignar Trabajo
                </h2>
                <form action="{{ route('trabajos.asignar') }}" method="POST">
                    @csrf
                    <div class="field" style="margin-bottom: 16px;">
                        <label>Seleccionar Empleado</label>
                        <select name="ci_empleado" required>
                            <option value="">— Elegir Empleado —</option>
                            @foreach($empleados as $emp)
                                <option value="{{ $emp->ci }}">{{ $emp->usuario->nombre ?? '' }} {{ $emp->usuario->apellido ?? '' }} (CI: {{ $emp->ci }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field" style="margin-bottom: 24px;">
                        <label>Designar Rol/Trabajo</label>
                        <select name="id_rol" required>
                            <option value="">— Elegir Trabajo —</option>
                            @foreach($rolesDisponibles as $r)
                                <option value="{{ $r->id }}">{{ $r->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-save" style="width: 100%;">Guardar Asignación</button>
                </form>
            </div>

            <div class="card" style="background:var(--bg-light); border:1px dashed var(--border); box-shadow:none; margin-bottom: 0;">
                <h2 style="font-size: 1.25rem;">Registrar Nuevo Rol</h2>
                <form action="{{ route('trabajos.store') }}" method="POST">
                    @csrf
                    <div class="field" style="margin-bottom: 16px;">
                        <label>Nombre del Trabajo</label>
                        <input type="text" name="nombre" placeholder="Ej: Gerente, Limpieza, Vendedor" required style="background: white;">
                    </div>
                    <div class="field" style="margin-bottom: 24px;">
                        <label>Descripción</label>
                        <input type="text" name="descripcion" placeholder="Breve descripción de las labores" style="background: white;">
                    </div>
                    <button type="submit" class="btn-action" style="width: 100%;">Crear Rol en Sistema</button>
                </form>
            </div>
        </div>
        @endcan

    </div>
</div>
@endsection
