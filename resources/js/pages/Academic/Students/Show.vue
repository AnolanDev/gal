<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <Button variant="ghost" size="sm" @click="router.visit(route('students.index'))">
            <Icon name="arrow-left" class="w-4 h-4" />
          </Button>
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
              {{ student.full_name }}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              {{ student.grade.full_name }} • Código: {{ student.code }}
            </p>
          </div>
        </div>
        
        <div class="flex items-center space-x-2">
          <Badge :variant="getStatusVariant(student.status)">
            {{ getStatusLabel(student.status) }}
          </Badge>
          <Button
            v-if="can.edit"
            variant="outline"
            size="sm"
            @click="router.visit(route('students.edit', student.id))"
          >
            <Icon name="edit" class="w-4 h-4 mr-2" />
            Editar
          </Button>
          <Button
            v-if="can.delete"
            variant="destructive"
            size="sm"
            @click="showDeleteModal = true"
          >
            <Icon name="trash" class="w-4 h-4" />
          </Button>
        </div>
      </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Information -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Personal Information -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center space-x-2">
              <Icon name="user" class="w-5 h-5" />
              <span>Información Personal</span>
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <div>
                  <Label class="text-sm font-medium text-gray-500">Nombre Completo</Label>
                  <p class="text-gray-900 dark:text-white">{{ student.full_name }}</p>
                </div>
                <div>
                  <Label class="text-sm font-medium text-gray-500">Documento</Label>
                  <p class="text-gray-900 dark:text-white">
                    {{ student.identification_type }}: {{ student.identification_number }}
                  </p>
                </div>
                <div>
                  <Label class="text-sm font-medium text-gray-500">Fecha de Nacimiento</Label>
                  <p class="text-gray-900 dark:text-white">
                    {{ formatDate(student.birth_date) }} ({{ student.age }} años)
                  </p>
                </div>
              </div>
              <div class="space-y-4">
                <div>
                  <Label class="text-sm font-medium text-gray-500">Género</Label>
                  <p class="text-gray-900 dark:text-white">{{ getGenderLabel(student.gender) }}</p>
                </div>
                <div>
                  <Label class="text-sm font-medium text-gray-500">Fecha de Matrícula</Label>
                  <p class="text-gray-900 dark:text-white">{{ formatDate(student.enrollment_date) }}</p>
                </div>
                <div>
                  <Label class="text-sm font-medium text-gray-500">Estado</Label>
                  <Badge :variant="getStatusVariant(student.status)">
                    {{ getStatusLabel(student.status) }}
                  </Badge>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Contact Information -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center space-x-2">
              <Icon name="phone" class="w-5 h-5" />
              <span>Información de Contacto</span>
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <div>
                  <Label class="text-sm font-medium text-gray-500">Dirección</Label>
                  <p class="text-gray-900 dark:text-white">{{ student.address }}</p>
                </div>
                <div v-if="student.phone">
                  <Label class="text-sm font-medium text-gray-500">Teléfono</Label>
                  <p class="text-gray-900 dark:text-white">{{ student.phone }}</p>
                </div>
              </div>
              <div class="space-y-4">
                <div>
                  <Label class="text-sm font-medium text-gray-500">Contacto de Emergencia</Label>
                  <p class="text-gray-900 dark:text-white">{{ student.emergency_contact }}</p>
                </div>
                <div>
                  <Label class="text-sm font-medium text-gray-500">Teléfono de Emergencia</Label>
                  <p class="text-gray-900 dark:text-white">{{ student.emergency_phone }}</p>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Medical Information -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center space-x-2">
              <Icon name="heart" class="w-5 h-5" />
              <span>Información Médica</span>
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div v-if="student.blood_type">
                <Label class="text-sm font-medium text-gray-500">Tipo de Sangre</Label>
                <p class="text-gray-900 dark:text-white">{{ student.blood_type }}</p>
              </div>
              <div v-if="student.medical_conditions && student.medical_conditions.length > 0" class="md:col-span-2">
                <Label class="text-sm font-medium text-gray-500">Condiciones Médicas</Label>
                <ul class="mt-1 space-y-1">
                  <li 
                    v-for="condition in student.medical_conditions" 
                    :key="condition"
                    class="text-gray-900 dark:text-white flex items-center"
                  >
                    <Icon name="dot" class="w-4 h-4 mr-1" />
                    {{ condition }}
                  </li>
                </ul>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Recent Grades -->
        <Card v-if="recentGrades && recentGrades.length > 0">
          <CardHeader>
            <CardTitle class="flex items-center space-x-2">
              <Icon name="bar-chart-3" class="w-5 h-5" />
              <span>Calificaciones Recientes</span>
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="space-y-3">
              <div 
                v-for="grade in recentGrades" 
                :key="grade.id"
                class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
              >
                <div>
                  <p class="font-medium text-gray-900 dark:text-white">{{ grade.subject.name }}</p>
                  <p class="text-sm text-gray-500">{{ formatDate(grade.created_at) }}</p>
                </div>
                <div class="text-right">
                  <p class="text-lg font-bold" :class="getGradeColor(grade.score)">
                    {{ grade.score }}
                  </p>
                  <p class="text-sm text-gray-500">{{ grade.type }}</p>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Academic Summary -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center space-x-2">
              <Icon name="graduation-cap" class="w-5 h-5" />
              <span>Resumen Académico</span>
            </CardTitle>
          </CardHeader>
          <CardContent class="space-y-4">
            <div>
              <Label class="text-sm font-medium text-gray-500">Grado Actual</Label>
              <p class="text-gray-900 dark:text-white">{{ student.grade.full_name }}</p>
            </div>
            <div>
              <Label class="text-sm font-medium text-gray-500">Acudiente</Label>
              <p class="text-gray-900 dark:text-white">{{ student.parent.name }}</p>
              <p class="text-sm text-gray-500">{{ student.parent.email }}</p>
            </div>
            <div>
              <Label class="text-sm font-medium text-gray-500">Asistencia</Label>
              <div class="flex items-center space-x-2">
                <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                  <div 
                    class="h-2 rounded-full transition-all duration-300"
                    :class="getAttendanceColor(attendanceRate)"
                    :style="{ width: `${attendanceRate}%` }"
                  ></div>
                </div>
                <span class="text-sm font-medium">{{ attendanceRate }}%</span>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Recent Activity -->
        <Card v-if="student.observations && student.observations.length > 0">
          <CardHeader>
            <CardTitle class="flex items-center space-x-2">
              <Icon name="message-circle" class="w-5 h-5" />
              <span>Observaciones Recientes</span>
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div class="space-y-3">
              <div 
                v-for="observation in student.observations" 
                :key="observation.id"
                class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
              >
                <p class="text-sm text-gray-900 dark:text-white">{{ observation.content }}</p>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                  <span>{{ observation.teacher.name }}</span>
                  <span>{{ formatDate(observation.created_at) }}</span>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Quick Actions -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center space-x-2">
              <Icon name="zap" class="w-5 h-5" />
              <span>Acciones Rápidas</span>
            </CardTitle>
          </CardHeader>
          <CardContent class="space-y-2">
            <Button variant="outline" size="sm" class="w-full justify-start">
              <Icon name="plus" class="w-4 h-4 mr-2" />
              Agregar Observación
            </Button>
            <Button variant="outline" size="sm" class="w-full justify-start">
              <Icon name="file-text" class="w-4 h-4 mr-2" />
              Generar Reporte
            </Button>
            <Button variant="outline" size="sm" class="w-full justify-start">
              <Icon name="mail" class="w-4 h-4 mr-2" />
              Contactar Acudiente
            </Button>
          </CardContent>
        </Card>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <DeleteConfirmationModal
      :show="showDeleteModal"
      :item-name="student.full_name"
      :loading="deleteForm.processing"
      @confirm="deleteStudent"
      @cancel="showDeleteModal = false"
    />
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Label } from '@/components/ui/label'
import Icon from '@/components/Icon.vue'
import DeleteConfirmationModal from '@/components/ui/DeleteConfirmationModal.vue'

