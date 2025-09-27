import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { getNavigationByRole, setCurrentNavigation, generateBreadcrumbs } from '@/Config/navigation.js'

export function useNavigation() {
  const page = usePage()
  
  // Usuario actual
  const user = computed(() => page.props.auth?.user)
  
  // Rol del usuario
  const userRole = computed(() => user.value?.role)
  
  // Ruta actual
  const currentRoute = computed(() => {
    // Convertir la ruta actual a nombre de ruta
    const routeName = page.props.route || route().current()
    return routeName
  })
  
  // Navegación base por rol
  const baseNavigation = computed(() => {
    if (!userRole.value) return []
    return getNavigationByRole(userRole.value)
  })
  
  // Navegación con estados actuales
  const navigation = computed(() => {
    return setCurrentNavigation(baseNavigation.value, currentRoute.value)
  })
  
  // Breadcrumbs para la página actual
  const breadcrumbs = computed(() => {
    return generateBreadcrumbs(baseNavigation.value, currentRoute.value)
  })
  
  // Verificar si el usuario tiene acceso a una ruta
  const hasAccess = (routeName) => {
    if (!userRole.value) return false
    
    const userNavigation = getNavigationByRole(userRole.value)
    
    // Buscar en navegación principal
    for (const item of userNavigation) {
      if (item.href === routeName) return true
      
      // Buscar en children
      if (item.children) {
        for (const child of item.children) {
          if (child.href === routeName) return true
        }
      }
    }
    
    return false
  }
  
  // Obtener título de la página actual
  const pageTitle = computed(() => {
    for (const item of baseNavigation.value) {
      if (item.href === currentRoute.value) {
        return item.name
      }
      
      if (item.children) {
        for (const child of item.children) {
          if (child.href === currentRoute.value) {
            return child.name
          }
        }
      }
    }
    
    return 'Dashboard'
  })
  
  // Verificar si una sección tiene items activos
  const haActiveChildren = (item) => {
    if (!item.children) return false
    return item.children.some(child => child.current)
  }
  
  // Obtener item padre de una ruta
  const getParentItem = (routeName) => {
    for (const item of baseNavigation.value) {
      if (item.children) {
        for (const child of item.children) {
          if (child.href === routeName) {
            return item
          }
        }
      }
    }
    return null
  }
  
  return {
    user,
    userRole,
    currentRoute,
    navigation,
    breadcrumbs,
    pageTitle,
    hasAccess,
    haActiveChildren,
    getParentItem
  }
}