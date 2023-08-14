import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/layouts/pdf.blade.php',
        './resources/views/pdf/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            }
        },
    },

    safelist: ['text-red-400'],

    plugins: [forms, typography, require('flowbite/plugin')],
};
