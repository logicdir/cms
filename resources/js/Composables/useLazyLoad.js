import { onMounted, onUnmounted, ref } from 'vue';

export function useLazyLoad() {
    const observer = ref(null);

    const observe = (el, callback) => {
        if (!el) return;

        observer.value = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    callback();
                    unobserve(el);
                }
            });
        }, {
            rootMargin: '50px',
            threshold: 0.1
        });

        observer.value.observe(el);
    };

    const unobserve = (el) => {
        if (observer.value && el) {
            observer.value.unobserve(el);
        }
    };

    onUnmounted(() => {
        if (observer.value) {
            observer.value.disconnect();
        }
    });

    return {
        observe,
        unobserve
    };
}
