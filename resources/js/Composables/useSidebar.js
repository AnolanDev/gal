import { ref, reactive, computed, watch } from 'vue'

// Estado global del sidebar
const sidebarState = reactive({
  isOpen: false,
  isMobile: false,
  isCollapsed: false,
})

// Persistencia en localStorage
const SIDEBAR_STORAGE_KEY = 'gal_sidebar_state'

// Función para detectar si es móvil
const checkIsMobile = () => {
  return window.innerWidth < 1024 // lg breakpoint
}

// Cargar estado inicial
const loadSidebarState = () => {
  try {
    const saved = localStorage.getItem(SIDEBAR_STORAGE_KEY)
    if (saved) {
      const parsed = JSON.parse(saved)
      // Solo restaurar isCollapsed en desktop
      if (!checkIsMobile()) {
        sidebarState.isCollapsed = parsed.isCollapsed || false
      }
    }
  } catch (error) {
    console.warn('Error loading sidebar state:', error)
  }
}

// Guardar estado
const saveSidebarState = () => {
  try {
    localStorage.setItem(SIDEBAR_STORAGE_KEY, JSON.stringify({
      isCollapsed: sidebarState.isCollapsed
    }))
  } catch (error) {
    console.warn('Error saving sidebar state:', error)
  }
}

export function useSidebar() {
  // Inicializar estado
  const initializeSidebar = () => {
    sidebarState.isMobile = checkIsMobile()
    sidebarState.isOpen = !sidebarState.isMobile
    loadSidebarState()
    
    // Listener para cambios de tamaño de pantalla
    const handleResize = () => {
      const wasMobile = sidebarState.isMobile
      sidebarState.isMobile = checkIsMobile()
      
      if (wasMobile && !sidebarState.isMobile) {
        // De móvil a desktop
        sidebarState.isOpen = true
      } else if (!wasMobile && sidebarState.isMobile) {
        // De desktop a móvil
        sidebarState.isOpen = false
        sidebarState.isCollapsed = false
      }
    }
    
    window.addEventListener('resize', handleResize)
    
    // Cleanup function
    return () => window.removeEventListener('resize', handleResize)
  }

  // Computadas reactivas
  const isOpen = computed(() => sidebarState.isOpen)
  const isMobile = computed(() => sidebarState.isMobile)
  const isCollapsed = computed(() => sidebarState.isCollapsed)
  const isDesktop = computed(() => !sidebarState.isMobile)

  // Sidebar width computada
  const sidebarWidth = computed(() => {
    if (sidebarState.isMobile) return '100%'
    return sidebarState.isCollapsed ? '80px' : '280px'
  })

  // Content margin computada
  const contentMargin = computed(() => {
    if (sidebarState.isMobile) return '0'
    if (!sidebarState.isOpen) return '0'
    return sidebarState.isCollapsed ? '80px' : '280px'
  })

  // Acciones
  const toggleSidebar = () => {
    sidebarState.isOpen = !sidebarState.isOpen
  }

  const openSidebar = () => {
    sidebarState.isOpen = true
  }

  const closeSidebar = () => {
    sidebarState.isOpen = false
  }

  const toggleCollapse = () => {
    if (!sidebarState.isMobile) {
      sidebarState.isCollapsed = !sidebarState.isCollapsed
      saveSidebarState()
    }
  }

  const collapseSidebar = () => {
    if (!sidebarState.isMobile) {
      sidebarState.isCollapsed = true
      saveSidebarState()
    }
  }

  const expandSidebar = () => {
    if (!sidebarState.isMobile) {
      sidebarState.isCollapsed = false
      saveSidebarState()
    }
  }

  // Cerrar sidebar cuando se hace clic en móvil
  const closeMobileSidebar = () => {
    if (sidebarState.isMobile) {
      sidebarState.isOpen = false
    }
  }

  // Manejar escape key
  const handleEscape = (event) => {
    if (event.key === 'Escape' && sidebarState.isOpen) {
      closeSidebar()
    }
  }

  // Manejar backdrop click
  const handleBackdropClick = () => {
    if (sidebarState.isMobile) {
      closeSidebar()
    }
  }

  // Watch para guardar cambios
  watch(
    () => sidebarState.isCollapsed,
    () => saveSidebarState(),
    { flush: 'post' }
  )

  return {
    // Estado
    isOpen,
    isMobile,
    isCollapsed,
    isDesktop,
    sidebarWidth,
    contentMargin,
    
    // Acciones
    initializeSidebar,
    toggleSidebar,
    openSidebar,
    closeSidebar,
    toggleCollapse,
    collapseSidebar,
    expandSidebar,
    closeMobileSidebar,
    handleEscape,
    handleBackdropClick,
  }
}