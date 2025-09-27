import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f0f9f0',
                    100: '#dcf2dc',
                    200: '#bde5bd',
                    300: '#8fd48f',
                    400: '#5bb85b',
                    500: '#369e36',
                    600: '#22831c',
                    700: '#1c6b18',
                    800: '#1a5e16',
                    900: '#164e13',
                },
                academic: {
                    'preescolar': '#fbbf24',
                    'primaria': '#10b981',
                    'presente': '#059669',
                    'ausente': '#dc2626',
                    'tardanza': '#d97706',
                    'excusado': '#369e36',
                }
            },
        },
    },

    plugins: [forms],
};
