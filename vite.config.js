import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js',
                    'resources/css/home.css', 'resources/js/home.js',
                    'resources/css/maps.css', 'resources/js/maps.js',
                    'resources/css/gallery.css', 'resources/js/gallery.js',
                    'resources/css/dashboard-admin.css', 'resources/js/dashboard-admin.js',
                    'resources/css/login.css', 'resources/js/login.js',
                    'resources/css/admin.css', 'resources/js/admin.js',
                    'resources/css/admin/haltes-index.css', 'resources/js/admin/haltes-index.js',
                    'resources/css/admin/haltes-edit.css', 'resources/js/admin/haltes-edit.js',
                    'resources/css/admin/haltes-create.css', 'resources/js/admin/haltes-create.js',
                    'resources/css/admin/halte-show.css', 'resources/js/admin/halte-show.js',
                    'resources/css/admin/rental/index.css', 'resources/js/admin/rental/index.js',
                    'resources/css/user/maps.css', 'resources/js/user/maps.js',
                    'resources/css/user/detail-halte.css', 'resources/js/user/detail-halte.js',
            ],
            refresh: true,
        }),
    ],
});
