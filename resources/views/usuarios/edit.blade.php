@extends('layouts.app')

@section('title', 'Editar Usuario - PROA-HNM')

@section('content')
    <div style="max-width: 800px; margin: 0 auto; animation: fadeInUp 0.8s ease-out;">
        <!-- Premium Header -->
        <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span
                    style="font-size: 10px; text-transform: uppercase; color: var(--proa-accent); font-weight: 900; letter-spacing: 2px; display: block; margin-bottom: 5px;">
                    Módulo Administrativo
                </span>
                <h1 style="font-size: 28px; color: var(--proa-primary); font-weight: 900; margin: 0; letter-spacing: -1px;">
                    Editar <span style="color: var(--proa-primary-dark)">Expediente de Personal</span>
                </h1>
            </div>
            <a href="{{ route('usuarios.index') }}" class="btn btn-outline" style="padding: 10px 20px; font-size: 11px;">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
        </div>

        <!-- Edit Form Card -->
        <div class="inst-card"
            style="border: none; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden;">
            <div class="card-header" style="background: var(--proa-gradient); padding: 20px 40px; border: none;">
                <h2
                    style="font-size: 14px; font-weight: 800; color: white; margin: 0; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-user-edit"></i> Información del Usuario: {{ $usuario->name }}
                </h2>
            </div>

            <div class="card-body" style="padding: 40px;">
                <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Row 1: Basic Info -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 25px;">
                        <div class="form-group-premium">
                            <label
                                style="display: block; font-size: 11px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                                Nombre Completo
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-user-md"
                                    style="position: absolute; left: 15px; top: 15px; color: var(--proa-accent); font-size: 14px;"></i>
                                <input type="text" name="name" value="{{ old('name', $usuario->name) }}" required
                                    style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; transition: var(--transition);"
                                    placeholder="Ej. Dr. Juan Pérez">
                            </div>
                        </div>

                        <div class="form-group-premium">
                            <label
                                style="display: block; font-size: 11px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                                Especialidad Médica
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-microscope"
                                    style="position: absolute; left: 15px; top: 15px; color: var(--proa-accent); font-size: 14px;"></i>
                                <input type="text" name="especialidad"
                                    value="{{ old('especialidad', $usuario->especialidad) }}"
                                    style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; transition: var(--transition);"
                                    placeholder="Ej. Pediatría, Cirugía...">
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Identity & Roles -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 25px;">
                        <div class="form-group-premium">
                            <label
                                style="display: block; font-size: 11px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                                Correo Institucional
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-envelope"
                                    style="position: absolute; left: 15px; top: 15px; color: var(--proa-accent); font-size: 14px;"></i>
                                <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required
                                    style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; transition: var(--transition);"
                                    placeholder="usuario@vigimed.hnm.gob.mx">
                            </div>
                        </div>

                        <div class="form-group-premium">
                            <label
                                style="display: block; font-size: 11px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">
                                Rol en el Sistema
                            </label>
                            <div style="position: relative;">
                                <i class="fas fa-shield-alt"
                                    style="position: absolute; left: 15px; top: 15px; color: var(--proa-accent); font-size: 14px;"></i>
                                <select name="role" required
                                    style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; font-weight: 700; background: white; cursor: pointer;">
                                    <option value="doctor" {{ $usuario->role === 'doctor' ? 'selected' : '' }}>Médico
                                        Especialista</option>
                                    <option value="admin" {{ $usuario->role === 'admin' ? 'selected' : '' }}>Administrador de
                                        Sistema</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Status Indicator (Full Width Bar) -->
                    <div
                        style="margin-bottom: 40px; padding: 15px 25px; border-radius: 12px; background: rgba(16, 185, 129, 0.04); border: 1.5px solid rgba(16, 185, 129, 0.1); display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-user-check" style="color: #10B981;"></i>
                            <span
                                style="font-size: 11px; font-weight: 900; color: var(--proa-primary); text-transform: uppercase; letter-spacing: 0.5px;">Estado
                                Actual de la Cuenta</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div
                                style="width: 8px; height: 8px; border-radius: 50%; background: #10B981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);">
                            </div>
                            <span
                                style="font-size: 12px; font-weight: 800; color: #065F46; text-transform: uppercase;">Acceso
                                Habilitado</span>
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div
                        style="background: #f8fafc; border: 1px dashed #e2e8f0; border-radius: 15px; padding: 30px; margin-bottom: 40px;">
                        <h3
                            style="font-size: 12px; font-weight: 900; color: var(--proa-primary); margin: 0 0 20px 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-key" style="color: var(--proa-accent);"></i> SEGURIDAD: ACTUALIZACIÓN DE
                            CONTRASEÑA
                        </h3>
                        <p style="font-size: 12px; color: var(--secondary); margin-bottom: 25px; font-weight: 500;">
                            Deje los campos de contraseña en blanco si no desea realizar cambios.
                        </p>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                            <div class="form-group-premium">
                                <label
                                    style="display: block; font-size: 11px; font-weight: 900; color: var(--secondary); text-transform: uppercase; margin-bottom: 8px;">Nueva
                                    Contraseña</label>
                                <input type="password" name="password"
                                    style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px;">
                            </div>
                            <div class="form-group-premium">
                                <label
                                    style="display: block; font-size: 11px; font-weight: 900; color: var(--secondary); text-transform: uppercase; margin-bottom: 8px;">Confirmar
                                    Contraseña</label>
                                <input type="password" name="password_confirmation"
                                    style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px;">
                            </div>
                        </div>
                    </div>

                    <!-- Actions Footer -->
                    <div
                        style="display: flex; justify-content: flex-end; gap: 15px; border-top: 1.5px solid var(--border); padding-top: 30px;">
                        <button type="submit" class="btn btn-primary"
                            style="padding: 16px 50px; font-weight: 900; background: var(--proa-gradient); border: none; box-shadow: 0 4px 15px rgba(111, 22, 63, 0.2); transition: all 0.3s;">
                            <i class="fas fa-save" style="margin-right: 10px;"></i> ACTUALIZAR CREDENCIALES
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
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

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--proa-primary) !important;
            box-shadow: 0 0 0 3px rgba(111, 22, 63, 0.1) !important;
        }
    </style>
@endsection