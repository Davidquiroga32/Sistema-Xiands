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
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                headings: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                black: '#080808',
                dark: '#111111',
                card: '#141414',
                card2: '#1a1a1a',
                border: '#222222',
                'border-lit': '#333333',
                'silver-dark': '#555555',
                silver: '#888888',
                'silver-light': '#b8b8b8',
                'silver-bright': '#d8d8d8',
                white: '#f0f0f0',
                'green-status': '#5a8a5a',
            },
            spacing: {
                'nav-h': '72px',
            },
            animation: {
                'fade-up': 'fadeUp 0.4s ease both',
                'fade-down': 'fadeDown 0.4s ease both',
                'shimmer': 'shimmer 3s ease-in-out infinite',
                'slide-up': 'slideUp 0.3s ease',
            },
        },
    },

    plugins: [forms],
};
