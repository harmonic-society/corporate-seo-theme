/**
 * Single Service Page JavaScript
 * 
 * @package Corporate_SEO_Pro
 */

(function() {
    'use strict';

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        setupSmoothScrolling();
        setupFeatureAnimations();
        setupPricingInteractions();
    }

    /**
     * Setup smooth scrolling for internal links
     */
    function setupSmoothScrolling() {
        const smoothScrollLinks = document.querySelectorAll('.smooth-scroll');
        
        smoothScrollLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                if (href && href.startsWith('#')) {
                    e.preventDefault();
                    
                    const targetId = href.substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
                        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
    }

    /**
     * Setup feature card animations
     */
    function setupFeatureAnimations() {
        const featureCards = document.querySelectorAll('.feature-card');
        
        if (featureCards.length === 0) return;
        
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('fade-in-up');
                    }, index * 100);
                    
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        featureCards.forEach(card => {
            observer.observe(card);
        });
    }

    /**
     * Setup pricing card interactions
     */
    function setupPricingInteractions() {
        const pricingCards = document.querySelectorAll('.pricing-card');
        
        pricingCards.forEach(card => {
            // Add hover effect
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
            
            // Make entire card clickable if it has a CTA
            const cta = card.querySelector('.plan-cta');
            if (cta) {
                card.style.cursor = 'pointer';
                card.addEventListener('click', function(e) {
                    if (e.target !== cta && !cta.contains(e.target)) {
                        cta.click();
                    }
                });
            }
        });
    }

})();