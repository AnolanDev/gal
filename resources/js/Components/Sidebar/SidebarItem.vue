<template>
  <Link
    :href="item.href ? route(item.href) : '#'"
    :class="[
      item.current
        ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-500'
        : 'text-gray-700 hover:text-primary-700 hover:bg-gray-50',
      'group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 transition-colors'
    ]"
    @click="$emit('item-click')"
  >
    <!-- Icon -->
    <component
      :is="iconComponent"
      :class="[
        item.current ? 'text-primary-500' : 'text-gray-400 group-hover:text-primary-500',
        'h-6 w-6 shrink-0 transition-colors'
      ]"
      aria-hidden="true"
    />
    
    <!-- Text (hidden when collapsed) -->
    <span v-if="!isCollapsed" class="truncate">{{ item.name }}</span>
    
    <!-- Tooltip for collapsed state -->
    <div 
      v-if="isCollapsed"
      class="fixed z-50 invisible group-hover:visible bg-gray-900 text-white text-xs rounded py-1 px-2 ml-12 mt-1 whitespace-nowrap"
    >
      {{ item.name }}
    </div>
  </Link>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import * as HeroIcons from '@heroicons/vue/24/outline'

const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  isCollapsed: {
    type: Boolean,
    default: false
  }
})

defineEmits(['item-click'])

// Obtener el componente de icono dinámicamente
const iconComponent = computed(() => {
  const iconName = props.item.icon
  return HeroIcons[iconName] || HeroIcons.QuestionMarkCircleIcon
})
</script>