interface Student {
  id: number
  code: string
  full_name: string
  first_name: string
  last_name: string
  identification_type: string
  identification_number: string
  birth_date: string
  age: number
  gender: string
  address: string
  phone: string | null
  emergency_contact: string
  emergency_phone: string
  blood_type: string | null
  medical_conditions: string[] | null
  enrollment_date: string
  status: string
  grade: {
    id: number
    full_name: string
  }
  parent: {
    id: number
    name: string
    email: string
  }
  attendances?: any[]
  gradeReports?: any[]
  observations?: Array<{
    id: number
    content: string
    created_at: string
    teacher: {
      name: string
    }
  }>
}

interface Props {
  student: Student
  recentGrades?: Array<{
    id: number
    score: number
    type: string
    created_at: string
    subject: {
      name: string
    }
  }>
  attendanceRate: number
  can: {
    edit: boolean
    delete: boolean
  }
}

const props = defineProps<Props>()

const showDeleteModal = ref(false)

const deleteForm = useForm({})

const deleteStudent = () => {
  deleteForm.delete(route('students.destroy', props.student.id), {
    onSuccess: () => {
      showDeleteModal.value = false
    },
    onError: () => {
      // Handle error
    }
  })
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('es-CO', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getStatusVariant = (status: string) => {
  switch (status) {
    case 'active': return 'success'
    case 'inactive': return 'secondary'
    case 'graduated': return 'default'
    case 'transferred': return 'destructive'
    default: return 'secondary'
  }
}

const getStatusLabel = (status: string) => {
  switch (status) {
    case 'active': return 'Activo'
    case 'inactive': return 'Inactivo'
    case 'graduated': return 'Graduado'
    case 'transferred': return 'Transferido'
    default: return status
  }
}

const getGenderLabel = (gender: string) => {
  switch (gender) {
    case 'M': return 'Masculino'
    case 'F': return 'Femenino'
    case 'Other': return 'Otro'
    default: return gender
  }
}

const getAttendanceColor = (rate: number) => {
  if (rate >= 90) return 'bg-green-500'
  if (rate >= 80) return 'bg-yellow-500'
  if (rate >= 70) return 'bg-orange-500'
  return 'bg-red-500'
}

const getGradeColor = (score: number) => {
  if (score >= 4.0) return 'text-green-600'
  if (score >= 3.0) return 'text-yellow-600'
  if (score >= 2.0) return 'text-orange-600'
  return 'text-red-600'
}
</script>