import { computed } from 'vue';

export function useImageOptimization() {
    /**
     * Generate responsive srcset for an image.
     */
    const getSrcSet = (path, widths = [320, 640, 768, 1024]) => {
        if (!path) return '';

        return widths
            .map(w => `${path}?w=${w}&q=80&fmt=webp ${w}w`)
            .join(', ');
    };

    /**
     * Get optimized WebP URL.
     */
    const getWebpUrl = (path, width = null) => {
        if (!path) return '';
        let url = `${path}?fmt=webp`;
        if (width) url += `&w=${width}`;
        return url;
    };

    return {
        getSrcSet,
        getWebpUrl
    };
}
