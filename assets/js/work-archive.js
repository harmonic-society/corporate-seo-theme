/**
 * Works Archive Page Interactions
 */
(function($) {
    'use strict';

    // DOM Ready
    $(document).ready(function() {
        initCategoryFilter();
        initStatisticsAnimation();
        initScrollAnimations();
        initWorkItemHover();
    });

    /**
     * Category Filter Functionality
     */
    function initCategoryFilter() {
        const filterButtons = $('.filter-btn');
        const workItems = $('.work-item');

        filterButtons.on('click', function() {
            const $this = $(this);
            const filterValue = $this.data('filter');

            // Update active state
            filterButtons.removeClass('active');
            $this.addClass('active');

            // Filter items with animation
            if (filterValue === 'all') {
                workItems.each(function(index) {
                    const $item = $(this);
                    setTimeout(function() {
                        $item.removeClass('hidden').addClass('show');
                    }, index * 50);
                });
            } else {
                workItems.each(function(index) {
                    const $item = $(this);
                    if ($item.hasClass(filterValue)) {
                        setTimeout(function() {
                            $item.removeClass('hidden').addClass('show');
                        }, index * 50);
                    } else {
                        $item.removeClass('show').addClass('hidden');
                    }
                });
            }

            // Trigger layout recalculation
            setTimeout(function() {
                $(window).trigger('resize');
            }, 300);
        });
    }

    /**
     * Statistics Counter Animation
     */
    function initStatisticsAnimation() {
        const statNumbers = $('.stat-number');
        let hasAnimated = false;

        function animateNumbers() {
            if (hasAnimated) return;

            const windowHeight = $(window).height();
            const scrollTop = $(window).scrollTop();

            statNumbers.each(function() {
                const $this = $(this);
                const elementTop = $this.offset().top;

                if (elementTop < scrollTop + windowHeight - 100) {
                    hasAnimated = true;
                    
                    const countTo = parseInt($this.attr('data-count'));
                    const duration = 2000;
                    const steps = 60;
                    const stepDuration = duration / steps;
                    let currentCount = 0;
                    const increment = countTo / steps;

                    const counter = setInterval(function() {
                        currentCount += increment;
                        if (currentCount >= countTo) {
                            currentCount = countTo;
                            clearInterval(counter);
                        }
                        
                        // Add suffix for percentage
                        const suffix = $this.next('.stat-label').text().includes('満足度') ? '%' : '+';
                        $this.text(Math.floor(currentCount) + suffix);
                    }, stepDuration);
                }
            });
        }

        // Check on scroll and initial load
        $(window).on('scroll', animateNumbers);
        animateNumbers();
    }

    /**
     * Scroll-triggered Animations
     */
    function initScrollAnimations() {
        const animatedElements = $('.work-item, .feature-card, .stat-item');

        function checkVisibility() {
            const windowHeight = $(window).height();
            const scrollTop = $(window).scrollTop();

            animatedElements.each(function() {
                const $element = $(this);
                const elementTop = $element.offset().top;
                const elementHeight = $element.outerHeight();

                if (elementTop < scrollTop + windowHeight - 50 && 
                    elementTop + elementHeight > scrollTop) {
                    $element.addClass('animate-in');
                }
            });
        }

        $(window).on('scroll resize', checkVisibility);
        checkVisibility();
    }

    /**
     * Work Item Hover Effects
     */
    function initWorkItemHover() {
        const workItems = $('.work-item');

        workItems.each(function() {
            const $item = $(this);
            const $overlay = $item.find('.work-overlay');
            const $image = $item.find('.work-image img');

            $item.on('mouseenter', function(e) {
                const x = e.pageX - $item.offset().left;
                const y = e.pageY - $item.offset().top;

                $overlay.css({
                    '--mouse-x': x + 'px',
                    '--mouse-y': y + 'px'
                });

                // Add parallax effect to image
                $item.on('mousemove.parallax', function(e) {
                    const mouseX = e.pageX - $item.offset().left;
                    const mouseY = e.pageY - $item.offset().top;
                    const moveX = (mouseX - $item.width() / 2) * 0.02;
                    const moveY = (mouseY - $item.height() / 2) * 0.02;

                    $image.css({
                        'transform': `scale(1.1) translate(${moveX}px, ${moveY}px)`
                    });
                });
            });

            $item.on('mouseleave', function() {
                $item.off('mousemove.parallax');
                $image.css({
                    'transform': 'scale(1) translate(0, 0)'
                });
            });
        });
    }

    /**
     * Smooth Scroll for Hero Section
     */
    $('.hero-scroll').on('click', function(e) {
        e.preventDefault();
        const target = $('.work-filter-section');
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 800, 'easeInOutCubic');
        }
    });

    /**
     * Particles Animation for Hero Background
     */
    function initParticlesAnimation() {
        const particles = $('.hero-particles span');
        
        particles.each(function(index) {
            const $particle = $(this);
            const delay = index * 0.5;
            const duration = 15 + Math.random() * 10;
            
            $particle.css({
                'animation-delay': `${delay}s`,
                'animation-duration': `${duration}s`
            });
        });
    }

    // Initialize particles on load
    initParticlesAnimation();

})(jQuery);

