/**
 * About page values section visibility fix
 * Ensures the 3つの調和 items are always visible
 */
document.addEventListener('DOMContentLoaded', function() {
    // Force show all value items
    const valueItems = document.querySelectorAll('.value-item');
    const valuesGrid = document.querySelector('.values-grid');
    const heroValues = document.querySelector('.hero-values');
    
    // Log for debugging
    console.log('Value items found:', valueItems.length);
    console.log('Values grid:', valuesGrid);
    console.log('Hero values:', heroValues);
    
    // Force visibility on hero values section
    if (heroValues) {
        heroValues.style.display = 'block';
        heroValues.style.visibility = 'visible';
        heroValues.style.opacity = '1';
        heroValues.style.minHeight = '400px';
    }
    
    // Force visibility on values grid
    if (valuesGrid) {
        valuesGrid.style.display = 'grid';
        valuesGrid.style.visibility = 'visible';
        valuesGrid.style.opacity = '1';
        valuesGrid.style.gridTemplateColumns = 'repeat(3, 1fr)';
        valuesGrid.style.gap = '3rem';
        valuesGrid.style.maxWidth = '1000px';
        valuesGrid.style.margin = '0 auto';
    }
    
    // Force visibility on each value item
    valueItems.forEach((item, index) => {
        // Remove all animations and transforms
        item.style.animation = 'none';
        item.style.transform = 'none';
        item.style.opacity = '1';
        item.style.visibility = 'visible';
        item.style.display = 'flex';
        item.style.flexDirection = 'column';
        item.style.alignItems = 'center';
        
        // Also apply to all child elements
        const children = item.querySelectorAll('*');
        children.forEach(child => {
            child.style.animation = 'none';
            child.style.transform = 'none';
            child.style.opacity = '1';
            child.style.visibility = 'visible';
        });
        
        console.log(`Value item ${index + 1} forced visible`);
    });
    
    // Remove any animation classes that might be interfering
    document.querySelectorAll('.fade-in, .fade-in-up, .fade-in-down, .animate').forEach(el => {
        if (el.closest('.hero-values')) {
            el.classList.remove('fade-in', 'fade-in-up', 'fade-in-down', 'animate');
        }
    });
    
    // Ensure icons are visible
    document.querySelectorAll('.value-icon i').forEach(icon => {
        icon.style.display = 'block';
        icon.style.opacity = '1';
        icon.style.visibility = 'visible';
    });
    
    // Check for mobile and apply mobile-specific fixes
    if (window.innerWidth <= 768) {
        if (valuesGrid) {
            valuesGrid.style.gridTemplateColumns = '1fr';
            valuesGrid.style.gap = '2rem';
            valuesGrid.style.maxWidth = '320px';
        }
    }
    
    // Re-check visibility after a delay (in case other scripts interfere)
    setTimeout(() => {
        valueItems.forEach(item => {
            if (getComputedStyle(item).opacity === '0' || getComputedStyle(item).visibility === 'hidden') {
                console.warn('Value item still hidden, forcing visibility again');
                item.style.opacity = '1';
                item.style.visibility = 'visible';
                item.style.display = 'flex';
            }
        });
    }, 1000);
});