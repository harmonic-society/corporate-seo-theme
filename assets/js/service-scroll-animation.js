/**
 * Service Card Scroll Animation
 *
 * Animates service cards with Intersection Observer API
 * @package Corporate_SEO_Pro
 */

(function() {
    'use strict';

    /**
     * Initialize scroll animations for service cards
     */
    function initServiceScrollAnimation() {
        // Check if IntersectionObserver is supported
        if (!('IntersectionObserver' in window)) {
            // Fallback: show all cards immediately
            const cards = document.querySelectorAll('[data-scroll-animate]');
            cards.forEach(card => card.classList.add('animate-in'));
            return;
        }

        // Intersection Observer options
        const observerOptions = {
            root: null, // viewport
            rootMargin: '0px 0px -100px 0px', // Trigger 100px before entering viewport
            threshold: 0.15 // Trigger when 15% of the element is visible
        };

        // Callback function for intersection
        const observerCallback = (entries, observer) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    // Add animation class with delay based on index
                    const card = entry.target;
                    const delay = index * 150; // 150ms delay between each card

                    setTimeout(() => {
                        card.classList.add('animate-in');
                    }, delay);

                    // Unobserve after animation to improve performance
                    observer.unobserve(card);
                }
            });
        };

        // Create the observer
        const observer = new IntersectionObserver(observerCallback, observerOptions);

        // Observe all service cards
        const serviceCards = document.querySelectorAll('[data-scroll-animate]');
        serviceCards.forEach(card => {
            observer.observe(card);
        });
    }

    /**
     * Initialize on DOM ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initServiceScrollAnimation);
    } else {
        initServiceScrollAnimation();
    }

})();
