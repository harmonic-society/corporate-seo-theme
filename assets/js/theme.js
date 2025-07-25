/**
 * Main Theme JavaScript
 * 
 * Consolidated theme functionality to avoid conflicts
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        
        // Initialize all theme components
        initMobileMenu();
        initScrollEffects();
        initSmoothScroll();
        initLazyLoading();
        initAnimations();
        
    });

    /**
     * Mobile Menu Functionality
     * DISABLED - Using mobile-menu-improved.js instead
     */
    function initMobileMenu() {
        return; // Disabled to prevent conflicts
        const toggleButton = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');
        const mobileOverlay = document.querySelector('.mobile-menu-overlay');
        const menuLinks = document.querySelectorAll('.mobile-menu a');
        
        if (!toggleButton || !mobileMenu) {
            return;
        }
        
        // Toggle menu
        toggleButton.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMenu();
        });
        
        // Close on overlay click
        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', function() {
                closeMenu();
            });
        }
        
        // Close on menu link click
        menuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                closeMenu();
            });
        });
        
        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                closeMenu();
            }
        });
        
        function toggleMenu() {
            const isOpen = mobileMenu.classList.contains('active');
            if (isOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        }
        
        function openMenu() {
            mobileMenu.classList.add('active');
            toggleButton.classList.add('active');
            if (mobileOverlay) {
                mobileOverlay.classList.add('active');
            }
            document.body.style.overflow = 'hidden';
            toggleButton.setAttribute('aria-expanded', 'true');
        }
        
        function closeMenu() {
            mobileMenu.classList.remove('active');
            toggleButton.classList.remove('active');
            if (mobileOverlay) {
                mobileOverlay.classList.remove('active');
            }
            document.body.style.overflow = '';
            toggleButton.setAttribute('aria-expanded', 'false');
        }
    }

    /**
     * Scroll Effects
     */
    function initScrollEffects() {
        const header = document.querySelector('.site-header');
        let lastScrollTop = 0;
        let scrollTimer = null;
        
        if (!header) {
            return;
        }
        
        window.addEventListener('scroll', function() {
            if (scrollTimer !== null) {
                clearTimeout(scrollTimer);
            }
            
            scrollTimer = setTimeout(function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                // Add scrolled class
                if (scrollTop > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
                
                // Hide/show header on scroll
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    header.style.transform = 'translateY(-100%)';
                } else {
                    header.style.transform = 'translateY(0)';
                }
                
                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            }, 50);
        });
    }

    /**
     * Smooth Scroll
     */
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                if (href === '#' || href === '#0') {
                    return;
                }
                
                const target = document.querySelector(href);
                
                if (target) {
                    e.preventDefault();
                    
                    const header = document.querySelector('.site-header');
                    const headerHeight = header ? header.offsetHeight : 0;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Lazy Loading
     */
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const images = document.querySelectorAll('img[loading="lazy"]');
            
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                            img.removeAttribute('data-srcset');
                        }
                        
                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });
            
            images.forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Animations on Scroll
     */
    function initAnimations() {
        if ('IntersectionObserver' in window) {
            const animatedElements = document.querySelectorAll('[data-aos]');
            
            const animationObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('aos-animate');
                    }
                });
            }, {
                threshold: 0.1
            });
            
            animatedElements.forEach(function(element) {
                animationObserver.observe(element);
            });
        }
    }

})();