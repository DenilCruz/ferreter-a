<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Bitácora del sistema
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow rounded-xl p-6 border border-slate-200">
                <h2 class="text-xl font-semibold mb-2">Registro de acciones sobre el sistema</h2>

                <div class="table-wrap">
                    <table class="tree-table">
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
                                                str_contains($accion, 'INSERT') => 'bg-teal-500',
                                                str_contains($accion, 'UPDATE') || str_contains($accion, 'MODIF') => 'bg-yellow-500',
                                                str_contains($accion, 'DELETE') || str_contains($accion, 'ELIM') => 'bg-red-500',
                                                default => 'bg-teal-500',
                                            };
                                        @endphp
                                        <span class="inline-block px-3 py-1 rounded-full text-white {{ $badgeClass }}">{{ $r->accion }}</span>
                                    </td>
                                    <td><code class="text-sm bg-slate-100 p-1 rounded">{{ $r->tabla }}</code></td>
                                    <td>{{ $r->descripcion }}</td>
                                    <td><small class="text-slate-500">{{ $r->ip }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>