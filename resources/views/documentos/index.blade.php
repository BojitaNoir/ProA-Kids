@extends('layouts.app')

@section('title', 'Biblioteca Médica')

@section('styles')
    <style>
        .full-width-container {
            width: 100%;
            padding: 20px 40px;
        }

        .search-container {
            position: relative;
            max-width: 800px;
            margin-bottom: 50px;
            animation: fadeInUp 0.7s ease-out;
        }

        .search-input {
            width: 100%;
            padding: 18px 25px 18px 30px;
            border-radius: var(--radius-full);
            border: 2px solid var(--border);
            background: white;
            box-shadow: var(--shadow-md);
            font-size: 18px;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--proa-accent);
            box-shadow: 0 8px 30px rgba(0, 212, 255, 0.15);
            transform: translateY(-2px);
        }

        .library-section {
            background: white;
            border-radius: var(--radius-lg);
            padding: 40px;
            border: 1px solid var(--border);
            margin-bottom: 50px;
            box-shadow: var(--shadow-soft);
            animation: fadeInUp 0.8s ease-out;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 35px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--light);
        }

        .section-icon {
            width: 50px;
            height: 50px;
            background: var(--proa-gradient);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 900;
            box-shadow: 0 4px 12px rgba(0, 86, 179, 0.2);
        }

        .section-title {
            font-size: 24px;
            font-weight: 900;
            color: var(--proa-primary-dark);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .doc-grid-premium {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 30px;
        }

        .premium-doc-card {
            background: var(--bg-light);
            border-radius: var(--radius-md);
            padding: 30px;
            border: 1px solid transparent;
            display: flex;
            flex-direction: column;
            gap: 20px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .premium-doc-card:hover {
            transform: translateY(-8px);
            background: white;
            box-shadow: var(--shadow-premium);
            border-color: var(--proa-accent);
        }

        .premium-doc-card::after {
            content: 'INSTITUCIONAL';
            position: absolute;
            top: 20px;
            right: -35px;
            background: var(--proa-accent);
            color: var(--proa-primary-dark);
            font-size: 9px;
            font-weight: 900;
            padding: 5px 40px;
            transform: rotate(45deg);
        }

        .doc-title {
            font-size: 19px;
            font-weight: 900;
            color: var(--proa-primary-dark);
            line-height: 1.4;
            margin-right: 30px;
        }

        .doc-meta {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 13px;
            color: var(--secondary);
            font-weight: 700;
        }

        .doc-actions {
            display: flex;
            gap: 12px;
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .btn-viewer {
            background: var(--proa-primary-dark);
            color: white;
        }

        .btn-viewer:hover {
            background: var(--proa-primary);
            color: white;
        }

        /* Modal Viewer Styles */
        .pdf-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 26, 53, 0.98);
            backdrop-filter: blur(15px);
            flex-direction: column;
        }

        .pdf-modal-header {
            padding: 15px 40px;
            background: var(--proa-primary-dark);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--proa-accent);
        }

        .pdf-modal-body {
            flex-grow: 1;
            padding: 10px;
            display: flex;
            justify-content: center;
            overflow-y: auto;
            background: #2c3e50;
        }

        /* PDF.js Canvas Container */
        #pdf-render-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
            width: 100%;
            max-width: 1000px;
        }

        canvas {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            max-width: 100%;
            background: white;
        }

        .close-viewer {
            cursor: pointer;
            font-size: 24px;
            font-weight: 900;
            color: var(--proa-accent);
            background: rgba(255, 255, 255, 0.1);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--transition);
        }

        .close-viewer:hover {
            background: var(--danger);
            color: white;
            transform: rotate(90deg);
        }

        /* PDF Toolbar Styles */
        .pdf-toolbar {
            background: #1a1a1a;
            padding: 10px 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            border-bottom: 1px solid #333;
        }

        .toolbar-btn {
            background: #333;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 800;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toolbar-btn:hover {
            background: var(--proa-accent);
            color: var(--proa-primary-dark);
        }

        .toolbar-divider {
            width: 1px;
            height: 20px;
            background: #444;
        }

        .zoom-display {
            color: #aaa;
            font-size: 12px;
            font-weight: 800;
            min-width: 50px;
            text-align: center;
        }

        .viewer-loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
            text-align: center;
            color: white;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.1);
            border-top-color: var(--proa-accent);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection

