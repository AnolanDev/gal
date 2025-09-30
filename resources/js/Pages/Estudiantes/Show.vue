<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Perfil del Estudiante
          </h2>
          <p class="text-sm text-gray-600 mt-1">
            {{ datosEstudiante?.nombre_completo || 'Cargando...' }}
          </p>
        </div>
        <div class="flex space-x-3">
          <Link
            :href="route('estudiantes.edit', datosEstudiante?.id)"
            v-if="datosEstudiante?.id"
            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 transition ease-in-out duration-150"
          >
            <PencilIcon class="h-4 w-4 mr-2" />
            Editar
          </Link>
          <Link
            :href="route('estudiantes.index')"
            class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 transition ease-in-out duration-150"
          >
            <ArrowLeftIcon class="h-4 w-4 mr-2" />
            Volver al Listado
          </Link>
        </div>
      </div>
    </template>

    <div v-if="!datosEstudiante || !datosEstudiante.id" class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 text-center">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 mx-auto"></div>
          <p class="mt-2 text-gray-600">Cargando información del estudiante...</p>
        </div>
      </div>
    </div>

    <div v-else class="py-6">
      <!-- Header with Photo and Basic Info -->
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
          <div class="p-6">
            <div class="flex items-start space-x-6">
              <!-- Foto -->
              <div class="flex-shrink-0">
                <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                  <img
                    v-if="datosEstudiante.foto_url"
                    :src="datosEstudiante.foto_url"
                    :alt="datosEstudiante.nombre_completo"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="text-gray-400 text-4xl font-bold">
                    {{ datosEstudiante.iniciales }}
                  </div>
                </div>
              </div>
              
              <!-- Info Básica -->
              <div class="flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ datosEstudiante.nombre_completo }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Código: {{ datosEstudiante.codigo_estudiante }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-700">Documento</p>
                    <p class="text-sm text-gray-900">{{ datosEstudiante.documento_identidad }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-700">Grado</p>
                    <p class="text-sm text-gray-900">{{ datosEstudiante.grado?.nombre || 'Sin grado' }}</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-700">Estado</p>
                    <span :class="datosEstudiante.estado_badge?.class" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ datosEstudiante.estado_badge?.text }}
                    </span>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-700">Edad</p>
                    <p class="text-sm text-gray-900">{{ datosEstudiante.edad }} años</p>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-700">Fecha de Ingreso</p>
                    <p class="text-sm text-gray-900">{{ datosEstudiante.fecha_ingreso_formatted }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div v-if="stats" class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Asistencias Este Mes</p>
                <p class="text-2xl font-bold text-green-600">{{ stats.asistencias_mes || 0 }}</p>
              </div>
              <CheckCircleIcon class="h-8 w-8 text-green-400" />
            </div>
          </div>
          
          <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Faltas Este Mes</p>
                <p class="text-2xl font-bold text-red-600">{{ stats.faltas_mes || 0 }}</p>
              </div>
              <XCircleIcon class="h-8 w-8 text-red-400" />
            </div>
          </div>
          
          <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Promedio General</p>
                <p class="text-2xl font-bold text-blue-600">{{ stats.promedio_general || 'N/A' }}</p>
              </div>
              <AcademicCapIcon class="h-8 w-8 text-blue-400" />
            </div>
          </div>
          
          <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-1">
                <p class="text-sm font-medium text-gray-600">Tiempo en Institución</p>
                <p class="text-2xl font-bold text-purple-600">{{ stats.tiempo_en_institucion || 'N/A' }}</p>
              </div>
              <ClockIcon class="h-8 w-8 text-purple-400" />
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs Content -->
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
          <!-- Tab Navigation -->
          <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
              <button
                v-for="(tab, index) in tabs"
                :key="index"
                @click="activeTab = index"
                :class="[
                  'px-6 py-3 border-b-2 font-medium text-sm',
                  activeTab === index
                    ? 'border-primary-500 text-primary-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                ]"
              >
                <component :is="tab.icon" class="h-4 w-4 mr-2 inline" />
                {{ tab.name }}
              </button>
            </nav>
          </div>

          <!-- Tab Content -->
          <div class="p-6">
            <!-- Tab 1: Información Personal -->
            <div v-show="activeTab === 0" class="space-y-6">
              <h3 class="text-lg font-medium text-gray-900">Información Personal</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <InfoCard label="Nombres" :value="datosEstudiante.nombres" />
                <InfoCard label="Apellidos" :value="datosEstudiante.apellidos" />
                <InfoCard label="Tipo de Documento" :value="datosEstudiante.tipo_documento_texto" />
                <InfoCard label="Documento de Identidad" :value="datosEstudiante.documento_identidad" />
                <InfoCard label="Género" :value="datosEstudiante.genero" />
                <InfoCard label="Fecha de Nacimiento" :value="datosEstudiante.fecha_nacimiento_formato" />
                <InfoCard label="País de Nacimiento" :value="datosEstudiante.birth_country?.name" />
                <InfoCard label="Departamento de Nacimiento" :value="datosEstudiante.birth_state?.name" />
                <InfoCard label="Ciudad de Nacimiento" :value="datosEstudiante.birth_city?.name" />
              </div>
            </div>

            <!-- Tab 2: Información de Contacto -->
            <div v-show="activeTab === 1" class="space-y-6">
              <h3 class="text-lg font-medium text-gray-900">Información de Contacto</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <InfoCard label="Dirección" :value="datosEstudiante.direccion" />
                <InfoCard label="Teléfono" :value="datosEstudiante.telefono" />
                <InfoCard label="Email" :value="datosEstudiante.email" />
                
                <!-- Contacto de Emergencia -->
                <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                  <h4 class="font-medium text-gray-900 mb-3">Contacto de Emergencia</h4>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <InfoCard label="Nombre" :value="datosEstudiante.contacto_emergencia_nombre" />
                    <InfoCard label="Teléfono" :value="datosEstudiante.contacto_emergencia_telefono" />
                    <InfoCard label="Relación" :value="datosEstudiante.contacto_emergencia_relacion" />
                  </div>
                </div>
              </div>
            </div>

            <!-- Tab 3: Información de Padres -->
            <div v-show="activeTab === 2" class="space-y-6">
              <h3 class="text-lg font-medium text-gray-900">Información de Padres</h3>
              
              <!-- Padre -->
              <div v-if="datosEstudiante.padre_nombres" class="bg-blue-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-3">Información del Padre</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                  <InfoCard label="Nombres" :value="datosEstudiante.padre_nombres" />
                  <InfoCard label="Apellidos" :value="datosEstudiante.padre_apellidos" />
                  <InfoCard label="Documento" :value="datosEstudiante.padre_documento" />
                  <InfoCard label="Teléfono" :value="datosEstudiante.padre_telefono" />
                  <InfoCard label="Email" :value="datosEstudiante.padre_email" />
                  <InfoCard label="Ocupación" :value="datosEstudiante.padre_ocupacion" />
                </div>
              </div>

              <!-- Madre -->
              <div v-if="datosEstudiante.madre_nombres" class="bg-pink-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-3">Información de la Madre</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                  <InfoCard label="Nombres" :value="datosEstudiante.madre_nombres" />
                  <InfoCard label="Apellidos" :value="datosEstudiante.madre_apellidos" />
                  <InfoCard label="Documento" :value="datosEstudiante.madre_documento" />
                  <InfoCard label="Teléfono" :value="datosEstudiante.madre_telefono" />
                  <InfoCard label="Email" :value="datosEstudiante.madre_email" />
                  <InfoCard label="Ocupación" :value="datosEstudiante.madre_ocupacion" />
                </div>
              </div>

              <!-- Acudiente -->
              <div v-if="datosEstudiante.acudiente_nombres" class="bg-green-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-3">Acudiente Adicional</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                  <InfoCard label="Nombres" :value="datosEstudiante.acudiente_nombres" />
                  <InfoCard label="Apellidos" :value="datosEstudiante.acudiente_apellidos" />
                  <InfoCard label="Documento" :value="datosEstudiante.acudiente_documento" />
                  <InfoCard label="Parentesco" :value="datosEstudiante.acudiente_parentesco" />
                  <InfoCard label="Teléfono" :value="datosEstudiante.acudiente_telefono" />
                  <InfoCard label="Email" :value="datosEstudiante.acudiente_email" />
                </div>
              </div>
            </div>

            <!-- Tab 4: Información Académica -->
            <div v-show="activeTab === 3" class="space-y-6">
              <h3 class="text-lg font-medium text-gray-900">Información Académica</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <InfoCard label="Grado Actual" :value="datosEstudiante.grado?.nombre" />
                <InfoCard label="Fecha de Ingreso" :value="datosEstudiante.fecha_ingreso_formatted" />
                <InfoCard label="Estado Académico" :value="datosEstudiante.estado" />
                <InfoCard label="Estudiante Nuevo" :value="datosEstudiante.es_estudiante_nuevo ? 'Sí' : 'No'" />
                
                <div v-if="!datosEstudiante.es_estudiante_nuevo" class="md:col-span-2 bg-yellow-50 p-4 rounded-lg">
                  <h4 class="font-medium text-gray-900 mb-3">Antecedentes Académicos</h4>
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <InfoCard label="Colegio de Procedencia" :value="datosEstudiante.colegio_procedencia" />
                    <InfoCard label="Último Grado Cursado" :value="datosEstudiante.ultimo_grado_cursado" />
                    <InfoCard label="Año de Finalización" :value="datosEstudiante.ano_finalizacion" />
                  </div>
                </div>
                
                <div v-if="datosEstudiante.observaciones_academicas" class="md:col-span-2">
                  <InfoCard label="Observaciones Académicas" :value="datosEstudiante.observaciones_academicas" multiline />
                </div>
              </div>
            </div>

            <!-- Tab 5: Información Médica -->
            <div v-show="activeTab === 4" class="space-y-6">
              <h3 class="text-lg font-medium text-gray-900">Información Médica</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <InfoCard label="Tipo de Sangre" :value="datosEstudiante.tipo_sangre" />
                <InfoCard label="EPS" :value="datosEstudiante.eps" />
                <InfoCard label="Número de Afiliación EPS" :value="datosEstudiante.numero_afiliacion_eps" />
                
                <div v-if="datosEstudiante.alergias" class="md:col-span-2">
                  <InfoCard label="Alergias" :value="datosEstudiante.alergias" multiline />
                </div>
                
                <div v-if="datosEstudiante.medicamentos" class="md:col-span-2">
                  <InfoCard label="Medicamentos" :value="datosEstudiante.medicamentos" multiline />
                </div>
                
                <div v-if="datosEstudiante.condiciones_medicas" class="md:col-span-2">
                  <InfoCard label="Condiciones Médicas" :value="datosEstudiante.condiciones_medicas" multiline />
                </div>
                
                <div v-if="datosEstudiante.restricciones_fisicas" class="md:col-span-2">
                  <InfoCard label="Restricciones Físicas" :value="datosEstudiante.restricciones_fisicas" multiline />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Actions Footer -->
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
          <div class="flex justify-between items-center">
            <div class="flex space-x-3">
              <button
                @click="abrirModalCambioEstado('activo')"
                v-if="datosEstudiante.estado !== 'activo' && datosEstudiante.estado !== 'retirado'"
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
              >
                <CheckCircleIcon class="h-4 w-4 mr-2" />
                Activar
              </button>
              <button
                @click="abrirModalCambioEstado('inactivo')"
                v-if="datosEstudiante.estado !== 'inactivo' && datosEstudiante.estado !== 'retirado'"
                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
              >
                <PauseCircleIcon class="h-4 w-4 mr-2" />
                Inactivar
              </button>
              <button
                @click="abrirModalCambioEstado('retirado')"
                v-if="datosEstudiante.estado !== 'retirado'"
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
              >
                <XCircleIcon class="h-4 w-4 mr-2" />
                Retirar
              </button>
            </div>
            <div class="text-sm text-gray-500">
              Última actualización: {{ datosEstudiante.updated_at_formatted }}
            </div>
          </div>
        </div>
      </div>

      <!-- Modal de Cambio de Estado -->
      <CambioEstadoModal
        :open="modalCambioEstado.abierto"
        :estudiante="datosEstudiante"
        :nuevo-estado="modalCambioEstado.nuevoEstado"
        @cerrar="cerrarModalCambioEstado"
        @confirmado="onEstadoCambiado"
      />
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import InfoCard from '@/Components/InfoCard.vue'
import CambioEstadoModal from '@/Components/Estudiantes/CambioEstadoModal.vue'

// Icons
import {
  PencilIcon,
  ArrowLeftIcon,
  CheckCircleIcon,
  XCircleIcon,
  AcademicCapIcon,
  ClockIcon,
  PauseCircleIcon,
  UserIcon,
  PhoneIcon,
  HeartIcon,
  BookOpenIcon,
  BeakerIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  estudiante: Object,
  stats: Object
})

