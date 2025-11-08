/**
 * Service Card Scroll Animation
 *
 * Animates service cards with Intersection Observer API
 * @package Corporate_SEO_Pro
 */

(function() {
    'use strict';

    // Add .js class to body for progressive enhancement
    document.documentElement.classList.add('js');

    /**
     * Initialize scroll animations for service cards
     */
    function initServiceScrollAnimation() {
        const serviceCards = document.querySelectorAll('[data-scroll-animate]');

        // If no cards found, exit
        if (serviceCards.length === 0) {
            return;
        }

        // Check if IntersectionObserver is supported
        if (!('IntersectionObserver' in window)) {
            // Fallback: show all cards immediately
            serviceCards.forEach(card => card.classList.add('animate-in'));
            return;
        }

        // Intersection Observer options
        const observerOptions = {
            root: null, // viewport
            rootMargin: '0px 0px -100px 0px', // Trigger 100px before entering viewport
            threshold: 0.15 // Trigger when 15% of the element is visible
        };

        // Callback function for intersection
        const observerCallback = (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    // Add animation class
                    const card = entry.target;

                    // Get the card's index in the parent
                    const allCards = Array.from(document.querySelectorAll('[data-scroll-animate]'));
                    const cardIndex = allCards.indexOf(card);
                    const delay = cardIndex * 150; // 150ms delay between each card

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
