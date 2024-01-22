const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            height: {
                'height-60vh': '60vh',
            },
            width: {
                'width-100vw': '100vw',
            },
            boxShadow: {
                '3xl': '7px 15px 7px -3px rgba(0, 0, 0, 0.3)',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
