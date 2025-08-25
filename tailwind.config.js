/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue', // Muito importante para o Tailwind ver os seus ficheiros Vue
    ],

    theme: {
        extend: {
            // Aqui poderemos adicionar as nossas cores personalizadas mais tarde
        },
    },

    plugins: [
        require('@tailwindcss/forms'), // Plugin que o Laravel Breeze (nosso starter kit) usa
        require('daisyui')           // O nosso plugin para componentes
    ],
};