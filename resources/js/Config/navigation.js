// Configuración de navegación por roles para GAL
// Iconos usando Heroicons (outline)

export const navigationConfig = {
  admin: [
    {
      name: 'Dashboard',
      href: 'dashboard',
      icon: 'ChartBarIcon',
      current: false
    },
    {
      name: 'Gestión de Usuarios',
      icon: 'UsersIcon',
      children: [
        {
          name: 'Docentes',
          href: null, // Placeholder - implementar ruta
          icon: 'AcademicCapIcon'
        },
        {
          name: 'Padres de Familia',
          href: null, // Placeholder - implementar ruta
          icon: 'UserGroupIcon'
        },
        {
          name: 'Administradores',
          href: null, // Placeholder - implementar ruta
          icon: 'ShieldCheckIcon'
        }
      ]
    },
    {
      name: 'Gestión Académica',
      icon: 'BuildingLibraryIcon',
      children: [
        {
          name: 'Grados',
          href: null, // Placeholder - implementar ruta
          icon: 'AcademicCapIcon'
        },
        {
          name: 'Materias',
          href: null, // Placeholder - implementar ruta
          icon: 'BookOpenIcon'
        },
        {
          name: 'Períodos Académicos',
          href: null, // Placeholder - implementar ruta
          icon: 'CalendarIcon'
        }
      ]
    },
    {
      name: 'Estudiantes',
      icon: 'UserIcon',
      children: [
        {
          name: 'Lista de Estudiantes',
          href: null, // Placeholder - implementar ruta
          icon: 'UserGroupIcon'
        },
        {
          name: 'Matrículas',
          href: null, // Placeholder - implementar ruta
          icon: 'DocumentTextIcon'
        },
        {
          name: 'Reportes',
          href: null, // Placeholder - implementar ruta
          icon: 'ChartBarIcon'
        }
      ]
    },
    {
      name: 'Asistencias',
      href: null, // Placeholder - implementar ruta
      icon: 'ClipboardDocumentCheckIcon',
      current: false
    },
    {
      name: 'Calificaciones',
      href: null, // Placeholder - implementar ruta
      icon: 'ChartBarIcon',
      current: false
    },
    {
      name: 'Reportes y Estadísticas',
      href: null, // Placeholder - implementar ruta
      icon: 'PresentationChartLineIcon',
      current: false
    },
    {
      name: 'Configuración',
      href: null, // Placeholder - implementar ruta
      icon: 'CogIcon',
      current: false
    }
  ],

  docente: [
    {
      name: 'Mi Dashboard',
      href: 'dashboard', // Usar dashboard por ahora
      icon: 'ChartBarIcon',
      current: false
    },
    {
      name: 'Mis Estudiantes',
      icon: 'UserGroupIcon',
      children: [
        {
          name: 'Lista del Grado',
          href: null, // Placeholder - implementar ruta
          icon: 'ListBulletIcon'
        },
        {
          name: 'Información Detallada',
          href: null, // Placeholder - implementar ruta
          icon: 'InformationCircleIcon'
        }
      ]
    },
    {
      name: 'Asistencias',
      icon: 'ClipboardDocumentCheckIcon',
      children: [
        {
          name: 'Tomar Asistencia',
          href: null, // Placeholder - implementar ruta
          icon: 'CheckCircleIcon'
        },
        {
          name: 'Reportes de Asistencia',
          href: null, // Placeholder - implementar ruta
          icon: 'DocumentChartBarIcon'
        }
      ]
    },
    {
      name: 'Calificaciones',
      icon: 'ChartBarIcon',
      children: [
        {
          name: 'Registrar Calificaciones',
          href: null, // Placeholder - implementar ruta
          icon: 'PencilIcon'
        },
        {
          name: 'Ver Calificaciones',
          href: null, // Placeholder - implementar ruta
          icon: 'EyeIcon'
        },
        {
          name: 'Reportes',
          href: null, // Placeholder - implementar ruta
          icon: 'DocumentTextIcon'
        }
      ]
    },
    {
      name: 'Mi Grado',
      href: null, // Placeholder - implementar ruta
      icon: 'BuildingLibraryIcon',
      current: false
    },
    {
      name: 'Reportes',
      href: null, // Placeholder - implementar ruta
      icon: 'PresentationChartLineIcon',
      current: false
    },
    {
      name: 'Mi Perfil',
      href: 'profile.edit', // Usar perfil existente
      icon: 'UserCircleIcon',
      current: false
    }
  ],

  padre: [
    {
      name: 'Dashboard',
      href: 'dashboard', // Usar dashboard por ahora
      icon: 'ChartBarIcon',
      current: false
    },
    {
      name: 'Mis Hijos',
      href: null, // Placeholder - implementar ruta
      icon: 'HeartIcon',
      current: false
    },
    {
      name: 'Asistencias',
      href: null, // Placeholder - implementar ruta
      icon: 'ClipboardDocumentCheckIcon',
      current: false
    },
    {
      name: 'Calificaciones',
      href: null, // Placeholder - implementar ruta
      icon: 'ChartBarIcon',
      current: false
    },
    {
      name: 'Calendario Escolar',
      href: null, // Placeholder - implementar ruta
      icon: 'CalendarDaysIcon',
      current: false
    },
    {
      name: 'Contactar Docente',
      href: null, // Placeholder - implementar ruta
      icon: 'ChatBubbleLeftEllipsisIcon',
      current: false
    },
    {
      name: 'Mi Perfil',
      href: 'profile.edit', // Usar perfil existente
      icon: 'UserCircleIcon',
      current: false
    }
  ]
}

// Función para obtener navegación por rol
export function getNavigationByRole(role) {
  return navigationConfig[role] || []
}

// Función para marcar item actual basado en la ruta
export function setCurrentNavigation(navigation, currentRoute) {
  return navigation.map(item => {
    if (item.children) {
      return {
        ...item,
        children: item.children.map(child => ({
          ...child,
          current: currentRoute === child.href
        })),
        current: item.children.some(child => currentRoute === child.href)
      }
    }
    
    return {
      ...item,
      current: currentRoute === item.href
    }
  })
}

// Función para obtener el item activo actual
export function getCurrentNavigationItem(navigation, currentRoute) {
  for (const item of navigation) {
    if (item.href === currentRoute) {
      return item
    }
    
    if (item.children) {
      for (const child of item.children) {
        if (child.href === currentRoute) {
          return child
        }
      }
    }
  }
  
  return null
}

// Función para generar breadcrumbs
export function generateBreadcrumbs(navigation, currentRoute) {
  const breadcrumbs = []
  
  for (const item of navigation) {
    if (item.href === currentRoute) {
      breadcrumbs.push({ name: item.name, href: item.href })
      break
    }
    
    if (item.children) {
      for (const child of item.children) {
        if (child.href === currentRoute) {
          breadcrumbs.push({ name: item.name, href: null })
          breadcrumbs.push({ name: child.name, href: child.href })
          break
        }
      }
    }
  }
  
  return breadcrumbs
}