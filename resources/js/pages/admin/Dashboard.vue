<template>
  <AppLayout>
    <template #header>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
          Dashboard Administrativo
        </h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Panel de control principal del sistema
        </p>
      </div>
    </template>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <Icon name="users" class="w-6 h-6 text-blue-600" />
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.students }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Estudiantes Activos</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <Icon name="user-check" class="w-6 h-6 text-green-600" />
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.teachers }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Docentes</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <Icon name="graduation-cap" class="w-6 h-6 text-purple-600" />
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.grades }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Grados</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <Icon name="heart" class="w-6 h-6 text-orange-600" />
              </div>
            </div>
            <div class="ml-4">
              <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.parents }}</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Padres de Familia</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="zap" class="w-5 h-5" />
            <span>Acciones Rápidas</span>
          </CardTitle>
        </CardHeader>
        <CardContent class="space-y-3">
          <Button 
            variant="outline" 
            class="w-full justify-start"
            @click="router.visit(route('students.create'))"
          >
            <Icon name="user-plus" class="w-4 h-4 mr-2" />
            Registrar Nuevo Estudiante
          </Button>
          <Button 
            variant="outline" 
            class="w-full justify-start"
            @click="router.visit(route('teachers.create'))"
          >
            <Icon name="user-check" class="w-4 h-4 mr-2" />
            Agregar Nuevo Docente
          </Button>
          <Button 
            variant="outline" 
            class="w-full justify-start"
            @click="router.visit(route('grades.create'))"
          >
            <Icon name="school" class="w-4 h-4 mr-2" />
            Crear Nuevo Grado
          </Button>
          <Button 
            variant="outline" 
            class="w-full justify-start"
            @click="router.visit(route('subjects.create'))"
          >
            <Icon name="book" class="w-4 h-4 mr-2" />
            Añadir Nueva Materia
          </Button>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="bar-chart-3" class="w-5 h-5" />
            <span>Resumen del Sistema</span>
          </CardTitle>
        </CardHeader>
        <CardContent class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Asistencia Promedio</span>
            <span class="font-semibold text-green-600">92%</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Estudiantes por Docente</span>
            <span class="font-semibold">14.2</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Promedio Académico</span>
            <span class="font-semibold text-blue-600">4.1</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Ocupación de Aulas</span>
            <span class="font-semibold text-orange-600">85%</span>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Navigation Sections -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <Card class="hover:shadow-md transition-shadow cursor-pointer" @click="router.visit(route('students.index'))">
        <CardContent class="p-6">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
              <Icon name="users" class="w-6 h-6 text-blue-600" />
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Estudiantes</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Gestionar registro estudiantil</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card class="hover:shadow-md transition-shadow cursor-pointer" @click="router.visit(route('teachers.index'))">
        <CardContent class="p-6">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
              <Icon name="user-check" class="w-6 h-6 text-green-600" />
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Docentes</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Administrar personal docente</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card class="hover:shadow-md transition-shadow cursor-pointer" @click="router.visit(route('grades.index'))">
        <CardContent class="p-6">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
              <Icon name="graduation-cap" class="w-6 h-6 text-purple-600" />
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grados</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Configurar niveles académicos</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card class="hover:shadow-md transition-shadow cursor-pointer" @click="router.visit(route('subjects.index'))">
        <CardContent class="p-6">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
              <Icon name="book" class="w-6 h-6 text-orange-600" />
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Materias</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Gestionar asignaturas</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card class="hover:shadow-md transition-shadow cursor-pointer">
        <CardContent class="p-6">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
              <Icon name="bar-chart-3" class="w-6 h-6 text-red-600" />
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reportes</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Informes y estadísticas</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card class="hover:shadow-md transition-shadow cursor-pointer">
        <CardContent class="p-6">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
              <Icon name="settings" class="w-6 h-6 text-gray-600" />
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Configuración</h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">Ajustes del sistema</p>
            </div>
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
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'

interface Stats {
  students: number
  teachers: number
  grades: number
  parents: number
  subjects: number
}

interface Student {
  id: number
  full_name: string
  code: string
  grade: {
    full_name: string
  }
  parent: {
    name: string
  }
}

interface Activity {
  id: number
  description: string
  time: string
  icon: string
  type: string
}

interface Props {
  stats: Stats
  recentStudents: Student[]
  recentActivity: Activity[]
}

defineProps<Props>()
</script>