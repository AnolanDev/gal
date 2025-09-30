<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Grado;
use App\Http\Requests\EstudianteStoreRequest;
use App\Http\Requests\EstudianteUpdateRequest;
use App\Http\Resources\EstudianteResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Estudiante::with(['grado', 'birthCountry', 'birthState', 'birthCity'])
            ->when($request->search, function ($query, $search) {
                $query->buscar($search);
            })
            ->when($request->grado_id, function ($query, $gradoId) {
                $query->porGrado($gradoId);
            })
            ->when($request->estado, function ($query, $estado) {
                $query->where('estado', $estado);
            })
            ->when($request->genero, function ($query, $genero) {
                $query->where('genero', $genero);
            })
            ->when($request->year, function ($query, $year) {
                $query->whereYear('fecha_ingreso', $year);
            })
            ->orderBy($request->sort_by ?? 'apellidos', $request->sort_direction ?? 'asc');

        $estudiantes = $query->paginate(15)->withQueryString();

        // Estadísticas para el dashboard
        $stats = [
            'total' => Estudiante::count(),
            'activos' => Estudiante::activos()->count(),
            'inactivos' => Estudiante::inactivos()->count(),
            'retirados' => Estudiante::retirados()->count(),
            'nuevos_este_ano' => Estudiante::whereYear('fecha_ingreso', now()->year)->count(),
            'por_grado' => Estudiante::with('grado')
                ->select('grado_id', DB::raw('count(*) as total'))
                ->where('estado', 'activo')
                ->groupBy('grado_id')
                ->get()
                ->map(function ($item) {
                    return [
                        'grado' => $item->grado->nombre ?? 'Sin grado',
                        'total' => $item->total
                    ];
                }),
            'por_genero' => Estudiante::select('genero', DB::raw('count(*) as total'))
                ->where('estado', 'activo')
                ->groupBy('genero')
                ->get()
                ->pluck('total', 'genero')
                ->toArray(),
        ];

        return Inertia::render('Estudiantes/Index', [
            'estudiantes' => EstudianteResource::collection($estudiantes),
            'grados' => Grado::orderBy('nombre')->get(['id', 'nombre']),
            'stats' => $stats,
            'filters' => $request->only(['search', 'grado_id', 'estado', 'genero', 'year']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Estudiantes/Create', [
            'grados' => Grado::orderBy('nombre')->get(['id', 'nombre']),
            'next_codigo' => Estudiante::generateNextCodigo(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EstudianteStoreRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validated();
            
            // Manejar upload de foto si existe
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('estudiantes/fotos', 'public');
                $validated['foto'] = basename($fotoPath);
            }
            
            // Crear el estudiante
            $estudiante = Estudiante::create($validated);
            
            DB::commit();
            
            return redirect()
                ->route('estudiantes.show', $estudiante)
                ->with('success', 'Estudiante creado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            // Si hubo upload de foto, eliminarla
            if (isset($fotoPath) && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            
            return back()
                ->withErrors(['error' => 'Error al crear el estudiante: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Estudiante $estudiante)
    {
        $estudiante->load([
            'grado', 
            'birthCountry', 
            'birthState', 
            'birthCity',
            'asistencias' => function ($query) {
                $query->latest()->limit(10);
            },
            'calificaciones' => function ($query) {
                $query->with('materia')->latest()->limit(10);
            }
        ]);
        
        // Estadísticas del estudiante
        $stats = [
            'asistencias_mes' => $estudiante->asistencias()
                ->whereMonth('fecha', now()->month)
                ->whereYear('fecha', now()->year)
                ->count(),
            'faltas_mes' => $estudiante->asistencias()
                ->where('estado', 'ausente')
                ->whereMonth('fecha', now()->month)
                ->whereYear('fecha', now()->year)
                ->count(),
            'promedio_general' => $estudiante->calificaciones()->avg('nota') ?? 0,
            'edad' => $estudiante->edad,
            'tiempo_en_institucion' => $estudiante->fecha_ingreso 
                ? $this->calcularTiempoEnInstitucion($estudiante->fecha_ingreso)
                : null,
        ];

        return Inertia::render('Estudiantes/Show', [
            'estudiante' => new EstudianteResource($estudiante),
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estudiante $estudiante)
    {
        $estudiante->load(['grado', 'birthCountry', 'birthState', 'birthCity']);
        
        return Inertia::render('Estudiantes/Edit', [
            'estudiante' => new EstudianteResource($estudiante),
            'grados' => Grado::orderBy('nombre')->get(['id', 'nombre']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EstudianteUpdateRequest $request, Estudiante $estudiante)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validated();
            
            // Manejar upload de nueva foto
            if ($request->hasFile('foto')) {
                // Eliminar foto anterior si existe
                if ($estudiante->foto && Storage::disk('public')->exists('estudiantes/fotos/' . $estudiante->foto)) {
                    Storage::disk('public')->delete('estudiantes/fotos/' . $estudiante->foto);
                }
                
                // Subir nueva foto
                $fotoPath = $request->file('foto')->store('estudiantes/fotos', 'public');
                $validated['foto'] = basename($fotoPath);
            }
            
            $estudiante->update($validated);
            
            DB::commit();
            
            return redirect()
                ->route('estudiantes.show', $estudiante)
                ->with('success', 'Estudiante actualizado exitosamente.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            return back()
                ->withErrors(['error' => 'Error al actualizar el estudiante: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estudiante $estudiante)
    {
        try {
            // Soft delete - cambiar estado en lugar de eliminar físicamente
            $estudiante->update(['estado' => 'retirado']);
            
            return redirect()
                ->route('estudiantes.index')
                ->with('success', 'Estudiante marcado como retirado exitosamente.');
                
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Error al retirar el estudiante: ' . $e->getMessage()]);
        }
    }

    /**
     * Show student profile with extended information
     */
    public function profile(Estudiante $estudiante)
    {
        $estudiante->load([
            'grado',
            'birthCountry', 
            'birthState', 
            'birthCity',
            'asistencias' => function ($query) {
                $query->latest()->limit(20);
            },
            'calificaciones' => function ($query) {
                $query->with('materia')->latest()->limit(20);
            }
        ]);

        // Estadísticas extendidas para el perfil
        $stats = [
            'informacion_completa' => $this->calcularCompleitudInformacion($estudiante),
            'resumen_asistencias' => $this->calcularResumenAsistencias($estudiante),
            'resumen_academico' => $this->calcularResumenAcademico($estudiante),
        ];

        return Inertia::render('Estudiantes/Profile', [
            'estudiante' => new EstudianteResource($estudiante),
            'stats' => $stats,
        ]);
    }

    /**
     * Update student status
     */
    public function updateStatus(Request $request, Estudiante $estudiante)
    {
        $request->validate([
            'estado' => 'required|in:activo,inactivo,retirado',
            'razon' => 'nullable|string|max:255',
        ]);

        try {
            $estudiante->update([
                'estado' => $request->estado,
                'observaciones' => $request->razon 
                    ? $estudiante->observaciones . "\n[" . now()->format('Y-m-d') . "] Estado cambiado a {$request->estado}: {$request->razon}"
                    : $estudiante->observaciones
            ]);
            
            return back()->with('success', 'Estado del estudiante actualizado exitosamente.');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar el estado: ' . $e->getMessage()]);
        }
    }

    /**
     * Change student status with professional modal workflow
     */
    public function cambiarEstado(Request $request, Estudiante $estudiante)
    {
        $request->validate([
            'estado' => 'required|in:activo,inactivo,retirado',
            'observaciones' => 'required_if:estado,retirado|nullable|string|max:500'
        ], [
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser activo, inactivo o retirado.',
            'observaciones.required_if' => 'Las observaciones son obligatorias para retirar un estudiante.',
            'observaciones.max' => 'Las observaciones no pueden exceder 500 caracteres.'
        ]);

        DB::beginTransaction();
        
        try {
            $estadoAnterior = $estudiante->estado;
            $nuevoEstado = $request->estado;
            
            // Validar transición de estado
            $this->validarTransicionEstado($estadoAnterior, $nuevoEstado);
            
            // Preparar observaciones con timestamp
            $observacionesActuales = $estudiante->observaciones ?: '';
            $nuevaObservacion = '';
            
            if ($request->observaciones) {
                $fecha = now()->format('Y-m-d H:i:s');
                $usuario = auth()->user()->name ?? 'Sistema';
                $nuevaObservacion = "\n[{$fecha}] Estado cambiado de '{$estadoAnterior}' a '{$nuevoEstado}' por {$usuario}: {$request->observaciones}";
            } else {
                $fecha = now()->format('Y-m-d H:i:s');
                $usuario = auth()->user()->name ?? 'Sistema';
                $nuevaObservacion = "\n[{$fecha}] Estado cambiado de '{$estadoAnterior}' a '{$nuevoEstado}' por {$usuario}";
            }
            
            // Actualizar estudiante
            $estudiante->update([
                'estado' => $nuevoEstado,
                'observaciones' => $observacionesActuales . $nuevaObservacion
            ]);
            
            // Log de auditoría
            \Log::info('Cambio de estado de estudiante', [
                'estudiante_id' => $estudiante->id,
                'codigo_estudiante' => $estudiante->codigo_estudiante,
                'nombre_completo' => $estudiante->nombre_completo,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $nuevoEstado,
                'observaciones' => $request->observaciones,
                'usuario_id' => auth()->id(),
                'usuario_nombre' => auth()->user()->name ?? 'Sistema',
                'timestamp' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            DB::commit();
            
            $mensajes = [
                'activo' => 'Estudiante activado exitosamente.',
                'inactivo' => 'Estudiante inactivado exitosamente.',
                'retirado' => 'Estudiante retirado exitosamente.'
            ];
            
            return back()->with('success', $mensajes[$nuevoEstado]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error al cambiar estado de estudiante', [
                'estudiante_id' => $estudiante->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'Error al cambiar el estado: ' . $e->getMessage()]);
        }
    }

    /**
     * Validate state transition rules
     */
    private function validarTransicionEstado(string $estadoActual, string $nuevoEstado): void
    {
        // No se puede cambiar desde retirado (estado final)
        if ($estadoActual === 'retirado') {
            throw new \InvalidArgumentException('No se puede cambiar el estado de un estudiante retirado.');
        }
        
        // No se puede cambiar al mismo estado
        if ($estadoActual === $nuevoEstado) {
            throw new \InvalidArgumentException("El estudiante ya se encuentra en estado '{$nuevoEstado}'.");
        }
        
        // Todas las demás transiciones son válidas:
        // activo -> inactivo
        // activo -> retirado  
        // inactivo -> activo
        // inactivo -> retirado
    }

    /**
     * Bulk update students status
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'estudiante_ids' => 'required|array',
            'estudiante_ids.*' => 'exists:estudiantes,id',
            'estado' => 'required|in:activo,inactivo,retirado',
            'razon' => 'nullable|string|max:255',
        ]);

        try {
            $updateData = ['estado' => $request->estado];
            
            if ($request->razon) {
                $updateData['observaciones'] = DB::raw("
                    CONCAT(
                        IFNULL(observaciones, ''), 
                        '\n[" . now()->format('Y-m-d') . "] Estado cambiado a {$request->estado}: {$request->razon}'
                    )
                ");
            }
            
            Estudiante::whereIn('id', $request->estudiante_ids)->update($updateData);
            
            $count = count($request->estudiante_ids);
            
            return back()->with('success', "Estado de {$count} estudiantes actualizado exitosamente.");
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al actualizar los estados: ' . $e->getMessage()]);
        }
    }

    /**
     * Search students for autocomplete
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $estudiantes = Estudiante::with('grado')
            ->buscar($search)
            ->where('estado', 'activo')
            ->limit(10)
            ->get(['id', 'nombres', 'apellidos', 'codigo_estudiante', 'grado_id']);

        return response()->json([
            'data' => $estudiantes->map(function ($estudiante) {
                return [
                    'id' => $estudiante->id,
                    'nombre_completo' => $estudiante->nombre_completo,
                    'codigo' => $estudiante->codigo_estudiante,
                    'grado' => $estudiante->grado->nombre ?? 'Sin grado',
                ];
            })
        ]);
    }

    /**
     * Delete student photo
     */
    public function deletePhoto(Estudiante $estudiante)
    {
        try {
            if ($estudiante->foto && Storage::disk('public')->exists('estudiantes/fotos/' . $estudiante->foto)) {
                Storage::disk('public')->delete('estudiantes/fotos/' . $estudiante->foto);
                $estudiante->update(['foto' => null]);
                
                return back()->with('success', 'Foto eliminada exitosamente.');
            }
            
            return back()->withErrors(['error' => 'No hay foto para eliminar.']);
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar la foto: ' . $e->getMessage()]);
        }
    }

    // ============================================
    // MÉTODOS PRIVADOS DE UTILIDAD
    // ============================================

    /**
     * Calcula el porcentaje de completitud de la información del estudiante
     */
    private function calcularCompleitudInformacion(Estudiante $estudiante): array
    {
        $camposRequeridos = [
            'nombres', 'apellidos', 'documento_identidad', 'fecha_nacimiento',
            'direccion', 'contacto_emergencia_nombre', 'contacto_emergencia_telefono',
            'tipo_sangre', 'eps'
        ];
        
        $camposOpcionales = [
            'telefono', 'email', 'foto', 'alergias', 'medicamentos', 
            'padre_nombres', 'madre_nombres'
        ];
        
        $completosRequeridos = 0;
        $completosOpcionales = 0;
        
        foreach ($camposRequeridos as $campo) {
            if (!empty($estudiante->$campo)) {
                $completosRequeridos++;
            }
        }
        
        foreach ($camposOpcionales as $campo) {
            if (!empty($estudiante->$campo)) {
                $completosOpcionales++;
            }
        }
        
        return [
            'requeridos' => round(($completosRequeridos / count($camposRequeridos)) * 100),
            'opcionales' => round(($completosOpcionales / count($camposOpcionales)) * 100),
            'general' => round((($completosRequeridos + $completosOpcionales) / (count($camposRequeridos) + count($camposOpcionales))) * 100),
        ];
    }

    /**
     * Calcula resumen de asistencias del estudiante
     */
    private function calcularResumenAsistencias(Estudiante $estudiante): array
    {
        $totalAsistencias = $estudiante->asistencias()->count();
        $presentesMes = $estudiante->asistencias()
            ->where('estado', 'presente')
            ->whereMonth('fecha', now()->month)
            ->count();
        $ausenteMes = $estudiante->asistencias()
            ->where('estado', 'ausente')
            ->whereMonth('fecha', now()->month)
            ->count();
            
        return [
            'total_registros' => $totalAsistencias,
            'presentes_mes' => $presentesMes,
            'ausentes_mes' => $ausenteMes,
            'porcentaje_asistencia_mes' => ($presentesMes + $ausenteMes) > 0 
                ? round(($presentesMes / ($presentesMes + $ausenteMes)) * 100) 
                : 0,
        ];
    }

    /**
     * Calcula resumen académico del estudiante
     */
    private function calcularResumenAcademico(Estudiante $estudiante): array
    {
        $calificaciones = $estudiante->calificaciones();
        
        return [
            'total_calificaciones' => $calificaciones->count(),
            'promedio_general' => round($calificaciones->avg('nota') ?? 0, 2),
            'nota_mas_alta' => $calificaciones->max('nota') ?? 0,
            'nota_mas_baja' => $calificaciones->min('nota') ?? 0,
            'materias_cursadas' => $calificaciones->distinct('materia_id')->count(),
        ];
    }

    /**
     * Calcula el tiempo en la institución en formato amigable
     */
    private function calcularTiempoEnInstitucion($fechaIngreso): string
    {
        $fechaIngreso = \Carbon\Carbon::parse($fechaIngreso);
        $ahora = now();
        
        // Verificar si la fecha de ingreso es futura
        if ($fechaIngreso->isFuture()) {
            return 'Fecha futura';
        }
        
        // Usar diffForHumans personalizado con Carbon
        $diff = $fechaIngreso->diff($ahora);
        
        $partes = [];
        
        // Años
        if ($diff->y > 0) {
            $partes[] = $diff->y . ($diff->y == 1 ? ' año' : ' años');
        }
        
        // Meses
        if ($diff->m > 0) {
            $partes[] = $diff->m . ($diff->m == 1 ? ' mes' : ' meses');
        }
        
        // Días (solo mostrar si no hay años o si hay menos de 2 unidades)
        if ($diff->d > 0 && (count($partes) == 0 || count($partes) == 1)) {
            $partes[] = $diff->d . ($diff->d == 1 ? ' día' : ' días');
        }
        
        // Casos especiales
        if (empty($partes)) {
            if ($diff->h > 0 || $diff->i > 0) {
                return 'Menos de un día';
            }
            return 'Hoy';
        }
        
        // Limitar a máximo 2 unidades para legibilidad
        $partes = array_slice($partes, 0, 2);
        
        return implode(' y ', $partes);
    }
}