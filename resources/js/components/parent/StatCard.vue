<template>
  <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">
          {{ title }}
        </p>
        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
          {{ value }}
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
          {{ subtitle }}
        </p>
      </div>
      <div 
        :class="[
          'w-10 h-10 rounded-full flex items-center justify-center',
          colorClasses[color]
        ]"
      >
        <Icon :name="icon" class="w-5 h-5 text-white" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Icon from '@/components/Icon.vue'

interface Props {
  title: string
  value: string
  subtitle: string
  icon: string
  color: 'blue' | 'green' | 'yellow' | 'purple' | 'red'
}

defineProps<Props>()

const colorClasses = {
  blue: 'bg-blue-500',
  green: 'bg-green-500',
  yellow: 'bg-yellow-500',
  purple: 'bg-purple-500',
  red: 'bg-red-500'
}
</script>