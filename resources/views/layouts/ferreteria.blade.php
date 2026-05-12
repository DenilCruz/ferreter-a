<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Ferretería Guisella')</title>
    <link href="{{ asset('css/ferreteria.css') }}" rel="stylesheet">
    @stack('head')
    <!-- Alpine.js (para los modales y botones) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="fg-body has-topbar">

    {{-- ═══════════════════════════════════════════════════
         TOPBAR UNIFICADA — se muestra en todas las páginas
         ═══════════════════════════════════════════════════ --}}
    @php
        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = \App\Models\Carrito::where('ci_usuario', Auth::user()->ci)->sum('cantidad');
        } else {
            $cart = session()->get('carrito', []);
            foreach ($cart as $item) {
                $cartCount += $item['cantidad'];
            }
        }
    @endphp

    <div class="topbar" x-data="{ mobileMenuOpen: false }">

        {{-- Logo / Marca --}}
        <a href="{{ url('/') }}" class="topbar-brand">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            Ferretería Guisella
        </a>

        {{-- Botón Menú Móvil (Hamburguesa) --}}
        <button class="hamburger-btn" @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Menú">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        {{-- Enlaces Escritorio --}}
        <div class="topbar-links desktop-only">
            <a href="{{ route('carrito.index') }}" class="cart-link" style="display: flex; align-items: center; gap: 6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                Carrito
                @if($cartCount > 0)
                    <span class="cart-count-badge" style="background: var(--primary); color: white; border-radius: 10px; padding: 2px 6px; font-size: 0.7rem; font-weight: bold;">{{ $cartCount }}</span>
                @else
                    <span class="cart-count-badge" style="background: var(--primary); color: white; border-radius: 10px; padding: 2px 6px; font-size: 0.7rem; font-weight: bold; display: none;">0</span>
                @endif
            </a>

            @auth
                {{-- Inventario visible para todos --}}
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Catálogo</a>
                <a href="{{ route('marcas.index') }}" class="{{ request()->routeIs('marcas.*') ? 'active' : '' }}">Marcas</a>
                <a href="{{ route('categorias.index') }}" class="{{ request()->routeIs('categorias.*') ? 'active' : '' }}">Categorías</a>

                {{-- Solo personal operativo (Admin y Almaceneros) ven Trabajos --}}
                @unless(Auth::user()->tipoPersona === 'C')
                    <a href="{{ route('trabajos.index') }}" class="{{ request()->routeIs('trabajos.index') ? 'active' : '' }}">Trabajos</a>

                    @can('admin')
                        <a href="{{ route('admin.marcas.index') }}" class="{{ request()->routeIs('admin.marcas.*') ? 'active' : '' }}">Marcas Admin</a>
                        <a href="{{ route('admin.categorias.index') }}" class="{{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">Categorías Admin</a>
                        <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">Personal</a>
                        <a href="{{ route('bitacora.index') }}" class="{{ request()->routeIs('bitacora.*') ? 'active' : '' }}">Bitácora</a>
                        <a href="{{ route('caja.index') }}" class="{{ request()->routeIs('caja.*') ? 'active' : '' }}">Caja</a>
                    @endcan
                @endunless

                {{-- Info del usuario y logout --}}
                <span class="topbar-sep">|</span>
                <span class="topbar-user">
                    @can('admin')
                        <span class="topbar-role-badge">Admin</span>
                    @endcan
                    {{ Auth::user()->name }}
                </span>
                <a href="{{ route('dashboard') }}">Mi perfil</a>
                <form method="POST" action="{{ route('logout') }}" class="inline-form">
                    @csrf
                    <button type="submit" class="btn-logout">Salir</button>
                </form>
            @else
                {{-- Visitante no autenticado --}}
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Catálogo</a>
<<<<<<< HEAD
=======
                <a href="{{ route('marcas.index') }}" class="{{ request()->routeIs('marcas.*') ? 'active' : '' }}">Marcas</a>
                <a href="{{ route('categorias.index') }}" class="{{ request()->routeIs('categorias.*') ? 'active' : '' }}">Categorías</a>
