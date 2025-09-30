<template>
  <TransitionRoot as="template" :show="open">
    <Dialog as="div" class="relative z-50" @close="cancelar">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel 
              class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md sm:p-6"
            >
              <!-- Header -->
              <div class="sm:flex sm:items-start">
                <div 
                  :class="[
                    'mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10',
                    iconoConfig.bgClass
                  ]"
                >
                  <component 
                    :is="iconoConfig.icono" 
                    :class="['h-6 w-6', iconoConfig.iconClass]" 
                    aria-hidden="true" 
                  />
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                  <DialogTitle as="h3" class="text-lg font-semibold leading-6 text-gray-900">
                    {{ tituloModal }}
                  </DialogTitle>
                  <div class="mt-2">
                    <p class="text-sm text-gray-600">
                      Estudiante: <strong>{{ estudiante?.nombre_completo }}</strong>
                    </p>
                    <p class="text-sm text-gray-600">
                      Código: <strong>{{ estudiante?.codigo_estudiante }}</strong>
                    </p>
                  </div>
                </div>
              </div>

              <!-- Body -->
              <div class="mt-5 sm:mt-4">
                <!-- Información de consecuencias -->
                <div 
                  v-if="descripcionCambio" 
                  :class="[
                    'rounded-md p-4 mb-4',
                    tipoDescripcion.bgClass,
                    tipoDescripcion.borderClass
                  ]"
                >
                  <div class="flex">
                    <div class="flex-shrink-0">
                      <component 
                        :is="tipoDescripcion.icono" 
                        :class="['h-5 w-5', tipoDescripcion.iconClass]" 
                        aria-hidden="true" 
                      />
                    </div>
                    <div class="ml-3">
                      <h3 :class="['text-sm font-medium', tipoDescripcion.titleClass]">
                        Consecuencias del cambio
                      </h3>
                      <div :class="['mt-2 text-sm', tipoDescripcion.textClass]">
                        <p>{{ descripcionCambio }}</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Campo de observaciones -->
                <div>
                  <label for="observaciones" class="block text-sm font-medium text-gray-700">
                    Observaciones
                    <span v-if="nuevoEstado === 'retirado'" class="text-red-500">*</span>
                  </label>
                  <div class="mt-1">
                    <textarea
                      id="observaciones"
                      v-model="form.observaciones"
                      rows="3"
                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"
                      :class="{
                        'border-red-300 focus:border-red-500 focus:ring-red-500': 
                        errorObservaciones
                      }"
                      :placeholder="placeholderObservaciones"
                      maxlength="500"
                    />
                  </div>
                  <div class="mt-1 flex justify-between">
                    <p v-if="errorObservaciones" class="text-sm text-red-600">
                      {{ errorObservaciones }}
                    </p>
                    <p class="text-xs text-gray-500 ml-auto">
                      {{ form.observaciones?.length || 0 }}/500
                    </p>
                  </div>
                </div>
              </div>

              <!-- Footer -->
              <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse sm:gap-3">
                <button
                  type="button"
                  :disabled="!puedeConfirmar || cargando"
                  @click="confirmar"
                  :class="[
                    'inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:w-auto',
                    puedeConfirmar && !cargando
                      ? `${botonConfig.bgClass} ${botonConfig.hoverClass} focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 ${botonConfig.focusClass}`
                      : 'bg-gray-300 cursor-not-allowed'
                  ]"
                >
                  <div v-if="cargando" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Procesando...
                  </div>
                  <span v-else>
                    {{ botonConfig.texto }}
                  </span>
                </button>
                <button
                  type="button"
                  @click="cancelar"
                  :disabled="cargando"
                  class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Cancelar
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { 
  CheckCircleIcon, 
  ExclamationTriangleIcon, 
  XCircleIcon,
  InformationCircleIcon 
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  open: {
    type: Boolean,
    default: false
  },
  estudiante: {
    type: Object,
    required: true
  },
  nuevoEstado: {
    type: String,
    default: null,
    validator: (value) => value === null || ['activo', 'inactivo', 'retirado'].includes(value)
  }
})

// Emits
const emit = defineEmits(['cerrar', 'confirmado'])

// Estado reactivo
const cargando = ref(false)
const form = ref({
  observaciones: ''
})

// Resetear form cuando se abre el modal
watch(() => props.open, (isOpen) => {
  if (isOpen) {
    form.value.observaciones = ''
  }
})

