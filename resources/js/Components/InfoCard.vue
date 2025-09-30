<template>
  <div>
    <p class="text-sm font-medium text-gray-700">{{ label }}</p>
    <p 
      v-if="multiline" 
      class="text-sm text-gray-900 mt-1 whitespace-pre-wrap"
    >
      {{ value || 'No registrado' }}
    </p>
    <p 
      v-else 
      class="text-sm text-gray-900 mt-1"
    >
      {{ value || 'No registrado' }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  label: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    default: null
  },
  multiline: {
    type: Boolean,
    default: false
  }
})
</script>