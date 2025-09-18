<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
            Mi Dashboard
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Bienvenido/a, {{ $page.props.auth.user.name }}
          </p>
        </div>
        <div class="relative">
          <Icon name="bell" class="w-6 h-6 text-gray-500" />
          <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
        </div>
      </div>
    </template>

    <!-- Quick Actions - Mobile First -->
    <div class="grid grid-cols-2 gap-4 mb-6">
      <QuickActionCard
        title="Asistencia"
        subtitle="Tomar hoy"
        icon="users"
        color="blue"
        @click="router.visit('/teacher/attendance')"
      />
      <QuickActionCard
        title="Notas"
        subtitle="Registrar"
        icon="clipboard"
        color="green"
        @click="router.visit('/teacher/grades')"
      />
      <QuickActionCard
        title="Observaciones"
        subtitle="3 pendientes"
        icon="message-circle"
        color="yellow"
        @click="router.visit('/teacher/observations')"
      />
      <QuickActionCard
        title="Horarios"
        subtitle="Ver clases"
        icon="calendar"
        color="purple"
        @click="router.visit('/teacher/schedule')"
      />
    </div>

    <!-- Today's Classes -->
    <Card class="mb-6">
      <CardHeader>
        <CardTitle class="text-lg">Clases de Hoy</CardTitle>
      </CardHeader>
      <CardContent>
        <div class="space-y-3">
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
              {{ class_item.status }}
            </Badge>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Recent Activity -->
    <Card>
      <CardHeader>
        <CardTitle class="text-lg">Actividad Reciente</CardTitle>
      </CardHeader>
      <CardContent>
        <div class="space-y-3">
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
      </CardContent>
    </Card>
  </AppLayout>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import Icon from '@/components/Icon.vue'
import QuickActionCard from '@/components/teacher/QuickActionCard.vue'

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

defineProps<{
  todayClasses: ClassItem[]
  recentActivity: Activity[]
}>()

const getStatusVariant = (status: string) => {
  switch (status) {
    case 'pending': return 'secondary'
    case 'in-progress': return 'default'
    case 'completed': return 'success'
    default: return 'secondary'
  }
}
</script>