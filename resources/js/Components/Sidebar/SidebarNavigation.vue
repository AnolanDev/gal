<template>
  <nav class="flex flex-1 flex-col">
    <ul role="list" class="flex flex-1 flex-col gap-y-7">
      <li>
        <ul role="list" class="-mx-2 space-y-1">
          <li v-for="item in navigation" :key="item.name">
            <!-- Single item without children -->
            <SidebarItem 
              v-if="!item.children"
              :item="item"
              :is-collapsed="isCollapsed"
              @item-click="handleItemClick"
            />
            
            <!-- Section with children -->
            <SidebarSection
              v-else
              :section="item"
              :is-collapsed="isCollapsed"
              @item-click="handleItemClick"
            />
          </li>
        </ul>
      </li>
      
      <!-- Role badge at bottom -->
      <li class="mt-auto">
        <div 
          v-if="!isCollapsed"
          class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400"
        >
          <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-[0.625rem] font-medium text-gray-400">
            {{ userRole?.charAt(0).toUpperCase() }}
          </div>
          <span class="capitalize">{{ userRole }}</span>
        </div>
        
        <!-- Collapsed role indicator -->
        <div 
          v-else
          class="group -mx-2 flex justify-center rounded-md p-2"
          :title="`Rol: ${userRole}`"
        >
          <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-[0.625rem] font-medium text-gray-400">
            {{ userRole?.charAt(0).toUpperCase() }}
          </div>
        </div>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import SidebarItem from './SidebarItem.vue'
import SidebarSection from './SidebarSection.vue'
import { useNavigation } from '@/Composables/useNavigation.js'
import { useSidebar } from '@/Composables/useSidebar.js'

const props = defineProps({
  isCollapsed: {
    type: Boolean,
    default: false
  }
})

const { navigation, userRole } = useNavigation()
const { closeMobileSidebar } = useSidebar()

// Manejar click en items (cerrar sidebar en móvil)
const handleItemClick = () => {
  closeMobileSidebar()
}
</script>