import axios from 'axios';

export const useAds = () => {
    /**
     * Record an ad impression.
     */
    const recordImpression = async (adUnitId) => {
        try {
            await axios.post('/api/ads/track', {
                ad_unit_id: adUnitId,
                url: window.location.href
            });
        } catch (e) {
            console.warn('Ad tracking failed');
        }
    };

    /**
     * Initialize AdSense units on the page.
     */
    const initAdsense = () => {
        try {
            (window.adsbygoogle = window.adsbygoogle || []).push({});
        } catch (e) {
            // Silently fail if AdSense is already initialized or blocked
        }
    };

    return {
        recordImpression,
        initAdsense
    };
};
