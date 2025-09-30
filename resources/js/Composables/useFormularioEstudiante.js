import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'

export function useFormularioEstudiante(initialData = null) {
  // Estado del wizard
  const pasoActual = ref(0)
  const pasoCompletado = ref([false, false, false, false, false])

  // Configuración de pasos
  const pasos = [
    {
      id: 0,
      title: 'Información Personal',
      description: 'Datos básicos del estudiante',
      icon: '👤',
      campos: ['nombres', 'apellidos', 'documento_identidad', 'tipo_documento', 'genero', 'fecha_nacimiento', 'birth_country_id', 'birth_state_id', 'birth_city_id']
    },
    {
      id: 1,
      title: 'Información de Contacto', 
      description: 'Dirección y contactos de emergencia',
      icon: '📞',
      campos: ['direccion', 'telefono', 'email', 'contacto_emergencia_nombre', 'contacto_emergencia_telefono', 'contacto_emergencia_relacion']
    },
    {
      id: 2,
      title: 'Información de Padres',
      description: 'Datos de padre, madre y acudiente autorizado',
      icon: '👨‍👩‍👧‍👦',
      campos: ['padre_nombres', 'madre_nombres'] // Al menos uno requerido
    },
    {
      id: 3,
      title: 'Información Académica',
      description: 'Datos de matrícula y antecedentes académicos',
      icon: '📚',
      campos: ['grado_id', 'fecha_ingreso', 'es_estudiante_nuevo']
    },
    {
      id: 4,
      title: 'Información Médica',
      description: 'Datos de salud y foto del estudiante',
      icon: '🏥',
      campos: ['tipo_sangre', 'eps']
    }
  ]

  // Preparar datos iniciales
  const getInitialData = () => {
    const defaultData = {
      // Paso 1: Información Personal
      nombres: '',
      apellidos: '',
      documento_identidad: '',
      tipo_documento: '',
      genero: '',
      fecha_nacimiento: '',
      birth_country_id: '',
      birth_state_id: '',
      birth_city_id: '',
      
      // Paso 2: Información de Contacto
      direccion: '',
      telefono: '',
      email: '',
      contacto_emergencia_nombre: '',
      contacto_emergencia_telefono: '',
      contacto_emergencia_relacion: '',
      
      // Paso 3: Información de Padres
      padre_nombres: '',
      padre_apellidos: '',
      padre_tipo_documento: '',
      padre_documento: '',
      padre_telefono: '',
      padre_email: '',
      padre_ocupacion: '',
      padre_lugar_trabajo: '',
      padre_autorizado_recoger: true,
      
      madre_nombres: '',
      madre_apellidos: '',
      madre_tipo_documento: '',
      madre_documento: '',
      madre_telefono: '',
      madre_email: '',
      madre_ocupacion: '',
      madre_lugar_trabajo: '',
      madre_autorizada_recoger: true,
      
      tiene_acudiente_adicional: false,
      acudiente_nombres: '',
      acudiente_apellidos: '',
      acudiente_tipo_documento: '',
      acudiente_documento: '',
      acudiente_parentesco: '',
      acudiente_telefono: '',
      acudiente_email: '',
      
      // Paso 4: Información Académica
      grado_id: '',
      fecha_ingreso: new Date().toISOString().split('T')[0],
      codigo_estudiante: '',
      estado: 'activo',
      es_estudiante_nuevo: true,
      colegio_procedencia: '',
      ultimo_grado_cursado: '',
      ano_finalizacion: '',
      tiene_certificados_pendientes: false,
      observaciones_academicas: '',
      
      // Paso 5: Información Médica
      tipo_sangre: '',
      eps: '',
      numero_afiliacion_eps: '',
      alergias: '',
      medicamentos: '',
      condiciones_medicas: '',
      restricciones_fisicas: '',
      foto: null,
      observaciones: ''
    }

    if (initialData) {
      // Mapear datos del estudiante existente
      return {
        ...defaultData,
        nombres: initialData.nombres || '',
        apellidos: initialData.apellidos || '',
        documento_identidad: initialData.documento_identidad || '',
        tipo_documento: initialData.tipo_documento || '',
        genero: initialData.genero || '',
        fecha_nacimiento: initialData.fecha_nacimiento || '',
        birth_country_id: initialData.birth_country_id || '',
        birth_state_id: initialData.birth_state_id || '',
        birth_city_id: initialData.birth_city_id || '',
        direccion: initialData.direccion || '',
        telefono: initialData.telefono || '',
        email: initialData.email || '',
        contacto_emergencia_nombre: initialData.contacto_emergencia_nombre || '',
        contacto_emergencia_telefono: initialData.contacto_emergencia_telefono || '',
        contacto_emergencia_relacion: initialData.contacto_emergencia_relacion || '',
        padre_nombres: initialData.padre_nombres || '',
        padre_apellidos: initialData.padre_apellidos || '',
        padre_tipo_documento: initialData.padre_tipo_documento || '',
        padre_documento: initialData.padre_documento || '',
        padre_telefono: initialData.padre_telefono || '',
        padre_email: initialData.padre_email || '',
        padre_ocupacion: initialData.padre_ocupacion || '',
        padre_lugar_trabajo: initialData.padre_lugar_trabajo || '',
        padre_autorizado_recoger: initialData.padre_autorizado_recoger ?? true,
        madre_nombres: initialData.madre_nombres || '',
        madre_apellidos: initialData.madre_apellidos || '',
        madre_tipo_documento: initialData.madre_tipo_documento || '',
        madre_documento: initialData.madre_documento || '',
        madre_telefono: initialData.madre_telefono || '',
        madre_email: initialData.madre_email || '',
        madre_ocupacion: initialData.madre_ocupacion || '',
        madre_lugar_trabajo: initialData.madre_lugar_trabajo || '',
        madre_autorizada_recoger: initialData.madre_autorizada_recoger ?? true,
        tiene_acudiente_adicional: initialData.tiene_acudiente_adicional ?? false,
        acudiente_nombres: initialData.acudiente_nombres || '',
        acudiente_apellidos: initialData.acudiente_apellidos || '',
        acudiente_tipo_documento: initialData.acudiente_tipo_documento || '',
        acudiente_documento: initialData.acudiente_documento || '',
        acudiente_parentesco: initialData.acudiente_parentesco || '',
        acudiente_telefono: initialData.acudiente_telefono || '',
        acudiente_email: initialData.acudiente_email || '',
        grado_id: initialData.grado_id || '',
        fecha_ingreso: initialData.fecha_ingreso || new Date().toISOString().split('T')[0],
        codigo_estudiante: initialData.codigo_estudiante || '',
        estado: initialData.estado || 'activo',
        es_estudiante_nuevo: initialData.es_estudiante_nuevo ?? true,
        colegio_procedencia: initialData.colegio_procedencia || '',
        ultimo_grado_cursado: initialData.ultimo_grado_cursado || '',
        ano_finalizacion: initialData.ano_finalizacion || '',
        tiene_certificados_pendientes: initialData.tiene_certificados_pendientes ?? false,
        observaciones_academicas: initialData.observaciones_academicas || '',
        tipo_sangre: initialData.tipo_sangre || '',
        eps: initialData.eps || '',
        numero_afiliacion_eps: initialData.numero_afiliacion_eps || '',
        alergias: initialData.alergias || '',
        medicamentos: initialData.medicamentos || '',
        condiciones_medicas: initialData.condiciones_medicas || '',
        restricciones_fisicas: initialData.restricciones_fisicas || '',
        foto: null, // Foto no se incluye en edición automática
        observaciones: initialData.observaciones || ''
      }
    }

    return defaultData
  }

  // Formulario principal con todos los campos
  const form = useForm(getInitialData())
  
  // Función para actualizar el formulario con nuevos datos
  const actualizarFormulario = (nuevosDatos) => {
    if (nuevosDatos && nuevosDatos.id) {
      Object.keys(form.data()).forEach(key => {
        if (nuevosDatos[key] !== undefined) {
          // EXCLUIR foto si es string (archivo existente)
          if (key === 'foto' && typeof nuevosDatos[key] === 'string') {
            // No actualizar el campo foto si es string (nombre de archivo existente)
            // Mantener foto como null para indicar que no hay archivo nuevo
            return
          }
          form[key] = nuevosDatos[key]
        }
      })
    }
  }

  // Validar paso actual
  const validarPaso = (numeroPaso) => {
    const paso = pasos[numeroPaso]
    if (!paso) return false

    const camposRequeridos = paso.campos
    let esValido = true

    // Validación especial para el paso de padres (al menos uno requerido)
    if (numeroPaso === 2) {
      const tienePadre = form.padre_nombres && form.padre_apellidos && form.padre_documento && form.padre_telefono
      const tieneMadre = form.madre_nombres && form.madre_apellidos && form.madre_documento && form.madre_telefono
      
      if (!tienePadre && !tieneMadre) {
        esValido = false
      }
      
      // Validar acudiente adicional si está marcado
      if (form.tiene_acudiente_adicional) {
        if (!form.acudiente_nombres || !form.acudiente_apellidos || !form.acudiente_documento || !form.acudiente_telefono || !form.acudiente_parentesco) {
          esValido = false
        }
      }
    } else if (numeroPaso === 3) {
      // Validación especial para el paso académico
      // Validar campos básicos requeridos
      for (const campo of camposRequeridos) {
        if (!form[campo]) {
          esValido = false
          break
        }
      }
      
      // Si no es estudiante nuevo, validar campos de antecedentes académicos
      if (!form.es_estudiante_nuevo && esValido) {
        if (!form.colegio_procedencia || !form.ultimo_grado_cursado || !form.ano_finalizacion) {
          esValido = false
        }
      }
    } else {
      // Validación normal para otros pasos
      for (const campo of camposRequeridos) {
        if (!form[campo]) {
          esValido = false
          break
        }
      }
    }

    pasoCompletado.value[numeroPaso] = esValido
    return esValido
  }

  // Navegación entre pasos
  const siguientePaso = () => {
    if (pasoActual.value < pasos.length - 1 && validarPaso(pasoActual.value)) {
      pasoActual.value++
    }
  }

  const pasoAnterior = () => {
    if (pasoActual.value > 0) {
      pasoActual.value--
    }
  }

  const irAPaso = (numeroPaso) => {
    if (numeroPaso >= 0 && numeroPaso < pasos.length) {
      pasoActual.value = numeroPaso
    }
  }

  // Computed properties
  const pasoActualData = computed(() => pasos[pasoActual.value])
  const esUltimoPaso = computed(() => pasoActual.value === pasos.length - 1)
  const esPrimerPaso = computed(() => pasoActual.value === 0)
  const porcentajeCompletado = computed(() => {
    const completados = pasoCompletado.value.filter(Boolean).length
    return Math.round((completados / pasos.length) * 100)
  })

  // Verificar si se puede avanzar
  const puedeAvanzar = computed(() => {
    return validarPaso(pasoActual.value)
  })

  // Verificar si el formulario está completo
  const formularioCompleto = computed(() => {
    return pasoCompletado.value.every(Boolean)
  })

  // Verificar si se puede guardar (para edición, menos restrictivo)
  const puedeGuardar = computed(() => {
    // En edición, permite guardar si hay al menos datos básicos
    return form.nombres && form.apellidos && form.documento_identidad && form.grado_id
  })

  // Limpiar errores de un paso específico
  const limpiarErroresPaso = (numeroPaso) => {
    const paso = pasos[numeroPaso]
    if (paso) {
      paso.campos.forEach(campo => {
        if (form.errors[campo]) {
          delete form.errors[campo]
        }
      })
    }
  }

  // Obtener errores del paso actual
  const erroresPasoActual = computed(() => {
    const paso = pasos[pasoActual.value]
    if (!paso) return {}
    
    const errores = {}
    paso.campos.forEach(campo => {
      if (form.errors[campo]) {
        errores[campo] = form.errors[campo]
      }
    })
    
    return errores
  })

  // Watch para validar automáticamente cuando cambian los datos
  watch(() => form.data(), () => {
    validarPaso(pasoActual.value)
  }, { deep: true })

  return {
    // Estado del wizard
    pasoActual,
    pasoCompletado,
    pasos,
    
    // Formulario
    form,
    
    // Computed
    pasoActualData,
    esUltimoPaso,
    esPrimerPaso,
    porcentajeCompletado,
    puedeAvanzar,
    formularioCompleto,
    puedeGuardar,
    erroresPasoActual,
    
    // Métodos de navegación
    siguientePaso,
    pasoAnterior,
    irAPaso,
    
    // Métodos de validación
    validarPaso,
    limpiarErroresPaso,
    
    // Método para actualizar formulario
    actualizarFormulario
  }
}