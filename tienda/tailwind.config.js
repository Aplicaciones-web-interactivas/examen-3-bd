/**
 * Tailwind configuration that maps project CSS variables to Tailwind utilities.
 * This allows using classes like `bg-accent`, `text-brand`, `font-sans` while
 * keeping runtime theming via CSS custom properties in `resources/css/app.css`.
 */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/css/**/*.css',
        './vendor/livewire/**/**/*.blade.php'
    ],
    theme: {
        extend: {
            colors: {
                accent: 'var(--color-accent)',
                'accent-light': 'var(--color-accent-light)',
                brand: 'var(--color-brand)',
                text: 'var(--color-text)',
                surface: 'var(--color-surface)'
                ,
                success: 'var(--color-success)',
                danger: 'var(--color-danger)',
                warning: 'var(--color-warning)',
                info: 'var(--color-info)',
                background: 'var(--color-background)',
                zinc: {
                    50: 'var(--color-zinc-50)',
                    100: 'var(--color-zinc-100)',
                    200: 'var(--color-zinc-200)',
                    300: 'var(--color-zinc-300)',
                    400: 'var(--color-zinc-400)',
                    500: 'var(--color-zinc-500)',
                    600: 'var(--color-zinc-600)',
                    700: 'var(--color-zinc-700)',
                    800: 'var(--color-zinc-800)',
                    900: 'var(--color-zinc-900)'
                }
            },
            fontFamily: {
                sans: ['var(--font-sans)']
            }
        }
    },
    plugins: [],
};
