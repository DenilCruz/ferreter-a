@extends('layouts.ferreteria')

@section('title', 'Panel de Control - Ferretería Guisella')

@section('content')
<div class="animate-fade-up">
    <div class="page-header">
        <div>
            <h1>Bienvenido, {{ Auth::user()->nombre }}</h1>
            <p class="subtitle" style="margin: 0;">
                @if($isAdmin)
                    Resumen del estado actual de la ferretería
                @else
                    Aquí están los detalles de tu cuenta
                @endif
            </p>
        </div>
    </div>

    @if($isAdmin)
        {{-- ═══════════════════════════════════════════════ --}}
        {{-- VISTA ADMINISTRADOR                             --}}
        {{-- ═══════════════════════════════════════════════ --}}

        {{-- Tarjetas de Estadísticas --}}
        <div class="stat-grid">

            <div class="stat-card">
                <div class="stat-icon" style="background: var(--success-light); color: var(--success);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                </div>
                <div class="stat-info">
                    <span>Total Productos</span>
                    <div>{{ $totalProductos }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #eff6ff; color: #3b82f6;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div class="stat-info">
                    <span>Personal & Usuarios</span>
                    <div>{{ $totalUsuarios }}</div>
                </div>
            </div>

            <div class="stat-card" style="border-left: 4px solid var(--danger);">
                <div class="stat-icon" style="background: var(--danger-light); color: var(--danger);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>
                <div class="stat-info">
                    <span style="color: var(--danger);">Stock Bajo / Crítico</span>
                    <div style="color: var(--danger);">{{ $stockBajo }}</div>
                </div>
            </div>

        </div>

        <div class="dashboard-grid admin-grid">

            {{-- Últimas Actividades --}}
            <div class="card">
                <h3 style="display: flex; align-items: center; gap: 10px; margin-bottom: 24px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20v-6M9 20v-10M15 20v-4M3 20h18"/></svg>
                    Actividad Reciente en Bitácora
                </h3>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Acción</th>
                                <th>Detalle</th>
                                <th>Tiempo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimasBitacoras as $log)
                            <tr>
                                <td>
                                    @if(strtoupper($log->accion) == 'INSERTAR')
                                        <span class="badge bg-insert">{{ $log->accion }}</span>
                                    @elseif(strtoupper($log->accion) == 'ACTUALIZAR')
                                        <span class="badge bg-update">{{ $log->accion }}</span>
                                    @elseif(strtoupper($log->accion) == 'ELIMINAR')
                                        <span class="badge bg-delete">{{ $log->accion }}</span>
                                    @else
                                        <span class="badge">{{ $log->accion }}</span>
                                    @endif
                                </td>
                                <td>{{ $log->descripcion }}</td>
                                <td class="muted">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right" style="margin-top: 20px;">
                    <a href="{{ route('bitacora.index') }}">Ver bitácora completa →</a>
                </div>
            </div>

            {{-- Accesos Rápidos --}}
            <div style="display: flex; flex-direction: column; gap: 30px;">
                <div class="card" style="background: var(--primary-gradient); color: white; border: none;">
                    <h3 style="color: white; margin-bottom: 20px;">Acciones Rápidas</h3>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <a href="{{ route('productos.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 14px; border-radius: var(--radius-sm); text-align: center; font-weight: 700; transition: background 0.2s;">+ Nuevo Producto</a>
                        <a href="{{ route('usuarios.index') }}" style="background: rgba(255,255,255,0.1); color: white; padding: 14px; border-radius: var(--radius-sm); text-align: center; font-weight: 600; transition: background 0.2s;">Gestionar Personal</a>
                    </div>
                </div>

                <div class="card" style="background: var(--bg-light); border: 1px dashed var(--border);">
                    <h4>Soporte del Sistema</h4>
                    <p class="muted" style="font-size: 0.9rem; margin-top: 10px;">Si tienes problemas con la sincronización de stock, contacta al soporte técnico o verifica tu conexión.</p>
                </div>
            </div>

        </div>

    @else
        {{-- ═══════════════════════════════════════════════ --}}
        {{-- VISTA USUARIO NORMAL — solo sus datos de cuenta --}}
        {{-- ═══════════════════════════════════════════════ --}}

        <div class="card" style="max-width: 640px;">
            <h3 style="margin-bottom: 24px;">Mis datos de cuenta</h3>

            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 12px 8px; color: var(--text-muted); font-weight: 600; width: 40%;">C.I.</td>
                        <td style="padding: 12px 8px;">{{ Auth::user()->ci }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 12px 8px; color: var(--text-muted); font-weight: 600;">Nombre</td>
                        <td style="padding: 12px 8px;">{{ Auth::user()->nombre }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 12px 8px; color: var(--text-muted); font-weight: 600;">Apellido</td>
                        <td style="padding: 12px 8px;">{{ Auth::user()->apellido }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 12px 8px; color: var(--text-muted); font-weight: 600;">Correo electrónico</td>
                        <td style="padding: 12px 8px;">{{ Auth::user()->email }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 12px 8px; color: var(--text-muted); font-weight: 600;">Teléfono</td>
                        <td style="padding: 12px 8px;">{{ Auth::user()->telefono ?? '—' }}</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 12px 8px; color: var(--text-muted); font-weight: 600;">Sexo</td>
                        <td style="padding: 12px 8px;">
                            @if(Auth::user()->sexo === 'M') Masculino
                            @elseif(Auth::user()->sexo === 'F') Femenino
                            @else {{ Auth::user()->sexo ?? '—' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 8px; color: var(--text-muted); font-weight: 600;">Domicilio</td>
                        <td style="padding: 12px 8px;">{{ Auth::user()->domicilio ?? '—' }}</td>
                    </tr>
                </tbody>
            </table>

            <div style="margin-top: 24px;">
                <a href="{{ route('profile.edit') }}" class="btn-save" style="text-decoration: none; display: inline-block;">Editar mi perfil</a>
            </div>
        </div>

    @endif
</div>
@endsection
