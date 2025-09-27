<template>
  <button
    type="button"
    class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500"
    :aria-expanded="isOpen"
    aria-label="Abrir menú principal"
    @click="toggleSidebar"
  >
    <span class="absolute -inset-0.5"></span>
    <span class="sr-only">Abrir menú principal</span>
    
    <!-- Hamburger icon -->
    <svg
      :class="{ 'hidden': isOpen, 'block': !isOpen }"
      class="h-6 w-6 transition-all duration-200"
      fill="none"
      viewBox="0 0 24 24"
      stroke-width="1.5"
      stroke="currentColor"
      aria-hidden="true"
    >
      <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
    </svg>
    
    <!-- Close icon -->
    <svg
      :class="{ 'block': isOpen, 'hidden': !isOpen }"
      class="h-6 w-6 transition-all duration-200"
      fill="none"
      viewBox="0 0 24 24"
      stroke-width="1.5"
      stroke="currentColor"
      aria-hidden="true"
    >
      <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
    </svg>
  </button>
</template>

<script setup>
import { useSidebar } from '@/Composables/useSidebar.js'

const { isOpen, toggleSidebar } = useSidebar()
</script>