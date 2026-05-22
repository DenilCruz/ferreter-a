@extends('layouts.ferreteria')

@section('title', 'Gestión y Arqueo de Caja')

@section('content')
<style>
/* ── Caja responsive ── */
.caja-header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 12px;
}
.caja-header-actions h1 {
    color: var(--primary);
    margin: 0;
    font-size: 1.75rem;
}
.caja-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.08);
}
.caja-table-wrap table {
    width: 100%;
    border-collapse: collapse;
    min-width: 560px;
}
.caja-table-wrap th {
    background: #f8fafc;
    padding: 14px 16px;
    border-bottom: 2px solid var(--border);
    color: var(--text-muted);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-weight: 700;
    text-align: left;
}
.caja-table-wrap td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    color: #4b5563;
    font-size: 0.92rem;
    vertical-align: middle;
}
.caja-table-wrap tbody tr:last-child td { border-bottom: none; }
.caja-table-wrap tbody tr:hover { background: #f0fdfa; }

.badge-abierta {
    background: #d1fae5; color: #065f46;
    padding: 3px 10px; border-radius: 9999px;
    font-size: 0.8rem; font-weight: 600;
}
.badge-cerrada {
    background: #f3f4f6; color: #374151;
    padding: 3px 10px; border-radius: 9999px;
    font-size: 0.8rem; font-weight: 600;
}
.btn-caja-primary {
    background: var(--primary);
    border: none;
    padding: 10px 20px;
    color: white;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.95rem;
    transition: background 0.2s;
    white-space: nowrap;
}
.btn-caja-primary:hover { background: var(--primary-hover); }

.modal-backdrop {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex; align-items: center; justify-content: center;
    z-index: 2000;
    padding: 16px;
    box-sizing: border-box;
}
.modal-content {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    width: 100%;
    max-width: 420px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}
.modal-content h3 { margin-top: 0; color: var(--primary); }
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 16px;
}
@media (max-width: 480px) {
    .caja-header-actions h1 { font-size: 1.3rem; }
    .modal-content { padding: 1.25rem; }
    .modal-footer { flex-direction: column-reverse; }
    .modal-footer button { width: 100%; justify-content: center; }
}
</style>

<div class="caja-container" x-data="{ openApertura: false, openCorte: false, currentCajaId: null, currentSaldoEsperado: 0 }">

    <div class="caja-header-actions">
        <h1>Gestión y Arqueo de Caja</h1>
        <button class="btn-caja-primary" @click="openApertura = true">
            + Apertura nueva
        </button>
    </div>

    @if(session('success'))
        <div style="background:#d1fae5; color:#065f46; padding:1rem; border-radius:8px; margin-bottom:1rem; border:1px solid #a7f3d0;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error_acceso'))
        <div style="background:#fee2e2; color:#b91c1c; padding:1rem; border-radius:8px; margin-bottom:1rem; border:1px solid #fecaca;">
            {{ session('error_acceso') }}
        </div>
    @endif

    <div class="caja-table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Monto Inicial</th>
                    <th>Fecha Apertura</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cajas as $caja)
                    <tr>
                        <td>{{ $caja->id }}</td>
                        <td>{{ $caja->user->nombre ?? 'Desconocido' }}</td>
                        <td>Bs. {{ number_format($caja->monto_apertura, 2) }}</td>
                        <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($caja->fecha_apertura)->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($caja->estado === 'abierta')
                                <span class="badge-abierta">Abierta</span>
                            @else
                                <span class="badge-cerrada">Cerrada</span>
                            @endif
                        </td>
                        <td>
                            @if($caja->estado === 'abierta')
                                <button @click="openCorte = true; currentCajaId = {{ $caja->id }}; currentSaldoEsperado = {{ $caja->saldo_esperado }};"
                                        title="Corte de caja"
                                        style="background:none; border:none; cursor:pointer; color:var(--primary); padding:4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                        <line x1="2" y1="10" x2="22" y2="10"></line>
                                        <path d="M7 15h.01"></path><path d="M11 15h2"></path>
                                    </svg>
                                </button>
                            @else
                                <a href="{{ route('caja.reporte', $caja->id) }}" title="Descargar reporte" style="color:var(--primary); display:inline-flex; align-items:center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:2.5rem; text-align:center; color:var(--text-muted); font-size:0.95rem;">
                            No hay cajas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:1rem;">{{ $cajas->links() }}</div>

    {{-- Modal Apertura --}}
    <div x-show="openApertura" style="display:none;" class="modal-backdrop">
        <div class="modal-content" @click.away="openApertura = false">
            <h3>Apertura de Caja</h3>
            <form action="{{ route('caja.apertura') }}" method="POST">
                @csrf
                <div style="margin-bottom:1rem;">
                    <label style="display:block; margin-bottom:6px; color:#374151; font-weight:600;">Monto inicial (Bs.)</label>
                    <input type="number" name="monto_apertura" step="0.01" min="0" required
                           style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:1rem;">
                </div>
                <div class="modal-footer">
                    <button type="button" @click="openApertura = false"
                            style="padding:10px 20px; border:1px solid var(--border); background:white; border-radius:8px; cursor:pointer; font-weight:600;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-caja-primary">Aperturar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Corte --}}
    <div x-show="openCorte" style="display:none;" class="modal-backdrop">
        <div class="modal-content" @click.away="openCorte = false">
            <h3>Corte de Caja</h3>
            <div style="margin-bottom:1rem; padding:14px; background:#f0fdfa; border-radius:8px; border:1px solid #ccfbf1;">
                <p style="margin:0; color:#0f766e; font-weight:600;">
                    Saldo Esperado: <span x-text="'Bs. ' + Number(currentSaldoEsperado).toFixed(2)"></span>
                </p>
                <small style="color:#0d9488;">(Fondo inicial + Ventas)</small>
            </div>
            <form :action="'/caja/corte/' + currentCajaId" method="POST">
                @csrf
                <div style="margin-bottom:1rem;">
                    <label style="display:block; margin-bottom:6px; color:#374151; font-weight:600;">Monto Real en Caja (Bs.)</label>
                    <input type="number" name="monto_real" step="0.01" min="0" required
                           style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:8px; font-size:1rem;">
                </div>
                <div class="modal-footer">
                    <button type="button" @click="openCorte = false"
                            style="padding:10px 20px; border:1px solid var(--border); background:white; border-radius:8px; cursor:pointer; font-weight:600;">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-caja-primary">Realizar Corte</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
