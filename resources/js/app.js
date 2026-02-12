import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';

createInertiaApp({
    resolve: (name) => {
        // Handle modules (e.g., "Blog::Pages/Post")
        if (name.includes('::')) {
            const [module, page] = name.split('::');
            const modulePage = import.meta.glob([
                '../../app/Modules/*/resources/js/Pages/**/*.vue',
                '../../modules/*/resources/js/Pages/**/*.vue'
            ]);

            const path = Object.keys(modulePage).find(p => p.includes(`${module}/resources/js/Pages/${page}.vue`));

            if (path) {
                return typeof modulePage[path] === 'function' ? modulePage[path]() : modulePage[path];
            }
        }

        return resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
});
