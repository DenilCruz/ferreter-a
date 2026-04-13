<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bitácora del Sistema</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #333; color: white; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8em; font-weight: bold; color: white; }
        .bg-insert { background: #28a745; }
        .bg-update { background: #ffc107; color: #333; }
        .bg-delete { background: #dc3545; }
    </style>
</head>
<body>
    <a href="{{ url('/') }}" style="text-decoration: none; color: #007bff;">← Volver al Inventario</a>
    <h1>📜 Bitácora de Auditoría</h1>
    
    <table>
        <thead>
            <tr>
                <th>Fecha/Hora</th>
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
                <td>
                    <span class="badge {{ $r->accion == 'INSERTAR' ? 'bg-insert' : '' }}">
                        {{ $r->accion }}
                    </span>
                </td>
                <td><code>{{ $r->tabla }}</code></td>
                <td>{{ $r->descripcion }}</td>
                <td><small>{{ $r->ip }}</small></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>