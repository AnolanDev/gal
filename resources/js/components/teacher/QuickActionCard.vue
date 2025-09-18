<template>
  <div 
    @click="$emit('click')"
    class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 cursor-pointer transition-all duration-200 hover:shadow-md hover:scale-105 active:scale-95"
  >
    <div class="flex flex-col items-center text-center space-y-2">
      <div 
        :class="[
          'w-12 h-12 rounded-full flex items-center justify-center',
          colorClasses[color]
        ]"
      >
        <Icon :name="icon" class="w-6 h-6 text-white" />
      </div>
      <div>
        <h3 class="font-medium text-gray-900 dark:text-white text-sm">
          {{ title }}
        </h3>
        <p class="text-xs text-gray-600 dark:text-gray-400">
          {{ subtitle }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Icon from '@/components/Icon.vue'

interface Props {
  title: string
  subtitle: string
  icon: string
  color: 'blue' | 'green' | 'yellow' | 'purple' | 'red'
}

defineProps<Props>()
defineEmits<{
  click: []
}>()

const colorClasses = {
  blue: 'bg-blue-500',
  green: 'bg-green-500',
  yellow: 'bg-yellow-500',
  purple: 'bg-purple-500',
  red: 'bg-red-500'
}
</script>