<template>
  <!-- Mobile sidebar overlay -->
  <div 
    v-if="isMobile && isOpen"
    class="relative z-50 lg:hidden"
    role="dialog"
    aria-modal="true"
  >
    <div 
      class="fixed inset-0 bg-gray-900/80 transition-opacity duration-300"
      @click="handleBackdropClick"
    ></div>

    <div class="fixed inset-0 flex">
      <div 
        class="relative mr-16 flex w-full max-w-xs flex-1 transform transition-transform duration-300"
        :class="[
          isOpen ? 'translate-x-0' : '-translate-x-full'
        ]"
      >
        <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
          <button 
            type="button" 
            class="-m-2.5 p-2.5"
            @click="closeSidebar"
          >
            <span class="sr-only">Cerrar menú</span>
            <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
          </button>
        </div>

        <!-- Mobile sidebar content -->
        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4 ring-1 ring-white/10">
          <div class="flex h-16 shrink-0 items-center">
            <LogoComponent 
              :link="route('dashboard')"
              size="normal"
              :show-text="true"
              aria-label="Ir al dashboard principal de GAL"
            />
          </div>
          <SidebarNavigation />
        </div>
      </div>
    </div>
  </div>

  <!-- Desktop sidebar -->
  <div 
    v-if="isDesktop"
    class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:flex-col transition-all duration-300"
    :style="{ width: sidebarWidth }"
  >
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 pb-4">
      <!-- Desktop header -->
      <div class="flex h-16 shrink-0 items-center justify-between">
        <LogoComponent 
          v-if="!isCollapsed"
          :link="route('dashboard')"
          size="normal"
          :show-text="true"
          aria-label="Ir al dashboard principal de GAL"
        />
        <LogoComponent 
          v-else
          :link="route('dashboard')"
          size="small"
          :show-text="false"
          aria-label="Ir al dashboard principal de GAL"
        />
        
        <!-- Collapse toggle button -->
        <button
          type="button"
          class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors"
          @click="toggleCollapse"
          :title="isCollapsed ? 'Expandir menú' : 'Contraer menú'"
        >
          <ChevronLeftIcon 
            v-if="!isCollapsed"
            class="h-4 w-4 text-gray-600" 
            aria-hidden="true" 
          />
          <ChevronRightIcon 
            v-else
            class="h-4 w-4 text-gray-600" 
            aria-hidden="true" 
          />
        </button>
      </div>
      
      <!-- Desktop navigation -->
      <SidebarNavigation :is-collapsed="isCollapsed" />
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue'
import { XMarkIcon, ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'
import LogoComponent from '@/Components/LogoComponent.vue'
import SidebarNavigation from './SidebarNavigation.vue'
import { useSidebar } from '@/Composables/useSidebar.js'

const {
  isOpen,
  isMobile,
  isDesktop,
  isCollapsed,
  sidebarWidth,
  closeSidebar,
  toggleCollapse,
  handleBackdropClick,
  handleEscape,
  initializeSidebar
} = useSidebar()

// Manejar eventos de teclado
onMounted(() => {
  const cleanup = initializeSidebar()
  document.addEventListener('keydown', handleEscape)
  
  onUnmounted(() => {
    document.removeEventListener('keydown', handleEscape)
    cleanup?.()
  })
})
</script>