<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
            Panel de Padres
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Seguimiento académico
          </p>
        </div>
        <Button variant="ghost" size="sm">
          <Icon name="settings" class="w-5 h-5" />
        </Button>
      </div>
    </template>

    <!-- Student Selector (if multiple children) -->
    <Card class="mb-6" v-if="students.length > 1">
      <CardContent class="p-4">
        <div class="flex items-center space-x-3">
          <Icon name="user" class="w-5 h-5 text-gray-500" />
          <select 
            v-model="selectedStudent" 
            class="flex-1 bg-transparent border-none text-lg font-medium focus:ring-0"
          >
            <option v-for="student in students" :key="student.id" :value="student.id">
              {{ student.name }}
            </option>
          </select>
        </div>
      </CardContent>
    </Card>

    <!-- Student Info Card -->
    <Card class="mb-6">
      <CardContent class="p-4">
        <div class="flex items-center space-x-4">
          <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
            <span class="text-white text-xl font-bold">
              {{ currentStudent.name.charAt(0) }}
            </span>
          </div>
          <div class="flex-1">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ currentStudent.name }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              {{ currentStudent.grade }} | Código: {{ currentStudent.code }}
            </p>
            <div class="flex items-center mt-1 space-x-4">
              <div class="flex items-center space-x-1">
                <Icon name="calendar" class="w-4 h-4 text-green-500" />
                <span class="text-xs text-green-600 dark:text-green-400">
                  {{ currentStudent.attendance_rate }}% asistencia
                </span>
              </div>
              <div class="flex items-center space-x-1">
                <Icon name="star" class="w-4 h-4 text-yellow-500" />
                <span class="text-xs text-yellow-600 dark:text-yellow-400">
                  Promedio: {{ currentStudent.average_grade }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4 mb-6">
      <StatCard
        title="Asistencia"
        :value="currentStudent.attendance_rate + '%'"
        subtitle="Este mes"
        icon="calendar"
        color="green"
      />
      <StatCard
        title="Tareas"
        :value="pendingTasks.toString()"
        subtitle="Pendientes"
        icon="clipboard"
        color="yellow"
      />
    </div>

    <!-- Recent Grades -->
    <Card class="mb-6">
      <CardHeader>
        <CardTitle class="text-base flex items-center space-x-2">
          <Icon name="trophy" class="w-5 h-5 text-yellow-500" />
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
              <p class="font-medium text-gray-900 dark:text-white text-sm">
                {{ grade.subject }}
              </p>
              <p class="text-xs text-gray-600 dark:text-gray-400">
                {{ grade.evaluation_type }} | {{ grade.date }}
              </p>
            </div>
            <div class="text-right">
              <Badge :variant="getGradeVariant(grade.score)">
                {{ grade.score }}
              </Badge>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                {{ grade.observation }}
              </p>
            </div>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Recent Communications -->
    <Card class="mb-6">
      <CardHeader>
        <CardTitle class="text-base flex items-center space-x-2">
          <Icon name="bell" class="w-5 h-5 text-blue-500" />
          <span>Comunicados</span>
        </CardTitle>
      </CardHeader>
      <CardContent>
        <div class="space-y-3">
          <div 
            v-for="communication in recentCommunications" 
            :key="communication.id"
            class="border-l-4 border-blue-400 pl-4 py-2"
          >
            <h4 class="font-medium text-gray-900 dark:text-white text-sm">
              {{ communication.title }}
            </h4>
            <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">
              {{ communication.date }} | {{ communication.sender }}
            </p>
            <p class="text-sm text-gray-700 dark:text-gray-300">
              {{ communication.excerpt }}
            </p>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-4">
      <Button 
        variant="outline" 
        class="flex flex-col items-center p-4 h-auto space-y-2"
        @click="router.visit('/parent/payments')"
      >
        <Icon name="credit-card" class="w-6 h-6" />
        <span class="text-sm">Ver Pagos</span>
      </Button>
      <Button 
        variant="outline" 
        class="flex flex-col items-center p-4 h-auto space-y-2"
        @click="router.visit('/parent/reports')"
      >
        <Icon name="download" class="w-6 h-6" />
        <span class="text-sm">Descargar Boletín</span>
      </Button>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'
import StatCard from '@/components/parent/StatCard.vue'

interface Student {
  id: number
  name: string
  grade: string
  code: string
  attendance_rate: number
  average_grade: string
}

interface Grade {
  id: number
  subject: string
  evaluation_type: string
  score: string
  date: string
  observation: string
}

interface Communication {
  id: number
  title: string
  excerpt: string
  date: string
  sender: string
}

const props = defineProps<{
  students: Student[]
  recentGrades: Grade[]
  recentCommunications: Communication[]
  pendingTasks: number
}>()

const selectedStudent = ref(props.students[0]?.id)

const currentStudent = computed(() => 
  props.students.find(s => s.id === selectedStudent.value) || props.students[0]
)

const getGradeVariant = (score: string) => {
  const numScore = parseFloat(score)
  if (numScore >= 4.5) return 'success'
  if (numScore >= 3.5) return 'default'
  return 'destructive'
}
</script>