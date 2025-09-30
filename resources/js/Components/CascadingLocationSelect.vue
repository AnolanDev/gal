<template>
  <div class="space-y-4">
    <!-- País -->
    <div>
      <label :for="`country_${uniqueId}`" class="block text-sm font-medium text-gray-700 mb-2">
        País {{ required ? '*' : '' }}
      </label>
      <select 
        :id="`country_${uniqueId}`"
        v-model="selectedCountry"
        :disabled="loading.countries"
        :required="required"
        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
        :class="{ 'border-red-500': errors.country }"
      >
        <option value="">
          {{ loading.countries ? 'Cargando países...' : 'Selecciona un país' }}
        </option>
        <option 
          v-for="country in countries" 
          :key="country.id" 
          :value="country.id"
        >
          {{ country.name }}
        </option>
      </select>
      <p v-if="errors.country" class="mt-1 text-sm text-red-600">
        {{ errors.country }}
      </p>
      <p v-if="error.countries" class="mt-1 text-sm text-red-600">
        {{ error.countries }}
      </p>
    </div>

    <!-- Departamento/Estado -->
    <div>
      <label :for="`state_${uniqueId}`" class="block text-sm font-medium text-gray-700 mb-2">
        Departamento/Estado {{ required ? '*' : '' }}
      </label>
      <select 
        :id="`state_${uniqueId}`"
        v-model="selectedState"
        :disabled="!selectedCountry || loading.states"
        :required="required"
        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
        :class="{ 'border-red-500': errors.state }"
      >
        <option value="">
          {{ getSelectText('states') }}
        </option>
        <option 
          v-for="state in states" 
          :key="state.id" 
          :value="state.id"
        >
          {{ state.name }}
        </option>
      </select>
      <p v-if="errors.state" class="mt-1 text-sm text-red-600">
        {{ errors.state }}
      </p>
      <p v-if="error.states" class="mt-1 text-sm text-red-600">
        {{ error.states }}
      </p>
    </div>

    <!-- Ciudad/Municipio -->
    <div>
      <label :for="`city_${uniqueId}`" class="block text-sm font-medium text-gray-700 mb-2">
        Ciudad/Municipio {{ required ? '*' : '' }}
      </label>
      <select 
        :id="`city_${uniqueId}`"
        v-model="selectedCity"
        :disabled="!selectedState || loading.cities"
        :required="required"
        class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
        :class="{ 'border-red-500': errors.city }"
      >
        <option value="">
          {{ getSelectText('cities') }}
        </option>
        <option 
          v-for="city in cities" 
          :key="city.id" 
          :value="city.id"
        >
          {{ city.name }}
          <span v-if="city.is_capital" class="text-xs text-gray-500">(Capital)</span>
        </option>
      </select>
      <p v-if="errors.city" class="mt-1 text-sm text-red-600">
        {{ errors.city }}
      </p>
      <p v-if="error.cities" class="mt-1 text-sm text-red-600">
        {{ error.cities }}
      </p>
    </div>

    <!-- Información adicional -->
    <div v-if="showSelectedInfo && isSelectionComplete()" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
      <div class="flex items-center">
        <svg class="h-4 w-4 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
        </svg>
        <span class="text-sm text-blue-700">
          Ubicación seleccionada: {{ getSelectedLocationText() }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, computed, watch, ref as vueRef } from 'vue'
import { useGeografia } from '@/Composables/useGeografia'

// Props
const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({
      country_id: null,
      state_id: null,
      city_id: null
    })
  },
  required: {
    type: Boolean,
    default: false
  },
  errors: {
    type: Object,
    default: () => ({})
  },
  showSelectedInfo: {
    type: Boolean,
    default: true
  }
})

// Emits
const emit = defineEmits(['update:modelValue', 'change'])

// ID único para evitar conflictos
const uniqueId = vueRef(Math.random().toString(36).substring(2, 15))

// Composable de geografía
const {
  countries,
  states,
  cities,
  selectedCountry,
  selectedState,
  selectedCity,
  loading,
  error,
  initialize,
  getSelectText,
  isSelectionComplete,
  setSelectedValues
} = useGeografia()

// Computed para el texto de ubicación seleccionada
const getSelectedLocationText = () => {
  const country = countries.value.find(c => c.id === selectedCountry.value)
  const state = states.value.find(s => s.id === selectedState.value)
  const city = cities.value.find(c => c.id === selectedCity.value)
  
  if (city && state && country) {
    return `${city.name}, ${state.name}, ${country.name}`
  }
  return ''
}

// Watch para emitir cambios al componente padre
watch([selectedCountry, selectedState, selectedCity], () => {
  const newValue = {
    country_id: selectedCountry.value,
    state_id: selectedState.value,
    city_id: selectedCity.value
  }
  
  emit('update:modelValue', newValue)
  emit('change', newValue)
}, { deep: true })

// Watch para sincronizar con valores del padre
watch(() => props.modelValue, async (newValue) => {
  if (newValue && (
    newValue.country_id !== selectedCountry.value ||
    newValue.state_id !== selectedState.value ||
    newValue.city_id !== selectedCity.value
  )) {
    await setSelectedValues(newValue)
  }
}, { immediate: true, deep: true })

// Inicializar el componente
onMounted(async () => {
  await initialize()
  
  // Si hay valores iniciales, establecerlos
  if (props.modelValue && (props.modelValue.country_id || props.modelValue.state_id || props.modelValue.city_id)) {
    await setSelectedValues(props.modelValue)
  }
})

// Exponer métodos para acceso externo si es necesario
defineExpose({
  reset: () => {
    selectedCountry.value = null
    selectedState.value = null
    selectedCity.value = null
  },
  isComplete: isSelectionComplete,
  getLocationText: getSelectedLocationText
})
</script>

<style scoped>
/* Estilos específicos del componente si son necesarios */
.form-input {
  @apply w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 disabled:bg-gray-100 disabled:cursor-not-allowed;
}
</style>