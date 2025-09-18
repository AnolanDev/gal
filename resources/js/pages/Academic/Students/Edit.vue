<template>
  <AppLayout>
    <template #header>
      <div class="flex items-center space-x-4">
        <Button variant="ghost" size="sm" @click="router.visit(route('students.show', student.id))">
          <Icon name="arrow-left" class="w-4 h-4" />
        </Button>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Editar Estudiante
          </h1>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Actualiza la información de {{ student.full_name }}
          </p>
        </div>
      </div>
    </template>

    <form @submit.prevent="submit" class="space-y-6">
      <!-- Personal Information -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="user" class="w-5 h-5" />
            <span>Información Personal</span>
          </CardTitle>
        </CardHeader>
        <CardContent class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Identification -->
            <div>
              <Label for="identification_type">Tipo de Documento *</Label>
              <select
                id="identification_type"
                v-model="form.identification_type"
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.identification_type }"
              >
                <option value="TI">Tarjeta de Identidad</option>
                <option value="RC">Registro Civil</option>
                <option value="CC">Cédula de Ciudadanía</option>
                <option value="CE">Cédula de Extranjería</option>
                <option value="PP">Pasaporte</option>
              </select>
              <InputError :message="errors.identification_type" />
            </div>

            <div>
              <Label for="identification_number">Número de Documento *</Label>
              <Input
                id="identification_number"
                v-model="form.identification_number"
                type="text"
                placeholder="Ingresa el número de documento"
                :class="{ 'border-red-500': errors.identification_number }"
              />
              <InputError :message="errors.identification_number" />
            </div>

            <!-- Names -->
            <div>
              <Label for="first_name">Primer Nombre *</Label>
              <Input
                id="first_name"
                v-model="form.first_name"
                type="text"
                placeholder="Primer nombre"
                :class="{ 'border-red-500': errors.first_name }"
              />
              <InputError :message="errors.first_name" />
            </div>

            <div>
              <Label for="last_name">Apellidos *</Label>
              <Input
                id="last_name"
                v-model="form.last_name"
                type="text"
                placeholder="Apellidos completos"
                :class="{ 'border-red-500': errors.last_name }"
              />
              <InputError :message="errors.last_name" />
            </div>

            <!-- Birth Info -->
            <div>
              <Label for="birth_date">Fecha de Nacimiento *</Label>
              <Input
                id="birth_date"
                v-model="form.birth_date"
                type="date"
                :class="{ 'border-red-500': errors.birth_date }"
              />
              <InputError :message="errors.birth_date" />
            </div>

            <div>
              <Label for="gender">Género *</Label>
              <select
                id="gender"
                v-model="form.gender"
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.gender }"
              >
                <option value="">Seleccionar</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
                <option value="Other">Otro</option>
              </select>
              <InputError :message="errors.gender" />
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
        <CardContent class="space-y-4">
          <div>
            <Label for="address">Dirección *</Label>
            <textarea
              id="address"
              v-model="form.address"
              rows="3"
              placeholder="Dirección completa de residencia"
              class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.address }"
            ></textarea>
            <InputError :message="errors.address" />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <Label for="phone">Teléfono</Label>
              <Input
                id="phone"
                v-model="form.phone"
                type="tel"
                placeholder="Número de teléfono"
                :class="{ 'border-red-500': errors.phone }"
              />
              <InputError :message="errors.phone" />
            </div>

            <div>
              <Label for="emergency_contact">Contacto de Emergencia *</Label>
              <Input
                id="emergency_contact"
                v-model="form.emergency_contact"
                type="text"
                placeholder="Nombre del contacto de emergencia"
                :class="{ 'border-red-500': errors.emergency_contact }"
              />
              <InputError :message="errors.emergency_contact" />
            </div>

            <div class="md:col-span-2">
              <Label for="emergency_phone">Teléfono de Emergencia *</Label>
              <Input
                id="emergency_phone"
                v-model="form.emergency_phone"
                type="tel"
                placeholder="Número de emergencia"
                :class="{ 'border-red-500': errors.emergency_phone }"
              />
              <InputError :message="errors.emergency_phone" />
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
        <CardContent class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <Label for="blood_type">Tipo de Sangre</Label>
              <select
                id="blood_type"
                v-model="form.blood_type"
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.blood_type }"
              >
                <option value="">Seleccionar</option>
                <option value="A+">A+</option>
                <option value="A-">A-</option>
                <option value="B+">B+</option>
                <option value="B-">B-</option>
                <option value="AB+">AB+</option>
                <option value="AB-">AB-</option>
                <option value="O+">O+</option>
                <option value="O-">O-</option>
              </select>
              <InputError :message="errors.blood_type" />
            </div>
          </div>

          <div>
            <Label for="medical_conditions">Condiciones Médicas</Label>
            <textarea
              id="medical_conditions"
              v-model="medicalConditionsText"
              rows="3"
              placeholder="Alergias, condiciones médicas, medicamentos, etc. (Una por línea)"
              class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              :class="{ 'border-red-500': errors.medical_conditions }"
            ></textarea>
            <p class="text-xs text-gray-500 mt-1">Ingrese cada condición en una línea separada</p>
            <InputError :message="errors.medical_conditions" />
          </div>
        </CardContent>
      </Card>

      <!-- Academic Information -->
      <Card>
        <CardHeader>
          <CardTitle class="flex items-center space-x-2">
            <Icon name="graduation-cap" class="w-5 h-5" />
            <span>Información Académica</span>
          </CardTitle>
        </CardHeader>
        <CardContent class="space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <Label for="grade_id">Grado *</Label>
              <select
                id="grade_id"
                v-model="form.grade_id"
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.grade_id }"
              >
                <option value="">Seleccionar grado</option>
                <option 
                  v-for="grade in grades" 
                  :key="grade.id" 
                  :value="grade.id"
                  :disabled="!grade.available_spots && grade.id !== student.grade_id"
                >
                  {{ grade.full_name }} 
                  <span v-if="grade.id === student.grade_id">(Actual)</span>
                  <span v-else-if="!grade.available_spots">(Sin cupos)</span>
                  <span v-else>({{ grade.available_spots }} cupos disponibles)</span>
                </option>
              </select>
              <InputError :message="errors.grade_id" />
            </div>

            <div>
              <Label for="parent_user_id">Padre/Acudiente *</Label>
              <select
                id="parent_user_id"
                v-model="form.parent_user_id"
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.parent_user_id }"
              >
                <option value="">Seleccionar acudiente</option>
                <option v-for="parent in parents" :key="parent.id" :value="parent.id">
                  {{ parent.name }} ({{ parent.email }})
                </option>
              </select>
              <InputError :message="errors.parent_user_id" />
            </div>

            <div>
              <Label for="enrollment_date">Fecha de Matrícula *</Label>
              <Input
                id="enrollment_date"
                v-model="form.enrollment_date"
                type="date"
                :class="{ 'border-red-500': errors.enrollment_date }"
              />
              <InputError :message="errors.enrollment_date" />
            </div>

            <div>
              <Label for="status">Estado *</Label>
              <select
                id="status"
                v-model="form.status"
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :class="{ 'border-red-500': errors.status }"
              >
                <option value="active">Activo</option>
                <option value="inactive">Inactivo</option>
                <option value="graduated">Graduado</option>
                <option value="transferred">Transferido</option>
              </select>
              <InputError :message="errors.status" />
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Form Actions -->
      <div class="flex flex-col sm:flex-row sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3">
        <Button
          type="button"
          variant="outline"
          @click="router.visit(route('students.show', student.id))"
          :disabled="form.processing"
        >
          Cancelar
        </Button>
        <Button
          type="submit"
          :disabled="form.processing"
        >
          <Icon v-if="form.processing" name="loader-2" class="w-4 h-4 mr-2 animate-spin" />
          Guardar Cambios
        </Button>
      </div>
    </form>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import Icon from '@/components/Icon.vue'
