<template>
  <!-- Flash Messages -->
  <div class="fixed top-20 right-4 z-50 space-y-2 max-w-sm w-full">
    <Transition
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-if="$page.props.flash.message && showMessage" 
        class="bg-primary-500 text-white px-4 py-3 rounded-lg shadow-lg relative cursor-pointer"
        @click="dismissMessage"
      >
        <div class="flex items-center">
          <InformationCircleIcon class="w-5 h-5 mr-2 flex-shrink-0" />
          <p class="text-sm font-medium">{{ $page.props.flash.message }}</p>
          <button @click.stop="dismissMessage" class="ml-auto pl-2">
            <XMarkIcon class="w-4 h-4" />
          </button>
        </div>
      </div>
    </Transition>

    <Transition
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-if="$page.props.flash.success && showSuccess" 
        class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg relative cursor-pointer"
        @click="dismissSuccess"
      >
        <div class="flex items-center">
          <CheckCircleIcon class="w-5 h-5 mr-2 flex-shrink-0" />
          <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
          <button @click.stop="dismissSuccess" class="ml-auto pl-2">
            <XMarkIcon class="w-4 h-4" />
          </button>
        </div>
      </div>
    </Transition>

    <Transition
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-if="$page.props.flash.error && showError" 
        class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg relative cursor-pointer"
        @click="dismissError"
      >
        <div class="flex items-center">
          <XCircleIcon class="w-5 h-5 mr-2 flex-shrink-0" />
          <p class="text-sm font-medium">{{ $page.props.flash.error }}</p>
          <button @click.stop="dismissError" class="ml-auto pl-2">
            <XMarkIcon class="w-4 h-4" />
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { InformationCircleIcon, CheckCircleIcon, XCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline'

// Estado de visibilidad de cada mensaje
const showMessage = ref(false)
const showSuccess = ref(false)
const showError = ref(false)

// Acceso a las props de la página
const page = usePage()

// Temporizadores para auto-dismiss
let messageTimer = null
let successTimer = null
let errorTimer = null

// Función para resetear y mostrar mensaje
const setupAutoHide = (type, duration = 5000) => {
  // Limpiar temporizador existente
  if (type === 'message' && messageTimer) {
    clearTimeout(messageTimer)
  } else if (type === 'success' && successTimer) {
    clearTimeout(successTimer)
  } else if (type === 'error' && errorTimer) {
    clearTimeout(errorTimer)
  }
  
  // Mostrar mensaje
  if (type === 'message') {
    showMessage.value = true
    messageTimer = setTimeout(() => {
      showMessage.value = false
    }, duration)
  } else if (type === 'success') {
    showSuccess.value = true
    successTimer = setTimeout(() => {
      showSuccess.value = false
    }, duration)
  } else if (type === 'error') {
    showError.value = true
    errorTimer = setTimeout(() => {
      showError.value = false
    }, duration)
  }
}

// Watchers para detectar nuevos mensajes flash
watch(() => page.props.flash.message, (newMessage) => {
  if (newMessage) {
    setupAutoHide('message', 5000)
  } else {
    showMessage.value = false
  }
})

watch(() => page.props.flash.success, (newSuccess) => {
  if (newSuccess) {
    setupAutoHide('success', 4000) // Success se oculta más rápido
  } else {
    showSuccess.value = false
  }
})

watch(() => page.props.flash.error, (newError) => {
  if (newError) {
    setupAutoHide('error', 8000) // Errores duran más tiempo
  } else {
    showError.value = false
  }
})

// Funciones para dismiss manual
const dismissMessage = () => {
  showMessage.value = false
  if (messageTimer) clearTimeout(messageTimer)
}

const dismissSuccess = () => {
  showSuccess.value = false
  if (successTimer) clearTimeout(successTimer)
}

const dismissError = () => {
  showError.value = false
  if (errorTimer) clearTimeout(errorTimer)
}

// Inicializar en mount si hay mensajes existentes
onMounted(() => {
  if (page.props.flash.message) {
    setupAutoHide('message', 5000)
  }
  if (page.props.flash.success) {
    setupAutoHide('success', 4000)
  }
  if (page.props.flash.error) {
    setupAutoHide('error', 8000)
  }
})
</script>