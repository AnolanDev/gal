<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Academic\Student;
use App\Models\Academic\Teacher;
use App\Models\Academic\Grade;
use App\Models\Academic\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles and Permissions
        $this->createRolesAndPermissions();
        
        // Create Admin User
        $this->createAdminUser();
        
        // Create Academic Structure
        $this->createGrades();
        $this->createSubjects();
        
        // Create Teachers
        $this->createTeachers();
        
        // Create Parents and Students
        $this->createParentsAndStudents();
    }

    private function createRolesAndPermissions(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $teacherRole = Role::create(['name' => 'teacher']);
        $parentRole = Role::create(['name' => 'parent']);
        $studentRole = Role::create(['name' => 'student']);

        // Create Permissions
        $permissions = [
            // Student Management
            'view_students',
            'create_students',
            'edit_students',
            'delete_students',
            
            // Teacher Management
            'view_teachers',
            'create_teachers',
            'edit_teachers',
            'delete_teachers',
            
            // Grade Management
            'view_grades',
            'manage_grades',
            
            // Attendance
            'view_attendance',
            'manage_attendance',
            
            // Grade Reports
            'view_grade_reports',
            'manage_grade_reports',
            
            // Observations
            'view_observations',
            'manage_observations',
            
            // Reports
            'view_reports',
            'generate_reports',
            
            // Payments
            'view_payments',
            'manage_payments',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign Permissions to Roles
        $adminRole->givePermissionTo(Permission::all());
        
        $teacherRole->givePermissionTo([
            'view_students',
            'view_attendance',
            'manage_attendance',
            'view_grade_reports',
            'manage_grade_reports',
            'view_observations',
            'manage_observations',
        ]);
        
        $parentRole->givePermissionTo([
            'view_students', // Only their own children
            'view_attendance',
            'view_grade_reports',
            'view_observations',
            'view_payments',
        ]);
    }

    private function createAdminUser(): void
    {
        $admin = User::create([
            'name' => 'Administrador GAL',
            'email' => 'admin@gal.edu.co',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('admin');
    }

    private function createGrades(): void
    {
        $grades = [
            // Preescolar
            ['name' => 'Pre-Jardín', 'level' => 'preschool', 'section' => 'A'],
            ['name' => 'Pre-Jardín', 'level' => 'preschool', 'section' => 'B'],
            ['name' => 'Jardín', 'level' => 'preschool', 'section' => 'A'],
            ['name' => 'Jardín', 'level' => 'preschool', 'section' => 'B'],
            ['name' => 'Transición', 'level' => 'preschool', 'section' => 'A'],
            ['name' => 'Transición', 'level' => 'preschool', 'section' => 'B'],
            
            // Básica Primaria
            ['name' => '1°', 'level' => 'elementary', 'section' => 'A'],
            ['name' => '1°', 'level' => 'elementary', 'section' => 'B'],
            ['name' => '2°', 'level' => 'elementary', 'section' => 'A'],
            ['name' => '2°', 'level' => 'elementary', 'section' => 'B'],
            ['name' => '3°', 'level' => 'elementary', 'section' => 'A'],
            ['name' => '3°', 'level' => 'elementary', 'section' => 'B'],
            ['name' => '4°', 'level' => 'elementary', 'section' => 'A'],
            ['name' => '4°', 'level' => 'elementary', 'section' => 'B'],
            ['name' => '5°', 'level' => 'elementary', 'section' => 'A'],
            ['name' => '5°', 'level' => 'elementary', 'section' => 'B'],
        ];

        foreach ($grades as $gradeData) {
            Grade::create([
                'name' => $gradeData['name'],
                'level' => $gradeData['level'],
                'section' => $gradeData['section'],
                'academic_year' => date('Y'),
                'max_students' => 25,
                'classroom' => fake()->numberBetween(101, 320),
                'status' => 'active',
            ]);
        }
    }

    private function createSubjects(): void
    {
        $subjects = [
            // Preescolar
            ['name' => 'Dimensión Comunicativa', 'area' => 'language', 'level' => 'preschool', 'is_main' => true],
            ['name' => 'Dimensión Cognitiva', 'area' => 'mathematics', 'level' => 'preschool', 'is_main' => true],
            ['name' => 'Dimensión Corporal', 'area' => 'physical_education', 'level' => 'preschool', 'is_main' => false],
            ['name' => 'Dimensión Artística', 'area' => 'arts', 'level' => 'preschool', 'is_main' => false],
            ['name' => 'Dimensión Ética', 'area' => 'ethics', 'level' => 'preschool', 'is_main' => false],
            
            // Primaria
            ['name' => 'Matemáticas', 'area' => 'mathematics', 'level' => 'elementary', 'is_main' => true],
            ['name' => 'Lenguaje', 'area' => 'language', 'level' => 'elementary', 'is_main' => true],
            ['name' => 'Ciencias Naturales', 'area' => 'science', 'level' => 'elementary', 'is_main' => true],
            ['name' => 'Ciencias Sociales', 'area' => 'social_studies', 'level' => 'elementary', 'is_main' => true],
            ['name' => 'Inglés', 'area' => 'english', 'level' => 'elementary', 'is_main' => false],
            ['name' => 'Educación Física', 'area' => 'physical_education', 'level' => 'elementary', 'is_main' => false],
            ['name' => 'Educación Artística', 'area' => 'arts', 'level' => 'elementary', 'is_main' => false],
            ['name' => 'Ética y Valores', 'area' => 'ethics', 'level' => 'elementary', 'is_main' => false],
            ['name' => 'Religión', 'area' => 'religion', 'level' => 'elementary', 'is_main' => false],
            ['name' => 'Tecnología e Informática', 'area' => 'technology', 'level' => 'elementary', 'is_main' => false],
        ];

        foreach ($subjects as $subjectData) {
            Subject::create([
                'name' => $subjectData['name'],
                'area' => $subjectData['area'],
                'academic_level' => $subjectData['level'],
                'is_main_subject' => $subjectData['is_main'],
                'hours_per_week' => $subjectData['is_main'] ? 5 : 2,
                'status' => 'active',
                'color' => fake()->hexColor(),
            ]);
        }
    }

    private function createTeachers(): void
    {
        $specializations = [
            'Educación Preescolar',
            'Básica Primaria',
            'Matemáticas',
            'Lenguaje',
            'Ciencias Naturales',
            'Ciencias Sociales',
            'Inglés',
            'Educación Física',
            'Artes',
        ];

        for ($i = 1; $i <= 20; $i++) {
            $user = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->email(),
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('teacher');

            Teacher::create([
                'user_id' => $user->id,
                'identification_number' => fake()->unique()->numerify('##########'),
                'identification_type' => fake()->randomElement(['CC', 'CE']),
                'specialization' => fake()->randomElement($specializations),
                'education_level' => fake()->randomElement(['Licenciatura', 'Especialización', 'Maestría']),
                'hire_date' => fake()->dateTimeBetween('-10 years', 'now'),
                'status' => 'active',
                'phone' => fake()->phoneNumber(),
                'emergency_contact' => fake()->name(),
                'emergency_phone' => fake()->phoneNumber(),
                'contract_type' => fake()->randomElement(['indefinido', 'temporal', 'catedra']),
            ]);
        }
    }

    private function createParentsAndStudents(): void
    {
        $grades = Grade::all();

        foreach ($grades as $grade) {
            // Create 15-20 students per grade
            $studentCount = fake()->numberBetween(15, 20);
            
            for ($i = 1; $i <= $studentCount; $i++) {
                // Create parent user
                $parent = User::create([
                    'name' => fake()->name(),
                    'email' => fake()->unique()->email(),
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]);

                $parent->assignRole('parent');

                // Create student
                $birthYear = $this->getBirthYearForGrade($grade->name, $grade->level);
                
                Student::create([
                    'identification_number' => fake()->unique()->numerify('##########'),
                    'identification_type' => 'TI',
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'birth_date' => fake()->dateTimeBetween($birthYear . '-01-01', $birthYear . '-12-31'),
                    'gender' => fake()->randomElement(['M', 'F']),
                    'address' => fake()->address(),
                    'phone' => fake()->phoneNumber(),
                    'emergency_contact' => fake()->name(),
                    'emergency_phone' => fake()->phoneNumber(),
                    'blood_type' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
                    'grade_id' => $grade->id,
                    'parent_user_id' => $parent->id,
                    'enrollment_date' => fake()->dateTimeBetween('2024-01-01', '2024-02-28'),
                    'status' => 'active',
                ]);
            }
        }
    }

    private function getBirthYearForGrade(string $gradeName, string $level): int
    {
        $currentYear = date('Y');
        
        if ($level === 'preschool') {
            return match ($gradeName) {
                'Pre-Jardín' => $currentYear - 3,
                'Jardín' => $currentYear - 4,
                'Transición' => $currentYear - 5,
                default => $currentYear - 4,
            };
        }
        
        // Elementary
        return match ($gradeName) {
            '1°' => $currentYear - 6,
            '2°' => $currentYear - 7,
            '3°' => $currentYear - 8,
            '4°' => $currentYear - 9,
            '5°' => $currentYear - 10,
            default => $currentYear - 7,
        };
    }
}