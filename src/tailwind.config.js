import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                nutri: {
                    DEFAULT: '#718426', // Verde amarelado — primary
                    dark: '#485f24',    // Verde escuro — primary dark
                    olive: '#a5ac2e',   // Verde oliva apagado — secondary/neutral
                    accent: '#cd9f29',  // Laranja apagado — accent
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
