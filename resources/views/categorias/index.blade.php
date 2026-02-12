@extends('layouts.app')

@section('title', 'Gestión de Categorías')

@section('content')
    <div style="margin-bottom: 40px; animation: fadeInDown 0.8s ease-out;">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
            <div>
                <span
                    style="font-size: 10px; text-transform: uppercase; color: var(--proa-accent); font-weight: 900; letter-spacing: 2px; display: block; margin-bottom: 5px;">
                    Configuración del Sistema
                </span>
                <h1 style="font-size: 32px; color: var(--proa-primary); font-weight: 900; margin: 0; letter-spacing: -1px;">
                    Clasificación de <span style="color: var(--proa-primary-dark)">Documentos</span>
                </h1>
            </div>
            <button onclick="showCreateModal()" 
                    style="background: var(--proa-gradient); color: white; border: none; padding: 14px 28px; border-radius: 14px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 10px 20px rgba(99, 17, 51, 0.15); display: flex; align-items: center; gap: 10px; cursor: pointer; transition: all 0.3s;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 30px rgba(99, 17, 51, 0.25)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(99, 17, 51, 0.15)';"
                    class="btn-elite-action">
                <i class="fas fa-plus-circle"></i>Nueva Categoría
            </button>
        </div>
    </div>

    <div class="proa-card" style="padding: 40px; border-radius: 30px; animation: fadeInUp 0.8s ease-out; box-shadow: var(--shadow-premium);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0 15px;">
                <thead>
                    <tr style="color: rgba(99, 17, 51, 0.5); font-size: 11px; text-transform: uppercase; font-weight: 900; letter-spacing: 2px;">
                        <th style="padding: 10px 25px; text-align: left;">Categoría Institucional</th>
                        <th style="padding: 10px 25px; text-align: center;">Volumen de Archivos</th>
                        <th style="padding: 10px 25px; text-align: right;">Gestión</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categorias as $cat)
                        <tr style="background: white; border-radius: 20px; transition: all 0.3s; box-shadow: 0 5px 15px rgba(0,0,0,0.02);" 
                            onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.05)';" 
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 15px rgba(0,0,0,0.02)';">
                            <td style="padding: 25px; border-radius: 20px 0 0 20px;">
                                <div style="display: flex; align-items: center; gap: 20px;">
                                    <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 18px; display: flex; align-items: center; justify-content: center; color: var(--proa-accent); font-size: 24px; border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
                                        @if($cat->icono)
                                            <img src="{{ asset('storage/' . $cat->icono) }}" style="width: 100%; height: 100%; object-fit: contain; padding: 5px;">
                                        @else
                                            <i class="fas fa-folder-tree"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div style="font-weight: 900; color: var(--proa-primary-dark); font-size: 16px;">{{ $cat->nombre }}</div>
                                        <div style="font-size: 11px; color: var(--secondary); font-weight: 700; text-transform: uppercase;">Tipo: Documentación {{ $cat->icono ? 'Personalizada' : 'Estándar' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 25px; text-align: center;">
                                <div style="background: {{ $cat->documentos_count > 0 ? 'rgba(0, 151, 167, 0.08)' : 'rgba(212, 175, 55, 0.08)' }}; 
                                            color: {{ $cat->documentos_count > 0 ? 'var(--proa-primary)' : 'var(--proa-accent)' }}; 
                                            display: inline-flex; align-items: center; gap: 8px; padding: 8px 18px; border-radius: 50px; font-weight: 900; font-size: 12px;">
                                    <i class="fas {{ $cat->documentos_count > 0 ? 'fa-file-shield' : 'fa-file-circle-exclamation' }}"></i>
                                    {{ $cat->documentos_count }} Archivos
                                </div>
                            </td>
                            <td style="padding: 25px; border-radius: 0 20px 20px 0; text-align: right;">
                                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                                    <button onclick="showEditModal({{ $cat->id }}, '{{ $cat->nombre }}')"
                                        style="width: 40px; height: 40px; border-radius: 12px; border: 1px solid var(--border); background: white; color: var(--proa-primary); cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center;"
                                        onmouseover="this.style.background='var(--proa-primary)'; this.style.color='white';"
                                        onmouseout="this.style.background='white'; this.style.color='var(--proa-primary)';"
                                        title="Redefinir Nombre e Imagen">
                                        <i class="fas fa-pen-nib"></i>
                                    </button>
                                    
                                    @if($cat->documentos_count == 0)
                                        <form action="{{ route('categorias.destroy', $cat->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDeleteCategory(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                style="width: 40px; height: 40px; border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.2); background: white; color: #ef4444; cursor: pointer; transition: all 0.3s; display: flex; align-items: center; justify-content: center;"
                                                onmouseover="this.style.background='#ef4444'; this.style.color='white';"
                                                onmouseout="this.style.background='white'; this.style.color='#ef4444';"
                                                title="Eliminar Categoría">
                                                <i class="fas fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button style="width: 40px; height: 40px; border-radius: 12px; border: 1px solid var(--border); background: #f8f9fa; color: #ced4da; cursor: not-allowed; display: flex; align-items: center; justify-content: center;"
                                            title="Bloqueado: Contiene documentos">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script Extra para confirmación Premium -->
    <script>
        function confirmDeleteCategory(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Está seguro?',
                text: "Esta acción eliminará la categoría permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
            return false;
        }
    </script>

    <!-- Modals -->
    <script>
        async function showCreateModal() {
            const { value: formValues } = await Swal.fire({
                title: 'Nueva Categoría Elite',
                html:
                    '<div style="text-align: left; padding: 10px;">' +
                    '<label style="font-weight: 800; font-size: 12px; color: #64748b; text-transform: uppercase;">Nombre de Categoría</label>' +
                    '<input id="swal-input1" class="swal2-input" style="margin-top: 5px; width: 85%;" placeholder="Ej: Guías PROA 2024">' +
                    '<label style="font-weight: 800; font-size: 12px; color: #64748b; text-transform: uppercase; margin-top: 20px; display: block;">Imagen de Icono (PNG/SVG)</label>' +
                    '<input type="file" id="swal-input2" class="swal2-file" style="margin-top: 5px; width: 85%; font-size: 13px;" accept="image/*">' +
                    '<div style="font-size: 11px; margin-top: 10px; color: #94a3b8; font-weight: 700;">Recomendado: 128x128px con fondo transparente</div>' +
                    '</div>',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Crear Categoría',
                confirmButtonColor: '#631133',
                preConfirm: () => {
                    const nombre = document.getElementById('swal-input1').value;
                    const icono = document.getElementById('swal-input2').files[0];
                    if (!nombre) {
                        Swal.showValidationMessage('El nombre es obligatorio');
                        return false;
                    }
                    return { nombre, icono };
                }
            });

            if (formValues) {
                const formData = new FormData();
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('nombre', formValues.nombre);
                if (formValues.icono) {
                    formData.append('icono', formValues.icono);
                }

                Swal.fire({
                    title: 'Procesando...',
                    didOpen: () => { Swal.showLoading(); }
                });

                fetch("{{ route('categorias.store') }}", {
                    method: 'POST',
                    body: formData
                }).then(() => {
                    window.location.reload();
                });
            }
        }

        async function showEditModal(id, currentName) {
            const { value: formValues } = await Swal.fire({
                title: 'Editar Categoría Elite',
                html:
                    '<div style="text-align: left; padding: 10px;">' +
                    '<label style="font-weight: 800; font-size: 12px; color: #64748b; text-transform: uppercase;">Nombre de Categoría</label>' +
                    `<input id="swal-input1" class="swal2-input" style="margin-top: 5px; width: 85%;" value="${currentName}">` +
                    '<label style="font-weight: 800; font-size: 12px; color: #64748b; text-transform: uppercase; margin-top: 20px; display: block;">Actualizar Imagen (Opcional)</label>' +
                    '<input type="file" id="swal-input2" class="swal2-file" style="margin-top: 5px; width: 85%; font-size: 13px;" accept="image/*">' +
                    '</div>',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Guardar Cambios',
                confirmButtonColor: '#631133',
                preConfirm: () => {
                    const nombre = document.getElementById('swal-input1').value;
                    const icono = document.getElementById('swal-input2').files[0];
                    if (!nombre) {
                        Swal.showValidationMessage('El nombre es obligatorio');
                        return false;
                    }
                    return { nombre, icono };
                }
            });

            if (formValues) {
                const formData = new FormData();
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('_method', 'PUT');
                formData.append('nombre', formValues.nombre);
                if (formValues.icono) {
                    formData.append('icono', formValues.icono);
                }

                Swal.fire({
                    title: 'Guardando...',
                    didOpen: () => { Swal.showLoading(); }
                });

                fetch(`/categorias/${id}`, {
                    method: 'POST', // Usamos POST con _method=PUT para que PHP/Laravel procese FormData con archivos
                    body: formData
                }).then(() => {
                    window.location.reload();
                });
            }
        }
    </script>
@endsection