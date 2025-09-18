<template>
  <AppLayout>
    <template #header>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
          Dashboard de Padres
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Seguimiento académico de sus hijos
        </p>
      </div>
    </template>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <Icon name="users" class="w-6 h-6 text-blue-600" />
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ students.length }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Hijos Registrados</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <Icon name="bar-chart-3" class="w-6 h-6 text-green-600" />
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ recentGrades.length }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Calificaciones Recientes</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <Icon name="bell" class="w-6 h-6 text-orange-600" />
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ pendingTasks }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Tareas Pendientes</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Students Overview -->
    <div v-if="students.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="users" class="w-5 h-5" />
            <span>Mis Hijos</span>
          </CardTitle>
        </CardHeader>
        <CardContent class="space-y-4">
          <div 
            v-for="student in students" 
            :key="student.id"
            class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            @click="router.visit(route('students.show', student.id))"
          >
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                {{ student.full_name.charAt(0) }}
              </div>
              <div>
                <h4 class="font-medium text-gray-900 dark:text-white">{{ student.full_name }}</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ student.grade.full_name }}</p>
              </div>
            </div>
            <Icon name="chevron-right" class="w-5 h-5 text-gray-400" />
          </div>
        </CardContent>
      </Card>

      <!-- Recent Grades -->
      <Card v-if="recentGrades.length > 0">
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="bar-chart-3" class="w-5 h-5" />
            <span>Calificaciones Recientes</span>
          </CardTitle>
        </CardHeader>
        <CardContent class="space-y-3">
          <div 
            v-for="grade in recentGrades.slice(0, 5)" 
            :key="grade.id"
            class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
          >
            <div>
              <p class="font-medium text-gray-900 dark:text-white">{{ grade.subject.name }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ grade.student.full_name }}</p>
              <p class="text-xs text-gray-500">{{ formatDate(grade.created_at) }}</p>
            </div>
            <div class="text-right">
              <p class="text-lg font-bold" :class="getGradeColor(grade.score)">
                {{ grade.score }}
              </p>
              <p class="text-xs text-gray-500">{{ grade.type }}</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- No Students Message -->
    <div v-else class="text-center py-12">
      <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <Icon name="users" class="w-8 h-8 text-gray-400" />
      </div>
      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
        No hay estudiantes registrados
      </h3>
      <p class="text-gray-600 dark:text-gray-400">
        Contacte con la administración para registrar a sus hijos en el sistema.
      </p>
    </div>

    <!-- Communications and Tasks -->
    <div v-if="students.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Communications -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="mail" class="w-5 h-5" />
            <span>Comunicaciones Recientes</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="recentCommunications.length > 0" class="space-y-3">
            <div 
              v-for="communication in recentCommunications" 
              :key="communication.id"
              class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg"
            >
              <h4 class="font-medium text-gray-900 dark:text-white">{{ communication.title }}</h4>
              <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ communication.excerpt }}</p>
              <div class="flex items-center justify-between mt-2 text-xs text-gray-500">
                <span>{{ communication.sender }}</span>
                <span>{{ communication.date }}</span>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <Icon name="mail" class="w-8 h-8 text-gray-400 mx-auto mb-2" />
            <p class="text-gray-600 dark:text-gray-400">No hay comunicaciones recientes</p>
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
        <CardContent class="space-y-3">
          <Button variant="outline" class="w-full justify-start">
            <Icon name="calendar" class="w-4 h-4 mr-2" />
            Ver Horarios de Clases
          </Button>
          <Button variant="outline" class="w-full justify-start">
            <Icon name="file-text" class="w-4 h-4 mr-2" />
            Solicitar Certificados
          </Button>
          <Button variant="outline" class="w-full justify-start">
            <Icon name="phone" class="w-4 h-4 mr-2" />
            Contactar Docentes
          </Button>
          <Button variant="outline" class="w-full justify-start">
            <Icon name="calendar-check" class="w-4 h-4 mr-2" />
            Agendar Cita
          </Button>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'

interface Student {
  id: number
  full_name: string
  grade: {
    id: number
    full_name: string
  }
}

interface Grade {
  id: number
  score: number
  type: string
  created_at: string
  subject: {
    name: string
  }
  student: {
    full_name: string
  }
}

interface Communication {
  id: number
  title: string
  excerpt: string
  date: string
  sender: string
}

interface Props {
  students: Student[]
  recentGrades: Grade[]
  recentCommunications: Communication[]
  pendingTasks: number
}

defineProps<Props>()

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('es-CO', {
    month: 'short',
    day: 'numeric'
  })
}

const getGradeColor = (score: number) => {
  if (score >= 4.0) return 'text-green-600'
  if (score >= 3.0) return 'text-yellow-600'
  if (score >= 2.0) return 'text-orange-600'
  return 'text-red-600'
}
</script>