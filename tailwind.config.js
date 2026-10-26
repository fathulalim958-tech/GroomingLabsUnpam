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
                // GlowCut Luxury Dark Palette mapped to slate & gray utilities
                slate: {
                    50: '#FAF7F2',
                    100: '#F5F0E6', // GlowCut Main warm off-white text
                    200: '#E5DDD5',
                    300: '#D1C7BD',
                    400: '#A7A7A7', // GlowCut Secondary muted text
                    500: '#737373',
                    600: '#4A4A4A',
                    700: '#343434', // GlowCut Fine dark border
                    750: '#232323',
                    800: '#171717', // GlowCut Card & Panel surface
                    850: '#141414', // GlowCut Input field background
                    900: '#111111', // GlowCut Secondary surface / headers
                    950: '#0B0B0B', // GlowCut Pure charcoal main background
                },
                gray: {
                    50: '#FAF7F2',
                    100: '#F5F0E6',
                    200: '#E5DDD5',
                    300: '#D1C7BD',
                    400: '#A7A7A7',
                    500: '#737373',
                    600: '#4A4A4A',
                    700: '#343434',
                    750: '#232323',
                    800: '#171717',
                    850: '#141414',
                    900: '#111111',
                    950: '#0B0B0B',
                },
                // GlowCut Champagne Gold Palette mapped to amber & gold
                gold: {
                    50: '#FAF6EB',
                    100: '#F5ECDC',
                    200: '#ECD9B5',
                    300: '#E2C48D',
                    400: '#F0D58A', // GlowCut Light Gold
                    500: '#D4AF5A', // GlowCut Main Champagne Gold
                    600: '#B8933E', // GlowCut Hover Gold
                    700: '#9C772F',
                    800: '#6E521F',
                    900: '#423112',
                    950: '#231805',
                },
                amber: {
                    50: '#FAF6EB',
                    100: '#F5ECDC',
                    200: '#ECD9B5',
                    300: '#E2C48D',
                    400: '#F0D58A', // GlowCut Light Gold
                    500: '#D4AF5A', // GlowCut Main Champagne Gold
                    600: '#B8933E', // GlowCut Hover Gold
                    700: '#9C772F',
                    800: '#6E521F',
                    900: '#423112',
                    950: '#231805',
                },
                yellow: {
                    400: '#F0D58A',
                    500: '#D4AF5A',
                    600: '#B8933E',
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
                glowcut: {
                    bg: '#0B0B0B',
                    surface: '#111111',
                    card: '#171717',
                    input: '#141414',
                    border: '#343434',
                    gold: '#D4AF5A',
                    'gold-light': '#F0D58A',
                    'gold-dark': '#B8933E',
                    text: '#F5F0E6',
                    'text-muted': '#A7A7A7',
                },
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
