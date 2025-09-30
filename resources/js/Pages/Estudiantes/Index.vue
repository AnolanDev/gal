<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Estudiantes
          </h2>
          <p class="text-sm text-gray-600 mt-1">
            Gestiona la información de todos los estudiantes del colegio
          </p>
        </div>
        <Link
          :href="route('estudiantes.create')"
          class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
          <PlusIcon class="h-4 w-4 mr-2" />
          Nuevo Estudiante
        </Link>
      </div>
    </template>

    <div class="py-6">
      <!-- Stats Cards -->
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Total</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.total }}</p>
              </div>
              <UserGroupIcon class="h-8 w-8 text-gray-400" />
            </div>
          </div>
          
          <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Activos</p>
                <p class="text-2xl font-bold text-green-600">{{ stats.activos }}</p>
              </div>
              <CheckCircleIcon class="h-8 w-8 text-green-400" />
            </div>
          </div>
          
          <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Inactivos</p>
                <p class="text-2xl font-bold text-yellow-600">{{ stats.inactivos }}</p>
              </div>
              <PauseCircleIcon class="h-8 w-8 text-yellow-400" />
            </div>
          </div>
          
          <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Retirados</p>
                <p class="text-2xl font-bold text-red-600">{{ stats.retirados }}</p>
              </div>
              <XCircleIcon class="h-8 w-8 text-red-400" />
            </div>
          </div>
        </div>
      </div>

      <!-- Filters and Search -->
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
          <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
              <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                Buscar estudiante
              </label>
              <div class="relative">
                <MagnifyingGlassIcon class="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
                <input
                  id="search"
                  v-model="form.search"
                  type="text"
                  placeholder="Nombre, apellido, código o documento..."
                  class="pl-10 w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                  @input="debouncedSearch"
                />
              </div>
            </div>
            
            <!-- Grado Filter -->
            <div>
              <label for="grado" class="block text-sm font-medium text-gray-700 mb-1">
                Grado
              </label>
              <select
                id="grado"
                v-model="form.grado_id"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                @change="applyFilters"
              >
                <option value="">Todos los grados</option>
                <option v-for="grado in grados" :key="grado.id" :value="grado.id">
                  {{ grado.nombre }}
                </option>
              </select>
            </div>
            
            <!-- Estado Filter -->
            <div>
              <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">
                Estado
              </label>
              <select
                id="estado"
                v-model="form.estado"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                @change="applyFilters"
              >
                <option value="">Todos los estados</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
                <option value="retirado">Retirado</option>
              </select>
            </div>
            
            <!-- Actions -->
            <div class="flex items-end gap-2">
              <button
                type="button"
                @click="clearFilters"
                class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors"
              >
                <XMarkIcon class="h-4 w-4" />
              </button>
              <button
                type="button"
                @click="exportData"
                class="px-3 py-2 bg-green-100 text-green-700 rounded-md hover:bg-green-200 transition-colors"
              >
                <ArrowDownTrayIcon class="h-4 w-4" />
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Students Table -->
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
          <div class="p-6">
            <!-- Bulk Actions -->
            <div v-if="selectedStudents.length > 0" class="mb-4 p-4 bg-blue-50 rounded-lg">
              <div class="flex items-center justify-between">
                <span class="text-sm text-blue-700">
                  {{ selectedStudents.length }} estudiante(s) seleccionado(s)
                </span>
                <div class="space-x-2">
                  <button
                    @click="bulkUpdateStatus('activo')"
                    class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700"
                  >
                    Activar
                  </button>
                  <button
                    @click="bulkUpdateStatus('inactivo')"
                    class="px-3 py-1 bg-yellow-600 text-white text-xs rounded hover:bg-yellow-700"
                  >
                    Inactivar
                  </button>
                  <button
                    @click="bulkUpdateStatus('retirado')"
                    class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700"
                  >
                    Retirar
                  </button>
                  <button
                    @click="clearSelection"
                    class="px-3 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700"
                  >
                    Limpiar
                  </button>
                </div>
              </div>
            </div>
            
            <!-- Loading Overlay -->
            <div v-if="loading" class="relative">
              <div class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
                <div class="flex items-center space-x-2">
                  <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-600"></div>
                  <span class="text-gray-600">Cargando estudiantes...</span>
                </div>
              </div>
            </div>
            
            <!-- Table -->
            <div class="overflow-x-auto" :class="{ 'opacity-50': loading }">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left">
                      <input
                        type="checkbox"
                        @change="toggleSelectAll"
                        :checked="allSelected"
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                      />
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Estudiante
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Código
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Grado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Estado
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Contacto
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Acciones
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="estudiante in estudiantes.data" :key="estudiante.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                      <input
                        type="checkbox"
                        :value="estudiante.id"
                        v-model="selectedStudents"
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                      />
                    </td>
                    <td class="px-6 py-4">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <img
                            v-if="estudiante.foto_url"
                            :src="estudiante.foto_url"
                            :alt="estudiante.nombre_completo"
                            class="h-10 w-10 rounded-full object-cover"
                          />
                          <div
                            v-else
                            class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center"
                          >
                            <UserIcon class="h-6 w-6 text-gray-400" />
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">
                            {{ estudiante.nombre_completo }}
                          </div>
                          <div class="text-sm text-gray-500">
                            {{ estudiante.documento_identidad }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ estudiante.codigo_estudiante }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ estudiante.grado?.nombre || 'Sin grado' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="estudiante.estado_badge.class" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                        {{ estudiante.estado_badge.text }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <div>{{ estudiante.telefono || 'No registrado' }}</div>
                      <div>{{ estudiante.email || 'No registrado' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                      <Link
                        :href="route('estudiantes.show', estudiante.id)"
                        class="text-primary-600 hover:text-primary-900"
                        title="Ver perfil"
                      >
                        <EyeIcon class="h-4 w-4" />
                      </Link>
                      <Link
                        :href="route('estudiantes.edit', estudiante.id)"
                        class="text-yellow-600 hover:text-yellow-900"
                        title="Editar"
                      >
                        <PencilIcon class="h-4 w-4" />
                      </Link>
                      <button
                        @click="deleteEstudiante(estudiante)"
                        class="text-red-600 hover:text-red-900"
                        title="Retirar"
                      >
                        <TrashIcon class="h-4 w-4" />
                      </button>
                    </td>
                  </tr>
                  
                  <!-- Empty state -->
                  <tr v-if="estudiantes.data.length === 0">
                    <td colspan="7" class="px-6 py-12 text-center">
                      <UserGroupIcon class="mx-auto h-12 w-12 text-gray-400" />
                      <h3 class="mt-2 text-sm font-medium text-gray-900">No hay estudiantes</h3>
                      <p class="mt-1 text-sm text-gray-500">
                        {{ hasFilters ? 'No se encontraron estudiantes con los filtros aplicados.' : 'Comienza agregando un nuevo estudiante.' }}
                      </p>
                      <div class="mt-6" v-if="!hasFilters">
                        <Link
                          :href="route('estudiantes.create')"
                          class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700"
                        >
                          <PlusIcon class="h-4 w-4 mr-2" />
                          Nuevo Estudiante
                        </Link>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            
            <!-- Pagination -->
            <div v-if="estudiantes.data.length > 0" class="mt-6">
              <Pagination :links="estudiantes.links" :meta="estudiantes.meta" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Confirmación -->
    <ConfirmModal
      :show="confirmModal.show"
      :type="confirmModal.type"
      :title="confirmModal.title"
      :message="confirmModal.message"
      :confirm-text="confirmModal.confirmText"
      @confirm="executeConfirmedAction"
      @close="closeConfirmModal"
    />

    <!-- Notificaciones -->
    <Notification
      :show="notification.show"
      :type="notification.type"
      :title="notification.title"
      :message="notification.message"
      @close="closeNotification"
    />
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import Notification from '@/Components/Notification.vue'

// Icons
import {
  PlusIcon,
  UserGroupIcon,
  CheckCircleIcon,
  PauseCircleIcon,
  XCircleIcon,
  MagnifyingGlassIcon,
  XMarkIcon,
  ArrowDownTrayIcon,
  UserIcon,
  EyeIcon,
  PencilIcon,
  TrashIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  estudiantes: Object,
  grados: Array,
  stats: Object,
  filters: Object
})

// Reactive data
const selectedStudents = ref([])
const form = ref({
  search: props.filters.search || '',
  grado_id: props.filters.grado_id || '',
  estado: props.filters.estado || '',
  genero: props.filters.genero || '',
  year: props.filters.year || ''
})

// Modal de confirmación
const confirmModal = ref({
  show: false,
  type: 'danger',
  title: '',
  message: '',
  confirmText: 'Confirmar',
  action: null,
  data: null
})

// Estado de carga
const loading = ref(false)

// Sistema de notificaciones
const notification = ref({
  show: false,
  type: 'success',
  title: '',
  message: ''
})

// Computed
const allSelected = computed(() => {
  return props.estudiantes.data.length > 0 && 
         selectedStudents.value.length === props.estudiantes.data.length
})

const hasFilters = computed(() => {
  return Object.values(props.filters).some(value => value !== null && value !== '')
})

// Simple debounce function
function debounce(func, delay) {
  let timeoutId
  return function (...args) {
    clearTimeout(timeoutId)
    timeoutId = setTimeout(() => func.apply(this, args), delay)
  }
}

// Methods
const debouncedSearch = debounce(() => {
  applyFilters()
}, 300)

const applyFilters = () => {
  loading.value = true
  router.get(route('estudiantes.index'), form.value, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      loading.value = false
    }
  })
}

