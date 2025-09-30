import { ref, watch } from 'vue'
import axios from 'axios'

export function useLugares() {
  const paises = ref([])
  const departamentos = ref([])
  const ciudades = ref([])
  
  const loading = ref({
    paises: false,
    departamentos: false,
    ciudades: false
  })

  const errors = ref({
    paises: null,
    departamentos: null,
    ciudades: null
  })

  // Cargar países al inicializar
  const cargarPaises = async () => {
    loading.value.paises = true
    errors.value.paises = null
    
    try {
      const response = await axios.get('/api/lugares/paises')
      paises.value = response.data
    } catch (error) {
      console.error('Error cargando países:', error)
      errors.value.paises = 'Error al cargar los países'
    } finally {
      loading.value.paises = false
    }
  }

  // Cargar departamentos según país seleccionado
  const cargarDepartamentos = async (paisId) => {
    if (!paisId) {
      departamentos.value = []
      ciudades.value = []
      return
    }

    loading.value.departamentos = true
    errors.value.departamentos = null
    ciudades.value = [] // Limpiar ciudades al cambiar departamento
    
    try {
      const response = await axios.get(`/api/lugares/departamentos/${paisId}`)
      departamentos.value = response.data
    } catch (error) {
      console.error('Error cargando departamentos:', error)
      errors.value.departamentos = 'Error al cargar los departamentos'
      departamentos.value = []
    } finally {
      loading.value.departamentos = false
    }
  }

  // Cargar ciudades según departamento seleccionado
  const cargarCiudades = async (departamentoId) => {
    if (!departamentoId) {
      ciudades.value = []
      return
    }

    loading.value.ciudades = true
    errors.value.ciudades = null
    
    try {
      const response = await axios.get(`/api/lugares/ciudades/${departamentoId}`)
      ciudades.value = response.data
    } catch (error) {
      console.error('Error cargando ciudades:', error)
      errors.value.ciudades = 'Error al cargar las ciudades'
      ciudades.value = []
    } finally {
      loading.value.ciudades = false
    }
  }

  // Obtener lugar completo por IDs
  const obtenerLugarCompleto = (paisId, departamentoId, ciudadId) => {
    const pais = paises.value.find(p => p.id === paisId)
    const departamento = departamentos.value.find(d => d.id === departamentoId)
    const ciudad = ciudades.value.find(c => c.id === ciudadId)
    
    return {
      pais: pais?.nombre || '',
      departamento: departamento?.nombre || '',
      ciudad: ciudad?.nombre || '',
      completo: [ciudad?.nombre, departamento?.nombre, pais?.nombre].filter(Boolean).join(', ')
    }
  }

  // Limpiar todas las listas
  const limpiarLugares = () => {
    paises.value = []
    departamentos.value = []
    ciudades.value = []
  }

  return {
    // Estados
    paises,
    departamentos,
    ciudades,
    loading,
    errors,
    
    // Métodos
    cargarPaises,
    cargarDepartamentos,
    cargarCiudades,
    obtenerLugarCompleto,
    limpiarLugares
  }
}