/**
 * Unified Mobile Menu System
 * Consolidates all mobile menu functionality into one stable implementation
 */
(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileMenu);
    } else {
        initMobileMenu();
    }

    function initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        const menuOverlay = document.querySelector('.mobile-menu-overlay');
        const menuItems = document.querySelectorAll('.mobile-menu a');
        const body = document.body;
        
        if (!menuToggle || !mobileMenu) {
            console.warn('Mobile menu elements not found');
            return;
        }

        // State management
        let isMenuOpen = false;
        let isAnimating = false;
        
        // Remove any existing event listeners by cloning elements
        const newToggle = menuToggle.cloneNode(true);
        menuToggle.parentNode.replaceChild(newToggle, menuToggle);
        
        // Toggle menu function with animation safety
        function toggleMenu() {
            if (isAnimating) return;
            
            isAnimating = true;
            isMenuOpen = !isMenuOpen;
            
            if (isMenuOpen) {
                openMenu();
            } else {
                closeMenu();
            }
            
            // Reset animation flag after transition
            setTimeout(() => {
                isAnimating = false;
            }, 300);
        }
        
        // Open menu with proper sequencing
        function openMenu() {
            // Add classes in sequence for smooth animation
            body.classList.add('mobile-menu-open');
            mobileMenu.classList.add('is-active');
            newToggle.classList.add('is-active');
            
            // Create or show overlay
            if (!menuOverlay) {
                const overlay = document.createElement('div');
                overlay.className = 'mobile-menu-overlay';
                document.body.appendChild(overlay);
                
                // Force reflow before adding active class
                overlay.offsetHeight;
                overlay.classList.add('is-active');
                
                // Add overlay click handler
                overlay.addEventListener('click', toggleMenu);
            } else {
                menuOverlay.classList.add('is-active');
            }
            
            // Prevent body scroll
            const scrollY = window.scrollY;
            body.style.position = 'fixed';
            body.style.top = `-${scrollY}px`;
            body.style.width = '100%';
            
            // Set ARIA attributes
            newToggle.setAttribute('aria-expanded', 'true');
            mobileMenu.setAttribute('aria-hidden', 'false');
            
            // Focus management
            mobileMenu.focus();
        }
        
        // Close menu with proper cleanup
        function closeMenu() {
            // Remove classes
            body.classList.remove('mobile-menu-open');
            mobileMenu.classList.remove('is-active');
            newToggle.classList.remove('is-active');
            
            // Hide overlay
            const overlay = document.querySelector('.mobile-menu-overlay');
            if (overlay) {
                overlay.classList.remove('is-active');
            }
            
            // Restore body scroll
            const scrollY = body.style.top;
            body.style.position = '';
            body.style.top = '';
            body.style.width = '';
            window.scrollTo(0, parseInt(scrollY || '0') * -1);
            
            // Set ARIA attributes
            newToggle.setAttribute('aria-expanded', 'false');
            mobileMenu.setAttribute('aria-hidden', 'true');
            
            // Return focus to toggle button
            newToggle.focus();
        }
        
        // Handle menu item clicks
        function handleMenuItemClick(e) {
            const link = e.currentTarget;
            
            // Handle anchor links
            if (link.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                const targetId = link.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    // Close menu first
                    closeMenu();
                    isMenuOpen = false;
                    
                    // Smooth scroll after menu closes
                    setTimeout(() => {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 300);
                }
            } else {
                // For regular links, close menu immediately
                closeMenu();
                isMenuOpen = false;
            }
        }
        
        // Touch handling for better mobile experience
        let touchStartY = 0;
        
        function handleTouchStart(e) {
            touchStartY = e.touches[0].clientY;
        }
        
        function handleTouchMove(e) {
            const touchY = e.touches[0].clientY;
            const scrollTop = mobileMenu.scrollTop;
            const scrollHeight = mobileMenu.scrollHeight;
            const height = mobileMenu.clientHeight;
            
            // Prevent rubber-band scrolling
            if ((scrollTop <= 0 && touchY > touchStartY) || 
                (scrollTop + height >= scrollHeight && touchY < touchStartY)) {
                e.preventDefault();
            }
        }
        
        // Keyboard navigation
        function handleKeyDown(e) {
            if (!isMenuOpen) return;
            
            switch(e.key) {
                case 'Escape':
                    toggleMenu();
                    break;
                case 'Tab':
                    // Trap focus within menu
                    const focusableElements = mobileMenu.querySelectorAll(
                        'a, button, [tabindex]:not([tabindex="-1"])'
                    );
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];
                    
                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                    break;
            }
        }
        
        // Debounced resize handler
        let resizeTimeout;
        function handleResize() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (window.innerWidth > 1024 && isMenuOpen) {
                    closeMenu();
                    isMenuOpen = false;
                }
            }, 250);
        }
        
        // Event listeners
        newToggle.addEventListener('click', toggleMenu);
        
        // Menu item clicks
        menuItems.forEach(item => {
            item.addEventListener('click', handleMenuItemClick);
        });
        
        // Touch events for better mobile experience
        mobileMenu.addEventListener('touchstart', handleTouchStart, { passive: true });
        mobileMenu.addEventListener('touchmove', handleTouchMove, { passive: false });
        
        // Keyboard navigation
        document.addEventListener('keydown', handleKeyDown);
        
        // Handle resize
        window.addEventListener('resize', handleResize);
        
        // Cleanup function for SPA navigation
        window.cleanupMobileMenu = function() {
            newToggle.removeEventListener('click', toggleMenu);
            document.removeEventListener('keydown', handleKeyDown);
            window.removeEventListener('resize', handleResize);
            menuItems.forEach(item => {
                item.removeEventListener('click', handleMenuItemClick);
            });
        };
    }
})();