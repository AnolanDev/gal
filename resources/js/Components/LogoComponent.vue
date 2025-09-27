<template>
  <component 
    :is="link ? 'Link' : 'div'" 
    :href="link" 
    :class="containerClasses"
    :aria-label="ariaLabel"
  >
    <!-- Logo Image -->
    <img 
      v-if="!imageError"
      :src="logoSrc"
      :alt="altText"
      :class="imageClasses"
      @error="handleImageError"
    />
    
    <!-- Fallback Text -->
    <span 
      v-if="imageError || showText"
      :class="textClasses"
    >
      {{ text }}
    </span>
  </component>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

// Props
const props = defineProps({
  size: {
    type: String,
    default: 'normal',
    validator: (value) => ['small', 'normal', 'large'].includes(value)
  },
  
  link: {
    type: [String, Boolean],
    default: false
  },
  
  text: {
    type: String,
    default: 'GAL'
  },
  
  showText: {
    type: Boolean,
    default: true
  },
  
  logoPath: {
    type: String,
    default: '/storage/images/logos/institution/logo-gal.png'
  },
  
  altText: {
    type: String,
    default: 'GAL Logo'
  },
  
  ariaLabel: {
    type: String,
    default: null
  }
})

// State
const imageError = ref(false)

// Computed
const containerClasses = computed(() => {
  const base = 'flex items-center space-x-3'
  const linkStyles = props.link ? 'hover:opacity-80 transition-opacity' : ''
  return [base, linkStyles].filter(Boolean).join(' ')
})

const imageClasses = computed(() => {
  const sizes = {
    small: 'h-8 w-auto',
    normal: 'h-14 w-auto', 
    large: 'h-16 w-auto'
  }
  return `object-contain ${sizes[props.size]}`
})

const textClasses = computed(() => {
  const sizes = {
    small: 'text-lg font-semibold',
    normal: 'text-xl font-bold',
    large: 'text-2xl font-bold'
  }
  return `text-primary-800 ${sizes[props.size]}`
})

const logoSrc = computed(() => props.logoPath)

// Methods
const handleImageError = () => {
  imageError.value = true
}
</script>