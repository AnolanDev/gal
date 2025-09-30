import { ref, computed, watch } from 'vue'

export function useCodigoEstudiante() {
  const documento = ref('')
  const grado = ref('')
  const fechaIngreso = ref('')
  const codigoGenerado = ref('')

  // Mapeo de grados a códigos
  const gradoCodigos = {
    'preescolar-jardin': 'PJ',
    'preescolar-transicion': 'PT', 
    'primero': 'P1',
    'segundo': 'P2',
    'tercero': 'P3',
    'cuarto': 'P4',
    'quinto': 'P5'
  }

  // Generar código automáticamente
  const generarCodigo = () => {
    if (!documento.value || !grado.value || !fechaIngreso.value) {
      codigoGenerado.value = ''
      return
    }

    try {
      const fecha = new Date(fechaIngreso.value)
      const año = fecha.getFullYear()
      
      // Obtener código del grado
      const codigoGrado = gradoCodigos[grado.value] || 'XX'
      
      // Usar últimos 3 dígitos del documento como identificador
      const ultimosDigitos = documento.value.toString().slice(-3).padStart(3, '0')
      
      // Formato: [CodigoGrado][Año][UltimosDigitosDocumento]
      // Ejemplo: PT2025123
      codigoGenerado.value = `${codigoGrado}${año}${ultimosDigitos}`
      
    } catch (error) {
      console.error('Error generando código:', error)
      codigoGenerado.value = ''
    }
  }

  // Generar código con consecutivo (para casos donde hay duplicados)
  const generarCodigoConConsecutivo = async (consecutivo = 1) => {
    if (!documento.value || !grado.value || !fechaIngreso.value) {
      return ''
    }

    try {
      const fecha = new Date(fechaIngreso.value)
      const año = fecha.getFullYear()
      const codigoGrado = gradoCodigos[grado.value] || 'XX'
      
      // Formato con consecutivo: [CodigoGrado][Año][Consecutivo]
      // Ejemplo: PT2025001
      const consecutivoStr = consecutivo.toString().padStart(3, '0')
      return `${codigoGrado}${año}${consecutivoStr}`
      
    } catch (error) {
      console.error('Error generando código con consecutivo:', error)
      return ''
    }
  }

  // Validar formato del código
  const validarCodigo = (codigo) => {
    if (!codigo) return false
    
    // Formato esperado: 2 letras + 4 dígitos + 3 dígitos
    // Ejemplo: PT2025123
    const formato = /^[A-Z]{2}\d{7}$/
    return formato.test(codigo)
  }

  // Extraer información del código
  const extraerInfoCodigo = (codigo) => {
    if (!validarCodigo(codigo)) {
      return null
    }

    const codigoGrado = codigo.substring(0, 2)
    const año = codigo.substring(2, 6)
    const consecutivo = codigo.substring(6, 9)

    // Buscar el grado correspondiente
    const gradoKey = Object.keys(gradoCodigos).find(key => gradoCodigos[key] === codigoGrado)
    
    return {
      codigoGrado,
      año: parseInt(año),
      consecutivo: parseInt(consecutivo),
      grado: gradoKey || 'desconocido'
    }
  }

  // Computed para verificar si se puede generar código
  const puedeGenerar = computed(() => {
    return documento.value && grado.value && fechaIngreso.value
  })

  // Watch para regenerar automáticamente
  watch([documento, grado, fechaIngreso], () => {
    if (puedeGenerar.value) {
      generarCodigo()
    }
  }, { immediate: true })

  return {
    // Estados reactivos
    documento,
    grado,
    fechaIngreso,
    codigoGenerado,
    
    // Computed
    puedeGenerar,
    
    // Métodos
    generarCodigo,
    generarCodigoConConsecutivo,
    validarCodigo,
    extraerInfoCodigo,
    
    // Constantes
    gradoCodigos
  }
}