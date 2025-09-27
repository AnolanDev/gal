<template>
  <div class="relative">
    <Dropdown align="right" width="64">
      <template #trigger>
        <button
          type="button"
          class="flex items-center gap-x-3 text-sm font-medium text-gray-900 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 rounded-lg p-1.5 transition-colors"
        >
          <!-- User info - visible on larger screens -->
          <div class="hidden sm:block text-right">
            <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
            <div class="text-xs text-gray-500 capitalize">{{ user.role }}</div>
          </div>
          
          <!-- Avatar -->
          <div class="relative">
            <div class="h-8 w-8 rounded-full bg-primary-100 border-2 border-primary-200 flex items-center justify-center">
              <span class="text-sm font-medium text-primary-800">
                {{ userInitials }}
              </span>
            </div>
            
            <!-- Online indicator -->
            <div class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full bg-green-400 border-2 border-white"></div>
          </div>
          
          <!-- Chevron down -->
          <ChevronDownIcon class="h-4 w-4 text-gray-400" aria-hidden="true" />
        </button>
      </template>

      <template #content>
        <div class="py-1">
          <!-- User info header - visible on mobile -->
          <div class="px-4 py-3 border-b border-gray-100 sm:hidden">
            <div class="flex items-center gap-x-3">
              <div class="h-10 w-10 rounded-full bg-primary-100 border-2 border-primary-200 flex items-center justify-center">
                <span class="text-sm font-medium text-primary-800">
                  {{ userInitials }}
                </span>
              </div>
              <div>
                <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                <div class="text-xs text-gray-500">{{ user.email }}</div>
                <div class="text-xs text-primary-600 capitalize font-medium">{{ user.role }}</div>
              </div>
            </div>
          </div>
          
          <!-- Menu items -->
          <div class="py-1">
            <DropdownLink :href="route('profile.edit')" class="flex items-center gap-x-3">
              <UserCircleIcon class="h-4 w-4 text-gray-400" />
              <span>Mi Perfil</span>
            </DropdownLink>
            
            <!-- Settings (future feature) -->
            <!-- <DropdownLink href="#" class="flex items-center gap-x-3">
              <CogIcon class="h-4 w-4 text-gray-400" />
              <span>Configuración</span>
            </DropdownLink> -->
            
            <!-- Help (future feature) -->
            <!-- <DropdownLink href="#" class="flex items-center gap-x-3">
              <QuestionMarkCircleIcon class="h-4 w-4 text-gray-400" />
              <span>Ayuda</span>
            </DropdownLink> -->
          </div>
          
          <!-- Logout -->
          <div class="border-t border-gray-100 py-1">
            <DropdownLink 
              :href="route('logout')" 
              method="post" 
              as="button"
              class="flex items-center gap-x-3 text-red-700 hover:bg-red-50"
            >
              <ArrowRightOnRectangleIcon class="h-4 w-4 text-red-500" />
              <span>Cerrar Sesión</span>
            </DropdownLink>
          </div>
        </div>
      </template>
    </Dropdown>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { 
  ChevronDownIcon,
  UserCircleIcon,
  ArrowRightOnRectangleIcon
} from '@heroicons/vue/24/outline'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import { useNavigation } from '@/Composables/useNavigation.js'

const { user } = useNavigation()

// Computed para obtener iniciales del usuario
const userInitials = computed(() => {
  if (!user.value?.name) return '??'
  
  const names = user.value.name.trim().split(' ')
  if (names.length === 1) {
    return names[0].charAt(0).toUpperCase()
  }
  
  return names[0].charAt(0).toUpperCase() + names[names.length - 1].charAt(0).toUpperCase()
})
</script>