const clearFilters = () => {
  form.value = {
    search: '',
    grado_id: '',
    estado: '',
    genero: '',
    year: ''
  }
  applyFilters()
}

const toggleSelectAll = () => {
  if (allSelected.value) {
    selectedStudents.value = []
  } else {
    selectedStudents.value = props.estudiantes.data.map(e => e.id)
  }
}

const clearSelection = () => {
  selectedStudents.value = []
}

const bulkUpdateStatus = (estado) => {
  if (selectedStudents.value.length === 0) return
  
  const estadoTexto = {
    'activo': 'activo',
    'inactivo': 'inactivo', 
    'retirado': 'retirado'
  }[estado]
  
  confirmModal.value = {
    show: true,
    type: estado === 'retirado' ? 'danger' : 'warning',
    title: `Cambiar Estado Masivo`,
    message: `¿Estás seguro de cambiar el estado de ${selectedStudents.value.length} estudiante(s) a "${estadoTexto}"?`,
    confirmText: 'Cambiar Estado',
    action: 'bulk-update',
    data: {
      estudiante_ids: selectedStudents.value,
      estado: estado
    }
  }
}

const deleteEstudiante = (estudiante) => {
  confirmModal.value = {
    show: true,
    type: 'danger',
    title: 'Retirar Estudiante',
    message: `¿Estás seguro de que deseas retirar a ${estudiante.nombre_completo}? Esta acción cambiará su estado a "retirado".`,
    confirmText: 'Retirar',
    action: 'delete',
    data: estudiante
  }
}

