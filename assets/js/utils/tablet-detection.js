/**
 * Tablet Detection and Utilities
 * 
 * Provides comprehensive tablet detection and utility functions
 * for optimized tablet experience
 */

(function() {
    'use strict';

    // Tablet detection utilities
    const TabletDetection = {
        // Breakpoint definitions matching CSS
        breakpoints: {
            tabletMin: 769,
            tabletMax: 1024,
            mobileMax: 768,
            desktopMin: 1025
        },

        /**
         * Check if device is a tablet based on screen size
         */
        isTabletSize() {
            const width = window.innerWidth;
            return width >= this.breakpoints.tabletMin && 
                   width <= this.breakpoints.tabletMax;
        },

        /**
         * Check if device is actually a tablet (not just tablet-sized)
         */
        isTabletDevice() {
            const userAgent = navigator.userAgent.toLowerCase();
            const isIPad = /ipad/.test(userAgent) || 
                          (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
            const isAndroidTablet = /android/.test(userAgent) && !/mobile/.test(userAgent);
            const isWindowsTablet = /windows/.test(userAgent) && /touch/.test(userAgent);
            
            return isIPad || isAndroidTablet || isWindowsTablet;
        },

        /**
         * Check if device has touch capability
         */
        isTouchDevice() {
            return ('ontouchstart' in window) || 
                   (navigator.maxTouchPoints > 0) || 
                   (navigator.msMaxTouchPoints > 0);
        },

        /**
         * Check if device supports hover
         */
        hasHoverCapability() {
            return window.matchMedia('(hover: hover)').matches;
        },

        /**
         * Check device orientation
         */
        getOrientation() {
            if (window.orientation !== undefined) {
                return Math.abs(window.orientation) === 90 ? 'landscape' : 'portrait';
            }
            return window.innerWidth > window.innerHeight ? 'landscape' : 'portrait';
        },

        /**
         * Check if in portrait mode
         */
        isPortrait() {
            return this.getOrientation() === 'portrait';
        },

        /**
         * Check if in landscape mode
         */
        isLandscape() {
            return this.getOrientation() === 'landscape';
        },

        /**
         * Get device type
         */
        getDeviceType() {
            const width = window.innerWidth;
            
            if (width <= this.breakpoints.mobileMax) {
                return 'mobile';
            } else if (width >= this.breakpoints.tabletMin && width <= this.breakpoints.tabletMax) {
                return this.isTabletDevice() ? 'tablet' : 'desktop-small';
            } else {
                return 'desktop';
            }
        },

        /**
         * Add device classes to body
         */
        addDeviceClasses() {
            const body = document.body;
            const deviceType = this.getDeviceType();
            const orientation = this.getOrientation();
            
            // Remove existing device classes
            body.classList.remove('device-mobile', 'device-tablet', 'device-desktop', 
                                'device-desktop-small', 'orientation-portrait', 
                                'orientation-landscape', 'touch-device', 'hover-device');
            
            // Add current device class
            body.classList.add(`device-${deviceType}`);
            body.classList.add(`orientation-${orientation}`);
            
            // Add touch/hover capability classes
            if (this.isTouchDevice()) {
                body.classList.add('touch-device');
            }
            if (this.hasHoverCapability()) {
                body.classList.add('hover-device');
            }
        },

        /**
         * Debounce function for resize events
         */
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        /**
         * Initialize tablet detection
         */
        init() {
            // Set initial classes
            this.addDeviceClasses();
            
            // Update on resize with debounce
            const debouncedResize = this.debounce(() => {
                this.addDeviceClasses();
                // Trigger custom event for other scripts
                window.dispatchEvent(new CustomEvent('devicechange', {
                    detail: {
                        type: this.getDeviceType(),
                        orientation: this.getOrientation(),
                        isTouch: this.isTouchDevice()
                    }
                }));
            }, 250);
            
            window.addEventListener('resize', debouncedResize);
            
            // Update on orientation change
            window.addEventListener('orientationchange', () => {
                setTimeout(() => {
                    this.addDeviceClasses();
                    window.dispatchEvent(new CustomEvent('deviceorientationchange', {
                        detail: {
                            orientation: this.getOrientation()
                        }
                    }));
                }, 100);
            });
        }
    };

    // Touch event utilities
    const TouchUtils = {
        /**
         * Add touch event with fallback to mouse
         */
        addTouchEvent(element, touchEvent, mouseEvent, handler) {
            if (TabletDetection.isTouchDevice()) {
                element.addEventListener(touchEvent, handler, { passive: true });
            } else {
                element.addEventListener(mouseEvent, handler);
            }
        },

        /**
         * Get touch/mouse coordinates
         */
        getEventCoordinates(event) {
            if (event.touches && event.touches.length > 0) {
                return {
                    x: event.touches[0].clientX,
                    y: event.touches[0].clientY
                };
            } else if (event.changedTouches && event.changedTouches.length > 0) {
                return {
                    x: event.changedTouches[0].clientX,
                    y: event.changedTouches[0].clientY
                };
            } else {
                return {
                    x: event.clientX,
                    y: event.clientY
                };
            }
        },

        /**
         * Detect swipe gestures
         */
        detectSwipe(element, callback, threshold = 50) {
            let startX = 0;
            let startY = 0;
            let startTime = 0;
            
            const handleStart = (e) => {
                const coords = this.getEventCoordinates(e);
                startX = coords.x;
                startY = coords.y;
                startTime = Date.now();
            };
            
            const handleEnd = (e) => {
                const coords = this.getEventCoordinates(e);
                const deltaX = coords.x - startX;
                const deltaY = coords.y - startY;
                const deltaTime = Date.now() - startTime;
                
                // Check if it's a swipe (not too slow)
                if (deltaTime < 500) {
                    if (Math.abs(deltaX) > threshold && Math.abs(deltaX) > Math.abs(deltaY)) {
                        callback({
                            direction: deltaX > 0 ? 'right' : 'left',
                            distance: Math.abs(deltaX)
                        });
                    } else if (Math.abs(deltaY) > threshold && Math.abs(deltaY) > Math.abs(deltaX)) {
                        callback({
                            direction: deltaY > 0 ? 'down' : 'up',
                            distance: Math.abs(deltaY)
                        });
                    }
                }
            };
            
            this.addTouchEvent(element, 'touchstart', 'mousedown', handleStart);
            this.addTouchEvent(element, 'touchend', 'mouseup', handleEnd);
        },

        /**
         * Prevent default touch behaviors
         */
        preventDefaultTouch(element) {
            element.addEventListener('touchstart', (e) => {
                if (e.touches.length > 1) {
                    e.preventDefault();
                }
            }, { passive: false });
        }
    };

    // Export to global scope
    window.TabletDetection = TabletDetection;
    window.TouchUtils = TouchUtils;

    // Auto-initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            TabletDetection.init();
        });
    } else {
        TabletDetection.init();
    }
})();