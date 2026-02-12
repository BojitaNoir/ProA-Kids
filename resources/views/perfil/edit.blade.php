@extends('layouts.app')

@section('title', 'Mi Perfil HNM')

@section('styles')
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.4);
            --profile-aura: radial-gradient(circle at top right, rgba(212, 175, 55, 0.15), transparent 70%);
        }

        .profile-wrapper {
            animation: fadeIn 1s ease-out;
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Unified Identity Card */
        .identity-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 40px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.08);
            display: grid;
            grid-template-columns: 400px 1fr;
            overflow: hidden;
            min-height: 700px;
            position: relative;
        }

        /* Left: Personal Aura */
        .aura-sidebar {
            background: var(--proa-primary-dark);
            position: relative;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            color: white;
            overflow: hidden;
        }

        .aura-sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--profile-aura);
            z-index: 0;
        }

        .aura-sidebar::after {
            content: 'HNM';
            position: absolute;
            left: -20px;
            bottom: -40px;
            font-size: 180px;
            font-weight: 900;
            color: rgba(255, 255, 255, 0.03);
            pointer-events: none;
            z-index: 0;
        }

        .portrait-frame {
            width: 180px;
            height: 180px;
            border-radius: 30px;
            background: var(--proa-gradient);
            padding: 5px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
            transform: rotate(-2deg);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .portrait-frame:hover {
            transform: rotate(0) scale(1.05);
        }

        .portrait-frame img {
            width: 100%;
            height: 100%;
            border-radius: 25px;
            object-fit: cover;
            border: 5px solid var(--proa-primary-dark);
        }

        .rank-badge {
            background: rgba(212, 175, 55, 0.15);
            color: var(--proa-accent);
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            z-index: 1;
        }

        /* Right: Config Stream */
        .config-main {
            padding: 70px 80px;
            background: rgba(255, 255, 255, 0.4);
            z-index: 1;
        }

        .config-title {
            font-size: 30px;
            font-weight: 900;
            color: var(--proa-primary-dark);
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .elite-input-group {
            margin-bottom: 30px;
        }

        .elite-label {
            display: block;
            font-size: 11px;
            font-weight: 900;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
        }

        .elite-input {
            width: 100%;
            padding: 18px 25px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid var(--border);
            border-radius: 18px;
            font-size: 15px;
            font-weight: 700;
            color: var(--proa-primary-dark);
            transition: all 0.3s;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .elite-input:focus {
            outline: none;
            border-color: var(--proa-primary);
            background: white;
            box-shadow: 0 10px 25px var(--proa-glow);
            transform: translateY(-2px);
        }

        .security-shard {
            background: white;
            border-radius: 24px;
            padding: 35px;
            margin: 40px 0;
            border: 1px solid var(--border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            position: relative;
        }

        .security-shard::before {
            content: '';
            position: absolute;
            left: 0;
            top: 30px;
            bottom: 30px;
            width: 4px;
            background: var(--proa-accent);
            border-radius: 0 4px 4px 0;
        }

        .btn-elite-save {
            background: var(--proa-primary);
            color: white;
            padding: 20px 40px;
            border-radius: 18px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-size: 13px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            box-shadow: 0 15px 30px rgba(111, 22, 63, 0.2);
        }

        .btn-elite-save:hover {
            background: var(--proa-primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(111, 22, 63, 0.3);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 900px) {
            .identity-card {
                grid-template-columns: 1fr;
            }

            .aura-sidebar {
                padding: 40px;
            }

            .config-main {
                padding: 40px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="profile-wrapper">
        <div class="identity-card">
            <!-- Aura Sidebar -->
            <div class="aura-sidebar">
                <div class="portrait-frame">
                    @php
                        // Limpiar nombre de títulos para el avatar
                        $avatarName = str_replace(
                            ['Dr.', 'Dra.', 'Lic.', 'Ing.', 'Mtro.', '(', ')'],
                            '',
                            $user->name
                        );
                        $formattedRole = $user->role === 'admin' ? 'Administrador' : 'Médico Especialista';
                    @endphp
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(trim($avatarName)) }}&background=631133&color=ffffff&bold=true&size=256&length=2"
                        alt="Avatar">
                </div>

                <div class="rank-badge">{{ $formattedRole }} Institucional</div>

                <h2
                    style="font-size: 26px; font-weight: 900; margin-bottom: 5px; line-height: 1.1; position: relative; z-index: 1;">
                    {{ $user->name }}
                </h2>

                <p
                    style="font-size: 14px; color: var(--proa-accent); font-weight: 700; opacity: 0.9; margin-top: 5px; position: relative; z-index: 1;">
                    {{ $user->email }}
                </p>

                <div style="margin-top: auto; padding-top: 40px; width: 100%; position: relative; z-index: 1;">
                    <div
                        style="display: flex; justify-content: space-between; font-size: 11px; font-weight: 800; color: rgba(255,255,255,0.4); text-transform: uppercase; margin-bottom: 10px;">
                        <span>Registro de Sistema</span>
                        <span style="color: white;">#{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div
                        style="display: flex; justify-content: space-between; font-size: 11px; font-weight: 800; color: rgba(255,255,255,0.4); text-transform: uppercase;">
                        <span>Vigencia desde</span>
                        <span style="color: white;">{{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Config Main -->
            <div class="config-main">
                <div style="margin-bottom: 50px;">
                    <h1 class="config-title">Configuración de Identidad</h1>
                    <p style="color: var(--secondary); font-size: 15px; font-weight: 600;">Actualice sus credenciales para
                        mantener la integridad del ecosistema HNM.</p>
                </div>

                <form action="{{ route('perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(!auth()->user()->isAdmin())
                        <div style="background: rgba(212, 175, 55, 0.1); border-left: 4px solid var(--proa-accent); padding: 15px; border-radius: 8px; margin-bottom: 30px;">
                            <p style="margin: 0; font-size: 13px; color: var(--proa-primary-dark); font-weight: 700;">
                                <i class="fas fa-lock" style="margin-right: 8px;"></i>
                                Los cambios de nombre y correo institucional deben ser gestionados por el Administrador de Sistemas.
                            </p>
                        </div>
                    @endif

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                        <div class="elite-input-group">
                            <label class="elite-label">Nombre Completo</label>
                            <input type="text" name="name" class="elite-input" value="{{ old('name', $user->name) }}"
                                {{ !auth()->user()->isAdmin() ? 'readonly style=background:rgba(0,0,0,0.03);cursor:not-allowed;' : 'required' }}>
                        </div>

                        <div class="elite-input-group">
                            <label class="elite-label">Correo Institucional</label>
                            <input type="email" name="email" class="elite-input" value="{{ old('email', $user->email) }}"
                                {{ !auth()->user()->isAdmin() ? 'readonly style=background:rgba(0,0,0,0.03);cursor:not-allowed;' : 'required' }}>
                        </div>
                    </div>

                    <div class="security-shard">
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 25px;">
                            <div
                                style="width: 32px; height: 32px; background: rgba(111, 22, 63, 0.1); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--proa-primary); font-size: 14px;">
                                <i class="fas fa-shield-halved"></i>
                            </div>
                            <h3
                                style="font-size: 13px; font-weight: 900; color: var(--proa-primary-dark); text-transform: uppercase; letter-spacing: 1px; margin: 0;">
                                Seguridad de la Cuenta</h3>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                            <div class="elite-input-group" style="margin-bottom: 0;">
                                <label class="elite-label">Nueva Contraseña <span
                                        style="text-transform: none; opacity: 0.5;">(Opcional)</span></label>
                                <input type="password" name="password" class="elite-input" placeholder="••••••••">
                            </div>

                            <div class="elite-input-group" style="margin-bottom: 0;">
                                <label class="elite-label">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" class="elite-input"
                                    placeholder="••••••••">
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <button type="submit" class="btn-elite-save">
                            Actualizar Credenciales de Acceso
                        </button>

                        <a href="{{ auth()->user()->isAdmin() ? route('dashboard') : route('doctor.dashboard') }}"
                            style="text-align: center; color: var(--secondary); font-size: 12px; font-weight: 800; text-transform: uppercase; text-decoration: none; padding: 10px; transition: all 0.3s;"
                            onmouseover="this.style.color='var(--proa-primary)'"
                            onmouseout="this.style.color='var(--secondary)'">
                            cancelar y volver al panel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection