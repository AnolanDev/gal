<template>
  <!-- Flash Messages -->
  <div class="fixed top-20 right-4 z-50 space-y-2 max-w-sm w-full">
    <Transition
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-if="$page.props.flash.message" 
        class="bg-primary-500 text-white px-4 py-3 rounded-lg shadow-lg"
      >
        <div class="flex items-center">
          <InformationCircleIcon class="w-5 h-5 mr-2 flex-shrink-0" />
          <p class="text-sm font-medium">{{ $page.props.flash.message }}</p>
        </div>
      </div>
    </Transition>

    <Transition
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-if="$page.props.flash.success" 
        class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg"
      >
        <div class="flex items-center">
          <CheckCircleIcon class="w-5 h-5 mr-2 flex-shrink-0" />
          <p class="text-sm font-medium">{{ $page.props.flash.success }}</p>
        </div>
      </div>
    </Transition>

    <Transition
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-100"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div 
        v-if="$page.props.flash.error" 
        class="bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg"
      >
        <div class="flex items-center">
          <XCircleIcon class="w-5 h-5 mr-2 flex-shrink-0" />
          <p class="text-sm font-medium">{{ $page.props.flash.error }}</p>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { InformationCircleIcon, CheckCircleIcon, XCircleIcon } from '@heroicons/vue/24/outline'
</script>