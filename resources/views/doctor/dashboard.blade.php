@extends('layouts.app')

@section('title', 'Portal del Médico')

@section('styles')
    <style>
        .medical-hero {
            background: linear-gradient(135deg, var(--proa-primary-dark) 0%, #001a35 100%);
            border-radius: var(--radius-lg);
            padding: 60px 50px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: var(--shadow-premium);
            animation: fadeInDown 0.8s ease-out;
        }

        .medical-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -10%;
            width: 70%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 212, 255, 0.05) 0%, transparent 70%);
            transform: rotate(-15deg);
        }

        .hero-badge {
            background: rgba(0, 212, 255, 0.1);
            color: var(--proa-accent);
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: inline-block;
            margin-bottom: 20px;
            border: 1px solid rgba(0, 212, 255, 0.2);
        }

        .medical-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-bottom: 60px;
        }

        .action-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 60px 40px;
            text-align: center;
            border: 1px solid var(--border);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: var(--shadow-soft);
            animation: fadeInUp 0.8s ease-out;
            min-height: 420px;
            justify-content: center;
        }

        .action-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-premium);
            border-color: var(--proa-accent);
        }

        .card-icon-box {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            color: var(--proa-primary);
            font-size: 32px;
            background: #f8fafc;
            transition: all 0.3s;
        }

        .action-card:hover .card-icon-box {
            background: var(--proa-gradient);
            color: white;
            box-shadow: 0 10px 20px rgba(111, 22, 63, 0.2);
        }

        .card-title {
            font-size: 20px;
            font-weight: 900;
            color: var(--proa-primary-dark);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-desc {
            color: var(--secondary);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .medical-notice {
            background: white;
            border-radius: var(--radius-lg);
            padding: 35px;
            border: 1px solid var(--border);
            display: flex;
            gap: 30px;
            align-items: center;
            box-shadow: var(--shadow-soft);
            border-left: 6px solid var(--proa-accent);
        }

        .notice-icon {
            width: 60px;
            height: 60px;
            background: #f0f7ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--proa-primary);
            font-size: 24px;
            flex-shrink: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('content')
    <div class="medical-hero">
        <div class="hero-badge">Pabellón Digital PROA-HNM</div>
        <h1 style="font-size: 36px; font-weight: 900; color: white; margin-bottom: 15px;">
            Bienvenido, <span style="color: var(--proa-accent);">{{ auth()->user()->name }}</span>
        </h1>
        <p style="font-size: 16px; color: rgba(255,255,255,0.7); max-width: 700px; line-height: 1.6; font-weight: 600;">
            Has ingresado al centro operativo de documentación institucional. Accede a los protocolos oficiales y colabora
            subiendo nuevas guías clínicas actualizadas.
        </p>
    </div>

    <div class="medical-grid">
        <!-- Biblioteca Médica -->
        <div class="action-card" style="animation-delay: 0.1s;">
            <div class="card-icon-box">
                <i class="fas fa-book-open"></i>
            </div>
            <h3 class="card-title">Biblioteca Médica</h3>
            <p class="card-desc">Consulta las guías clínicas, protocolos de diagnóstico y manuales oficiales autorizados por
                el hospital.</p>
            <a href="{{ route('documentos.index') }}" class="btn btn-outline"
                style="width: 100%; padding: 12px; font-weight: 800; border-width: 2px;">
                VER DOCUMENTACIÓN
            </a>
        </div>

        <!-- Colaboración -->
        <div class="action-card" style="animation-delay: 0.2s;">
            <div class="card-icon-box">
                <i class="fas fa-cloud-arrow-up"></i>
            </div>
            <h3 class="card-title">Carga de Recursos</h3>
            <p class="card-desc">Colabore con la institución subiendo actualizaciones de protocolos para la revisión del
                comité técnico.</p>
            <a href="{{ route('documentos.create') }}" class="btn btn-accent"
                style="width: 100%; padding: 12px; font-weight: 800;">
                SUBIR PROTOCOLO
            </a>
        </div>
    </div>

    <div class="medical-notice">
        <div class="notice-icon">
            <i class="fas fa-circle-info"></i>
        </div>
        <div>
            <h4
                style="color: var(--proa-primary-dark); font-weight: 900; margin-bottom: 5px; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">
                Información Institucional
            </h4>
            <p style="color: var(--secondary); font-size: 14px; font-weight: 600; margin: 0; line-height: 1.5;">
                Toda la documentación cargada en este portal debe cumplir con los estándares de identidad institucional y
                ser validada por la Dirección Médica del HNM antes de su publicación general.
            </p>
        </div>
    </div>
@endsection