>>>>>>> origin/luis-miguel
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}">Registrarse</a>
            @endauth
        </div>

        {{-- Menú Móvil Desplegable --}}
        <div class="mobile-menu" :class="{ 'is-open': mobileMenuOpen }" style="display: none;" x-show="mobileMenuOpen" x-transition.opacity>
            <a href="{{ route('carrito.index') }}" class="cart-link" style="display: flex; align-items: center; justify-content: space-between;">
                <span style="display: flex; align-items: center; gap: 8px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    Carrito
                </span>
                @if($cartCount > 0)
                    <span class="cart-count-badge" style="background: var(--primary); color: white; border-radius: 10px; padding: 2px 8px; font-size: 0.8rem; font-weight: bold;">{{ $cartCount }}</span>
                @else
                    <span class="cart-count-badge" style="background: var(--primary); color: white; border-radius: 10px; padding: 2px 8px; font-size: 0.8rem; font-weight: bold; display: none;">0</span>
                @endif
            </a>

            @auth
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Catálogo</a>
                <a href="{{ route('marcas.index') }}" class="{{ request()->routeIs('marcas.*') ? 'active' : '' }}">Marcas</a>
                <a href="{{ route('categorias.index') }}" class="{{ request()->routeIs('categorias.*') ? 'active' : '' }}">Categorías</a>

                @unless(Auth::user()->tipoPersona === 'C')
                    <a href="{{ route('trabajos.index') }}" class="{{ request()->routeIs('trabajos.index') ? 'active' : '' }}">Trabajos</a>

                    @can('admin')
                        <a href="{{ route('admin.marcas.index') }}" class="{{ request()->routeIs('admin.marcas.*') ? 'active' : '' }}">Marcas Admin</a>
                        <a href="{{ route('admin.categorias.index') }}" class="{{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">Categorías Admin</a>
                        <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">Personal</a>
                        <a href="{{ route('bitacora.index') }}" class="{{ request()->routeIs('bitacora.*') ? 'active' : '' }}">Bitácora</a>
                        <a href="{{ route('caja.index') }}" class="{{ request()->routeIs('caja.*') ? 'active' : '' }}">Caja</a>
                    @endcan
                @endunless

                <div class="mobile-menu-user">
                    <span class="topbar-user" style="font-size: 1rem;">
                        @can('admin')
                            <span class="topbar-role-badge">Admin</span>
                        @endcan
                        {{ Auth::user()->name }}
                    </span>
                    <a href="{{ route('dashboard') }}" style="display: inline-block; padding: 6px 12px; margin: 0;">Mi perfil</a>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout">Salir</button>
                </form>
            @else
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Catálogo</a>
<<<<<<< HEAD
=======
                <a href="{{ route('marcas.index') }}" class="{{ request()->routeIs('marcas.*') ? 'active' : '' }}">Marcas</a>
                <a href="{{ route('categorias.index') }}" class="{{ request()->routeIs('categorias.*') ? 'active' : '' }}">Categorías</a>
>>>>>>> origin/luis-miguel
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}">Registrarse</a>
            @endauth
        </div>
    </div>

    <div class="wrap @yield('wrap_class')">

        {{-- Mensajes de Retroalimentación Globales --}}
        @if(session('success'))
            <div class="alert alert-success animate-fade-up" style="margin-bottom: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error animate-fade-up" style="margin-bottom: 20px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error animate-fade-up" style="margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Alerta de acceso denegado (redirigido por AdminMiddleware) --}}
        @if(session('error_acceso'))
            <div class="alert alert-error" style="margin-bottom: 20px;">
                🔒 {{ session('error_acceso') }}
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
    
    <script>
        // Guardar la posición de desplazamiento antes de recargar
        window.addEventListener('beforeunload', function() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });

        // Restaurar la posición de desplazamiento al cargar la página
        window.addEventListener('load', function() {
            if (sessionStorage.getItem('scrollPosition') !== null) {
                window.scrollTo(0, parseInt(sessionStorage.getItem('scrollPosition')));
                sessionStorage.removeItem('scrollPosition'); // Limpiar para que no afecte a otras navegaciones
            }
        });

        // Lógica para formularios AJAX del carrito
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.classList.contains('ajax-cart-form')) {
                e.preventDefault();
                const form = e.target;
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar badge del carrito
                        document.querySelectorAll('.cart-count-badge').forEach(badge => {
                            badge.textContent = data.cartCount;
                            badge.style.display = data.cartCount > 0 ? 'inline-block' : 'none';
                        });

                        // Mostrar un toast
                        showToast('¡Carrito actualizado!');

                        // Si es actualización del index del carrito
                        if (data.subtotal && data.total) {
                            const tr = form.closest('tr');
                            if (tr) {
                                const subtotalTd = tr.querySelector('.item-subtotal');
                                if (subtotalTd) subtotalTd.textContent = data.subtotal + ' Bs.';
                            }
                            const totalElements = document.querySelectorAll('.cart-total');
                            totalElements.forEach(el => el.textContent = data.total + ' Bs.');
                        }
                        
                        if (form.classList.contains('ajax-remove')) {
                            const tr = form.closest('tr');
                            if (tr) tr.remove();
                            const totalElements = document.querySelectorAll('.cart-total');
                            if(data.total) totalElements.forEach(el => el.textContent = data.total + ' Bs.');
                            
                            if (data.cartCount == 0) {
                                window.location.reload();
                            }
                        }
                    } else {
                        // Ocurrió un error (por ejemplo, stock insuficiente)
                        showToast(data.message || 'Ocurrió un error', 'error');
                        
                        // Si nos dicen a qué valor regresar el input, lo hacemos
                        if (data.revertTo !== undefined) {
                            const input = form.querySelector('input[name="cantidad"]');
                            if (input) input.value = data.revertTo;
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.textContent = message;
            toast.style.position = 'fixed';
            toast.style.bottom = '20px';
            toast.style.right = '20px';
            toast.style.background = type === 'error' ? 'var(--danger)' : 'var(--success)';
            toast.style.color = 'white';
            toast.style.padding = '12px 24px';
            toast.style.borderRadius = '8px';
            toast.style.zIndex = '9999';
            toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
            toast.style.animation = 'fadeInUp 0.3s ease-out';
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.5s';
                setTimeout(() => toast.remove(), 500);
            }, 2000);
        }
    </script>
</body>
</html>
