/**
 * Tablet Menu Enhancements
 * 
 * Enhances mobile menu for tablet devices with better UX
 */

(function() {
    'use strict';

    // Check dependencies
    if (typeof TabletDetection === 'undefined') {
        console.warn('Tablet menu enhancements require tablet-detection.js');
        return;
    }

    const TabletMenuEnhancements = {
        menuModal: null,
        menuToggle: null,
        isTablet: false,

        init() {
            this.isTablet = TabletDetection.isTabletSize() || TabletDetection.isTabletDevice();
            
            if (!this.isTablet) return;

            // Wait for mobile menu to be initialized
            setTimeout(() => {
                this.enhanceMenu();
                this.addTabletSpecificFeatures();
                this.optimizeMenuAnimations();
            }, 100);
        },

        enhanceMenu() {
            // Get menu elements
            this.menuModal = document.querySelector('.mobile-menu-modal');
            this.menuToggle = document.querySelector('.mobile-menu-toggle');
            
            if (!this.menuModal || !this.menuToggle) return;

            // Add tablet-specific classes
            this.menuModal.classList.add('tablet-enhanced');
            
            // Adjust menu width for tablets
            if (TabletDetection.isLandscape()) {
                this.menuModal.style.maxWidth = '400px';
            } else {
                this.menuModal.style.maxWidth = '85%';
            }

            // Enhance menu items for touch
            this.enhanceMenuItems();
        },

        enhanceMenuItems() {
            const menuItems = this.menuModal.querySelectorAll('.mobile-menu-modal-list > li');
            
            menuItems.forEach(item => {
                const hasChildren = item.querySelector('.sub-menu');
                
                if (hasChildren) {
                    // Add expand/collapse functionality for submenus
                    const link = item.querySelector('a');
                    const expandButton = document.createElement('button');
                    expandButton.className = 'submenu-toggle';
                    expandButton.innerHTML = '<i class="fas fa-chevron-down"></i>';
                    expandButton.setAttribute('aria-label', 'Toggle submenu');
                    
                    link.parentNode.insertBefore(expandButton, hasChildren);
                    
                    expandButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        this.toggleSubmenu(item, expandButton);
                    });

                    // Also allow tapping the parent link to toggle
                    link.addEventListener('click', (e) => {
                        if (TabletDetection.isTouchDevice()) {
                            e.preventDefault();
                            this.toggleSubmenu(item, expandButton);
                        }
                    });
                }

                // Add touch feedback
                item.addEventListener('touchstart', () => {
                    item.classList.add('touch-active');
                }, { passive: true });

                item.addEventListener('touchend', () => {
                    setTimeout(() => {
                        item.classList.remove('touch-active');
                    }, 300);
                }, { passive: true });
            });
        },

        toggleSubmenu(item, button) {
            const submenu = item.querySelector('.sub-menu');
            const isOpen = item.classList.contains('submenu-open');
            
            if (isOpen) {
                // Close submenu
                item.classList.remove('submenu-open');
                submenu.style.maxHeight = '0';
                button.innerHTML = '<i class="fas fa-chevron-down"></i>';
            } else {
                // Close other submenus first
                const openSubmenus = this.menuModal.querySelectorAll('.submenu-open');
                openSubmenus.forEach(openItem => {
                    openItem.classList.remove('submenu-open');
                    const openSubmenu = openItem.querySelector('.sub-menu');
                    const openButton = openItem.querySelector('.submenu-toggle');
                    if (openSubmenu) openSubmenu.style.maxHeight = '0';
                    if (openButton) openButton.innerHTML = '<i class="fas fa-chevron-down"></i>';
                });
                
                // Open this submenu
                item.classList.add('submenu-open');
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                button.innerHTML = '<i class="fas fa-chevron-up"></i>';
            }
        },

        addTabletSpecificFeatures() {
            // Add swipe to close functionality
            this.addSwipeToClose();
            
            // Add search functionality in menu
            this.addMenuSearch();
            
            // Enhance CTA button
            this.enhanceCTAButton();
            
            // Add orientation change handler
            this.handleOrientationChange();
        },

        addSwipeToClose() {
            if (!TouchUtils || !this.menuModal) return;
            
            TouchUtils.detectSwipe(this.menuModal, (swipeData) => {
                if (swipeData.direction === 'right' && swipeData.distance > 100) {
                    const closeButton = this.menuModal.querySelector('.mobile-menu-modal-close');
                    if (closeButton) closeButton.click();
                }
            });
        },

        addMenuSearch() {
            const menuBody = this.menuModal.querySelector('.mobile-menu-modal-body');
            if (!menuBody) return;
            
            const searchForm = document.createElement('div');
            searchForm.className = 'tablet-menu-search';
            searchForm.innerHTML = `
                <form role="search" method="get" action="${window.location.origin}">
                    <div class="search-input-wrapper">
                        <input type="search" 
                               placeholder="サイト内を検索..." 
                               name="s" 
                               class="tablet-search-input"
                               autocomplete="off">
                        <button type="submit" class="tablet-search-button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            `;
            
            menuBody.insertBefore(searchForm, menuBody.firstChild);
            
            // Focus search input when menu opens
            const searchInput = searchForm.querySelector('.tablet-search-input');
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.target.classList.contains('is-active')) {
                        setTimeout(() => {
                            searchInput.focus();
                        }, 300);
                    }
                });
            });
            
            observer.observe(this.menuModal, {
                attributes: true,
                attributeFilter: ['class']
            });
        },

        enhanceCTAButton() {
            const ctaButton = this.menuModal.querySelector('.mobile-menu-modal-cta');
            if (!ctaButton) return;
            
            // Make CTA button more prominent on tablets
            ctaButton.classList.add('tablet-enhanced-cta');
            
            // Add icon
            const icon = document.createElement('i');
            icon.className = 'fas fa-envelope';
            ctaButton.insertBefore(icon, ctaButton.firstChild);
            
            // Add touch feedback
            ctaButton.addEventListener('touchstart', () => {
                ctaButton.classList.add('touch-active');
            }, { passive: true });
            
            ctaButton.addEventListener('touchend', () => {
                setTimeout(() => {
                    ctaButton.classList.remove('touch-active');
                }, 300);
            }, { passive: true });
        },

        handleOrientationChange() {
            window.addEventListener('deviceorientationchange', (e) => {
                if (!this.menuModal) return;
                
                const orientation = e.detail.orientation;
                
                if (orientation === 'landscape') {
                    this.menuModal.style.maxWidth = '400px';
                    this.menuModal.classList.add('landscape-mode');
                } else {
                    this.menuModal.style.maxWidth = '85%';
                    this.menuModal.classList.remove('landscape-mode');
                }
            });
        },

        optimizeMenuAnimations() {
            if (!this.menuModal) return;
            
            // Use transform for better performance
            const style = document.createElement('style');
            style.textContent = `
                .tablet-enhanced.mobile-menu-modal {
                    transform: translateX(100%);
                    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }
                
                .tablet-enhanced.mobile-menu-modal.is-active {
                    transform: translateX(0);
                }
                
                .tablet-enhanced .submenu-toggle {
                    width: 44px;
                    height: 44px;
                    background: none;
                    border: none;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    color: #666;
                    transition: transform 0.3s ease;
                }
                
                .tablet-enhanced .submenu-toggle:active {
                    transform: scale(0.9);
                }
                
                .tablet-enhanced .sub-menu {
                    max-height: 0;
                    overflow: hidden;
                    transition: max-height 0.3s ease;
                }
                
                .tablet-enhanced .tablet-menu-search {
                    padding: 1rem;
                    border-bottom: 1px solid #e5e7eb;
                }
                
                .tablet-enhanced .search-input-wrapper {
                    display: flex;
                    gap: 0;
                    background: #f3f4f6;
                    border-radius: 8px;
                    overflow: hidden;
                }
                
                .tablet-enhanced .tablet-search-input {
                    flex: 1;
                    border: none;
                    background: none;
                    padding: 0.875rem 1rem;
                    font-size: 1rem;
                    min-height: 48px;
                }
                
                .tablet-enhanced .tablet-search-button {
                    width: 48px;
                    background: none;
                    border: none;
                    color: #666;
                    cursor: pointer;
                }
                
                .tablet-enhanced .tablet-enhanced-cta {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    padding: 1rem 2rem;
                    font-size: 1.0625rem;
                    min-height: 56px;
                }
                
                .tablet-enhanced.landscape-mode {
                    height: 100vh;
                    overflow-y: auto;
                }
                
                .tablet-enhanced .mobile-menu-modal-list > li.touch-active {
                    background: rgba(0, 0, 0, 0.05);
                }
            `;
            
            document.head.appendChild(style);
        }
    };

    // Initialize
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            TabletMenuEnhancements.init();
        });
    } else {
        TabletMenuEnhancements.init();
    }

    // Re-initialize on device change
    window.addEventListener('devicechange', (e) => {
        if (e.detail.type === 'tablet') {
            TabletMenuEnhancements.init();
        }
    });

    // Export
    window.TabletMenuEnhancements = TabletMenuEnhancements;
})();