/**
 * Service Features Enhancement
 * 
 * Add interactive animations and effects to service feature lists
 */
(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initServiceFeatures();
    });

    /**
     * Initialize service features enhancements
     */
    function initServiceFeatures() {
        const featureLists = document.querySelectorAll('.service-archive-item .feature-list');
        
        if (featureLists.length === 0) return;

        // Add intersection observer for animation on scroll
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver(handleIntersection, observerOptions);

        featureLists.forEach(list => {
            // Add initial state
            const items = list.querySelectorAll('li');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                item.style.transition = `all 0.5s ease ${index * 0.1}s`;
            });

            // Observe the list
            observer.observe(list);

            // Add hover effects
            items.forEach(item => {
                item.addEventListener('mouseenter', handleFeatureHover);
                item.addEventListener('mouseleave', handleFeatureLeave);
            });
        });

        // Add dynamic icon animations
        animateFeatureIcons();
    }

    /**
     * Handle intersection for scroll animations
     */
    function handleIntersection(entries, observer) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const items = entry.target.querySelectorAll('li');
                items.forEach((item, index) => {
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateX(0)';
                    }, index * 100);
                });
                observer.unobserve(entry.target);
            }
        });
    }

    /**
     * Handle feature item hover
     */
    function handleFeatureHover(e) {
        const item = e.currentTarget;
        item.style.paddingLeft = '2.75rem';
        
        // Animate the icon
        const iconBefore = item.querySelector('::before');
        if (iconBefore) {
            item.style.setProperty('--icon-scale', '1.2');
        }
    }

    /**
     * Handle feature item leave
     */
    function handleFeatureLeave(e) {
        const item = e.currentTarget;
        item.style.paddingLeft = '';
        item.style.setProperty('--icon-scale', '1');
    }

    /**
     * Animate feature icons
     */
    function animateFeatureIcons() {
        const style = document.createElement('style');
        style.textContent = `
            .service-archive-item .feature-list li::before {
                transform: scale(var(--icon-scale, 1));
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .service-archive-item:hover .feature-list li::before {
                animation: pulse 2s infinite;
            }
            
            @keyframes pulse {
                0% {
                    transform: scale(1);
                    box-shadow: 0 2px 8px rgba(0, 134, 123, 0.2);
                }
                50% {
                    transform: scale(1.05);
                    box-shadow: 0 4px 16px rgba(0, 134, 123, 0.4);
                }
                100% {
                    transform: scale(1);
                    box-shadow: 0 2px 8px rgba(0, 134, 123, 0.2);
                }
            }
            
            /* Stagger animation for each item */
            .service-archive-item:hover .feature-list li:nth-child(1)::before {
                animation-delay: 0s;
            }
            
            .service-archive-item:hover .feature-list li:nth-child(2)::before {
                animation-delay: 0.2s;
            }
            
            .service-archive-item:hover .feature-list li:nth-child(3)::before {
                animation-delay: 0.4s;
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Add ripple effect on click
     */
    function addRippleEffect() {
        document.addEventListener('click', function(e) {
            const featureItem = e.target.closest('.service-archive-item .feature-list li');
            if (!featureItem) return;

            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            
            const rect = featureItem.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';

            featureItem.appendChild(ripple);

            setTimeout(() => {
                ripple.remove();
            }, 600);
        });

        // Add ripple styles
        const style = document.createElement('style');
        style.textContent = `
            .service-archive-item .feature-list li {
                position: relative;
                overflow: hidden;
            }
            
            .ripple {
                position: absolute;
                border-radius: 50%;
                background: rgba(0, 134, 123, 0.3);
                transform: scale(0);
                animation: ripple-animation 0.6s ease-out;
                pointer-events: none;
            }
            
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Initialize ripple effect
    addRippleEffect();

})();