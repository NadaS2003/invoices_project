// vite.config.mjs
import { defineConfig } from 'vite';
import laravelVitePlugin from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravelVitePlugin({
            input: [
                'resources/js/app.js',
                'resources/sass/app.scss', // Ensure this is included
            ],
            refresh: true,
        }),
    ],
});
