@extends('layouts.app')

@section('title', 'Panel de Control HNM')

@section('styles')
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.4);
            --proa-glow: rgba(111, 22, 63, 0.15);
        }

        .dashboard-container {
            animation: fadeIn 1s ease-out;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Institutional Aura Hero */
        .aura-hero {
            background: linear-gradient(135deg, var(--proa-primary-dark) 0%, #3d0a21 100%);
            border-radius: 30px;
            padding: 50px 60px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .aura-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -10%;
            width: 80%;
            height: 200%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.08) 0%, transparent 70%);
            transform: rotate(-15deg);
        }

        .aura-hero::after {
            content: 'HNM';
            position: absolute;
            right: -20px;
            bottom: -30px;
            font-size: 200px;
            font-weight: 900;
            color: rgba(255, 255, 255, 0.03);
            pointer-events: none;
            letter-spacing: -10px;
        }

        /* Modern Stat Modules */
        .stat-module {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 24px;
            padding: 35px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 20px;
            text-decoration: none;
            color: inherit;
        }

        .stat-module:hover {
            transform: translateY(-8px);
            background: white;
            box-shadow: 0 20px 40px var(--proa-glow);
            border-color: var(--proa-primary);
        }

        .icon-circle {
            width: 60px;
            height: 60px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            transition: all 0.3s;
        }

        .stat-module:hover .icon-circle {
            transform: scale(1.1) rotate(5deg);
        }

        /* Access Panels */
        .access-panel {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 24px;
            padding: 35px;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 200px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .access-btn {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 12px 20px;
            border-radius: 14px;
            color: white;
            text-decoration: none;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s;
            text-align: center;
            display: block;
        }

        .access-btn:hover {
            background: var(--proa-accent);
            color: var(--proa-primary-dark);
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
            border-color: var(--proa-accent);
        }

        /* Activity Stream */
        .activity-stream {
            background: white;
            border-radius: 30px;
            border: 1px solid var(--border);
            padding: 30px;
            box-shadow: var(--shadow-soft);
        }

        .stream-item {
            padding: 20px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .stream-item:hover {
            background: #f8fafc;
            border-color: #f1f5f9;
        }

        .stream-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--proa-primary);
            font-size: 18px;
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
    </style>
@endsection

@section('content')
    <div class="dashboard-container">
        <!-- Master Aura Hero -->
        <div class="aura-hero">
            <div style="z-index: 1;">
                <div
                    style="background: rgba(212, 175, 55, 0.15); color: var(--proa-accent); padding: 6px 15px; border-radius: 50px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; display: inline-block; margin-bottom: 20px; border: 1px solid rgba(212, 175, 55, 0.3);">
                    Vigilancia Inteligente HNM
                </div>
                <h1
                    style="font-size: 38px; color: white; font-weight: 900; margin: 0; letter-spacing: -1px; line-height: 1;">
                    Panel <span style="color: var(--proa-accent);">PROA-HNM</span>
                </h1>
                <p
                    style="color: rgba(255,255,255,0.6); font-size: 15px; font-weight: 600; margin-top: 12px; max-width: 500px;">
                    Control integral de recursos humanos y biblioteca clínica bajo los estándares del Hospital del Niño
                    Morelense.
                </p>
            </div>
            <div style="text-align: right; z-index: 1;">
                <div
                    style="display: flex; align-items: center; gap: 15px; background: rgba(255,255,255,0.05); padding: 15px 25px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1);">
                    <div
                        style="width: 10px; height: 10px; border-radius: 50%; background: #10b981; box-shadow: 0 0 15px #10b981;">
                    </div>
                    <div>
                        <div
                            style="font-size: 10px; color: rgba(255,255,255,0.4); font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                            Estado Local</div>
                        <div style="font-size: 13px; color: white; font-weight: 900;">SISTEMA ACTIVO</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pulse Grid -->
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 30px; margin-bottom: 40px;">
            <!-- Personnel -->
            <a href="{{ route('usuarios.index') }}" class="stat-module">
                <div class="icon-circle" style="background: rgba(111, 22, 63, 0.1); color: var(--proa-primary);">
                    <i class="fas fa-user-doctor"></i>
                </div>
                <div>
                    <div
                        style="font-size: 11px; font-weight: 900; color: var(--secondary); text-transform: uppercase; letter-spacing: 1.5px;">
                        Gestión de Personal</div>
                    <div style="font-size: 38px; font-weight: 900; color: var(--proa-primary-dark); margin: 5px 0;">
                        {{ $userCount }}
                    </div>
                    <div style="font-size: 13px; color: var(--secondary); font-weight: 700;">Colaboradores Autorizados</div>
                </div>
                <div
                    style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--border); font-size: 11px; font-weight: 800; color: var(--proa-primary); display: flex; align-items: center; justify-content: space-between;">
                    Administrar Equipo <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <!-- Library -->
            <a href="{{ route('documentos.index') }}" class="stat-module">
                <div class="icon-circle" style="background: rgba(0, 151, 167, 0.1); color: #00838F;">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <div
                        style="font-size: 11px; font-weight: 900; color: var(--secondary); text-transform: uppercase; letter-spacing: 1.5px;">
                        Biblioteca Digital</div>
                    <div style="font-size: 38px; font-weight: 900; color: #006064; margin: 5px 0;">{{ $docCount }}</div>
                    <div style="font-size: 13px; color: var(--secondary); font-weight: 700;">Recursos Médicos</div>
                </div>
                <div
                    style="margin-top: auto; padding-top: 20px; border-top: 1px solid var(--border); font-size: 11px; font-weight: 800; color: #00838F; display: flex; align-items: center; justify-content: space-between;">
                    Consultar Protocolos <i class="fas fa-arrow-right"></i>
                </div>
            </a>

            <!-- Rapid Action -->
            <div class="access-panel">
                <div>
                    <div
                        style="font-size: 11px; font-weight: 900; color: var(--proa-accent); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-bolt"></i> Operaciones Rápidas
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr; gap: 12px;">
                        <a href="{{ route('usuarios.create') }}" class="access-btn">Alta de Personal</a>
                        <a href="{{ route('documentos.create') }}" class="access-btn">Subir Documento</a>
                    </div>
                </div>
                <div style="font-size: 10px; color: rgba(255,255,255,0.4); font-weight: 700; font-style: italic;">
                    Accesos directos de administración HNM
                </div>
            </div>
        </div>

        <!-- Main Stream & Intelligence -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items: start;">
            <!-- Activity Stream -->
            <div class="activity-stream">
                <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
                    <h2
                        style="font-size: 18px; font-weight: 900; color: var(--proa-primary-dark); margin: 0; display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-clock-rotate-left" style="color: var(--proa-accent);"></i>
                        Flujo de Actividad Reciente
                    </h2>
                    <a href="{{ route('documentos.index') }}"
                        style="font-size: 12px; font-weight: 900; color: var(--proa-primary); text-decoration: none; border-bottom: 2px solid var(--proa-glow);">HISTORIAL
                        COMPLETO</a>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @forelse($recientes as $item)
                        <div class="stream-item">
                            <div class="stream-icon">
                                <i class="fas fa-file-circle-check"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 800; color: var(--proa-primary-dark); font-size: 15px;">
                                    {{ $item->nombre }}
                                </div>
                                <div style="font-size: 12px; color: var(--secondary); font-weight: 600; margin-top: 2px;">
                                    {{ $item->uploader->name }} • {{ $item->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <span
                                    style="font-size: 9px; font-weight: 900; color: white; text-transform: uppercase; background: var(--proa-primary); padding: 5px 12px; border-radius: 50px; letter-spacing: 0.5px;">
                                    {{ $item->categoria->nombre ?? 'Sin Categoría' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div style="padding: 100px 30px; text-align: center; color: var(--secondary);">
                            <i class="fas fa-inbox" style="font-size: 40px; margin-bottom: 20px; opacity: 0.2;"></i>
                            <p style="font-weight: 800; font-size: 14px;">No se registran movimientos recientes.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Intelligence Side -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 30px;">
                <!-- Mission -->
                <div
                    style="background: white; border-radius: 24px; padding: 35px; border: 1px solid var(--border); box-shadow: var(--shadow-soft);">
                    <div
                        style="width: 40px; height: 40px; background: rgba(212, 175, 55, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--proa-accent); font-size: 18px; margin-bottom: 25px;">
                        <i class="fas fa-building-columns"></i>
                    </div>
                    <h3
                        style="font-size: 12px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 15px;">
                        Misión Institucional</h3>
                    <p
                        style="font-size: 14px; color: var(--proa-primary-dark); font-weight: 600; line-height: 1.8; font-style: italic; margin-bottom: 20px;">
                        "Brindar servicios de salud especializados de la más alta calidad a la infancia y adolescencia, con
                        un sentido de trascendencia humana."
                    </p>
                    <div
                        style="background: #f8fafc; padding: 15px; border-radius: 12px; font-size: 11px; color: var(--secondary); font-weight: 700; border-left: 4px solid var(--proa-accent);">
                        Dirección Médica HNM • 2026
                    </div>
                </div>

                <!-- Security Intelligence Monitor -->
                <div
                    style="background: white; border-radius: 24px; padding: 35px; border: 1px solid var(--border); box-shadow: var(--shadow-soft);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                        <h3
                            style="font-size: 12px; font-weight: 900; color: var(--secondary); text-transform: uppercase; letter-spacing: 1.5px; margin: 0;">
                            Monitor de Seguridad</h3>
                        <div
                            style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; box-shadow: 0 0 10px #10b981;">
                        </div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        @forelse($bitacoraReciente as $log)
                            <div
                                style="background: #f8fafc; padding: 12px 15px; border-radius: 12px; border-left: 3px solid var(--proa-primary);">
                                <div
                                    style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                                    <span
                                        style="font-size: 11px; font-weight: 900; color: var(--proa-primary-dark);">{{ $log->accion }}</span>
                                    <span
                                        style="font-size: 9px; color: var(--secondary); font-weight: 700;">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <div style="font-size: 10px; color: var(--secondary); font-weight: 600; line-height: 1.4;">
                                    {{ Str::limit($log->descripcion, 60) }}
                                </div>
                            </div>
                        @empty
                            <div
                                style="text-align: center; padding: 20px; color: var(--secondary); font-size: 11px; font-weight: 700;">
                                Sin alertas recientes.
                            </div>
                        @endforelse
                    </div>

                    <a href="{{ route('bitacora.index') }}"
                        style="display: block; text-align: center; margin-top: 25px; font-size: 11px; font-weight: 900; color: var(--proa-primary); text-decoration: none; border-top: 1px solid #f1f5f9; padding-top: 15px;">
                        VER BITÁCORA COMPLETA
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection