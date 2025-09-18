<template>
  <Card class="hover:shadow-md transition-shadow duration-200">
    <CardContent class="p-4">
      <!-- Student Photo and Basic Info -->
      <div class="flex items-start space-x-4 mb-4">
        <div class="flex-shrink-0">
          <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-semibold">
            <img 
              v-if="student.photo_path" 
              :src="student.photo_path" 
              :alt="student.full_name"
              class="w-12 h-12 rounded-full object-cover"
            />
            <span v-else class="text-sm">
              {{ student.full_name.charAt(0) }}
            </span>
          </div>
        </div>
        
        <div class="flex-1 min-w-0">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
            {{ student.full_name }}
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ student.grade.full_name }}
          </p>
          <p class="text-xs text-gray-500 dark:text-gray-500">
            Código: {{ student.code }}
          </p>
        </div>

        <!-- Status Badge -->
        <Badge :variant="getStatusVariant(student.status)">
          {{ getStatusLabel(student.status) }}
        </Badge>
      </div>

      <!-- Student Details -->
      <div class="space-y-2 mb-4">
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
          <Icon name="id-card" class="w-4 h-4 mr-2" />
          <span>{{ student.identification_number }}</span>
        </div>
        
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
          <Icon name="calendar" class="w-4 h-4 mr-2" />
          <span>{{ student.age }} años</span>
        </div>
        
        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
          <Icon name="user" class="w-4 h-4 mr-2" />
          <span class="truncate">{{ student.parent.name }}</span>
        </div>
      </div>

      <!-- Progress Indicators -->
      <div class="mb-4">
        <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
          <span>Asistencia</span>
          <span>{{ student.attendance_rate }}%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
          <div 
            class="h-2 rounded-full transition-all duration-300"
            :class="getAttendanceColor(student.attendance_rate)"
            :style="{ width: `${student.attendance_rate}%` }"
          ></div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex space-x-2">
        <Button
          variant="outline"
          size="sm"
          @click="$emit('view', student)"
          class="flex-1"
        >
          <Icon name="eye" class="w-4 h-4 mr-1" />
          Ver
        </Button>
        
        <Button
          v-if="canEdit"
          variant="outline"
          size="sm"
          @click="$emit('edit', student)"
          class="flex-1"
        >
          <Icon name="edit" class="w-4 h-4 mr-1" />
          Editar
        </Button>
        
        <Button
          v-if="canDelete"
          variant="destructive"
          size="sm"
          @click="$emit('delete', student)"
        >
          <Icon name="trash" class="w-4 h-4" />
        </Button>
      </div>
    </CardContent>
  </Card>
</template>

<script setup lang="ts">
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import Icon from '@/components/Icon.vue'

interface Student {
  id: number
  code: string
  full_name: string
  identification_number: string
  age: number
  grade: {
    id: number
    full_name: string
  }
  parent: {
    name: string
    email: string
  }
  status: string
  photo_path?: string
  attendance_rate: number
}

interface Props {
  student: Student
  canEdit?: boolean
  canDelete?: boolean
}

defineProps<Props>()

defineEmits<{
  view: [student: Student]
  edit: [student: Student]
  delete: [student: Student]
}>()

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

const getAttendanceColor = (rate: number) => {
  if (rate >= 90) return 'bg-green-500'
  if (rate >= 80) return 'bg-yellow-500'
  if (rate >= 70) return 'bg-orange-500'
  return 'bg-red-500'
}
</script>