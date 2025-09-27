<template>
  <header class="sticky top-0 z-50 flex h-16 shrink-0 items-center border-b border-gray-200 bg-white px-4 shadow-sm sm:px-6 lg:px-8">
    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
      
      <!-- Mobile menu button -->
      <button
        v-if="isMobile"
        type="button"
        class="-m-2.5 p-2.5 text-gray-700 lg:hidden"
        @click="toggleSidebar"
      >
        <span class="sr-only">Abrir menú</span>
        <Bars3Icon class="h-6 w-6" aria-hidden="true" />
      </button>

      <!-- Logo section -->
      <div class="flex lg:gap-x-6">
        <div class="flex lg:shrink-0">
          <LogoComponent 
            :link="route('dashboard')"
            size="normal"
            :show-text="true"
            aria-label="Ir al dashboard principal de GAL"
          />
        </div>
      </div>

      <!-- Right section -->
      <div class="flex flex-1 justify-end gap-x-4 self-stretch lg:gap-x-6">
        
        <!-- Search (future feature) -->
        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
          <!-- Search will go here in future -->
        </div>

        <!-- Notifications (future feature) -->
        <!-- <button
          type="button"
          class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500"
        >
          <span class="sr-only">Ver notificaciones</span>
          <BellIcon class="h-6 w-6" aria-hidden="true" />
        </button> -->

        <!-- Profile dropdown -->
        <UserDropdown />
      </div>
    </div>
  </header>
</template>

<script setup>
import { Bars3Icon } from '@heroicons/vue/24/outline'
import LogoComponent from '@/Components/LogoComponent.vue'
import UserDropdown from './UserDropdown.vue'
import { useSidebar } from '@/Composables/useSidebar.js'

const { isMobile, toggleSidebar } = useSidebar()
</script>