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
                // GlowCut Deep High-Contrast Obsidian & Jet Black Palette
                slate: {
                    50: '#FAFAFA',
                    100: '#FFFFFF', // Pure crisp white text (kontras tinggi, tidak kusam)
                    200: '#ECECEC',
                    300: '#D4D4D4',
                    400: '#B0B0B0', // Neutral bright gray secondary text
                    500: '#737373',
                    600: '#404040',
                    700: '#2A2A2A', // Crisp fine border
                    750: '#1E1E1E',
                    800: '#141414', // Deep obsidian card & panel
                    850: '#0E0E0E', // Deep jet input field
                    900: '#0C0C0C', // Deep black secondary surface / headers
                    950: '#050505', // Deep Jet Black (pekat, tidak pucat keabuan)
                },
                gray: {
                    50: '#FAFAFA',
                    100: '#FFFFFF',
                    200: '#ECECEC',
                    300: '#D4D4D4',
                    400: '#B0B0B0',
                    500: '#737373',
                    600: '#404040',
                    700: '#2A2A2A',
                    750: '#1E1E1E',
                    800: '#141414',
                    850: '#0E0E0E',
                    900: '#0C0C0C',
                    950: '#050505',
                },
                // Rich Vibrant Royal Gold Palette (pekat, berkilau, mewah, tidak pucat)
                gold: {
                    50: '#FFFDF5',
                    100: '#FFF4D4',
                    200: '#FCE6A3',
                    300: '#FBD778',
                    400: '#F7C754', // Vibrant shining light gold
                    500: '#E5A823', // Rich Warm Royal Gold (pekat & menyala)
                    600: '#C88A14', // Deep burnished hover gold
                    700: '#A6700E',
                    800: '#7A5007',
                    900: '#4D3203',
                    950: '#2B1B01',
                },
                amber: {
                    50: '#FFFDF5',
                    100: '#FFF4D4',
                    200: '#FCE6A3',
                    300: '#FBD778',
                    400: '#F7C754', // Vibrant shining light gold
                    500: '#E5A823', // Rich Warm Royal Gold
                    600: '#C88A14', // Deep burnished hover gold
                    700: '#A6700E',
                    800: '#7A5007',
                    900: '#4D3203',
                    950: '#2B1B01',
                },
                yellow: {
                    400: '#F7C754',
                    500: '#E5A823',
                    600: '#C88A14',
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
                    bg: '#050505',
                    surface: '#0C0C0C',
                    card: '#141414',
                    input: '#0E0E0E',
                    border: '#2A2A2A',
                    gold: '#E5A823',
                    'gold-light': '#F7C754',
                    'gold-dark': '#C88A14',
                    text: '#FFFFFF',
                    'text-muted': '#B0B0B0',
                },
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