// Extraer datos del wrapper Resource
const datosEstudiante = computed(() => {
  // Si los datos vienen envueltos en 'data' (como EstudianteResource), extraerlos
  if (props.estudiante?.data) {
    return props.estudiante.data
  }
  // Si vienen directos, usarlos así
  return props.estudiante || {}
})

// Reactive data
const activeTab = ref(0)
const modalCambioEstado = ref({
  abierto: false,
  nuevoEstado: null
})

// Tabs configuration
const tabs = [
  { name: 'Personal', icon: UserIcon },
  { name: 'Contacto', icon: PhoneIcon },
  { name: 'Padres', icon: HeartIcon },
  { name: 'Académico', icon: BookOpenIcon },
  { name: 'Médico', icon: BeakerIcon }
]

// Methods
const abrirModalCambioEstado = (nuevoEstado) => {
  modalCambioEstado.value.nuevoEstado = nuevoEstado
  modalCambioEstado.value.abierto = true
}

const cerrarModalCambioEstado = () => {
  modalCambioEstado.value.abierto = false
  modalCambioEstado.value.nuevoEstado = null
}

const onEstadoCambiado = () => {
  cerrarModalCambioEstado()
  // El modal maneja la actualización automáticamente a través de Inertia
}

// Método legacy mantenido para compatibilidad
const updateStatus = (nuevoEstado) => {
  const mensaje = {
    'activo': '¿Activar este estudiante?',
    'inactivo': '¿Inactivar este estudiante?',
    'retirado': '¿Retirar este estudiante?'
  }[nuevoEstado]

  if (confirm(mensaje)) {
    router.patch(route('estudiantes.update-status', datosEstudiante.value.id), {
      estado: nuevoEstado,
      razon: prompt('Razón del cambio (opcional):') || null
    })
  }
}
</script>