import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            screens: {
                'xs': '380px',
                // => @media (min-width: 380px) { ... }
            },
            colors: {
                // Colores principales del logo
                primary: {
                    50: '#f0f7ff',
                    100: '#e0efff',
                    200: '#bae2ff',
                    300: '#7cc8ff',
                    400: '#36acff',
                    500: '#0891ff',
                    600: '#0074d9',
                    700: '#005fb3',
                    800: '#005194',
                    900: '#123155', // Color principal actual
                    950: '#0d1e35',
                },

                // Tonos azules secundarios
                secondary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#83aec5', // Color secundario actual
                    600: '#5a91b5',
                    700: '#5a748b',
                    800: '#123155',
                    900: '#0d1e35',
                },

                // Tonos coral/salmón para acentos
                coral: {
                    50: '#fef7f7',
                    100: '#feecec',
                    200: '#fddede',
                    300: '#fcc4c4',
                    400: '#f89b9b',
                    500: '#D94F41', // Color coral principal
                    600: '#b23a2f',
                    700: '#9b5e62', // Color marrón-coral
                    800: '#7d4a4e',
                    900: '#693e42',
                },

                // Tonos gris-azul
                slate: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#5a748b', // Color gris-azul medio
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0d0e1a', // Color más oscuro
                },

                // Crema/beige para fondos suaves
                cream: {
                    50: '#fefdfb',
                    100: '#fefcf7',
                    200: '#fef9ef',
                    300: '#fef4e2',
                    400: '#fdecc8',
                    500: '#f5efdd', // Color crema principal
                    600: '#e6d5a3',
                    700: '#d4b86e',
                    800: '#c2984a',
                    900: '#a67c35',
                },

                // Colores de marca adicionales
                brand: {
                    light: '#83aec5',    // Azul claro
                    primary: '#123155',   // Azul marino
                    dark: '#0d0e1a',      // Casi negro
                    coral: '#D94F41',     // Coral
                    salmon: '#9b5e62',    // Salmón/marrón
                    slate: '#5a748b',     // Gris azulado
                    cream: '#f5efdd',     // Crema
                    steel: '#7793b4',     // Azul acero
                },

                // Mantener colores de Tailwind por defecto
                white: '#ffffff',
                black: '#000000',
                transparent: 'transparent',
                current: 'currentColor',

                // Grises personalizados basados en tu paleta
                gray: {
                    50: '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // Agregamos gradientes personalizados
            backgroundImage: {
                'gradient-primary': 'linear-gradient(135deg, #123155 0%, #83aec5 100%)',
                'gradient-coral': 'linear-gradient(135deg, #D94F41 0%, #9b5e62 100%)',
                'gradient-ocean': 'linear-gradient(135deg, #123155 0%, #5a748b 50%, #83aec5 100%)',
                'gradient-sunset': 'linear-gradient(135deg, #f5efdd 0%, #D94F41 50%, #123155 100%)',
            },
        },
    },

    plugins: [forms, typography],
};
