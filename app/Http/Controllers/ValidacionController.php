<?php

namespace App\Http\Controllers;

use App\Models\AlertaPeligro;
use App\Models\Patologia;
use App\Models\Medicamento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ValidacionController extends Controller
{
    /**
     * Valida la prescripción médica y calcula el aclaramiento de creatinina.
     * 
     * Este endpoint es crítico para la seguridad del paciente dentro de PROA-HNM, ya que:
     * 1. Detecta interacciones peligrosas (ej: AINEs en Dengue)
     * 2. Calcula la función renal para prevenir nefrotoxicidad
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function validar(Request $request): JsonResponse
    {
        // Validar API Key
        $apiKey = $request->header('X-API-KEY');
        if ($apiKey !== config('app.proa_api_key', 'VIGIMED-2024-SECURE-KEY')) {
            return response()->json([
                'error' => 'No autorizado. API Key inválida.',
            ], 401);
        }

        // Validar datos de entrada
        $validated = $request->validate([
            'edad' => 'required|integer|min:1|max:120',
            'peso' => 'required|numeric|min:1|max:300',
            'creatinina' => 'required|numeric|min:0.1|max:20',
            'genero' => 'required|in:masculino,femenino',
            'id_patologia' => 'required|exists:patologias,id',
            'id_medicamento' => 'required|exists:medicamentos,id',
        ]);

        // Extraer datos validados
        $edad = $validated['edad'];
        $peso = $validated['peso'];
        $creatinina = $validated['creatinina'];
        $genero = $validated['genero'];
        $idPatologia = $validated['id_patologia'];
        $idMedicamento = $validated['id_medicamento'];

        /**
         * CÁLCULO DEL ACLARAMIENTO DE CREATININA (ClCr)
         * Fórmula de Cockcroft-Gault
         * 
         * IMPORTANCIA CLÍNICA:
         * El ClCr es un indicador fundamental de la función renal. Muchos medicamentos
         * se eliminan por vía renal, y una función renal disminuida puede causar:
         * - Acumulación del fármaco en el organismo
         * - Toxicidad medicamentosa
         * - Nefrotoxicidad (daño renal adicional)
         * - Efectos adversos graves
         * 
         * La fórmula de Cockcroft-Gault estima el ClCr basándose en:
         * - Edad: La función renal disminuye con la edad
         * - Peso: Relacionado con la masa muscular y producción de creatinina
         * - Creatinina sérica: Producto de desecho muscular eliminado por el riñón
         * - Género: Las mujeres tienen menor masa muscular (factor 0.85)
         * 
         * Fórmula:
         * ClCr (ml/min) = [(140 - edad) × peso] / (72 × creatinina sérica)
         * Si es mujer: multiplicar por 0.85
         * 
         * Interpretación:
         * - Normal: > 90 ml/min
         * - Leve: 60-89 ml/min
         * - Moderada: 30-59 ml/min (ajustar dosis)
         * - Severa: 15-29 ml/min (contraindicar muchos fármacos)
         * - Falla renal: < 15 ml/min (diálisis)
         */

        $clcr = ((140 - $edad) * $peso) / (72 * $creatinina);

        // Aplicar factor de corrección para género femenino
        if ($genero === 'femenino') {
            $clcr = $clcr * 0.85;
        }

        // Redondear a 2 decimales
        $clcr = round($clcr, 2);

        // Determinar categoría de función renal
        $categoriaRenal = $this->categorizarFuncionRenal($clcr);

        /**
         * VERIFICACIÓN DE ALERTAS DE SEGURIDAD
         * 
         * Busca si existe una contraindicación entre la patología y el medicamento.
         * Esto es especialmente crítico para combinaciones peligrosas como:
         * - Dengue + AINEs (riesgo de hemorragia)
         * - Insuficiencia renal + nefrotóxicos
         * - Hipertensión + vasoconstrictores
         */
        $alerta = AlertaPeligro::where('patologia_id', $idPatologia)
            ->where('medicamento_id', $idMedicamento)
            ->first();

        // Obtener información adicional
        $patologia = Patologia::find($idPatologia);
        $medicamento = Medicamento::find($idMedicamento);

        // Preparar respuesta
        $response = [
            'validacion_exitosa' => is_null($alerta),
            'paciente' => [
                'edad' => $edad,
                'peso' => $peso,
                'genero' => $genero,
                'creatinina_serica' => $creatinina,
            ],
            'funcion_renal' => [
                'clcr_ml_min' => $clcr,
                'categoria' => $categoriaRenal,
                'interpretacion' => $this->interpretarClCr($clcr),
            ],
            'prescripcion' => [
                'patologia' => $patologia->nombre,
                'medicamento' => $medicamento->nombre,
                'familia_medicamento' => $medicamento->familia,
            ],
        ];

        if ($alerta) {
            // Existe una alerta de peligro
            $response['alerta_seguridad'] = [
                'nivel' => 'CRÍTICO',
                'mensaje' => $alerta->mensaje_error,
                'recomendacion' => 'NO PRESCRIBIR. Consultar con médico especialista.',
                'medicamento_info' => [
                    'ficha_tecnica' => $medicamento->ficha_tecnica,
                    'edades' => $medicamento->edades_recomendadas,
                    'dosis' => $medicamento->dosis_recomendada,
                    'indicaciones' => $medicamento->indicaciones_especiales,
                ],
            ];
            $resultado_log = 'CRÍTICO';
            $mensaje_log = $alerta->mensaje_error;
        } else {
            // No hay alertas, pero verificar función renal
            if ($clcr < 30) {
                $response['advertencia_renal'] = [
                    'nivel' => 'IMPORTANTE',
                    'mensaje' => 'Función renal severamente disminuida. Considerar ajuste de dosis o medicamento alternativo.',
                ];
                $resultado_log = 'ALERTA';
                $mensaje_log = 'Función renal severamente disminuida.';
            } elseif ($clcr < 60) {
                $response['advertencia_renal'] = [
                    'nivel' => 'MODERADO',
                    'mensaje' => 'Función renal moderadamente disminuida. Verificar si requiere ajuste de dosis.',
                ];
                $resultado_log = 'ALERTA';
                $mensaje_log = 'Función renal moderadamente disminuida.';
            } else {
                $resultado_log = 'EXITOSO';
                $mensaje_log = 'Terapia segura según criterios institucionales.';
            }
        }

        // Registrar en la bitácora
        try {
            $userId = auth()->id();
            $userName = auth()->check() ? auth()->user()->name : 'Sistema';

            \App\Models\RegistroValidacion::create([
                'user_id' => $userId ?? 1, // Fallback a admin si se llama desde sistema
                'uploaded_by' => $userName, // Added uploaded_by
                'patologia' => $patologia->nombre,
                'medicamento' => $medicamento->nombre,
                'clcr' => $clcr,
                'resultado' => $resultado_log,
                'mensaje_resultado' => $mensaje_log,
            ]);
        } catch (\Exception $e) {
            // Silenciar errores de log si fallan, para no romper la validación principal
        }

        return response()->json($response);
    }

    /**
     * Categoriza la función renal según el ClCr
     */
    private function categorizarFuncionRenal(float $clcr): string
    {
        if ($clcr >= 90)
            return 'Normal';
        if ($clcr >= 60)
            return 'Levemente disminuida';
        if ($clcr >= 30)
            return 'Moderadamente disminuida';
        if ($clcr >= 15)
            return 'Severamente disminuida';
        return 'Falla renal';
    }

    /**
     * Proporciona interpretación clínica del ClCr
     */
    private function interpretarClCr(float $clcr): string
    {
        if ($clcr >= 90) {
            return 'Función renal normal. No requiere ajuste de dosis.';
        } elseif ($clcr >= 60) {
            return 'Función renal levemente disminuida. Monitorear y considerar ajuste en medicamentos nefrotóxicos.';
        } elseif ($clcr >= 30) {
            return 'Función renal moderadamente disminuida. Ajustar dosis según protocolo y evitar nefrotóxicos.';
        } elseif ($clcr >= 15) {
            return 'Función renal severamente disminuida. Requiere ajuste significativo de dosis. Considerar diálisis.';
        } else {
            return 'Falla renal terminal. Muchos medicamentos están contraindicados. Requiere diálisis.';
        }
    }

    /**
     * Obtiene todas las patologías disponibles
     */
    public function obtenerPatologias(): JsonResponse
    {
        $patologias = Patologia::all();
        return response()->json($patologias);
    }

    /**
     * Obtiene todos los medicamentos disponibles
     */
    public function obtenerMedicamentos(): JsonResponse
    {
        $medicamentos = Medicamento::latest()->get();
        return response()->json($medicamentos);
    }

    public function obtenerEstructura(): JsonResponse
    {
        $modulos = \App\Models\Modulo::with(['categorias.subcategorias'])->get();

        $resultado = $modulos->map(function ($mod) {
            return [
                'id' => $mod->id,
                'nombre' => $mod->nombre,
                'icono' => $mod->icono,
                // Proveemos ambos nombres de llaves para máxima compatibilidad con la App
                'categories' => $mod->categorias->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'nombre' => $cat->nombre,
                        'subcategories' => $cat->subcategorias->map(function ($sub) {
                            return [
                                'id' => $sub->id,
                                'nombre' => $sub->nombre
                            ];
                        }),
                        'subcategorias' => $cat->subcategorias->map(function ($sub) {
                            return [
                                'id' => $sub->id,
                                'nombre' => $sub->nombre
                            ];
                        })
                    ];
                }),
                'categorias' => $mod->categorias->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'nombre' => $cat->nombre,
                        'subcategorias' => $cat->subcategorias->map(function ($sub) {
                            return [
                                'id' => $sub->id,
                                'nombre' => $sub->nombre
                            ];
                        })
                    ];
                })
            ];
        });

        // Retornamos el array directamente para que ApiService.fetchEstructura lo reciba sin envoltorio
        return response()->json($resultado);
    }

    /**
     * Obtiene la lista de documentos con la nueva estructura y metadatos.
     */
    public function obtenerDocumentos(): JsonResponse
    {
        $userId = auth()->id();

        $documentos = \App\Models\Documento::with(['uploader', 'subcategoria.categoria.modulo'])
            ->where(function ($query) use ($userId) {
                $query->where('visibilidad', 'admin_public')
                    ->orWhere('visibilidad', 'public') // Legacy
                    ->orWhere(function ($q) use ($userId) {
                        $q->where('visibilidad', 'private')
                            ->where('uploaded_by', $userId);
                    })
                    ->orWhere('visibilidad', 'group');
            })
            ->latest()
            ->get();

        $resultado = $documentos->map(function ($doc) {
            return [
                'id' => $doc->id,
                'nombre' => $doc->nombre,
                'subcategory_id' => $doc->subcategory_id,
                'uploaded_by_id' => $doc->uploaded_by,
                'uploaded_by_name' => $doc->uploader ? $doc->uploader->name : 'Sistema',
                'version' => $doc->currentVersion->version_number ?? 1,
                'fecha' => $doc->created_at->format('Y-m-d'),
                'download_url' => route('documentos.download', $doc->id),
                'preview_url' => route('documentos.view', $doc->id),
                'visibilidad' => $doc->visibilidad === 'public' ? 'admin_public' : $doc->visibilidad
            ];
        });

        // 2. Formatear la respuesta agrupada (categorias_dinamicas) para la UI de la App
        $categoriasDinamicas = \App\Models\Categoria::with([
            'documentos' => function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    $q->where('visibilidad', 'admin_public')
                        ->orWhere('visibilidad', 'public')
                        ->orWhere(function ($sq) use ($userId) {
                            $sq->where('visibilidad', 'private')
                                ->where('uploaded_by', $userId);
                        })
                        ->orWhere('visibilidad', 'group');
                })->latest();
            }
        ])->get()->map(function ($cat) {
            return [
                'categoria_id' => $cat->id,
                'categoria_nombre' => $cat->nombre,
                'categoria_icono' => $cat->icono ? asset('storage/' . $cat->icono) : null,
                'documentos' => $cat->documentos->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'nombre' => $doc->nombre,
                        'version' => $doc->currentVersion->version_number ?? 1,
                        'fecha' => $doc->created_at->format('Y-m-d'),
                        'download_url' => route('documentos.download', $doc->id),
                        'preview_url' => route('documentos.view', $doc->id),
                        'uploaded_by_id' => $doc->uploaded_by,
                        'uploaded_by_name' => $doc->uploader ? $doc->uploader->name : 'Sistema',
                        'visibilidad' => $doc->visibilidad
                    ];
                })
            ];
        });

        // 3. Agrupación por filtros de legado (guias, diagnosticos)
        $guias = $resultado->filter(function ($doc) use ($documentos) {
            $cat = $documentos->find($doc['id'])->categoria;
            return $cat && str_contains(strtolower($cat->nombre), 'guia');
        })->values();

        $diagnosticos = $resultado->filter(function ($doc) use ($documentos) {
            $cat = $documentos->find($doc['id'])->categoria;
            return $cat && str_contains(strtolower($cat->nombre), 'diag');
        })->values();

        return response()->json([
            'categorias_dinamicas' => $categoriasDinamicas,
            'documentos_totales' => $resultado,
            'guias' => $guias,
            'diagnosticos' => $diagnosticos,
            'guias_clinicas_mejores_practicas' => $guias,
            'protocolo_diagnostico_autorizado' => $diagnosticos
        ]);
    }
}
