# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with the GAL School Management System.

## Project Overview

**GAL** is a comprehensive school management system for preschool and elementary education, designed with mobile-first principles and clean architecture.

### Target Users
- **Administrators**: Manage enrollments, payments, reports
- **Teachers**: Record attendance, grades, observations
- **Parents**: View academic progress, payments, communications
- **Students**: View activities and tasks (minimal usage)

## Development Commands

### PHP/Laravel Commands
- `composer dev` - Start full development environment (Laravel + Queue + Logs + Vite)
- `composer dev:ssr` - Start with server-side rendering
- `composer test` - Run PHP tests (Pest framework)
- `./vendor/bin/pint` - Format PHP code with Laravel Pint
- `php artisan migrate:fresh --seed` - Reset database with test data
- `php artisan db:seed --class=SchoolSeeder` - Seed school-specific data

### Frontend Commands
- `npm run dev` - Start Vite development server
- `npm run build` - Build production assets
- `npm run build:ssr` - Build for server-side rendering
- `npm run lint` - Lint and fix JavaScript/TypeScript/Vue files
- `npm run format` - Format code with Prettier

### Database
- **Production**: MySQL
- **Testing**: MySQL (separate database)
- **Migrations**: Located in `database/migrations/`
- **Seeders**: `SchoolSeeder.php` creates complete test environment

## System Architecture

### Backend Structure
```
app/
├── Models/Academic/          # Student, Teacher, Grade, Subject
├── Models/Evaluation/        # Attendance, GradeReport, Observation
├── Models/Payment/           # Payment, Fee, Debt
├── Models/Communication/     # Notification, Circular, Message
├── Http/Controllers/Academic/ # Academic management controllers
├── Http/Requests/Academic/   # Form validation requests
├── Services/Academic/        # Business logic services
└── Policies/                 # Authorization policies
```

### Frontend Structure
```
resources/js/
├── pages/
│   ├── teacher/             # Teacher dashboard and tools
│   ├── parent/              # Parent panel (mobile-first)
│   ├── admin/               # Administrative interface
│   └── auth/                # Authentication pages
├── components/
│   ├── teacher/             # Teacher-specific components
│   ├── parent/              # Parent-specific components
│   └── ui/                  # Reusable UI components
└── layouts/                 # Page layouts for different roles
```

## Key Models and Relationships

### Academic Models
- **Student**: Core student information, belongs to Grade and Parent
- **Teacher**: Staff information, manages multiple Grades and Subjects
- **Grade**: Class groups (Pre-K, 1st, 2nd, etc.)
- **Subject**: Academic subjects with areas and levels

### Evaluation Models
- **Attendance**: Daily attendance records
- **GradeReport**: Academic evaluations and scores
- **Observation**: Behavioral notes (positive/negative)

### Key Relationships
- Student → Grade (belongs to)
- Student → Parent/User (belongs to)
- Teacher → Grades (many-to-many with subjects)
- Grade → Students (has many)
- Grade → Subjects (many-to-many)

## Security & Permissions

### Role System (Spatie Laravel Permission)
- **admin**: Full system access
- **teacher**: Academic management for assigned classes
- **parent**: View-only access to their children's data
- **student**: Minimal read access

### Data Protection
- Row-level security for parent-student relationships
- Teacher access limited to assigned grades/subjects
- Input validation and sanitization
- File upload restrictions and virus scanning
- Activity logging for audit trails

## Mobile-First Design Principles

### UI/UX Guidelines
- **Touch-friendly**: 44px minimum touch targets
- **Responsive**: Mobile → Tablet → Desktop progression
- **Simple Navigation**: Maximum 3 clicks to any function
- **Clear Typography**: Minimum 16px font size
- **High Contrast**: WCAG AA compliance
- **Fast Loading**: Optimize images and assets

### Component Design
- Use grid layouts (2 columns on mobile)
- Card-based interfaces for data display
- Quick action buttons with icons
- Swipe gestures for lists
- Pull-to-refresh functionality

## Development Workflow

### Phase 1 (MVP - Current)
- ✅ Authentication and roles
- ✅ Basic student management
- ✅ Teacher dashboard structure
- ✅ Parent panel design
- 🔄 Grade and attendance management
- 🔄 Basic reporting

### Phase 2 (Advanced)
- Payment management
- Communication system
- Advanced reporting
- Mobile notifications
- Performance optimization

### Best Practices
1. **Clean Architecture**: Services for business logic, Controllers for HTTP
2. **Validation**: Use Form Requests for all data validation
3. **Authorization**: Check permissions at controller and model level
4. **Testing**: Write feature tests for critical user journeys
5. **Documentation**: Update this file with architectural changes

## Common Development Tasks

### Adding New Academic Features
1. Create model in `app/Models/Academic/`
2. Add migration with proper foreign keys
3. Create service in `app/Services/Academic/`
4. Build controller with authorization
5. Design mobile-first Vue components
6. Add feature tests

### Database Operations
- Use soft deletes for academic records
- Include audit trails for sensitive data
- Maintain referential integrity
- Index foreign keys and search fields

### Frontend Components
- Follow atomic design principles
- Use TypeScript for all new components
- Implement loading states and error handling
- Test on mobile devices frequently

## Recommended Extensions

### Laravel Packages
- `spatie/laravel-permission` - Role management ✅
- `barryvdh/laravel-dompdf` - PDF generation
- `spatie/laravel-medialibrary` - File management
- `spatie/laravel-activitylog` - Audit logging ✅

### Vue 3 Packages
- `@vueuse/core` - Vue utilities ✅
- `vue-toastification` - Notifications
- `chart.js` - Charts and analytics
- `@headlessui/vue` - Accessible components ✅

## Performance & Scalability

### Database Optimization
- Use eager loading for relationships
- Implement database indexes strategically
- Consider read replicas for reporting
- Cache frequently accessed data

### Frontend Optimization
- Code splitting by route and role
- Image optimization and lazy loading
- Service worker for offline functionality
- Bundle analysis and tree shaking

### Server Configuration
- Redis for caching and sessions
- Queue workers for background tasks
- Load balancing for high traffic
- CDN for static assets

## Testing Strategy

### Backend Testing
- Feature tests for user workflows
- Unit tests for business logic services
- Database tests with transactions
- API endpoint testing

### Frontend Testing
- Component testing with Vue Test Utils
- E2E testing for critical paths
- Mobile device testing
- Accessibility testing

## Deployment & Monitoring

### Production Environment
- PHP 8.2+ with OPcache
- MySQL 8.0+ with proper configuration
- Redis for caching
- SSL/TLS encryption
- Regular security updates

### Monitoring
- Laravel Telescope for debugging
- Application performance monitoring
- Error tracking and alerting
- Database query analysis
- User activity analytics