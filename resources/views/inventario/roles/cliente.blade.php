@extends('layouts.ferreteria')

@section('title', 'Catálogo de Productos - Ferretería Guisella')

@section('content')
    <div class="animate-fade-up">
        <h1 style="margin: 0;">Catálogo de Productos</h1>
        <p class="subtitle">Explora nuestro inventario disponible</p>
    </div>

    <div class="catalog-container">
        @foreach($categorias as $categoria)
            @include('inventario.categoria-recursiva', ['categoria' => $categoria])
        @endforeach
    </div>

    <div style="margin-top: 40px; padding: 30px; background: white; border-radius: 20px; text-align: center; border: 1px dashed #cbd5e1;">
        @guest
            <p style="color: var(--muted);">¿Necesitas finalizar un pedido o cotización? <a href="{{ route('login') }}" style="color: var(--primary); font-weight: bold;">Inicia sesión</a> para proceder al pago.</p>
        @else
            <p style="color: var(--muted);">¿Listo para pedir? <a href="{{ route('carrito.index') }}" style="color: var(--primary); font-weight: bold;">Revisa tu carrito</a> y procede al pago.</p>
        @endguest
    </div>
@endsection
