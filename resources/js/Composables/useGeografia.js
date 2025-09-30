import { ref, watch } from 'vue'
import axios from 'axios'

export function useGeografia() {
    // Estados reactivos
    const countries = ref([])
    const states = ref([])
    const cities = ref([])
    
    const selectedCountry = ref(null)
    const selectedState = ref(null)
    const selectedCity = ref(null)
    
    const loading = ref({
        countries: false,
        states: false,
        cities: false
    })

    const error = ref({
        countries: null,
        states: null,
        cities: null
    })
    
    // Cargar países
    const loadCountries = async () => {
        loading.value.countries = true
        error.value.countries = null
        
        try {
            const response = await axios.get('/api/geografia/countries')
            countries.value = response.data
        } catch (err) {
            console.error('Error cargando países:', err)
            error.value.countries = 'Error al cargar países'
            countries.value = []
        } finally {
            loading.value.countries = false
        }
    }
    
    // Cargar estados/departamentos
    const loadStates = async (countryId) => {
        if (!countryId) {
            states.value = []
            return
        }
        
        loading.value.states = true
        error.value.states = null
        
        try {
            const response = await axios.get(`/api/geografia/countries/${countryId}/states-by-id`)
            states.value = response.data
        } catch (err) {
            console.error('Error cargando estados:', err)
            error.value.states = 'Error al cargar estados'
            states.value = []
        } finally {
            loading.value.states = false
        }
    }
    
    // Cargar ciudades
    const loadCities = async (stateId) => {
        if (!stateId) {
            cities.value = []
            return
        }
        
        loading.value.cities = true
        error.value.cities = null
        
        try {
            const response = await axios.get(`/api/geografia/states/${stateId}/cities-by-id`)
            cities.value = response.data
        } catch (err) {
            console.error('Error cargando ciudades:', err)
            error.value.cities = 'Error al cargar ciudades'
            cities.value = []
        } finally {
            loading.value.cities = false
        }
    }

    // Buscar lugares (autocompletado)
    const searchPlaces = async (term) => {
        if (!term || term.length < 2) {
            return []
        }

        try {
            const response = await axios.get('/api/geografia/search', {
                params: { q: term }
            })
            return response.data
        } catch (err) {
            console.error('Error en búsqueda de lugares:', err)
            return []
        }
    }

    // Obtener información completa de ubicación
    const getFullLocation = async (cityId) => {
        if (!cityId) return null

        try {
            const response = await axios.get(`/api/geografia/full-location/${cityId}`)
            return response.data
        } catch (err) {
            console.error('Error obteniendo ubicación completa:', err)
            return null
        }
    }
    
    // Watchers para cascading effect
    watch(selectedCountry, (newCountry) => {
        // Limpiar selecciones dependientes
        selectedState.value = null
        selectedCity.value = null
        states.value = []
        cities.value = []
        
        if (newCountry) {
            loadStates(newCountry)
        }
    })
    
    watch(selectedState, (newState) => {
        // Limpiar selecciones dependientes
        selectedCity.value = null
        cities.value = []
        
        if (newState) {
            loadCities(newState)
        }
    })
    
    // Inicializar
    const initialize = async () => {
        await loadCountries()
    }

    // Cargar datos iniciales basados en valores existentes
    const initializeWithValues = async (countryId, stateId, cityId) => {
        await loadCountries()
        
        if (countryId) {
            selectedCountry.value = parseInt(countryId)
            await loadStates(countryId)
            
            if (stateId) {
                selectedState.value = parseInt(stateId)
                await loadCities(stateId)
                
                if (cityId) {
                    selectedCity.value = parseInt(cityId)
                }
            }
        }
    }
    
    // Reset completo
    const reset = () => {
        selectedCountry.value = null
        selectedState.value = null
        selectedCity.value = null
        states.value = []
        cities.value = []
        
        // Limpiar errores
        error.value.countries = null
        error.value.states = null
        error.value.cities = null
    }

    // Obtener el texto para los selects cuando están deshabilitados
    const getSelectText = (type) => {
        switch (type) {
            case 'states':
                if (!selectedCountry.value) return 'Primero selecciona un país'
                if (loading.value.states) return 'Cargando departamentos...'
                if (error.value.states) return 'Error cargando departamentos'
                return 'Selecciona un departamento'
            
            case 'cities':
                if (!selectedState.value) return 'Primero selecciona un departamento'
                if (loading.value.cities) return 'Cargando ciudades...'
                if (error.value.cities) return 'Error cargando ciudades'
                return 'Selecciona una ciudad'
            
            default:
                return ''
        }
    }

    // Validar selección completa
    const isSelectionComplete = () => {
        return selectedCountry.value && selectedState.value && selectedCity.value
    }

    // Obtener los valores seleccionados como objeto
    const getSelectedValues = () => {
        return {
            country_id: selectedCountry.value,
            state_id: selectedState.value,
            city_id: selectedCity.value
        }
    }

    // Establecer valores desde un objeto
    const setSelectedValues = async (values) => {
        if (values && (values.country_id || values.state_id || values.city_id)) {
            await initializeWithValues(values.country_id, values.state_id, values.city_id)
        }
    }
    
    return {
        // Estados reactivos
        countries,
        states,
        cities,
        selectedCountry,
        selectedState,
        selectedCity,
        loading,
        error,
        
        // Métodos principales
        loadCountries,
        loadStates,
        loadCities,
        searchPlaces,
        getFullLocation,
        initialize,
        initializeWithValues,
        reset,
        
        // Utilidades
        getSelectText,
        isSelectionComplete,
        getSelectedValues,
        setSelectedValues
    }
}