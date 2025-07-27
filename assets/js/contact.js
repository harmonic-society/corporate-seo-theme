/**
 * Contact Page Functionality
 */
(function() {
    'use strict';

    // Count Up Animation for Trust Indicators
    const countElements = document.querySelectorAll('.contact-hero .count-up');
    if (countElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const element = entry.target;
                    const target = parseInt(element.dataset.target);
                    const duration = 2000;
                    const increment = target / (duration / 16);
                    let current = 0;
                    
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= target) {
                            current = target;
                            clearInterval(timer);
                        }
                        element.textContent = Math.floor(current);
                    }, 16);
                    
                    observer.unobserve(element);
                }
            });
        }, { threshold: 0.5 });
        
        countElements.forEach(element => {
            observer.observe(element);
        });
    }

    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const isOpen = this.getAttribute('aria-expanded') === 'true';
            
            // Close all other questions
            faqQuestions.forEach(q => {
                if (q !== this) {
                    q.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Toggle current question
            this.setAttribute('aria-expanded', !isOpen);
        });
    });

    // Smooth Scroll for Scroll Prompt
    const scrollPrompt = document.querySelector('.scroll-prompt');
    if (scrollPrompt) {
        scrollPrompt.addEventListener('click', function() {
            const contactMain = document.querySelector('.contact-main');
            if (contactMain) {
                contactMain.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    // Contact Form 7 Integration
    if (typeof wpcf7 !== 'undefined') {
        document.addEventListener('wpcf7submit', function(event) {
            const form = event.target;
            const submitButton = form.querySelector('.wpcf7-submit');
            
            if (submitButton) {
                submitButton.classList.add('loading');
            }
        }, false);

        document.addEventListener('wpcf7invalid', function(event) {
            const form = event.target;
            const submitButton = form.querySelector('.wpcf7-submit');
            
            if (submitButton) {
                submitButton.classList.remove('loading');
            }
        }, false);

        document.addEventListener('wpcf7spam', function(event) {
            const form = event.target;
            const submitButton = form.querySelector('.wpcf7-submit');
            
            if (submitButton) {
                submitButton.classList.remove('loading');
            }
        }, false);

        document.addEventListener('wpcf7mailfailed', function(event) {
            const form = event.target;
            const submitButton = form.querySelector('.wpcf7-submit');
            
            if (submitButton) {
                submitButton.classList.remove('loading');
            }
        }, false);

        document.addEventListener('wpcf7mailsent', function(event) {
            const form = event.target;
            const submitButton = form.querySelector('.wpcf7-submit');
            
            if (submitButton) {
                submitButton.classList.remove('loading');
                
                // Success animation
                submitButton.innerHTML = '<i class="fas fa-check"></i> 送信完了';
                submitButton.style.background = '#28a745';
                
                setTimeout(() => {
                    submitButton.innerHTML = '送信する';
                    submitButton.style.background = '';
                }, 3000);
            }
        }, false);
    }

    // Form Field Animation
    const formInputs = document.querySelectorAll('.wpcf7-form-control');
    formInputs.forEach(input => {
        // Add focus effect
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
        
        // Check if field has value on load
        if (input.value) {
            input.parentElement.classList.add('focused');
        }
    });

    // Parallax Effect for Hero Background
    const contactHero = document.querySelector('.contact-hero');
    if (contactHero) {
        const bgGradient = contactHero.querySelector('.bg-gradient');
        const bgParticles = contactHero.querySelectorAll('.bg-particles span');
        
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * 0.5;
            
            if (bgGradient) {
                bgGradient.style.transform = `rotate(${scrolled * 0.1}deg) scale(${1 + scrolled * 0.0002})`;
            }
            
            bgParticles.forEach((particle, index) => {
                const speed = 0.2 + (index * 0.1);
                particle.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
        
        // Mouse move effect
        contactHero.addEventListener('mousemove', (e) => {
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;
            
            if (bgGradient) {
                bgGradient.style.transformOrigin = `${x * 100}% ${y * 100}%`;
            }
        });
    }

    // Add ripple effect to submit button
    const submitButtons = document.querySelectorAll('.wpcf7-submit');
    submitButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });


})();