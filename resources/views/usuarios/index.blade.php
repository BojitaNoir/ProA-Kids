@extends('layouts.app')

@section('title', 'Gestión de Personal')

@section('content')
    <!-- Premium Header with Stats -->
    <div style="margin-bottom: 40px; animation: fadeInDown 0.8s ease-out;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
            <div>
                <span style="font-size: 10px; text-transform: uppercase; color: var(--proa-accent); font-weight: 900; letter-spacing: 2px; display: block; margin-bottom: 5px;">
                    Administración de Recursos Humanos
                </span>
                <h1 style="font-size: 32px; color: var(--proa-primary); font-weight: 900; margin: 0; letter-spacing: -1px;">
                    Control de <span style="color: var(--proa-primary-dark)">Personal PROA-HNM</span>
                </h1>
            </div>
            <div style="display: flex; gap: 15px;">
                <div class="stat-pill-premium" style="background: white; padding: 12px 25px; border-radius: 15px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-align: center;">
                    <div style="font-size: 9px; text-transform: uppercase; color: var(--secondary); font-weight: 800; letter-spacing: 1px;">Total</div>
                    <div style="font-size: 20px; font-weight: 900; color: var(--proa-primary);">{{ $usuarios->count() }}</div>
                </div>
                <div class="stat-pill-premium" style="background: white; padding: 12px 25px; border-radius: 15px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); text-align: center;">
                    <div style="font-size: 9px; text-transform: uppercase; color: var(--secondary); font-weight: 800; letter-spacing: 1px;">Médicos</div>
                    <div style="font-size: 20px; font-weight: 900; color: var(--proa-primary-dark);">{{ $usuarios->where('role', 'doctor')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Search & Tools bar -->
        <div style="background: white; padding: 15px 30px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-soft); display: flex; align-items: center; gap: 20px;">
            <div style="flex: 1; position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--proa-accent);"></i>
                <input type="text" id="userSearch" placeholder="Escriba nombre, email o especialidad para filtrar..." 
                    style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; transition: all 0.3s;"
                    onfocus="this.style.borderColor='var(--proa-primary)'; this.style.boxShadow='0 0 0 3px rgba(111, 22, 63, 0.1)'"
                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary" style="padding: 12px 25px; font-size: 11px; white-space: nowrap; background: var(--proa-gradient); border: none;">
                    <i class="fas fa-user-plus"></i> Alta de Nuevo Usuario
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline" style="padding: 12px 25px; font-size: 11px; white-space: nowrap;">
                    <i class="fas fa-arrow-left"></i> Volver al Panel
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="inst-card" style="border: none; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg); overflow: hidden; animation: fadeInUp 0.8s ease-out 0.2s both;">
        <div class="card-header" style="background: white; border-bottom: 1px solid var(--border); padding: 25px 40px;">
            <h2 style="font-size: 16px; font-weight: 900; color: var(--proa-primary); display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-user-shield" style="color: var(--proa-accent);"></i>
                Médicos y Administradores Registrados
            </h2>
        </div>
        
        <div class="card-body" style="padding: 0;">
            <table class="data-table" id="usersTable">
                <thead style="background: var(--bg-light);">
                    <tr>
                        <th style="padding: 20px 40px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary);">Personal</th>
                        <th style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary);">Especialidad / Rol</th>
                        <th style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary);">Fecha Incorporación</th>
                        <th style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--secondary); text-align: right; padding-right: 40px;">Acciones de Seguridad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                        <tr class="user-row" style="border-bottom: 1px solid #f1f5f9; transition: var(--transition);">
                            <td style="padding: 20px 40px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div style="width: 45px; height: 45px; border-radius: 12px; background: {{ $usuario->role === 'admin' ? 'var(--proa-gradient)' : 'linear-gradient(135deg, #0097A7, #006064)' }}; display: flex; align-items: center; justify-content: center; color: white; font-weight: 900; font-size: 14px; box-shadow: var(--shadow-sm);">
                                        {{ strtoupper(substr($usuario->name, 0, 1)) }}{{ strtoupper(substr(strstr($usuario->name, ' '), 1, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; color: var(--proa-primary-dark); font-size: 14px;">{{ $usuario->name }}</div>
                                        <div style="font-size: 12px; color: var(--secondary); font-weight: 500;">{{ $usuario->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($usuario->role === 'admin')
                                    <span style="background: rgba(111, 22, 63, 0.08); color: var(--proa-primary); padding: 6px 15px; border-radius: 50px; font-size: 10px; font-weight: 900; text-transform: uppercase; border: 1px solid rgba(111, 22, 63, 0.15); display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-crown" style="font-size: 8px;"></i> Administrador
                                    </span>
                                @else
                                    <span style="background: rgba(0, 151, 167, 0.08); color: #00838F; padding: 6px 15px; border-radius: 50px; font-size: 10px; font-weight: 900; text-transform: uppercase; border: 1px solid rgba(0, 151, 167, 0.15); display: inline-flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-stethoscope" style="font-size: 8px;"></i> Médico Especialista
                                    </span>
                                @endif

                                @if($usuario->especialidad)
                                    <div style="font-size: 11px; color: var(--secondary); font-weight: 800; margin-top: 5px; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 5px;">
                                        <i class="fas fa-microscope" style="font-size: 9px; color: var(--proa-accent);"></i>
                                        {{ $usuario->especialidad }}
                                    </div>
                                @endif
                            </td>
                            <td style="color: var(--secondary); font-weight: 700; font-size: 13px;">
                                <i class="far fa-calendar-alt" style="margin-right: 5px; color: var(--proa-accent);"></i>
                                {{ $usuario->created_at->format('d M, Y') }}
                            </td>
                            <td style="text-align: right; padding-right: 40px; display: flex; justify-content: flex-end; gap: 8px;">
                                <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn-edit-premium">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                @if($usuario->id !== auth()->id())
                                    <button
                                        onclick="confirmarBaja('{{ route('usuarios.destroy', $usuario->id) }}', '{{ $usuario->name }}')"
                                        class="btn-revoke-premium">
                                        Revocar
                                    </button>
                                @else
                                    <span style="font-size: 11px; font-weight: 800; color: var(--secondary); text-transform: uppercase; opacity: 0.5; align-self: center;">Activo</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <style>
        .user-row:hover {
            background-color: #f8fafc;
            transform: scale(1.002);
            box-shadow: inset 4px 0 0 var(--proa-primary);
        }

        .btn-edit-premium {
            background: white;
            border: 1.5px solid var(--border);
            color: var(--proa-primary);
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-edit-premium:hover {
            background: var(--bg-light);
            border-color: var(--proa-primary);
            transform: translateY(-1px);
        }

        .btn-revoke-premium {
            background: white;
            border: 1.5px solid #fee2e2;
            color: #ef4444;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.5px;
        }

        .btn-revoke-premium:hover {
            background: #ef4444;
            color: white;
            border-color: #ef4444;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
            transform: translateY(-1px);
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Real-time Search
        document.getElementById('userSearch').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.user-row');
            
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                if(text.includes(term)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        function confirmarBaja(url, nombre) {
            Swal.fire({
                title: '¿REVOCAR ACCESO?',
                text: `Se eliminarán las credenciales de ${nombre} permanentemente. Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'SÍ, REVOCAR ACCESO',
                cancelButtonText: 'CANCELAR',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
