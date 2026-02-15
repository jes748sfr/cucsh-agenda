import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Colores Institucionales UDG / CUCSH
                udg: {
                    blue: '#202945',         // Azul profundo institucional
                    'blue-light': '#2d3a60', // Para estados hover
                    'blue-dark': '#131929',  // Para estados active/focus
                    red: '#e31837',          // Rojo institucional / Errores
                    gold: '#e8ab16',         // Dorado / Detalles a destacar
                },
                // Alias semánticos (Obliga a Claude a usar estos en lugar de genéricos)
                primary: {
                    DEFAULT: '#202945',
                    hover: '#2d3a60',
                },
                danger: {
                    DEFAULT: '#e31837',
                },
            },
        },
    },

    plugins: [forms],
};
