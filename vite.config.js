import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0', // This allows Vite to be accessed from any IP address
        port: 8000,      // Ensure this is the port you will use
        hmr: {
            host: '172.20.30.22', // This should remain localhost
        },
    },
});
