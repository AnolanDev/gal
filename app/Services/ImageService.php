<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageService
{
    public const STUDENT_PROFILE_PATH = 'images/students/profiles';
    public const STUDENT_DOCUMENT_PATH = 'images/students/documents';
    public const TEACHER_PROFILE_PATH = 'images/teachers/profiles';
    public const TEACHER_DOCUMENT_PATH = 'images/teachers/documents';
    public const LOGO_INSTITUTION_PATH = 'images/logos/institution';
    public const LOGO_DEPARTMENT_PATH = 'images/logos/departments';
    public const DOCUMENT_PATH = 'images/documents';
    public const GENERAL_PATH = 'images/general';

    private $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('public');
    }

    /**
     * Upload and store an image
     */
    public function uploadImage(UploadedFile $file, string $path, array $options = []): string
    {
        $filename = $this->generateFilename($file, $options['prefix'] ?? null);
        $fullPath = $path . '/' . $filename;

        // Resize image if dimensions are specified
        if (isset($options['width']) || isset($options['height'])) {
            $image = Image::make($file);
            
            if (isset($options['width']) && isset($options['height'])) {
                $image->fit($options['width'], $options['height']);
            } elseif (isset($options['width'])) {
                $image->resize($options['width'], null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $image->resize(null, $options['height'], function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $this->disk->put($fullPath, $image->stream());
        } else {
            $this->disk->putFileAs($path, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Delete an image
     */
    public function deleteImage(string $path): bool
    {
        if ($this->disk->exists($path)) {
            return $this->disk->delete($path);
        }
        
        return false;
    }

    /**
     * Get image URL
     */
    public function getImageUrl(string $path): string
    {
        return Storage::url($path);
    }

    /**
     * Upload student profile image
     */
    public function uploadStudentProfile(UploadedFile $file, string $studentId): string
    {
        return $this->uploadImage($file, self::STUDENT_PROFILE_PATH, [
            'prefix' => 'student_' . $studentId,
            'width' => 300,
            'height' => 300
        ]);
    }

    /**
     * Upload teacher profile image
     */
    public function uploadTeacherProfile(UploadedFile $file, string $teacherId): string
    {
        return $this->uploadImage($file, self::TEACHER_PROFILE_PATH, [
            'prefix' => 'teacher_' . $teacherId,
            'width' => 300,
            'height' => 300
        ]);
    }

    /**
     * Upload institution logo
     */
    public function uploadInstitutionLogo(UploadedFile $file): string
    {
        return $this->uploadImage($file, self::LOGO_INSTITUTION_PATH, [
            'prefix' => 'logo-gal',
            'width' => 500
        ]);
    }

    /**
     * Get GAL institution logo path
     */
    public function getGalLogoPath(): string
    {
        return self::LOGO_INSTITUTION_PATH . '/logo-gal.png';
    }

    /**
     * Get GAL institution logo URL
     */
    public function getGalLogoUrl(): string
    {
        return $this->getImageUrl($this->getGalLogoPath());
    }

    /**
     * Upload department logo
     */
    public function uploadDepartmentLogo(UploadedFile $file, string $departmentId): string
    {
        return $this->uploadImage($file, self::LOGO_DEPARTMENT_PATH, [
            'prefix' => 'dept_' . $departmentId,
            'width' => 300
        ]);
    }

    /**
     * Upload document image
     */
    public function uploadDocument(UploadedFile $file, string $prefix = null): string
    {
        return $this->uploadImage($file, self::DOCUMENT_PATH, [
            'prefix' => $prefix ?? 'doc'
        ]);
    }

    /**
     * Generate unique filename
     */
    private function generateFilename(UploadedFile $file, string $prefix = null): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);
        
        $name = $prefix ? $prefix . '_' . $timestamp . '_' . $random : $timestamp . '_' . $random;
        
        return $name . '.' . $extension;
    }

    /**
     * Get image dimensions
     */
    public function getImageDimensions(string $path): array
    {
        if (!$this->disk->exists($path)) {
            return ['width' => 0, 'height' => 0];
        }

        $image = Image::make($this->disk->get($path));
        
        return [
            'width' => $image->width(),
            'height' => $image->height()
        ];
    }

    /**
     * Check if file is valid image
     */
    public function isValidImage(UploadedFile $file): bool
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        return in_array($file->getMimeType(), $allowedMimes) && $file->getSize() <= $maxSize;
    }
}