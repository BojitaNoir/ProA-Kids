@extends('layouts.app')

@section('title', 'Registro Documental')

@section('content')
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 0;">

        <div class="inst-card" style="width: 100%; max-width: 600px; padding: 40px; box-shadow: var(--shadow-premium);">
            <div
                style="text-align: center; margin-bottom: 35px; border-bottom: 2px solid var(--proa-accent); padding-bottom: 25px;">
                <img src="{{ asset('images/logo_color.png') }}" alt="Logo" style="height: 60px; margin-bottom: 15px;">
                <h1
                    style="font-size: 22px; color: var(--proa-primary); font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">
                    Registro de Documento</h1>
                <p style="color: var(--secondary); font-size: 13px; font-weight: 700;">HNM - Gestión de Documentos
                    Institucional</p>
            </div>

            <form action="{{ route('documentos.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                <div class="form-group">
                    <label class="form-label">Título Oficial del Documento</label>
                    <input type="text" name="nombre" class="form-control" required
                        placeholder="Ej: Guía de Vancomicina 2024" value="{{ old('nombre') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Clasificación Institucional</label>
                    <select name="categoria_id" class="form-control" required>
                        <option value="" disabled selected>Seleccione una categoría</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Expediente PDF (Máx. 15MB)</label>
                    <div style="border: 2px dashed var(--proa-accent); padding: 30px; text-align: center; border-radius: var(--radius-md); cursor: pointer; background: var(--bg-light); transition: var(--transition);"
                        onclick="document.getElementById('archivo').click()"
                        onmouseover="this.style.background='white'; this.style.borderColor='var(--proa-primary)'"
                        onmouseout="this.style.background='var(--bg-light)'; this.style.borderColor='var(--proa-accent)'">

                        <p id="label-text" style="font-weight: 800; color: var(--proa-primary); font-size: 14px;">Haga clic
                            para seleccionar el archivo</p>
                        <p style="font-size: 11px; color: var(--secondary); margin-top: 5px;">Solo documentos certificados
                            en formato PDF</p>
                        <input type="file" id="archivo" name="archivo" accept=".pdf" required style="display:none"
                            onchange="handleFileChange(this)">
                    </div>
                </div>

                <div style="margin-top: 40px; display: flex; flex-direction: column; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Publicar Registro</button>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('dashboard') }}" class="btn btn-outline" style="width: 100%;">Volver al Panel</a>
                    @else
                        <a href="{{ route('doctor.dashboard') }}" class="btn btn-outline" style="width: 100%;">Volver al
                            Inicio</a>
                    @endif
                </div>
            </form>
        </div>

        <p
            style="margin-top: 30px; color: var(--secondary); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
            © 2024 Hospital del Niño Morelense - Uso Exclusivo Autorizado
        </p>
@endsection

    @section('scripts')
        <script>
            function handleFileChange(input) {
                const label = document.getElementById('label-text');
                if (input.files.length > 0) {
                    const file = input.files[0];

                    // Validar formato
                    if (file.type !== 'application/pdf') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Formato No Permitido',
                            text: 'Solo se permiten documentos en formato PDF institucional.',
                            confirmButtonColor: '#0056b3'
                        });
                        resetInput(input, label);
                        return;
                    }

                    // Validar tamaño (15MB = 15 * 1024 * 1024 bytes)
                    if (file.size > 15 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Archivo Demasiado Grande',
                            text: 'El documento supera el límite institucional de 15MB. Por favor, optimice el archivo antes de subirlo.',
                            confirmButtonColor: '#0056b3'
                        });
                        resetInput(input, label);
                        return;
                    }

                    label.textContent = 'Archivo Seleccionado: ' + file.name;
                    label.style.color = 'var(--success)';

                    Swal.fire({
                        icon: 'info',
                        title: 'Archivo Listo',
                        text: file.name,
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top'
                    });
                }
            }

            function resetInput(input, label) {
                input.value = '';
                label.textContent = 'Haga clic para seleccionar el archivo';
                label.style.color = 'var(--proa-primary)';
            }

            document.getElementById('uploadForm').onsubmit = function () {
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Subiendo documento al servidor institucional',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
            }
        </script>
    @endsection