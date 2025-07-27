/**
 * Hero Feature Visibility Fix
 * Ensures hero features become visible even if animations fail
 */

(function() {
    'use strict';

    // Function to force visibility of hero features
    function ensureHeroFeaturesVisible() {
        const heroFeatures = document.querySelectorAll('.hero-feature, .feature-item');
        
        heroFeatures.forEach(feature => {
            // Check if element is still invisible
            const computedStyle = window.getComputedStyle(feature);
            if (computedStyle.opacity === '0') {
                feature.style.opacity = '1';
                feature.style.transform = 'translateY(0)';
            }
        });
        
        // Add loaded class to body
        document.body.classList.add('loaded');
        
        // Add animation-complete class to hero sections
        const heroSections = document.querySelectorAll('.hero-section');
        heroSections.forEach(section => {
            section.classList.add('animation-complete');
        });
    }

    // Run after DOM is loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for animations to complete (1.6s = 0.8s delay + 0.8s duration)
            setTimeout(ensureHeroFeaturesVisible, 1600);
        });
    } else {
        // DOM already loaded
        setTimeout(ensureHeroFeaturesVisible, 1600);
    }

    // Also run on window load as a backup
    window.addEventListener('load', function() {
        setTimeout(ensureHeroFeaturesVisible, 100);
    });

    // Run immediately if animations are disabled
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        ensureHeroFeaturesVisible();
    }

})();