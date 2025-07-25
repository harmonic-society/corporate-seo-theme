/**
 * Tablet Optimizations
 * 
 * Main tablet optimization script that enhances UX for tablet devices
 */

(function() {
    'use strict';

    // Wait for dependencies
    if (typeof TabletDetection === 'undefined' || typeof TouchUtils === 'undefined') {
        console.warn('Tablet optimization requires tablet-detection.js');
        return;
    }

    const TabletOptimizations = {
        /**
         * Initialize all tablet optimizations
         */
        init() {
            if (!TabletDetection.isTabletSize() && !TabletDetection.isTabletDevice()) {
                return;
            }

            this.optimizeHoverEffects();
            this.enhanceTouchInteractions();
            this.optimizeScrolling();
            this.improveFormControls();
            this.setupOrientationHandling();
            this.optimizeAnimations();
        },

        /**
         * Replace hover effects with touch-friendly alternatives
         */
        optimizeHoverEffects() {
            // Only apply to touch devices
            if (!TabletDetection.isTouchDevice()) return;

            // Replace CSS hover with touch events
            const hoverElements = document.querySelectorAll('.post-card, .service-card, .work-item, .btn');
            
            hoverElements.forEach(element => {
                // Remove hover effects on touch
                element.addEventListener('touchstart', function() {
                    this.classList.add('touch-active');
                }, { passive: true });

                element.addEventListener('touchend', function() {
                    setTimeout(() => {
                        this.classList.remove('touch-active');
                    }, 300);
                }, { passive: true });

                // Prevent hover state sticking
                element.addEventListener('touchmove', function() {
                    this.classList.remove('touch-active');
                }, { passive: true });
            });

            // Handle dropdown menus
            const dropdowns = document.querySelectorAll('.menu-item-has-children');
            dropdowns.forEach(dropdown => {
                const link = dropdown.querySelector('a');
                
                link.addEventListener('touchstart', function(e) {
                    if (!dropdown.classList.contains('touch-open')) {
                        e.preventDefault();
                        // Close other dropdowns
                        dropdowns.forEach(d => d.classList.remove('touch-open'));
                        dropdown.classList.add('touch-open');
                    }
                }, { passive: false });
            });

            // Close dropdowns on touch outside
            document.addEventListener('touchstart', function(e) {
                if (!e.target.closest('.menu-item-has-children')) {
                    dropdowns.forEach(d => d.classList.remove('touch-open'));
                }
            }, { passive: true });
        },

        /**
         * Enhance touch interactions
         */
        enhanceTouchInteractions() {
            // Fast tap implementation
            const clickableElements = document.querySelectorAll('a, button, .clickable');
            
            clickableElements.forEach(element => {
                let touchStartTime;
                let touchStartX;
                let touchStartY;
                
                element.addEventListener('touchstart', function(e) {
                    touchStartTime = Date.now();
                    const touch = e.touches[0];
                    touchStartX = touch.clientX;
                    touchStartY = touch.clientY;
                }, { passive: true });
                
                element.addEventListener('touchend', function(e) {
                    const touchEndTime = Date.now();
                    const touch = e.changedTouches[0];
                    const touchEndX = touch.clientX;
                    const touchEndY = touch.clientY;
                    
                    // Check if it's a tap (not a swipe)
                    const deltaX = Math.abs(touchEndX - touchStartX);
                    const deltaY = Math.abs(touchEndY - touchStartY);
                    const deltaTime = touchEndTime - touchStartTime;
                    
                    if (deltaX < 10 && deltaY < 10 && deltaTime < 200) {
                        // It's a tap - trigger click immediately
                        e.preventDefault();
                        this.click();
                    }
                }, { passive: false });
            });

            // Add swipe navigation for galleries
            const galleries = document.querySelectorAll('.gallery, .carousel, .slider');
            galleries.forEach(gallery => {
                TouchUtils.detectSwipe(gallery, (swipeData) => {
                    if (swipeData.direction === 'left') {
                        // Next slide
                        const nextBtn = gallery.querySelector('.next, .carousel-next');
                        if (nextBtn) nextBtn.click();
                    } else if (swipeData.direction === 'right') {
                        // Previous slide
                        const prevBtn = gallery.querySelector('.prev, .carousel-prev');
                        if (prevBtn) prevBtn.click();
                    }
                });
            });
        },

        /**
         * Optimize scrolling behavior
         */
        optimizeScrolling() {
            // Smooth scrolling with touch momentum
            const scrollContainers = document.querySelectorAll('.scroll-container, .overflow-x-auto');
            
            scrollContainers.forEach(container => {
                let isScrolling = false;
                let startX;
                let scrollLeft;
                
                container.addEventListener('touchstart', function(e) {
                    isScrolling = true;
                    startX = e.touches[0].pageX - container.offsetLeft;
                    scrollLeft = container.scrollLeft;
                }, { passive: true });
                
                container.addEventListener('touchmove', function(e) {
                    if (!isScrolling) return;
                    const x = e.touches[0].pageX - container.offsetLeft;
                    const walk = (x - startX) * 2; // Increase scroll speed
                    container.scrollLeft = scrollLeft - walk;
                }, { passive: true });
                
                container.addEventListener('touchend', function() {
                    isScrolling = false;
                }, { passive: true });
            });

            // Parallax optimization for tablets
            if (TabletDetection.isTabletDevice()) {
                const parallaxElements = document.querySelectorAll('[data-parallax]');
                parallaxElements.forEach(element => {
                    // Reduce parallax effect on tablets
                    const speed = parseFloat(element.dataset.parallax);
                    element.dataset.parallax = speed * 0.5;
                });
            }
        },

        /**
         * Improve form controls for touch
         */
        improveFormControls() {
            // Enhance select boxes
            const selects = document.querySelectorAll('select');
            selects.forEach(select => {
                // Add touch-friendly wrapper
                const wrapper = document.createElement('div');
                wrapper.className = 'select-wrapper touch-optimized';
                select.parentNode.insertBefore(wrapper, select);
                wrapper.appendChild(select);
            });

            // Improve number inputs
            const numberInputs = document.querySelectorAll('input[type="number"]');
            numberInputs.forEach(input => {
                // Add increment/decrement buttons for touch
                const wrapper = document.createElement('div');
                wrapper.className = 'number-input-wrapper';
                
                const decrementBtn = document.createElement('button');
                decrementBtn.className = 'number-decrement';
                decrementBtn.innerHTML = '-';
                decrementBtn.type = 'button';
                
                const incrementBtn = document.createElement('button');
                incrementBtn.className = 'number-increment';
                incrementBtn.innerHTML = '+';
                incrementBtn.type = 'button';
                
                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(decrementBtn);
                wrapper.appendChild(input);
                wrapper.appendChild(incrementBtn);
                
                // Handle button clicks
                decrementBtn.addEventListener('click', () => {
                    input.stepDown();
                    input.dispatchEvent(new Event('change'));
                });
                
                incrementBtn.addEventListener('click', () => {
                    input.stepUp();
                    input.dispatchEvent(new Event('change'));
                });
            });

            // Auto-zoom prevention
            const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    if (TabletDetection.isTabletDevice()) {
                        // Temporarily set font size to prevent zoom
                        this.style.fontSize = '16px';
                    }
                });
                
                input.addEventListener('blur', function() {
                    // Reset font size
                    this.style.fontSize = '';
                });
            });
        },

        /**
         * Handle orientation changes
         */
        setupOrientationHandling() {
            window.addEventListener('deviceorientationchange', (e) => {
                const orientation = e.detail.orientation;
                
                // Adjust layouts based on orientation
                if (orientation === 'portrait') {
                    // Portrait optimizations
                    document.querySelectorAll('.grid').forEach(grid => {
                        if (!grid.classList.contains('force-grid')) {
                            grid.classList.add('portrait-stack');
                        }
                    });
                } else {
                    // Landscape optimizations
                    document.querySelectorAll('.grid').forEach(grid => {
                        grid.classList.remove('portrait-stack');
                    });
                }
                
                // Recalculate viewport-dependent elements
                this.recalculateViewport();
            });
        },

        /**
         * Optimize animations for tablet performance
         */
        optimizeAnimations() {
            if (!TabletDetection.isTabletDevice()) return;

            // Reduce animation complexity
            const animatedElements = document.querySelectorAll('[data-animation]');
            animatedElements.forEach(element => {
                const animation = element.dataset.animation;
                
                // Simplify complex animations
                if (animation.includes('parallax') || animation.includes('3d')) {
                    element.dataset.animation = 'fade';
                }
            });

            // Disable auto-playing videos on tablets to save resources
            const videos = document.querySelectorAll('video[autoplay]');
            videos.forEach(video => {
                video.removeAttribute('autoplay');
                // Add play button overlay
                const playButton = document.createElement('button');
                playButton.className = 'video-play-button';
                playButton.innerHTML = '<i class="fa fa-play"></i>';
                video.parentNode.appendChild(playButton);
                
                playButton.addEventListener('click', () => {
                    video.play();
                    playButton.style.display = 'none';
                });
            });

            // Throttle scroll animations
            let scrollTimeout;
            window.addEventListener('scroll', () => {
                document.body.classList.add('is-scrolling');
                
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    document.body.classList.remove('is-scrolling');
                }, 150);
            }, { passive: true });
        },

        /**
         * Recalculate viewport-dependent elements
         */
        recalculateViewport() {
            // Update CSS custom properties
            document.documentElement.style.setProperty('--tablet-vh', `${window.innerHeight * 0.01}px`);
            document.documentElement.style.setProperty('--tablet-vw', `${window.innerWidth * 0.01}px`);
            
            // Trigger resize event for other scripts
            window.dispatchEvent(new Event('resize'));
        }
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            TabletOptimizations.init();
        });
    } else {
        TabletOptimizations.init();
    }

    // Re-initialize on device change
    window.addEventListener('devicechange', (e) => {
        if (e.detail.type === 'tablet') {
            TabletOptimizations.init();
        }
    });

    // Export for external use
    window.TabletOptimizations = TabletOptimizations;
})();