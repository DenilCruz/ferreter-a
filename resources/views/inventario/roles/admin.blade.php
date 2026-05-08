@extends('layouts.ferreteria')

@section('title', 'Ferretería Guisella - Inventario')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
        <div>
            <h1 style="margin: 0;">Catálogo de Productos</h1>
            <p class="subtitle" style="margin: 0;">Gestión de inventario y stock</p>
        </div>

        @can('admin')
            <div class="action-buttons" style="margin: 0;">
                <a href="{{ route('productos.create') }}" class="btn-logout" style="background: var(--primary-grad); color: white; text-decoration: none; display: flex; align-items: center; gap: 8px; padding: 10px 25px; border-radius: 14px; font-weight: 700; box-shadow: 0 10px 20px -5px rgba(15, 118, 110, 0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Nuevo Producto
                </a>
            </div>
        @endcan
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="catalog-container">
        @foreach($categorias as $categoria)
            @include('inventario.categoria-recursiva', ['categoria' => $categoria])
        @endforeach
    </div>

    {{-- El administrador ahora usa páginas dedicadas para Crear y Editar, por lo que ya no hay modales aquí --}}
@endsection
