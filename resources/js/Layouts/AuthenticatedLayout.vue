<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Top Header -->
    <TopHeader />
    
    <!-- Sidebar -->
    <MainSidebar />
    
    <!-- Main content area -->
    <div 
      class="transition-all duration-300 lg:pl-0"
      :style="{ 
        marginLeft: contentMargin,
        paddingTop: isMobile ? '0' : '0'
      }"
    >
      <!-- Page header with breadcrumbs -->
      <header 
        v-if="$slots.header || showBreadcrumbs"
        class="bg-white border-b border-gray-200 px-4 py-4 sm:px-6 lg:px-8"
      >
        <!-- Breadcrumbs -->
        <nav v-if="showBreadcrumbs && breadcrumbs.length > 0" class="flex mb-4" aria-label="Breadcrumb">
          <ol role="list" class="flex items-center space-x-2">
            <li>
              <div>
                <Link :href="route('dashboard')" class="text-gray-400 hover:text-gray-500">
                  <HomeIcon class="h-5 w-5 flex-shrink-0" aria-hidden="true" />
                  <span class="sr-only">Inicio</span>
                </Link>
              </div>
            </li>
            <li v-for="(breadcrumb, index) in breadcrumbs" :key="breadcrumb.name">
              <div class="flex items-center">
                <ChevronRightIcon class="h-5 w-5 flex-shrink-0 text-gray-400" aria-hidden="true" />
                <Link
                  v-if="breadcrumb.href && index < breadcrumbs.length - 1"
                  :href="route(breadcrumb.href)"
                  class="ml-2 text-sm font-medium text-gray-500 hover:text-gray-700"
                >
                  {{ breadcrumb.name }}
                </Link>
                <span
                  v-else
                  class="ml-2 text-sm font-medium text-gray-900"
                  :aria-current="index === breadcrumbs.length - 1 ? 'page' : undefined"
                >
                  {{ breadcrumb.name }}
                </span>
              </div>
            </li>
          </ol>
        </nav>
        
        <!-- Page header slot -->
        <div v-if="$slots.header">
          <slot name="header" />
        </div>
        
        <!-- Auto-generated page title if no header slot -->
        <div v-else-if="pageTitle">
          <h1 class="text-2xl font-semibold text-gray-900">{{ pageTitle }}</h1>
        </div>
      </header>

      <!-- Main page content -->
      <main class="flex-1">
        <div class="px-4 py-6 sm:px-6 lg:px-8">
          <slot />
        </div>
      </main>
    </div>

    <!-- Flash Messages -->
    <FlashMessages />
  </div>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import { HomeIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'
import TopHeader from '@/Components/Header/TopHeader.vue'
import MainSidebar from '@/Components/Sidebar/MainSidebar.vue'
import FlashMessages from '@/Components/FlashMessages.vue'
import { useSidebar } from '@/Composables/useSidebar.js'
import { useNavigation } from '@/Composables/useNavigation.js'

// Props for customization
const props = defineProps({
  showBreadcrumbs: {
    type: Boolean,
    default: true
  }
})

// Composables
const { isMobile, contentMargin, initializeSidebar } = useSidebar()
const { breadcrumbs, pageTitle } = useNavigation()

// Initialize on mount
onMounted(() => {
  const cleanup = initializeSidebar()
  
  onUnmounted(() => {
    cleanup?.()
  })
})
</script>