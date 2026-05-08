@extends('layouts.ferreteria')

@section('title', 'Bitácora del sistema')
@section('wrap_class', 'wide')

@section('content')
    <a href="{{ route('inventario') }}" class="back">← Volver al inventario</a>
    <h1>Bitácora de auditoría</h1>
    <p class="subtitle">Registro de acciones sobre el sistema</p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Fecha / hora</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Tabla</th>
                    <th>Descripción</th>
                    <th>IP</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $r)
                    <tr>
                        <td>{{ $r->created_at->format('d/m/Y H:i:s') }}</td>
                        <td><strong>{{ $r->usuario }}</strong></td>
                        <td><x-accion-badge :accion="$r->accion" /></td>
                        <td><code>{{ $r->tabla }}</code></td>
                        <td>{{ $r->descripcion }}</td>
                        <td><small class="muted">{{ $r->ip }}</small></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
