import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            'vue': 'vue/dist/vue.esm-bundler.js',
        },
    },
    server: {
        host: 'localhost',
        port: 5173,
        headers: {
            'X-Frame-Options': 'SAMEORIGIN',
            'X-XSS-Protection': '1; mode=block',
            'X-Content-Type-Options': 'nosniff',
            'Referrer-Policy': 'strict-origin-when-cross-origin',
            'Permissions-Policy': 'geolocation=()',
            'Strict-Transport-Security': 'max-age=31536000; includeSubDomains',
            'Content-Security-Policy': [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:*",
                "script-src-elem 'self' 'unsafe-inline' http://localhost:*",
                "style-src 'self' 'unsafe-inline' http://localhost:*",
                "style-src-elem 'self' 'unsafe-inline' http://localhost:*",
                "img-src 'self' data: blob:",
                "font-src 'self' data:",
                "connect-src 'self' ws://localhost:* http://localhost:*"
            ].join('; ')
        },
    }
});