const closeConfirmModal = () => {
  confirmModal.value.show = false
}

const executeConfirmedAction = () => {
  const { action, data } = confirmModal.value
  
  if (action === 'delete') {
    router.delete(route('estudiantes.destroy', data.id), {
      onSuccess: () => {
        showNotification('success', 'Estudiante Retirado', `${data.nombre_completo} ha sido marcado como retirado.`)
        closeConfirmModal()
      },
      onError: () => {
        showNotification('error', 'Error', 'No se pudo retirar el estudiante. Inténtalo nuevamente.')
        closeConfirmModal()
      }
    })
  } else if (action === 'bulk-update') {
    router.patch(route('estudiantes.bulk-update-status'), data, {
      onSuccess: () => {
        selectedStudents.value = []
        showNotification('success', 'Estados Actualizados', `Se actualizó el estado de ${data.estudiante_ids.length} estudiante(s).`)
        closeConfirmModal()
      },
      onError: () => {
        showNotification('error', 'Error', 'No se pudieron actualizar los estados. Inténtalo nuevamente.')
        closeConfirmModal()
      }
    })
  }
}

const showNotification = (type, title, message) => {
  notification.value = {
    show: true,
    type,
    title,
    message
  }
  
  // Auto-cerrar después de 5 segundos
  setTimeout(() => {
    closeNotification()
  }, 5000)
}

const closeNotification = () => {
  notification.value.show = false
}

const exportData = () => {
  // TODO: Implement export functionality
  alert('Funcionalidad de exportación en desarrollo')
}

// Watch for flash messages
watch(() => usePage().props.flash, (flash) => {
  if (flash.success) {
    showNotification('success', 'Operación Exitosa', flash.success)
  }
  if (flash.error) {
    showNotification('error', 'Error', flash.error)
  }
}, { deep: true, immediate: true })
</script>