import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import colors from "tailwindcss/colors";

import preset from './vendor/filament/support/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    presets: [
        preset,
        require("./vendor/power-components/livewire-powergrid/tailwind.config.js"),
    ],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*Table.php',
        './vendor/power-components/livewire-powergrid/resources/views/**/*.php',
        './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    safelist: [
        'text-red-400',
        'text-green-400',
        'text-yellow-400',
        'text-orange-400',
        'text-teal-400',
        'text-blue-400',
        'text-indigo-400',
        'text-purple-400',
        'text-pink-400',
        'text-cyan-400',
        'text-cyan-300',
        'text-yellow-600'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Montserrat', ...defaultTheme.fontFamily.sans],
            },
            maxWidth: {
                'prose': '100ch',
                '8xl': '90rem', // 1440px
            },
            colors: {
                primary: '#6366F1',
                danger: '#6366F1',
                'primary-light': '#818CF8', // светлая версия primary
                'primary-dark': '#4F46E5', // темная версия primary
                'pg-primary': colors.gray
            },
            backgroundColor: {
                'secondary': '#101827',
            },
            borderColor: {
                primary: '#6366F1',
            },
            textColor: {
                DEFAULT: '#101827', // Устанавливаем цвет текста по умолчанию
            },
            width: {
                '128': '32rem',   // 512px
                '160': '40rem',   // 640px
                '192': '48rem',   // 768px
                '224': '56rem',   // 896px
                '256': '64rem',   // 1024px
                '288': '72rem',   // 1152px
                '320': '80rem',   // 1280px
                '384': '96rem',   // 1536px
            },
            height: {
                '10': '40px',
            },
            boxShadow: {
                'top': '0 -2px 4px rgba(0, 0, 0, 0.06)',
                'bottom': '0 2px 4px rgba(0, 0, 0, 0.06)',
            },
        },
    },

    plugins: [forms, typography],
};
