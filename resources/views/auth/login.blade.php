@extends('layouts.app')

@section('title', 'Acceso Institucional')

@section('content')
    <main
        style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at center, #6F163F 0%, #2b0816 100%); position: relative; overflow: hidden; padding: 20px;">

        <!-- Ambient Orbs -->
        <div
            style="position: absolute; top: -10%; left: -10%; width: 50%; height: 50%; background: #9b8042; filter: blur(150px); opacity: 0.2; border-radius: 50%; animation: float 10s infinite ease-in-out;">
        </div>
        <div
            style="position: absolute; bottom: -10%; right: -10%; width: 50%; height: 50%; background: #6F163F; filter: blur(150px); opacity: 0.3; border-radius: 50%; animation: float 15s infinite ease-in-out reverse;">
        </div>

        <!-- Majestic Card Container -->
        <div class="majestic-card"
            style="width: 100%; max-width: 1000px; min-height: 600px; display: flex; border-radius: 30px; overflow: hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.5); position: relative; z-index: 10; background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(40px); border: 1px solid rgba(255, 255, 255, 0.1);">

            <!-- LEFT PANEL: Brand Identity -->
            <div class="brand-panel"
                style="flex: 0 0 45%; background: linear-gradient(135deg, rgba(111, 22, 63, 0.9), rgba(43, 8, 22, 0.95)); padding: 60px 40px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; position: relative; border-right: 1px solid rgba(255,255,255,0.05);">

                <!-- Pattern Overlay -->
                <div
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px; opacity: 0.03;">
                </div>

                <div style="margin-bottom: 40px; position: relative;">
                    <div
                        style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 180px; height: 180px; background: radial-gradient(circle, rgba(180, 150, 88, 0.2) 0%, rgba(255,255,255,0) 70%); border-radius: 50%; animation: pulse 3s infinite ease-in-out;">
                    </div>
                    <img src="{{ asset('images/logo_color.png') }}" alt="Logo HNM"
                        style="height: 140px; position: relative; z-index: 1; filter: drop-shadow(0 15px 30px rgba(0,0,0,0.4));">
                </div>

                <h2
                    style="color: white; font-size: 28px; font-weight: 900; margin-bottom: 15px; line-height: 1.2; letter-spacing: -0.5px;">
                    Portal <span style="color: #b49658;">PROA-HNM</span>
                </h2>

                <div style="width: 60px; height: 4px; background: #b49658; margin: 0 auto 25px; border-radius: 2px;"></div>

                <p style="color: rgba(255,255,255,0.7); font-size: 15px; font-weight: 500; line-height: 1.6;">
                    Unidad de Vigilancia Epidemiológica &amp; Control de Infecciones
                </p>

                <div style="margin-top: auto; padding-top: 40px;">
                    <span
                        style="display: inline-block; padding: 8px 16px; background: rgba(255,255,255,0.1); border-radius: 20px; color: #b49658; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                        Acceso Seguro SSL
                    </span>
                </div>
            </div>

            <!-- RIGHT PANEL: Login Form -->
            <div class="form-panel"
                style="flex: 1; padding: 60px 50px; display: flex; flex-direction: column; justify-content: center; background: rgba(0,0,0,0.2);">

                <h1 style="font-size: 36px; color: white; font-weight: 800; margin-bottom: 10px; letter-spacing: -1px;">
                    Bienvenido
                </h1>
                <p style="color: rgba(255,255,255,0.5); font-size: 16px; margin-bottom: 50px;">
                    Ingrese sus credenciales institucionales para continuar.
                </p>

                <form method="POST" action="{{ url('/login') }}">
                    @csrf

                    <div class="form-group" style="margin-bottom: 30px;">
                        <label
                            style="display: block; color: #b49658; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px;">
                            ID Institucional
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="usuario@vigimed.hnm.gob.mx"
                            required autofocus
                            style="width: 100%; height: 65px; background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 16px; padding: 0 25px; color: white; font-size: 16px; font-weight: 500; transition: all 0.3s; outline: none;"
                            onfocus="this.style.borderColor='#b49658'; this.style.background='rgba(255,255,255,0.08)';"
                            onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.background='rgba(255,255,255,0.05)';">
                    </div>

                    <div class="form-group" style="margin-bottom: 35px;">
                        <label
                            style="display: block; color: #b49658; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px;">
                            Contraseña
                        </label>
                        <input type="password" name="password" placeholder="••••••••" required
                            style="width: 100%; height: 65px; background: rgba(255, 255, 255, 0.05); border: 2px solid rgba(255, 255, 255, 0.1); border-radius: 16px; padding: 0 25px; color: white; font-size: 16px; font-weight: 500; transition: all 0.3s; outline: none;"
                            onfocus="this.style.borderColor='#b49658'; this.style.background='rgba(255,255,255,0.08)';"
                            onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.background='rgba(255,255,255,0.05)';">
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 40px;">
                        <label
                            style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-size: 14px; color: rgba(255,255,255,0.7); font-weight: 500;">
                            <input type="checkbox" name="remember"
                                style="accent-color: #b49658; width: 20px; height: 20px; cursor: pointer;">
                            Mantener sesión activa
                        </label>
                    </div>

                    <button type="submit"
                        style="width: 100%; height: 70px; background: linear-gradient(90deg, #b49658, #d4af37); color: #2b0816; font-size: 18px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; border: none; border-radius: 16px; cursor: pointer; box-shadow: 0 15px 30px rgba(180, 150, 88, 0.25); transition: all 0.3s; position: relative; overflow: hidden;"
                        onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 20px 40px rgba(180, 150, 88, 0.35)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 15px 30px rgba(180, 150, 88, 0.25)';">
                        INGRESAR AL PORTAL
                    </button>
                </form>

                <div style="margin-top: 25px;">
                    <form method="POST" action="{{ route('quick-login') }}">
                        @csrf
                        <button type="submit"
                            style="width: 100%; height: 55px; background: rgba(255,255,255,0.05); color: #b49658; font-size: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border: 1px solid rgba(180, 150, 88, 0.3); border-radius: 14px; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.background='rgba(180, 150, 88, 0.1)'; this.style.borderColor='rgba(180, 150, 88, 0.6)';"
                            onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(180, 150, 88, 0.3)';"
                            onclick="this.innerHTML='<i class=\'fas fa-circle-notch fa-spin\'></i> INICIANDO ACCESO...'">
                            <i class="fas fa-stethoscope" style="margin-right: 8px;"></i> Acceso Rápido Médico
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <style>
        @keyframes float {
            0% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(20px, 20px);
            }

            100% {
                transform: translate(0, 0);
            }
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(0.95);
                opacity: 0.5;
            }

            50% {
                transform: translate(-50%, -50%) scale(1.05);
                opacity: 0.8;
            }

            100% {
                transform: translate(-50%, -50%) scale(0.95);
                opacity: 0.5;
            }
        }

        ::placeholder {
            color: rgba(255, 255, 255, 0.3) !important;
        }

        /* Autofill fix */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #240b15 inset !important;
            -webkit-text-fill-color: white !important;
            border-color: #b49658 !important;
        }

        /* Responsive Breakpoint */
        @media (max-width: 900px) {
            .majestic-card {
                flex-direction: column;
                max-width: 500px !important;
            }

            .brand-panel {
                padding: 40px 20px !important;
                flex: none !important;
            }

            .form-panel {
                padding: 40px 30px !important;
            }
        }
    </style>
@endsection

@section('scripts')
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Credenciales Incorrectas',
                text: "{{ $errors->first() }}",
                confirmButtonText: 'INTENTAR NUEVAMENTE',
                confirmButtonColor: '#b49658',
                background: '#2b0816',
                color: '#ffffff',
                iconColor: '#b49658'
            });
        </script>
    @endif
@endsection