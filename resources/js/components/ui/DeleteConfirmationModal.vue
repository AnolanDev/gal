<template>
  <Dialog :open="show" @update:open="(open) => !open && $emit('cancel')">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle class="flex items-center space-x-2">
          <Icon name="alert-triangle" class="w-5 h-5 text-red-500" />
          <span>Confirmar Eliminación</span>
        </DialogTitle>
        <DialogDescription>
          Esta acción no se puede deshacer. ¿Estás seguro de que quieres eliminar a 
          <strong>{{ itemName }}</strong>?
        </DialogDescription>
      </DialogHeader>
      
      <DialogFooter class="sm:flex-row-reverse">
        <Button
          variant="destructive"
          @click="$emit('confirm')"
          :disabled="loading"
        >
          <Icon v-if="loading" name="loader-2" class="w-4 h-4 mr-2 animate-spin" />
          Eliminar
        </Button>
        <Button
          variant="outline"
          @click="$emit('cancel')"
          :disabled="loading"
        >
          Cancelar
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Button } from '@/components/ui/button'
import Icon from '@/components/Icon.vue'

interface Props {
  show: boolean
  itemName?: string
  loading?: boolean
}

defineProps<Props>()

defineEmits<{
  confirm: []
  cancel: []
}>()
</script>