import InputError from '@/components/InputError.vue'

interface Grade {
  id: number
  full_name: string
  available_spots: number
}

interface Parent {
  id: number
  name: string
  email: string
}

interface Student {
  id: number
  full_name: string
  code: string
  identification_type: string
  identification_number: string
  first_name: string
  last_name: string
  birth_date: string
  gender: string
  address: string
  phone: string | null
  emergency_contact: string
  emergency_phone: string
  blood_type: string | null
  medical_conditions: string[]
  grade_id: number
  parent_user_id: number
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
}

interface Props {
  student: Student
  grades: Grade[]
  parents: Parent[]
}

const props = defineProps<Props>()

const form = useForm({
  identification_type: '',
  identification_number: '',
  first_name: '',
  last_name: '',
  birth_date: '',
  gender: '',
  address: '',
  phone: '',
  emergency_contact: '',
  emergency_phone: '',
  blood_type: '',
  medical_conditions: [] as string[],
  grade_id: '',
  parent_user_id: '',
  enrollment_date: '',
  status: '',
})

const errors = computed(() => form.errors)

const medicalConditionsText = ref('')

// Initialize form with student data
onMounted(() => {
  form.identification_type = props.student.identification_type
  form.identification_number = props.student.identification_number
  form.first_name = props.student.first_name
  form.last_name = props.student.last_name
  form.birth_date = props.student.birth_date
  form.gender = props.student.gender
  form.address = props.student.address
  form.phone = props.student.phone || ''
  form.emergency_contact = props.student.emergency_contact
  form.emergency_phone = props.student.emergency_phone
  form.blood_type = props.student.blood_type || ''
  form.medical_conditions = props.student.medical_conditions || []
  form.grade_id = props.student.grade_id
  form.parent_user_id = props.student.parent_user_id
  form.enrollment_date = props.student.enrollment_date
  form.status = props.student.status

  // Set medical conditions text
  medicalConditionsText.value = props.student.medical_conditions?.join('\n') || ''
})

// Watch for changes in medical conditions text and convert to array
watch(medicalConditionsText, (newValue) => {
  form.medical_conditions = newValue
    .split('\n')
    .map(condition => condition.trim())
    .filter(condition => condition.length > 0)
})

const submit = () => {
  form.put(route('students.update', props.student.id), {
    onSuccess: () => {
      // Handle success - will be redirected by controller
    },
    onError: (errors) => {
      // Handle validation errors - they're automatically displayed
      console.error('Validation errors:', errors)
    }
  })
}
</script>