/**
 * Lightbox functionality for work detail gallery
 */
if (typeof jQuery !== 'undefined' && jQuery('.work-gallery').length > 0) {
    // Simple lightbox implementation
    jQuery(document).ready(function($) {
        const galleryLinks = $('.gallery-link[data-lightbox]');
        
        if (galleryLinks.length > 0) {
            // Create lightbox HTML
            const lightboxHTML = `
                <div class="work-lightbox" style="display: none;">
                    <div class="lightbox-overlay"></div>
                    <div class="lightbox-content">
                        <img src="" alt="">
                        <button class="lightbox-close"><i class="fas fa-times"></i></button>
                        <button class="lightbox-prev"><i class="fas fa-chevron-left"></i></button>
                        <button class="lightbox-next"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
            `;
            
            $('body').append(lightboxHTML);
            
            const $lightbox = $('.work-lightbox');
            const $lightboxImg = $lightbox.find('img');
            let currentIndex = 0;
            
            // Open lightbox
            galleryLinks.on('click', function(e) {
                e.preventDefault();
                currentIndex = galleryLinks.index(this);
                const imgSrc = $(this).attr('href');
                
                $lightboxImg.attr('src', imgSrc);
                $lightbox.fadeIn(300);
                $('body').addClass('lightbox-open');
            });
            
            // Close lightbox
            $('.lightbox-close, .lightbox-overlay').on('click', function() {
                $lightbox.fadeOut(300);
                $('body').removeClass('lightbox-open');
            });
            
            // Navigation
            $('.lightbox-prev').on('click', function() {
                currentIndex = (currentIndex - 1 + galleryLinks.length) % galleryLinks.length;
                const imgSrc = galleryLinks.eq(currentIndex).attr('href');
                $lightboxImg.attr('src', imgSrc);
            });
            
            $('.lightbox-next').on('click', function() {
                currentIndex = (currentIndex + 1) % galleryLinks.length;
                const imgSrc = galleryLinks.eq(currentIndex).attr('href');
                $lightboxImg.attr('src', imgSrc);
            });
            
            // Keyboard navigation
            $(document).on('keydown', function(e) {
                if ($lightbox.is(':visible')) {
                    if (e.key === 'Escape') {
                        $('.lightbox-close').click();
                    } else if (e.key === 'ArrowLeft') {
                        $('.lightbox-prev').click();
                    } else if (e.key === 'ArrowRight') {
                        $('.lightbox-next').click();
                    }
                }
            });
        }
    });
}

/**
 * Results Statistics Animation for Work Detail Page
 */
if (typeof jQuery !== 'undefined' && jQuery('.results-stats').length > 0) {
    jQuery(document).ready(function($) {
        const statCards = $('.stat-card');
        let hasAnimated = false;

        function animateStats() {
            if (hasAnimated) return;

            const windowHeight = $(window).height();
            const scrollTop = $(window).scrollTop();
            const statsTop = $('.results-stats').offset().top;

            if (statsTop < scrollTop + windowHeight - 100) {
                hasAnimated = true;

                statCards.each(function(index) {
                    const $card = $(this);
                    const $number = $card.find('.stat-number');
                    const target = parseInt($number.attr('data-count'));

                    setTimeout(function() {
                        $card.addClass('animate-in');

                        // Animate number
                        const duration = 2000;
                        const steps = 50;
                        const stepDuration = duration / steps;
                        let current = 0;
                        const increment = target / steps;

                        const counter = setInterval(function() {
                            current += increment;
                            if (current >= target) {
                                current = target;
                                clearInterval(counter);
                            }
                            $number.text(Math.floor(current).toLocaleString());
                        }, stepDuration);
                    }, index * 200);
                });
            }
        }

        $(window).on('scroll', animateStats);
        animateStats();
    });
}