<template>
  <AppLayout>
    <template #header>
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Estudiantes
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Gestiona la información de todos los estudiantes
          </p>
        </div>
        <div v-if="can.create" class="flex space-x-3">
          <Button
            variant="outline"
            @click="exportStudents"
            :disabled="students.data.length === 0"
          >
            <Icon name="download" class="w-4 h-4 mr-2" />
            Exportar
          </Button>
          <Button @click="router.visit(route('students.create'))">
            <Icon name="plus" class="w-4 h-4 mr-2" />
            Nuevo Estudiante
          </Button>
        </div>
      </div>
    </template>

    <!-- Filters -->
    <Card class="mb-6">
      <CardContent class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Search Input -->
          <div class="md:col-span-2">
            <div class="relative">
              <Icon name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
              <Input
                v-model="filters.search"
                placeholder="Buscar por nombre, código o documento..."
                class="pl-10"
                @input="debouncedSearch"
              />
            </div>
          </div>

          <!-- Grade Filter -->
          <div>
            <select
              v-model="filters.grade_id"
              @change="handleFilterChange"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Todos los grados</option>
              <option v-for="grade in grades" :key="grade.id" :value="grade.id">
                {{ grade.full_name }}
              </option>
            </select>
          </div>

          <!-- Status Filter -->
          <div>
            <select
              v-model="filters.status"
              @change="handleFilterChange"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Todos los estados</option>
              <option value="active">Activo</option>
              <option value="inactive">Inactivo</option>
              <option value="graduated">Graduado</option>
              <option value="transferred">Transferido</option>
            </select>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Students Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
      <StudentCard
        v-for="student in students.data"
        :key="student.id"
        :student="student"
        @view="viewStudent"
        @edit="editStudent"
        @delete="deleteStudent"
        :can-edit="canEditStudent(student)"
        :can-delete="canDeleteStudent(student)"
      />
    </div>

    <!-- Empty State -->
    <Card v-if="students.data.length === 0" class="text-center py-12">
      <CardContent>
        <Icon name="users" class="w-16 h-16 text-gray-400 mx-auto mb-4" />
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
          No se encontraron estudiantes
        </h3>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
          {{ hasFilters ? 'Intenta ajustar los filtros de búsqueda' : 'Comienza agregando tu primer estudiante' }}
        </p>
        <Button v-if="can.create && !hasFilters" @click="router.visit(route('students.create'))">
          <Icon name="plus" class="w-4 h-4 mr-2" />
          Agregar Estudiante
        </Button>
      </CardContent>
    </Card>

    <!-- Pagination -->
    <div v-if="students.data.length > 0" class="mt-6">
      <Pagination :links="students.links" />
    </div>

    <!-- Delete Confirmation Modal -->
    <DeleteConfirmationModal
      :show="showDeleteModal"
      :item-name="studentToDelete?.full_name"
      @confirm="confirmDelete"
      @cancel="cancelDelete"
    />
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { debounce } from 'lodash-es'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import Icon from '@/components/Icon.vue'
import StudentCard from '@/components/Academic/StudentCard.vue'
import Pagination from '@/components/ui/Pagination.vue'
import DeleteConfirmationModal from '@/components/ui/DeleteConfirmationModal.vue'

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

interface Grade {
  id: number
  full_name: string
}

interface Props {
  students: {
    data: Student[]
    links: any[]
  }
  grades: Grade[]
  filters: {
    search?: string
    grade_id?: string
    status?: string
  }
  can: {
    create: boolean
    export: boolean
  }
}

const props = defineProps<Props>()
const page = usePage()

const filters = ref({
  search: props.filters.search || '',
  grade_id: props.filters.grade_id || '',
  status: props.filters.status || ''
})

const showDeleteModal = ref(false)
const studentToDelete = ref<Student | null>(null)

const hasFilters = computed(() => 
  filters.value.search || filters.value.grade_id || filters.value.status
)

const debouncedSearch = debounce(() => {
  handleFilterChange()
}, 300)

const handleFilterChange = () => {
  router.get(route('students.index'), filters.value, {
    preserveState: true,
    preserveScroll: true,
  })
}

const viewStudent = (student: Student) => {
  router.visit(route('students.show', student.id))
}

const editStudent = (student: Student) => {
  router.visit(route('students.edit', student.id))
}

const deleteStudent = (student: Student) => {
  studentToDelete.value = student
  showDeleteModal.value = true
}

const confirmDelete = () => {
  if (studentToDelete.value) {
    router.delete(route('students.destroy', studentToDelete.value.id), {
      onSuccess: () => {
        showDeleteModal.value = false
        studentToDelete.value = null
      }
    })
  }
}

const cancelDelete = () => {
  showDeleteModal.value = false
  studentToDelete.value = null
}

const canEditStudent = (student: Student): boolean => {
  const user = page.props.auth.user
  if (user.roles?.some((role: any) => role.name === 'admin')) return true
  if (user.roles?.some((role: any) => role.name === 'teacher')) {
    // Check if teacher can manage this student
    return true // This would be properly implemented based on teacher-grade relationships
  }
  return false
}

const canDeleteStudent = (student: Student): boolean => {
  const user = page.props.auth.user
  return user.roles?.some((role: any) => role.name === 'admin') || false
}

const exportStudents = () => {
  router.get(route('students.export'), filters.value, {
    onSuccess: () => {
      // Handle export success
    }
  })
}

// Watch for route changes to update filters
watch(() => page.props.filters, (newFilters: any) => {
  filters.value = {
    search: newFilters.search || '',
    grade_id: newFilters.grade_id || '',
    status: newFilters.status || ''
  }
}, { deep: true })
</script>