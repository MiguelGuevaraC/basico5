<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-base-url" content="{{ url('/') }}">
    <title>{{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/cards.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/modals.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/alerts.css') }}">
</head>
<body>
    <div class="app-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="bi bi-box-seam"></i>
                    <span>{{ config('app.name') }}</span>
                </div>
            </div>

            <ul class="sidebar-menu">
                <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                    <a href="{{ route('categorias.index') }}">
                        <i class="bi bi-tags"></i>
                        <span>Categorías</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('marcas.*') ? 'active' : '' }}">
                    <a href="{{ route('marcas.index') }}">
                        <i class="bi bi-award"></i>
                        <span>Marcas</span>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('productos.*') ? 'active' : '' }}">
                    <a href="{{ route('productos.index') }}">
                        <i class="bi bi-box"></i>
                        <span>Productos</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                @auth
                    <div class="user-info">
                        <div class="user-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="user-details">
                            <div class="user-name">{{ auth()->user()->name }}</div>
                            <div class="user-username">{{ auth()->user()->username }}</div>
                        </div>
                    </div>
                    <form method="post" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="bi bi-box-arrow-left"></i>
                            <span>Cerrar sesión</span>
                        </button>
                    </form>
                @endauth
            </div>
        </nav>

        <main class="main-content">
            <div class="content-wrapper">
                @if (session('status'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

<script>
    window.APP_BASE_URL = document.querySelector('meta[name="app-base-url"]')?.getAttribute('content') || '';
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
<script src="{{ asset('assets/js/http.js') }}"></script>
<script src="{{ asset('assets/js/datagrid.js') }}"></script>
<script src="{{ asset('assets/js/databox.js') }}"></script>
@stack('scripts')
</body>
</html>
