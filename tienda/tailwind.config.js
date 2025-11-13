module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/css/**/*.css',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/livewire/flux-pro/stubs/**/*.blade.php',
        './vendor/livewire/flux/stubs/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                brand: 'var(--color-brand)',
                accent: 'var(--color-accent)',
                'accent-light': 'var(--color-accent-light)',
                success: 'var(--color-success)',
                danger: 'var(--color-danger)',
                warning: 'var(--color-warning)',
                info: 'var(--color-info)',
                background: 'var(--color-background)',
                text: 'var(--color-text)',
                surface: 'var(--color-surface)'
            },
            fontFamily: {
                sans: ['var(--font-sans)']
            }
        }
    },
    plugins: []
};
