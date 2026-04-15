<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bitácora del sistema</title>
    <style>
        :root {
            --bg: #e8eef3;
            --surface: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
            --accent: #0f766e;
            --radius: 12px;
            --shadow: 0 1px 2px rgba(15, 23, 42, 0.06), 0 8px 24px rgba(15, 23, 42, 0.08);
        }
        * { box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            padding: 24px;
            background: linear-gradient(160deg, var(--bg) 0%, #dbeafe 100%);
            color: var(--text);
            min-height: 100vh;
        }
        .wrap { max-width: 1200px; margin: 0 auto; }
        .back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
            text-decoration: none;
            color: var(--accent);
            font-weight: 600;
            font-size: 0.95rem;
        }
        .back:hover { text-decoration: underline; }
        h1 {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0 0 8px;
        }
        .subtitle { color: var(--muted); margin: 0 0 24px; font-size: 0.95rem; }
        .table-wrap {
            background: var(--surface);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }
        table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        th, td { padding: 12px 14px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th {
            background: #334155;
            color: #fff;
            font-weight: 600;
        }
        tbody tr:hover td { background: #f8fafc; }
        tbody tr:last-child td { border-bottom: none; }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .bg-insert { background: #059669; color: #fff; }
        .bg-update { background: #d97706; color: #fff; }
        .bg-delete { background: #dc2626; color: #fff; }
        code { font-size: 0.85em; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="wrap">
        <a href="{{ url('/') }}" class="back">← Volver al inventario</a>
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
                        <td>
                            @php
                                $accion = strtoupper((string) $r->accion);
                                $badgeClass = match (true) {
                                    str_contains($accion, 'INSERT') => 'bg-insert',
                                    str_contains($accion, 'UPDATE') || str_contains($accion, 'MODIF') => 'bg-update',
                                    str_contains($accion, 'DELETE') || str_contains($accion, 'ELIM') => 'bg-delete',
                                    default => 'bg-insert',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $r->accion }}</span>
                        </td>
                        <td><code>{{ $r->tabla }}</code></td>
                        <td>{{ $r->descripcion }}</td>
                        <td><small style="color: var(--muted);">{{ $r->ip }}</small></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
