<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HNM - @yield('title', 'Sistema Institucional')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome 6.5.1 Free -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Custom SweetAlert Styles to match PROA-HNM */
        .swal2-popup {
            border-radius: 15px !important;
            border-bottom: 5px solid var(--proa-accent) !important;
        }

        .swal2-title {
            color: var(--proa-primary) !important;
            font-weight: 800 !important;
        }

        .swal2-confirm {
            background: var(--proa-primary) !important;
            border-radius: 8px !important;
            text-transform: uppercase !important;
            font-weight: 700 !important;
            letter-spacing: 1px !important;
        }
    </style>
</head>

<body class="fade-in">

    @auth
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-text" style="font-size: 20px; line-height: 1.2; letter-spacing: 1px;">HOSPITAL DEL
                    NIÑO<br>MORELENSE</div>
                <div
                    style="font-size: 9px; color: var(--proa-accent); margin-top: 8px; letter-spacing: 1.5px; font-weight: 800; text-transform: uppercase;">
                    Unidad de Vigilancia Epidemiológica</div>
            </div>

            <nav class="nav-menu">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('dashboard') }}"
                        class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">Panel Control</a>
                    <a href="{{ route('usuarios.index') }}"
                        class="nav-link-item {{ request()->routeIs('usuarios.index') ? 'active' : '' }}">Gestión Personal</a>
                    <a href="{{ route('perfil.edit') }}"
                        class="nav-link-item {{ request()->routeIs('perfil.edit') ? 'active' : '' }}">Mi Perfil</a>
                @else
                    <a href="{{ route('doctor.dashboard') }}"
                        class="nav-link-item {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">Inicio Médico</a>
                @endif

                <a href="{{ route('documentos.index') }}"
                    class="nav-link-item {{ request()->routeIs('documentos.index') ? 'active' : '' }}">Biblioteca</a>
            </nav>

        </aside>

        <main class="dashboard-content">
            <!-- Top Utility Bar: Premium Identity & Controls -->
            <!-- Top Utility Bar: Unified Premium Identity & Controls -->
            <div
                style="display: flex; justify-content: flex-end; align-items: center; padding: 20px 40px; position: fixed; top: 0; right: 0; left: var(--sidebar-width); z-index: 900; background: rgba(244, 247, 250, 0.8); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border);">

                <!-- Unified User Pill -->
                <div
                    style="display: flex; align-items: center; background: white; padding: 6px; padding-right: 20px; border-radius: 50px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); gap: 15px;">
                    <!-- Avatar & Info -->
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div
                            style="width: 35px; height: 35px; background: var(--proa-gradient); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; font-weight: 900; box-shadow: 0 4px 10px rgba(0, 86, 179, 0.2);">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div style="text-align: left;">
                            <div
                                style="font-size: 12px; font-weight: 800; color: var(--proa-primary-dark); line-height: 1;">
                                {{ auth()->user()->name }}
                            </div>
                            <div
                                style="font-size: 9px; color: var(--proa-accent); font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-top: 2px;">
                                {{ auth()->user()->role }}
                            </div>
                        </div>
                    </div>

                    <!-- Vertical Divider -->
                    <div style="width: 1px; height: 25px; background: var(--border);"></div>

                    <!-- Action: Logout -->
                    <button type="button" onclick="confirmLogout()"
                        style="background: transparent; border: none; font-size: 10px; font-weight: 800; color: #ef4444; text-transform: uppercase; cursor: pointer; display: flex; align-items: center; gap: 8px; padding: 5px 10px; border-radius: 20px; transition: var(--transition);"
                        onmouseover="this.style.background='#fff1f1'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-sign-out-alt"></i> Salir
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            @yield('content')
        </main>
    @else
        @yield('content')
    @endauth

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonText: 'Entendido'
            });
        </script>
    @endif

    <script>
        function confirmLogout() {
            Swal.fire({
                title: '¿Finalizar Sesión?',
                text: "Se cerrará el acceso al sistema institucional PROA-HNM.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#631133',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'SÍ, SALIR',
                cancelButtonText: 'CANCELAR',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }
    </script>
    @yield('scripts')
</body>

</html>