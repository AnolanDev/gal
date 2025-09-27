<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Institution Information
    |--------------------------------------------------------------------------
    |
    | Configuration for Gimnasio Americano Lugano institution details
    |
    */

    'name' => 'Gimnasio Americano Lugano',
    'short_name' => 'GAL',
    'logo' => 'logo-gal.png',
    'logo_path' => 'images/logos/institution/logo-gal.png',
    
    // Contact Information
    'address' => env('INSTITUTION_ADDRESS', ''),
    'phone' => env('INSTITUTION_PHONE', ''),
    'email' => env('INSTITUTION_EMAIL', ''),
    'website' => env('INSTITUTION_WEBSITE', ''),
    
    // Academic Configuration
    'academic_year_start_month' => 2, // February
    'academic_year_end_month' => 12,  // December
    
    // Image Settings
    'logo_max_width' => 500,
    'logo_max_height' => 200,
    'profile_image_size' => 300,
];