import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // Official LPKKS GROOMING LAB UNPAM Palette
                navy: {
                    DEFAULT: '#0B2D50',
                    50: '#F0F5FA',
                    100: '#EAF3FF', // Light Blue
                    200: '#D7E7FA', // Soft Blue
                    300: '#B0CEF2',
                    400: '#64A0E0',
                    500: '#1769AA', // Primary Blue
                    600: '#1769AA',
                    700: '#164A7A', // Secondary Navy
                    800: '#103B63',
                    900: '#0B2D50', // Primary Navy
                    950: '#061A2F',
                },
                lpkks: {
                    bg: '#F7FAFE',
                    surface: '#FFFFFF',
                    border: '#E2EAF4',
                    text: '#14243A',
                    muted: '#64748B',
                    navy: '#0B2D50',
                    'navy-dark': '#061A2F',
                    'navy-light': '#164A7A',
                    blue: '#1769AA',
                    'blue-light': '#EAF3FF',
                    'blue-soft': '#D7E7FA',
                },
                slate: {
                    50: '#F8FAFC',
                    100: '#F1F5F9',
                    200: '#E2E8F0',
                    300: '#CBD5E1',
                    400: '#94A3B8',
                    500: '#64748B',
                    600: '#475569',
                    700: '#334155',
                    800: '#1E293B',
                    900: '#0F172A',
                    950: '#0A0F1D',
                },
                emerald: {
                    400: '#34D399',
                    500: '#10B981',
                    600: '#059669',
                },
                rose: {
                    400: '#F87171',
                    500: '#EF4444',
                    600: '#DC2626',
                },
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
