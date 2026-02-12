@extends('layouts.app')

@section('title', 'Alta de Personal')

@section('content')
    <div style="max-width: 800px; margin: 0 auto; animation: fadeInUp 0.8s ease-out;">
        <!-- Header -->
        <div style="margin-bottom: 40px; text-align: center;">
            <span
                style="font-size: 10px; text-transform: uppercase; color: var(--proa-accent); font-weight: 900; letter-spacing: 2px; display: block; margin-bottom: 10px;">
                Seguridad e Identidad Institucional
            </span>
            <h1 style="font-size: 32px; color: var(--proa-primary); font-weight: 900; margin: 0; letter-spacing: -1px;">
                Alta de <span style="color: var(--proa-primary-dark)">Nuevo Personal HNM</span>
            </h1>
            <p style="color: var(--secondary); font-size: 14px; font-weight: 600; margin-top: 10px;">
                Registre las credenciales de acceso para médicos y administradores autorizados.
            </p>
        </div>

        <!-- Registration Card -->
        <div class="inst-card"
            style="background: white; border-radius: 20px; box-shadow: var(--shadow-lg); overflow: hidden; border: 1px solid var(--border);">
            <div
                style="background: var(--proa-gradient); padding: 20px 40px; color: white; display: flex; align-items: center; gap: 15px;">
                <i class="fas fa-id-card-alt" style="font-size: 24px; opacity: 0.9;"></i>
                <h2 style="font-size: 16px; font-weight: 800; margin: 0; text-transform: uppercase; letter-spacing: 1px;">
                    Formulario de Registro Electrónico
                </h2>
            </div>

            <form action="{{ route('usuarios.store') }}" method="POST" style="padding: 40px;">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                    <!-- Full Name -->
                    <div>
                        <label
                            style="display: block; font-size: 11px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                            Nombre Completo
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-user-md"
                                style="position: absolute; left: 15px; top: 15px; color: var(--proa-accent); font-size: 14px;"></i>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej. Dr. Juan Pérez"
                                required
                                style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; font-weight: 600; transition: all 0.3s;">
                        </div>
                    </div>

                    <!-- Specialty -->
                    <div>
                        <label
                            style="display: block; font-size: 11px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                            Especialidad Médica
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-microscope"
                                style="position: absolute; left: 15px; top: 15px; color: var(--proa-accent); font-size: 14px;"></i>
                            <input type="text" name="especialidad" value="{{ old('especialidad') }}"
                                placeholder="Ej. Pediatría, Proctología..."
                                style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; font-weight: 600; transition: all 0.3s;">
                        </div>
                    </div>

                    <!-- Institutional Email -->
                    <div>
                        <label
                            style="display: block; font-size: 11px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                            Correo Institucional
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-envelope"
                                style="position: absolute; left: 15px; top: 15px; color: var(--proa-accent); font-size: 14px;"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="usuario@proanet.gob.mx"
                                required
                                style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; font-weight: 600; transition: all 0.3s;">
                        </div>
                        @error('email') <span
                            style="color: #ef4444; font-size: 11px; font-weight: 700; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Role -->
                    <div>
                        <label
                            style="display: block; font-size: 11px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                            Rol en la Plataforma
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-user-tag"
                                style="position: absolute; left: 15px; top: 15px; color: var(--proa-accent); font-size: 14px; z-index: 10;"></i>
                            <select name="role" required
                                style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; font-weight: 700; background: white; appearance: none; cursor: pointer; transition: all 0.3s;">
                                <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Médico Especialista
                                </option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador de Sistema
                                </option>
                            </select>
                            <i class="fas fa-chevron-down"
                                style="position: absolute; right: 15px; top: 15px; color: var(--secondary); pointer-events: none;"></i>
                        </div>
                        @error('role') <span
                            style="color: #ef4444; font-size: 11px; font-weight: 700; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label
                            style="display: block; font-size: 11px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                            Contraseña de Acceso
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-lock"
                                style="position: absolute; left: 15px; top: 15px; color: var(--proa-accent); font-size: 14px;"></i>
                            <input type="password" name="password" placeholder="Mínimo 8 caracteres" required
                                style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; font-weight: 600; transition: all 0.3s;">
                        </div>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label
                            style="display: block; font-size: 11px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                            Confirmar Contraseña
                        </label>
                        <div style="position: relative;">
                            <i class="fas fa-check-double"
                                style="position: absolute; left: 15px; top: 15px; color: var(--proa-accent); font-size: 14px;"></i>
                            <input type="password" name="password_confirmation" placeholder="Repita la contraseña" required
                                style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.5px solid #e2e8f0; font-size: 14px; font-weight: 600; transition: all 0.3s;">
                        </div>
                        @error('password') <span
                            style="color: #ef4444; font-size: 11px; font-weight: 700; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="margin-top: 40px; display: flex; align-items: center; gap: 20px;">
                    <button type="submit" class="btn btn-primary"
                        style="flex: 2; padding: 15px; font-size: 12px; text-transform: uppercase; font-weight: 900; background: var(--proa-gradient); border: none; box-shadow: 0 5px 15px rgba(111, 22, 63, 0.3);">
                        <i class="fas fa-save" style="margin-right: 8px;"></i> Guardar Credenciales
                    </button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline"
                        style="flex: 1; padding: 15px; font-size: 12px; text-transform: uppercase; font-weight: 800; border-color: #cbd5e1; color: #64748b;">
                        Cancelar
                    </a>
                </div>
            </form>

            <div
                style="background: #f8fafc; padding: 20px 40px; border-top: 1px solid var(--border); display: flex; align-items: center; gap: 15px;">
                <i class="fas fa-shield-alt" style="color: #64748b;"></i>
                <p style="margin: 0; font-size: 11px; color: #64748b; font-weight: 600; line-height: 1.4;">
                    <strong>Nota de Seguridad:</strong> Todas las altas de personal son auditadas.
                    Asegúrese de que el correo institucional sea válido para el envío de notificaciones del sistema.
                </p>
            </div>
        </div>
    </div>

    <style>
        input:focus,
        select:focus {
            outline: none;
            border-color: var(--proa-primary) !important;
            box-shadow: 0 0 0 4px rgba(111, 22, 63, 0.1) !important;
            transform: translateY(-1px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection