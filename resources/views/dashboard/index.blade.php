@extends('layouts.ferreteria')

@section('title', 'Panel de Control - Ferretería Guisella')

@section('content')
<div class="animate-fade-up">
    <div style="margin-bottom: 30px;">
        <h1 style="margin: 0;">Bienvenido, {{ Auth::user()->nombre }}</h1>
        <p class="subtitle" style="margin: 0;">Resumen del estado actual de la ferretería</p>
    </div>

    {{-- Tarjetas de Estadísticas --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px; margin-bottom: 40px;">
        
        <div class="category-section" style="padding: 25px; display: flex; align-items: center; gap: 20px;">
            <div style="background: #ecfdf5; color: #10b981; padding: 15px; border-radius: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
            </div>
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Productos</span>
                <div style="font-size: 1.8rem; font-weight: 900; color: #0f172a;">{{ $totalProductos }}</div>
            </div>
        </div>

        <div class="category-section" style="padding: 25px; display: flex; align-items: center; gap: 20px;">
            <div style="background: #eff6ff; color: #3b82f6; padding: 15px; border-radius: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Usuarios</span>
                <div style="font-size: 1.8rem; font-weight: 900; color: #0f172a;">{{ $totalUsuarios }}</div>
            </div>
        </div>

        <div class="category-section" style="padding: 25px; display: flex; align-items: center; gap: 20px; border-left: 5px solid #ef4444;">
            <div style="background: #fef2f2; color: #ef4444; padding: 15px; border-radius: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            </div>
            <div>
                <span style="font-size: 0.8rem; font-weight: 700; color: #ef4444; text-transform: uppercase;">Stock Bajo</span>
                <div style="font-size: 1.8rem; font-weight: 900; color: #991b1b;">{{ $stockBajo }}</div>
            </div>
        </div>

    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        
        {{-- Últimas Actividades --}}
        <div class="category-section" style="padding: 30px;">
            <h3 style="margin-top: 0; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20v-6M9 20v-10M15 20v-4M3 20h18"/></svg>
                Actividad Reciente en Bitácora
            </h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 1px solid #f1f5f9;">
                        <th style="padding: 12px; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Acción</th>
                        <th style="padding: 12px; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Detalle</th>
                        <th style="padding: 12px; color: #64748b; font-size: 0.8rem; text-transform: uppercase;">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimasBitacoras as $log)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td style="padding: 12px;"><span class="badge" style="background: #f1f5f9; color: #475569; padding: 4px 10px;">{{ $log->accion }}</span></td>
                        <td style="padding: 12px; font-size: 0.9rem;">{{ $log->descripcion }}</td>
                        <td style="padding: 12px; font-size: 0.8rem; color: #94a3b8;">{{ $log->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top: 20px; text-align: right;">
                <a href="{{ route('bitacora.index') }}" style="color: var(--accent); font-weight: 700; text-decoration: none; font-size: 0.9rem;">Ver bitácora completa →</a>
            </div>
        </div>

        {{-- Accesos Rápidos --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">
            <div class="category-section" style="padding: 30px; background: var(--primary-grad); color: white;">
                <h3 style="margin-top: 0; color: white;">Acciones Rápidas</h3>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('productos.create') }}" style="background: rgba(255,255,255,0.2); color: white; padding: 12px; border-radius: 12px; text-decoration: none; font-weight: 700; text-align: center;">+ Nuevo Producto</a>
                    <a href="{{ route('usuarios.index') }}" style="background: rgba(255,255,255,0.1); color: white; padding: 12px; border-radius: 12px; text-decoration: none; font-weight: 600; text-align: center;">Gestionar Personal</a>
                </div>
            </div>

            <div class="category-section" style="padding: 25px;">
                <h4 style="margin-top: 0;">Soporte del Sistema</h4>
                <p style="font-size: 0.85rem; color: #64748b;">Si tienes problemas con la sincronización de stock, contacta al soporte técnico.</p>
            </div>
        </div>

    </div>
</div>
@endsection
