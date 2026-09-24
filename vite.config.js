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
    server: {
        host: 'localhost',
        port: 5173,
        strictPort: false,
    },
    resolve: {
        // No alias to Vue's "full build": every template is compiled at build time, so the browser doesn't
        // need to download the template compiler.
        alias: {
            '@': '/resources/js',
        },
    },
    // Minification is on for production builds (JS and CSS); this also strips leftover `debugger` statements.
    esbuild: {
        drop: ['debugger'],
        legalComments: 'none',
    },
    build: {
        rollupOptions: {
            output: {
                // Vue and the router change rarely, so they get their own file: a new release of the site
                // doesn't make returning visitors download them again.
                manualChunks(id) {
                    if (id.includes('node_modules/vue/') || id.includes('node_modules/@vue/') || id.includes('node_modules/vue-router/')) {
                        return 'vendor';
                    }
                },
            },
        },
    },
});
