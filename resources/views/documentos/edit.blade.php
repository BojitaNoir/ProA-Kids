@extends('layouts.app')

@section('title', 'Editar Documento')

@section('content')
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 0;">

        <div class="inst-card" style="width: 100%; max-width: 600px; padding: 40px; box-shadow: var(--shadow-premium);">
            <div
                style="text-align: center; margin-bottom: 35px; border-bottom: 2px solid var(--proa-accent); padding-bottom: 25px;">
                <h1
                    style="font-size: 22px; color: var(--proa-primary); font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">
                    Editar Documento</h1>
                <p style="color: var(--secondary); font-size: 13px; font-weight: 700;">HNM - Actualización de Metadatos</p>
            </div>

            <form action="{{ route('documentos.update', $documento->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Título Oficial del Documento</label>
                    <input type="text" name="nombre" class="form-control" required
                        value="{{ old('nombre', $documento->nombre) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Clasificación Institucional</label>
                    <select name="categoria_id" class="form-control" required>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ (old('categoria_id', $documento->categoria_id) == $cat->id) ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div
                    style="margin-top: 20px; padding: 15px; background: rgba(0,0,0,0.03); border-radius: 8px; font-size: 12px; color: #666;">
                    <i class="fas fa-info-circle" style="margin-right: 8px;"></i>
                    Nota: Para cambiar el archivo PDF, se recomienda eliminar el registro actual y subir uno nuevo.
                </div>

                <div style="margin-top: 40px; display: flex; flex-direction: column; gap: 12px;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Actualizar Registro</button>
                    <a href="{{ route('documentos.index') }}" class="btn btn-outline" style="width: 100%;">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection