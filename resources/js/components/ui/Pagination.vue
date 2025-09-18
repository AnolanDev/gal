<template>
  <nav class="flex items-center justify-between">
    <div class="flex-1 flex justify-between sm:hidden">
      <!-- Mobile pagination -->
      <Link
        v-if="prevPageUrl"
        :href="prevPageUrl"
        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
      >
        Anterior
      </Link>
      <Link
        v-if="nextPageUrl"
        :href="nextPageUrl"
        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
      >
        Siguiente
      </Link>
    </div>
    
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700 dark:text-gray-300">
          Mostrando
          <span class="font-medium">{{ from }}</span>
          a
          <span class="font-medium">{{ to }}</span>
          de
          <span class="font-medium">{{ total }}</span>
          resultados
        </p>
      </div>
      
      <div>
        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
          <!-- Previous Page Link -->
          <Link
            v-if="prevPageUrl"
            :href="prevPageUrl"
            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
          >
            <Icon name="chevron-left" class="h-5 w-5" />
          </Link>
          <span
            v-else
            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-300 cursor-not-allowed"
          >
            <Icon name="chevron-left" class="h-5 w-5" />
          </span>

          <!-- Pagination Elements -->
          <template v-for="(link, index) in paginationLinks" :key="index">
            <Link
              v-if="link.url && !link.active"
              :href="link.url"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50"
              v-html="link.label"
            />
            <span
              v-else-if="link.active"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600"
              v-html="link.label"
            />
            <span
              v-else
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
              v-html="link.label"
            />
          </template>

          <!-- Next Page Link -->
          <Link
            v-if="nextPageUrl"
            :href="nextPageUrl"
            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
          >
            <Icon name="chevron-right" class="h-5 w-5" />
          </Link>
          <span
            v-else
            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-300 cursor-not-allowed"
          >
            <Icon name="chevron-right" class="h-5 w-5" />
          </span>
        </nav>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import Icon from '@/components/Icon.vue'

interface PaginationLink {
  url: string | null
  label: string
  active: boolean
}

interface Props {
  links: PaginationLink[]
}

const props = defineProps<Props>()

const paginationLinks = computed(() => {
  return props.links.slice(1, -1) // Remove first and last (prev/next)
})

const prevPageUrl = computed(() => {
  return props.links[0]?.url
})

const nextPageUrl = computed(() => {
  return props.links[props.links.length - 1]?.url
})

const from = computed(() => {
  // Extract from meta if available, otherwise calculate
  return 1 // This would come from Laravel's pagination meta
})

const to = computed(() => {
  // Extract from meta if available, otherwise calculate
  return 15 // This would come from Laravel's pagination meta
})

const total = computed(() => {
  // Extract from meta if available, otherwise calculate
  return 100 // This would come from Laravel's pagination meta
})
</script>