@section('content')
    <div class="full-width-container">
        <div
            style="margin-bottom: 40px; animation: slideInLeft 0.5s ease-out; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h1
                    style="font-size: 36px; font-weight: 900; color: var(--proa-primary-dark); letter-spacing: -1px; margin-bottom: 10px;">
                    Biblioteca Médica Institucional
                </h1>
                <p style="color: var(--secondary); font-size: 18px; font-weight: 500;">
                    Catálogo digital clasificado por el Departamento de Infectología PROA-HNM.
                </p>
            </div>

            <div style="display: flex; gap: 15px;">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('categorias.index') }}" class="btn btn-outline"
                        style="border-radius: 12px; font-weight: 800; padding: 15px 25px;">
                        <i class="fas fa-tags" style="margin-right: 8px;"></i>Categorías
                    </a>
                @endif

                <a href="{{ route('documentos.create') }}"
                    style="background: var(--proa-gradient); color: white; border: none; padding: 15px 30px; border-radius: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 10px 20px rgba(0, 151, 167, 0.2); display: flex; align-items: center; gap: 10px; text-decoration: none; transition: all 0.3s;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 30px rgba(0, 151, 167, 0.3)';"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 20px rgba(0, 151, 167, 0.2)';">
                    <i class="fas fa-cloud-arrow-up"></i>
                    Subir Archivo
                </a>
            </div>
        </div>

        <div class="search-container">
            <input type="text" id="docSearch" class="search-input" placeholder="Buscar por título, categoría o autor...">
        </div>

        @foreach($categorias as $categoria)
            @php
                $docsDeCategoria = $documentos->where('categoria_id', $categoria->id);
            @endphp

            @if($docsDeCategoria->count() > 0)
                <!-- Sección: {{ $categoria->nombre }} -->
                <div class="library-section">
                    <div class="section-header">
                        <div class="section-icon">{{ strtoupper(substr($categoria->nombre, 0, 3)) }}</div>
                        <h2 class="section-title">{{ $categoria->nombre }}</h2>
                    </div>

                    <div class="doc-grid-premium">
                        @foreach($docsDeCategoria as $doc)
                            <div class="premium-doc-card searchable" data-title="{{ strtolower($doc->nombre) }}"
                                data-cat="{{ strtolower($categoria->nombre) }}"
                                data-user="{{ strtolower($doc->uploader->name ?? 'sistema') }}">

                                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                    <div class="doc-title">{{ $doc->nombre }}</div>
                                    <div
                                        style="font-size: 10px; background: rgba(180, 150, 88, 0.1); color: var(--proa-accent); padding: 4px 8px; border-radius: 4px; font-weight: 800; text-transform: uppercase;">
                                        PDF
                                    </div>
                                </div>

                                <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 5px;">
                                    <div class="doc-meta" style="color: var(--proa-primary-dark); opacity: 0.8;">
                                        <i class="fas fa-user-doctor" style="width: 15px;"></i>
                                        <span>Subido por: <strong>{{ $doc->uploader->name ?? 'Sistema' }}</strong></span>
                                    </div>
                                    <div class="doc-meta">
                                        <i class="fas fa-calendar-day" style="width: 15px;"></i>
                                        <span>Fecha: {{ $doc->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="doc-meta">
                                        <i class="fas fa-folder-open" style="width: 15px;"></i>
                                        <span>Categoría: {{ $categoria->nombre }}</span>
                                    </div>
                                </div>

                                <div class="doc-actions">
                                    <a href="{{ route('documentos.download', $doc->id) }}" class="btn btn-primary"
                                        style="flex: 1; padding: 12px; font-weight: 800; font-size: 13px;">
                                        <i class="fas fa-download" style="margin-right: 8px;"></i>Descargar
                                    </a>
                                    <button onclick="previewPDF('{{ route('documentos.view', $doc->id) }}', '{{ $doc->nombre }}')"
                                        class="btn btn-outline" style="padding: 12px; font-weight: 800; font-size: 13px;">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @if(auth()->user()->role === 'admin' || auth()->id() === $doc->uploaded_by)
                                        <a href="{{ route('documentos.edit', $doc->id) }}" class="btn"
                                            style="border: 1px solid var(--proa-accent); color: var(--proa-accent); background: transparent; padding: 12px;">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif

                                    @if(auth()->user()->role === 'admin')
                                        <button onclick="confirmDelete('{{ route('documentos.destroy', $doc->id) }}')" class="btn"
                                            style="border: 1px solid #ef4444; color: #ef4444; background: transparent; padding: 12px;">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if($documentos->count() == 0)
            <div class="proa-card" style="text-align: center; padding: 100px 40px;">
                <div style="font-size: 60px; color: var(--border); margin-bottom: 25px;">
                    <i class="fas fa-file-medical"></i>
                </div>
                <h3 style="color: var(--proa-primary-dark); font-weight: 900; margin-bottom: 10px;">Biblioteca Vacía</h3>
                <p style="color: var(--secondary);">No se han subido documentos institucionales todavía.</p>
            </div>
        @endif
    </div>

    <!-- Dynamic PDF Modal Viewer Elite -->
    <div id="pdfModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 23, 42, 0.9); z-index:10000; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
        <div
            style="position:relative; width:95%; height:92%; margin:2% auto; background:#f8fafc; border-radius:30px; overflow:hidden; box-shadow: 0 50px 100px rgba(0,0,0,0.6); border: 1px solid rgba(255,255,255,0.1); animation: zoomIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">

            <!-- Modal Header Elite -->
            <div
                style="padding:20px 35px; background:white; color:var(--proa-primary-dark); display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid #e2e8f0; position: relative; z-index: 10;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div
                        style="width: 40px; height: 40px; background: var(--proa-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div>
                        <h3 id="modalTitle"
                            style="margin:0; font-size:16px; font-weight:900; text-transform:uppercase; letter-spacing:1px; color:#1e293b;">
                            Previsualización Institucional</h3>
                        <p style="margin:0; font-size:11px; color:#64748b; font-weight:700;">HNM - CONTROL DOCUMENTAL PROA
                        </p>
                    </div>
                </div>

                <div style="display: flex; gap: 15px; align-items: center;">
                    <a id="modalExternalLink" href="#" target="_blank"
                       style="background:#f1f5f9; border:none; color:var(--proa-primary); width:45px; height:45px; border-radius:15px; cursor:pointer; font-size:18px; transition:all 0.2s; display:flex; align-items:center; justify-content:center; text-decoration:none;"
                       onmouseover="this.style.background='#e2e8f0';"
                       onmouseout="this.style.background='#f1f5f9';"
                       title="Abrir en pestaña nueva">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    <button onclick="closePDF()"
                        style="background:#f1f5f9; border:none; color:#475569; width:45px; height:45px; border-radius:15px; cursor:pointer; font-size:18px; transition:all 0.2s; display:flex; align-items:center; justify-content:center;"
                        onmouseover="this.style.background='#e2e8f0'; this.style.color='#1e293b';"
                        onmouseout="this.style.background='#f1f5f9'; this.style.color='#475569';">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- PDF Container -->
            <div style="width:100%; height:calc(100% - 85px); background:#cbd5e1; position:relative;">
                <iframe id="pdfFrame" src="" style="width:100%; height:100%; border:none;"></iframe>
            </div>
        </div>
    </div>

    <style>
        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
@endsection

@section('scripts')

    <script>
        // PDF Preview Function with History Integration
        function previewPDF(url, title) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalExternalLink').href = url;
            
            // Añadir parámetros para mejorar compatibilidad y forzar visualización
            // view=FitH hace que el PDF se ajuste al ancho
            const viewerUrl = url + '#toolbar=0&navpanes=0&scrollbar=0&view=FitH';
            
            const frame = document.getElementById('pdfFrame');
            frame.src = viewerUrl;
            
            document.getElementById('pdfModal').style.display = 'block';
            document.body.style.overflow = 'hidden'; // Lock scroll

            // Sincronizar con el historial del navegador para que "atrás" cierre el modal
            history.pushState({ modalOpen: true }, '');
        }

        function closePDF(fromPopState = false) {
            document.getElementById('pdfModal').style.display = 'none';
            document.getElementById('pdfFrame').src = '';
            document.body.style.overflow = 'auto'; // Unlock scroll

            // If we closed it manually (not via back button), remove the dummy state
            if (!fromPopState && history.state && history.state.modalOpen) {
                history.back();
            }
        }

        // Listen for browser back button
        window.onpopstate = function (event) {
            if (document.getElementById('pdfModal').style.display === 'block') {
                closePDF(true);
            }
        };

        // Search Functionality
        document.getElementById('docSearch').addEventListener('input', function (e) {
            const term = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.searchable');

            cards.forEach(card => {
                const title = card.getAttribute('data-title');
                if (title.includes(term)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        function confirmDelete(url) {
            Swal.fire({
                title: '¿Confirmar Baja?',
                text: "Esta acción eliminará el documento permanentemente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Dar de baja',
                cancelButtonText: 'Cancelar'
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