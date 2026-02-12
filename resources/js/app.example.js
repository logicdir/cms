import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

createInertiaApp({
    resolve: (name) => {
        // Handle modules (e.g., "Blog::Pages/Post")
        if (name.includes('::')) {
            const [module, page] = name.split('::');
            // Check both app/Modules and modules/ directories
            const modulePage = import.meta.glob([
                '../../app/Modules/*/resources/js/Pages/**/*.vue',
                '../../modules/*/resources/js/Pages/**/*.vue'
            ]);

            // Map the module name to the actual path
            // This is a simplified version; in production, you'd match the module name to the folder
            const path = Object.keys(modulePage).find(p => p.includes(`${module}/resources/js/Pages/${page}.vue`));
            
            if (path) {
                return typeof modulePage[path] === 'function' ? modulePage[path]() : modulePage[path];
            }
        }

        // Default resolution for core app
        return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});
