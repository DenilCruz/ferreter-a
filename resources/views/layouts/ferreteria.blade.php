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
            @auth
                {{-- Inventario visible para todos --}}
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Catálogo</a>

                {{-- Solo personal operativo (Admin y Almaceneros) ven Trabajos --}}
                @unless(Auth::user()->tipoPersona === 'C')
                    <a href="{{ route('trabajos.index') }}" class="{{ request()->routeIs('trabajos.index') ? 'active' : '' }}">Trabajos</a>
                    
                    @can('admin')
                        <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">Personal</a>
                        <a href="{{ route('bitacora.index') }}" class="{{ request()->routeIs('bitacora.*') ? 'active' : '' }}">Bitácora</a>
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
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}">Registrarse</a>
            @endauth
        </div>

        {{-- Menú Móvil Desplegable --}}
        <div class="mobile-menu" :class="{ 'is-open': mobileMenuOpen }" style="display: none;" x-show="mobileMenuOpen" x-transition.opacity>
            @auth
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Catálogo</a>

                @unless(Auth::user()->tipoPersona === 'C')
                    <a href="{{ route('trabajos.index') }}" class="{{ request()->routeIs('trabajos.index') ? 'active' : '' }}">Trabajos</a>
                    
                    @can('admin')
                        <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">Personal</a>
                        <a href="{{ route('bitacora.index') }}" class="{{ request()->routeIs('bitacora.*') ? 'active' : '' }}">Bitácora</a>
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
                <a href="{{ route('login') }}">Iniciar sesión</a>
                <a href="{{ route('register') }}">Registrarse</a>
            @endauth
        </div>
    </div>

    <div class="wrap @yield('wrap_class')">

        {{-- Alerta de acceso denegado (redirigido por AdminMiddleware) --}}
        @if(session('error_acceso'))
            <div class="alert alert-error" style="margin-bottom: 20px;">
                🔒 {{ session('error_acceso') }}
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
