<template>
  <teleport to="body">
    <transition
      enter-active-class="duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Overlay -->
        <div 
          class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
          @click="$emit('close')"
        ></div>
        
        <!-- Modal -->
        <div class="flex min-h-full items-center justify-center p-4">
          <transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <div
              v-if="show"
              class="relative bg-white rounded-lg shadow-xl max-w-lg w-full mx-auto"
            >
              <!-- Header -->
              <div class="flex items-start p-6 pb-4">
                <div :class="iconBgClass" class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10">
                  <component :is="icon" :class="iconClass" class="h-6 w-6" />
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                  <h3 class="text-lg font-medium leading-6 text-gray-900">
                    {{ title }}
                  </h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">
                      {{ message }}
                    </p>
                  </div>
                </div>
              </div>
              
              <!-- Actions -->
              <div class="bg-gray-50 px-6 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button
                  type="button"
                  :class="confirmButtonClass"
                  class="inline-flex w-full justify-center rounded-md border border-transparent px-4 py-2 text-base font-medium text-white shadow-sm sm:ml-3 sm:w-auto sm:text-sm focus:outline-none focus:ring-2 focus:ring-offset-2"
                  @click="$emit('confirm')"
                >
                  {{ confirmText }}
                </button>
                <button
                  type="button"
                  class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                  @click="$emit('close')"
                >
                  {{ cancelText }}
                </button>
              </div>
            </div>
          </transition>
        </div>
      </div>
    </transition>
  </teleport>
</template>

<script setup>
import { computed } from 'vue'
import { ExclamationTriangleIcon, InformationCircleIcon, CheckCircleIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  type: {
    type: String,
    default: 'warning', // warning, danger, success, info
    validator: (value) => ['warning', 'danger', 'success', 'info'].includes(value)
  },
  title: {
    type: String,
    required: true
  },
  message: {
    type: String,
    required: true
  },
  confirmText: {
    type: String,
    default: 'Confirmar'
  },
  cancelText: {
    type: String,
    default: 'Cancelar'
  }
})

defineEmits(['confirm', 'close'])

const icon = computed(() => {
  const icons = {
    warning: ExclamationTriangleIcon,
    danger: ExclamationTriangleIcon,
    success: CheckCircleIcon,
    info: InformationCircleIcon
  }
  return icons[props.type]
})

const iconBgClass = computed(() => {
  const classes = {
    warning: 'bg-yellow-100',
    danger: 'bg-red-100',
    success: 'bg-green-100',
    info: 'bg-blue-100'
  }
  return classes[props.type]
})

const iconClass = computed(() => {
  const classes = {
    warning: 'text-yellow-600',
    danger: 'text-red-600',
    success: 'text-green-600',
    info: 'text-blue-600'
  }
  return classes[props.type]
})

const confirmButtonClass = computed(() => {
  const classes = {
    warning: 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
    danger: 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
    success: 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
    info: 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'
  }
  return classes[props.type]
})
</script>