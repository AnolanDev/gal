<?php

namespace App\Policies;

use App\Models\Academic\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher', 'parent']);
    }

    public function view(User $user, Student $student): bool
    {
        // Admin can view all students
        if ($user->hasRole('admin')) {
            return true;
        }

        // Parents can only view their own children
        if ($user->hasRole('parent')) {
            return $student->parent_user_id === $user->id;
        }

        // Teachers can view students in their assigned grades
        if ($user->hasRole('teacher') && $user->teacher) {
            return $user->teacher->canManageStudent($student);
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }

    public function update(User $user, Student $student): bool
    {
        // Admin can update all students
        if ($user->hasRole('admin')) {
            return true;
        }

        // Teachers can update students in their assigned grades
        if ($user->hasRole('teacher') && $user->teacher) {
            return $user->teacher->canManageStudent($student);
        }

        return false;
    }

    public function delete(User $user, Student $student): bool
    {
        // Only admins can delete students
        return $user->hasRole('admin');
    }

    public function restore(User $user, Student $student): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Student $student): bool
    {
        return $user->hasRole('admin');
    }

    public function transfer(User $user, Student $student): bool
    {
        return $user->hasRole('admin');
    }

    public function export(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'teacher']);
    }
}