<template>
  <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
    <div class="-mt-px flex w-0 flex-1">
      <Component
        :is="links.prev ? Link : 'span'"
        :href="links.prev"
        class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
        :class="{ 'cursor-not-allowed opacity-50': !links.prev }"
      >
        <ArrowLongLeftIcon class="mr-3 h-5 w-5 text-gray-400" aria-hidden="true" />
        Anterior
      </Component>
    </div>
    
    <div class="hidden md:-mt-px md:flex">
      <template v-for="(link, index) in links.numbered" :key="index">
        <Component
          :is="link.url ? Link : 'span'"
          :href="link.url"
          class="inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium"
          :class="[
            link.active 
              ? 'border-primary-500 text-primary-600' 
              : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
            !link.url && !link.active ? 'cursor-not-allowed' : ''
          ]"
          v-html="link.label"
        />
      </template>
    </div>
    
    <div class="-mt-px flex w-0 flex-1 justify-end">
      <Component
        :is="links.next ? Link : 'span'"
        :href="links.next"
        class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
        :class="{ 'cursor-not-allowed opacity-50': !links.next }"
      >
        Siguiente
        <ArrowLongRightIcon class="ml-3 h-5 w-5 text-gray-400" aria-hidden="true" />
      </Component>
    </div>
  </nav>
  
  <!-- Mobile pagination info -->
  <div class="flex justify-between items-center mt-4 sm:hidden">
    <p class="text-sm text-gray-700">
      Mostrando 
      <span class="font-medium">{{ meta.from }}</span>
      a 
      <span class="font-medium">{{ meta.to }}</span>
      de 
      <span class="font-medium">{{ meta.total }}</span>
      resultados
    </p>
  </div>
  
  <!-- Desktop pagination info -->
  <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-center mt-4">
    <p class="text-sm text-gray-700">
      Mostrando 
      <span class="font-medium">{{ meta.from }}</span>
      a 
      <span class="font-medium">{{ meta.to }}</span>
      de 
      <span class="font-medium">{{ meta.total }}</span>
      resultados
    </p>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowLongLeftIcon, ArrowLongRightIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
  links: {
    type: [Array, Object],
    required: true
  },
  meta: {
    type: Object,
    default: () => ({})
  }
})

// Process pagination links
const links = computed(() => {
  const paginationLinks = props.links || []
  
  // Handle Laravel Resource pagination format (object)
  if (typeof paginationLinks === 'object' && !Array.isArray(paginationLinks)) {
    return {
      prev: paginationLinks.prev || null,
      next: paginationLinks.next || null,
      numbered: []
    }
  }
  
  // Handle standard Laravel pagination format (array)
  if (Array.isArray(paginationLinks) && paginationLinks.length === 0) {
    return {
      prev: null,
      next: null,
      numbered: []
    }
  }
  
  if (Array.isArray(paginationLinks)) {
    return {
      prev: paginationLinks[0]?.url || null,
      next: paginationLinks[paginationLinks.length - 1]?.url || null,
      numbered: paginationLinks.slice(1, -1) // Remove first (prev) and last (next)
    }
  }
  
  return {
    prev: null,
    next: null,
    numbered: []
  }
})

// Extract meta information
const meta = computed(() => {
  return {
    from: props.meta.from || 0,
    to: props.meta.to || 0,
    total: props.meta.total || 0,
    current_page: props.meta.current_page || 1,
    last_page: props.meta.last_page || 1,
    per_page: props.meta.per_page || 15
  }
})
</script>