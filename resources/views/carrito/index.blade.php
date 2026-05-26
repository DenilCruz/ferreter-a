@extends('layouts.ferreteria')

@section('title', 'Mi Carrito - Ferretería Guisella')

@section('content')
    <div class="animate-fade-up">
        <h1 style="margin: 0;">Mi Carrito de Compras</h1>
        <p class="subtitle">Revisa los productos seleccionados y procede al pago.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if(count($cartItems) > 0)
        <div class="card animate-fade-up" style="animation-delay: 0.1s;">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-center">Precio Unitario</th>
                            <th class="text-center" style="width: 150px;">Cantidad</th>
                            <th class="text-right">Subtotal</th>
                            <th class="text-center" style="width: 80px;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-main);">{{ $item['nombre'] }}</div>
                                </td>
                                <td class="text-center">{{ number_format($item['precio'], 2) }} Bs.</td>
                                <td class="text-center">
                                    <form action="{{ route('carrito.update') }}" method="POST" class="ajax-cart-form" style="display: flex; gap: 8px; justify-content: center;">
                                        @csrf
                                        <input type="hidden" name="idproducto" value="{{ $item['idproducto'] }}">
                                        <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1" style="width: 70px; padding: 6px; text-align: center;" onchange="this.form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }))">
                                        <button type="submit" class="btn-action" style="padding: 6px 10px;" title="Actualizar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/></svg>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-right item-subtotal" style="font-weight: 800; color: var(--primary);">
                                    {{ number_format($item['subtotal'], 2) }} Bs.
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('carrito.remove') }}" method="POST" class="ajax-cart-form ajax-remove">
                                        @csrf
                                        <input type="hidden" name="idproducto" value="{{ $item['idproducto'] }}">
                                        <button type="submit" class="btn-action danger" style="padding: 6px 10px;" title="Eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 30px; flex-wrap: wrap; gap: 20px;">
                <div style="display: flex; gap: 12px;">
                    <form action="{{ route('carrito.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-action danger">Vaciar Carrito</button>
                    </form>
                    <a href="{{ url('/') }}" class="btn-action">Seguir Comprando</a>
                </div>

                <div style="background: var(--bg-light); padding: 24px; border-radius: var(--radius-md); min-width: 300px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-weight: 600;">
                        <span>Subtotal:</span>
                        <span class="cart-total">{{ number_format($total, 2) }} Bs.</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 1.5rem; font-weight: 900; color: var(--text-main); margin-bottom: 24px; padding-top: 12px; border-top: 1px dashed var(--border);">
                        <span>Total:</span>
                        <span class="cart-total" style="color: var(--primary);">{{ number_format($total, 2) }} Bs.</span>
                    </div>

                    <!-- Contenedor del Botón de PayPal -->
                    <div id="paypal-button-container" style="width: 100%; margin-top: 15px;"></div>
                </div>
            </div>
        </div>
    @else
        <div class="card animate-fade-up text-center" style="padding: 60px 20px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--text-light)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 20px;"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <h2 style="color: var(--text-muted); margin-bottom: 16px;">Tu carrito está vacío</h2>
            <p style="color: var(--text-light); margin-bottom: 24px;">No has agregado ningún producto todavía.</p>
            <a href="{{ url('/') }}" class="btn-save">Ir al Catálogo</a>
        </div>
    @endif
@endsection

@push('scripts')
    <!-- REEMPLAZA "TU_CLIENT_ID" CON TU CLIENT ID REAL DE PAYPAL -->
    <script src="https://www.paypal.com/sdk/js?client-id=ATGzsQEj91F9YM-HUJNNGwvraBLy9yiwK7G4IhgCOtbQQwfZtA4lyL9bff68j3-LbSoR9HHHHwGzHFj9&currency=USD"></script>
    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                // Aquí se configura el monto a cobrar.
                // Nota: PayPal no soporta Bolivianos (BOB) nativamente, 
                // debes considerar realizar una conversión a USD en tu controlador o aquí mismo.
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '{{ number_format($total, 2, '.', '') }}' // Formato numérico de javascript sin comas
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                // Se captura el dinero una vez aprobado por el cliente
                return actions.order.capture().then(function(details) {
                    alert('Pago completado con éxito por ' + details.payer.name.given_name);
                    
                    // Aquí puedes redirigir a una ruta de éxito, para vaciar el carrito y crear la orden real.
                    // window.location.href = "/carrito/success";
                });
            },
            onCancel: function(data) {
                console.log('El pago fue cancelado por el usuario.');
            },
            onError: function(err) {
                console.error('Ocurrió un error en el flujo de PayPal:', err);
                alert('Ocurrió un problema al procesar el pago con PayPal.');
            }
        }).render('#paypal-button-container');
    </script>
@endpush
