/**
 * Sticky Header Handler
 * Ensures proper sticky header behavior across all devices
 */
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    const header = document.querySelector('.site-header');
    if (!header) return;

    // Force fixed positioning on all devices
    const ensureFixedHeader = () => {
        // Check if header has fixed position
        const computedStyle = window.getComputedStyle(header);
        const position = computedStyle.getPropertyValue('position');
        
        if (position !== 'fixed') {
            console.warn('Header not fixed, forcing fixed position');
            header.style.position = 'fixed';
            header.style.top = '0';
            header.style.left = '0';
            header.style.right = '0';
            header.style.width = '100%';
            header.style.zIndex = '1100';
        }
    };

    // Initial check
    ensureFixedHeader();

    // Check on resize (for responsive changes)
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(ensureFixedHeader, 250);
    });

    // Ensure proper spacing for iOS devices
    const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    if (isIOS) {
        document.documentElement.classList.add('ios-device');
    }

    // Handle admin bar offset
    const adminBar = document.querySelector('#wpadminbar');
    if (adminBar) {
        const updateAdminBarOffset = () => {
            const adminBarHeight = adminBar.offsetHeight;
            header.style.top = adminBarHeight + 'px';
        };
        
        updateAdminBarOffset();
        window.addEventListener('resize', updateAdminBarOffset);
    }

    // Smooth scroll adjustment for sticky header
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#' || href === '#0') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const headerHeight = header.offsetHeight;
                const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight - 10;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
});