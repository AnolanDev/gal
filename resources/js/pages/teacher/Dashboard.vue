<template>
  <AppLayout>
    <template #header>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
          Dashboard de Docentes
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Bienvenido/a, {{ $page.props.auth.user.name }}
        </p>
      </div>
    </template>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <Icon name="users" class="w-6 h-6 text-blue-600" />
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ myStudents }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Mis Estudiantes</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <Icon name="graduation-cap" class="w-6 h-6 text-green-600" />
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ myGrades }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Grados Asignados</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <Icon name="book" class="w-6 h-6 text-purple-600" />
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ mySubjects }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Materias</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <Icon name="calendar" class="w-6 h-6 text-orange-600" />
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ todayClasses.length }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Clases Hoy</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <Card class="hover:shadow-md transition-shadow cursor-pointer" @click="router.visit(route('teacher.my-students'))">
        <CardContent class="p-4">
          <div class="flex flex-col items-center text-center space-y-2">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <Icon name="users" class="w-6 h-6 text-blue-600" />
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">Asistencia</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Tomar hoy</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card class="hover:shadow-md transition-shadow cursor-pointer" @click="router.visit(route('teacher.my-grades'))">
        <CardContent class="p-4">
          <div class="flex flex-col items-center text-center space-y-2">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <Icon name="clipboard" class="w-6 h-6 text-green-600" />
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">Notas</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Registrar</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card class="hover:shadow-md transition-shadow cursor-pointer">
        <CardContent class="p-4">
          <div class="flex flex-col items-center text-center space-y-2">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
              <Icon name="message-circle" class="w-6 h-6 text-yellow-600" />
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">Observaciones</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">3 pendientes</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card class="hover:shadow-md transition-shadow cursor-pointer">
        <CardContent class="p-4">
          <div class="flex flex-col items-center text-center space-y-2">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <Icon name="calendar" class="w-6 h-6 text-purple-600" />
            </div>
            <div>
              <h3 class="font-medium text-gray-900 dark:text-white">Horarios</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Ver clases</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Today's Classes and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Today's Classes -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="calendar" class="w-5 h-5" />
            <span>Clases de Hoy</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="todayClasses.length > 0" class="space-y-3">
            <div 
              v-for="class_item in todayClasses" 
              :key="class_item.id"
              class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg"
            >
              <div>
                <p class="font-medium text-gray-900 dark:text-white">
                  {{ class_item.subject }} - {{ class_item.grade }}
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                  {{ class_item.time }} | Salón {{ class_item.classroom }}
                </p>
              </div>
              <Badge :variant="getStatusVariant(class_item.status)">
                {{ getStatusLabel(class_item.status) }}
              </Badge>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <Icon name="calendar" class="w-8 h-8 text-gray-400 mx-auto mb-2" />
            <p class="text-gray-600 dark:text-gray-400">No hay clases programadas para hoy</p>
          </div>
        </CardContent>
      </Card>

      <!-- Recent Activity -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="activity" class="w-5 h-5" />
            <span>Actividad Reciente</span>
          </CardTitle>
        </CardHeader>
        <CardContent>
          <div v-if="recentActivity.length > 0" class="space-y-3">
            <div 
              v-for="activity in recentActivity" 
              :key="activity.id"
              class="flex items-start space-x-3"
            >
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                  <Icon :name="activity.icon" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                </div>
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-900 dark:text-white">
                  {{ activity.description }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                  {{ activity.time }}
                </p>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <Icon name="activity" class="w-8 h-8 text-gray-400 mx-auto mb-2" />
            <p class="text-gray-600 dark:text-gray-400">No hay actividad reciente</p>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import Icon from '@/components/Icon.vue'

interface ClassItem {
  id: number
  subject: string
  grade: string
  time: string
  classroom: string
  status: 'pending' | 'in-progress' | 'completed'
}

interface Activity {
  id: number
  description: string
  time: string
  icon: string
}

interface Props {
  todayClasses: ClassItem[]
  recentActivity: Activity[]
  myStudents: number
  myGrades: number
  mySubjects: number
}

defineProps<Props>()

const getStatusVariant = (status: string) => {
  switch (status) {
    case 'pending': return 'secondary'
    case 'in-progress': return 'default'
    case 'completed': return 'success'
    default: return 'secondary'
  }
}

const getStatusLabel = (status: string) => {
  switch (status) {
    case 'pending': return 'Pendiente'
    case 'in-progress': return 'En curso'
    case 'completed': return 'Completada'
    default: return status
  }
}
</script>