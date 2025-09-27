<template>
  <div>
    <!-- Section header -->
    <button
      v-if="!isCollapsed"
      type="button"
      :class="[
        isExpanded ? 'text-gray-900' : 'text-gray-600 hover:text-gray-900',
        'group flex w-full items-center gap-x-3 rounded-md p-2 text-left text-sm font-semibold leading-6 transition-colors'
      ]"
      @click="toggleExpanded"
      :aria-expanded="isExpanded"
    >
      <!-- Section icon -->
      <component
        :is="iconComponent"
        :class="[
          section.current ? 'text-primary-500' : 'text-gray-400 group-hover:text-primary-500',
          'h-6 w-6 shrink-0 transition-colors'
        ]"
        aria-hidden="true"
      />
      
      <!-- Section name -->
      <span class="flex-1 truncate">{{ section.name }}</span>
      
      <!-- Chevron -->
      <ChevronRightIcon
        :class="[
          isExpanded ? 'rotate-90 text-gray-500' : 'text-gray-400',
          'ml-auto h-5 w-5 shrink-0 transition-transform duration-200'
        ]"
        aria-hidden="true"
      />
    </button>
    
    <!-- Collapsed section header (shows only icon) -->
    <div
      v-else
      class="group relative flex justify-center rounded-md p-2 hover:bg-gray-50"
      :title="section.name"
    >
      <component
        :is="iconComponent"
        :class="[
          section.current ? 'text-primary-500' : 'text-gray-400 group-hover:text-primary-500',
          'h-6 w-6 shrink-0 transition-colors'
        ]"
        aria-hidden="true"
      />
      
      <!-- Tooltip for collapsed state -->
      <div class="fixed z-50 invisible group-hover:visible bg-gray-900 text-white text-xs rounded py-1 px-2 ml-12 mt-1 whitespace-nowrap">
        {{ section.name }}
      </div>
    </div>

    <!-- Children -->
    <ul 
      v-if="!isCollapsed && isExpanded" 
      class="mt-1 px-2"
    >
      <li v-for="item in section.children" :key="item.name">
        <Link
          :href="item.href ? route(item.href) : '#'"
          :class="[
            item.current
              ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500'
              : 'text-gray-600 hover:text-primary-700 hover:bg-gray-50',
            'group flex gap-x-3 rounded-md py-2 pl-9 pr-2 text-sm leading-6 font-medium transition-colors'
          ]"
          @click="$emit('item-click')"
        >
          <!-- Child icon -->
          <component
            :is="childIconComponent(item)"
            :class="[
              item.current ? 'text-primary-500' : 'text-gray-400 group-hover:text-primary-500',
              'h-4 w-4 shrink-0 transition-colors'
            ]"
            aria-hidden="true"
          />
          
          <!-- Child name -->
          <span class="truncate">{{ item.name }}</span>
        </Link>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ChevronRightIcon } from '@heroicons/vue/24/outline'
import * as HeroIcons from '@heroicons/vue/24/outline'

const props = defineProps({
  section: {
    type: Object,
    required: true
  },
  isCollapsed: {
    type: Boolean,
    default: false
  }
})

defineEmits(['item-click'])

// Estado local para expansión
const isExpanded = ref(props.section.current || false)

// Expandir automáticamente si tiene children activos
watch(
  () => props.section.current,
  (newValue) => {
    if (newValue) {
      isExpanded.value = true
    }
  },
  { immediate: true }
)

// Toggle expansión
const toggleExpanded = () => {
  isExpanded.value = !isExpanded.value
}

// Obtener el componente de icono de la sección
const iconComponent = computed(() => {
  const iconName = props.section.icon
  return HeroIcons[iconName] || HeroIcons.FolderIcon
})

// Obtener el componente de icono de un child
const childIconComponent = (item) => {
  const iconName = item.icon
  return HeroIcons[iconName] || HeroIcons.CircleStackIcon
}

// Colapsar cuando el sidebar se colapsa
watch(
  () => props.isCollapsed,
  (newValue) => {
    if (newValue) {
      isExpanded.value = false
    } else if (props.section.current) {
      isExpanded.value = true
    }
  }
)
</script>