// Configuraciones por tipo de estado
const configuraciones = {
  activo: {
    titulo: 'Activar Estudiante',
    descripcion: 'El estudiante podrá asistir a clases, participar en actividades académicas y acceder a todos los servicios de la institución.',
    icono: CheckCircleIcon,
    iconoBg: 'bg-green-100',
    iconoColor: 'text-green-600',
    botonBg: 'bg-green-600',
    botonHover: 'hover:bg-green-500',
    botonFocus: 'focus-visible:outline-green-600',
    botonTexto: 'Activar Estudiante',
    descripcionBg: 'bg-green-50',
    descripcionBorder: 'border-l-4 border-green-400',
    descripcionIcono: InformationCircleIcon,
    descripcionIconoColor: 'text-green-400',
    descripcionTitleColor: 'text-green-800',
    descripcionTextColor: 'text-green-700',
    placeholder: 'Razón de la activación (opcional)'
  },
  inactivo: {
    titulo: 'Inactivar Estudiante',
    descripcion: 'El estudiante será marcado como inactivo temporalmente. No podrá asistir a clases pero mantendrá su registro académico.',
    icono: ExclamationTriangleIcon,
    iconoBg: 'bg-yellow-100',
    iconoColor: 'text-yellow-600',
    botonBg: 'bg-yellow-600',
    botonHover: 'hover:bg-yellow-500',
    botonFocus: 'focus-visible:outline-yellow-600',
    botonTexto: 'Inactivar Estudiante',
    descripcionBg: 'bg-yellow-50',
    descripcionBorder: 'border-l-4 border-yellow-400',
    descripcionIcono: ExclamationTriangleIcon,
    descripcionIconoColor: 'text-yellow-400',
    descripcionTitleColor: 'text-yellow-800',
    descripcionTextColor: 'text-yellow-700',
    placeholder: 'Razón de la inactivación (recomendada)'
  },
  retirado: {
    titulo: 'Retirar Estudiante',
    descripcion: 'El estudiante será retirado permanentemente de la institución. Esta acción no se puede revertir y requiere observaciones obligatorias.',
    icono: XCircleIcon,
    iconoBg: 'bg-red-100',
    iconoColor: 'text-red-600',
    botonBg: 'bg-red-600',
    botonHover: 'hover:bg-red-500',
    botonFocus: 'focus-visible:outline-red-600',
    botonTexto: 'Retirar Estudiante',
    descripcionBg: 'bg-red-50',
    descripcionBorder: 'border-l-4 border-red-400',
    descripcionIcono: XCircleIcon,
    descripcionIconoColor: 'text-red-400',
    descripcionTitleColor: 'text-red-800',
    descripcionTextColor: 'text-red-700',
    placeholder: 'Motivo del retiro (obligatorio)'
  }
}

// Computed properties
const config = computed(() => {
  if (!props.nuevoEstado) return configuraciones.activo // fallback por defecto
  return configuraciones[props.nuevoEstado]
})

const tituloModal = computed(() => config.value.titulo)

const descripcionCambio = computed(() => config.value.descripcion)

const placeholderObservaciones = computed(() => config.value.placeholder)

const iconoConfig = computed(() => ({
  icono: config.value.icono,
  bgClass: config.value.iconoBg,
  iconClass: config.value.iconoColor
}))

const botonConfig = computed(() => ({
  bgClass: config.value.botonBg,
  hoverClass: config.value.botonHover,
  focusClass: config.value.botonFocus,
  texto: config.value.botonTexto
}))

const tipoDescripcion = computed(() => ({
  bgClass: config.value.descripcionBg,
  borderClass: config.value.descripcionBorder,
  icono: config.value.descripcionIcono,
  iconClass: config.value.descripcionIconoColor,
  titleClass: config.value.descripcionTitleColor,
  textClass: config.value.descripcionTextColor
}))

// Validaciones
const errorObservaciones = computed(() => {
  if (props.nuevoEstado === 'retirado' && !form.value.observaciones?.trim()) {
    return 'Las observaciones son obligatorias para retirar un estudiante'
  }
  return null
})

const puedeConfirmar = computed(() => {
  // Si no hay estado seleccionado, no se puede confirmar
  if (!props.nuevoEstado) return false
  
  // Para retiro, las observaciones son obligatorias
  if (props.nuevoEstado === 'retirado') {
    return form.value.observaciones?.trim().length > 0
  }
  // Para otros estados, no hay validaciones especiales
  return true
})

// Métodos
const confirmar = async () => {
  if (!puedeConfirmar.value || cargando.value) return

  cargando.value = true

  try {
    await router.patch(
      route('estudiantes.cambiar-estado', props.estudiante.id),
      {
        estado: props.nuevoEstado,
        observaciones: form.value.observaciones?.trim() || null
      },
      {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          emit('confirmado')
          cerrarModal()
        },
        onError: (errors) => {
          console.error('Error al cambiar estado:', errors)
        }
      }
    )
  } catch (error) {
    console.error('Error inesperado:', error)
  } finally {
    cargando.value = false
  }
}

const cancelar = () => {
  if (cargando.value) return
  cerrarModal()
}

const cerrarModal = () => {
  form.value.observaciones = ''
  emit('cerrar')
}
</script>