import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/auth.css', 
                'resources/css/dashboard.css', 
                'resources/css/jobs.css',
                'resources/css/recruiter.css',
                'resources/css/seeker.css',
                'resources/css/job-details.css',
                'resources/css/animations.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
