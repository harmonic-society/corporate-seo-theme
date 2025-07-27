# Claude Project Guidelines

## Project Overview

WordPress Theme "Corporate SEO Pro" - A corporate WordPress theme specialized in SEO optimization, performance, and Japanese market support

## Brand Identity

### Brand Colors
- **Primary Color**: `#00867b` - Use this color as the base throughout all projects
- **Secondary Color**: `#10b981` - Use as accent color
- Derived brand colors should be created by adjusting the brightness and saturation of the primary color
- Examples:
  - Light: `rgba(0, 134, 123, 0.1)` (10% opacity)
  - Medium: `rgba(0, 134, 123, 0.5)` (50% opacity)
  - Dark: `#006b61` (darker shade)

## Coding Standards

### WordPress Theme Development Standards

1. **Naming Conventions**
   - PHP files: kebab-case (e.g., `theme-setup.php`)
   - PHP functions: snake_case with prefix (e.g., `corporate_seo_pro_setup()`)
   - CSS classes: kebab-case (e.g., `service-grid-item`)
   - JavaScript functions: camelCase (e.g., `handleMobileMenu()`)
   - Constants: UPPER_SNAKE_CASE (e.g., `CORPORATE_SEO_PRO_VERSION`)

2. **WordPress Coding Standards**
   - Follow WordPress Coding Standards
   - Use prefix: `corporate_seo_pro_`
   - Text domain: `corporate-seo-pro`

3. **Escaping and Sanitization**
   ```php
   // Always escape output
   echo esc_html( $title );
   echo esc_url( $link );
   echo esc_attr( $attribute );
   
   // Sanitize input values
   $clean_data = sanitize_text_field( $_POST['data'] );
   ```

### File Structure Organization

```
corporate-seo-theme/
├── assets/
│   ├── css/
│   │   ├── base/              # Base styles
│   │   │   ├── base.css       # Reset and base settings
│   │   │   ├── typography.css # Typography
│   │   │   └── responsive.css # Responsive settings
│   │   ├── components/        # Component-specific CSS
│   │   │   ├── navigation.css
│   │   │   ├── buttons.css
│   │   │   ├── forms.css
│   │   │   ├── hero.css
│   │   │   └── mobile-menu.css
│   │   ├── layouts/          # Layout related
│   │   ├── pages/            # Page-specific styles
│   │   │   ├── pages.css
│   │   │   └── single-service.css
│   │   ├── utilities/        # Utility classes
│   │   │   └── utilities.css
│   │   └── color-scheme-teal.css  # Color scheme
│   ├── js/
│   │   ├── utils/            # Utility functions
│   │   │   ├── animation-utils.js
│   │   │   └── tablet-detection.js
│   │   ├── navigation.js     # Navigation
│   │   ├── mobile-menu.js    # Mobile menu
│   │   └── theme.js          # Main JavaScript
│   └── images/              # Image files
├── inc/                     # PHP function files
│   ├── theme-setup.php      # Theme initial setup
│   ├── assets.php           # CSS/JS loading
│   ├── post-types.php       # Custom post types
│   ├── customizer.php       # Customizer settings
│   ├── seo-functions.php    # SEO related functions
│   ├── structured-data.php  # Structured data
│   └── template-functions.php # Template functions
├── template-parts/          # Reusable templates
│   ├── content-*.php        # Content display
│   ├── hero-*.php           # Hero sections
│   └── service-*.php        # Service related
├── languages/               # Translation files
├── acf-json/               # ACF configuration files
├── functions.php           # Main functions file
├── style.css              # Theme info and base styles
└── Template files
    ├── index.php
    ├── front-page.php
    ├── page.php
    ├── single.php
    ├── archive.php
    └── 404.php
```

### CSS Writing Rules

```css
/* WordPress theme CSS example */
.service-grid-item {
  /* Layout */
  display: flex;
  flex-direction: column;
  
  /* Sizing */
  width: 100%;
  padding: 2rem;
  margin-bottom: 2rem;
  
  /* Visual styles */
  background-color: #ffffff;
  border: 1px solid rgba(0, 134, 123, 0.1);
  border-radius: 8px;
  
  /* Effects */
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.service-grid-item:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 134, 123, 0.15);
}
```

### PHP Template Example

```php
<?php
/**
 * Service archive template
 *
 * @package Corporate_SEO_Pro
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <div class="service-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content', 'service' );
                endwhile;
                ?>
            </div>
            
            <?php the_posts_navigation(); ?>
        <?php else : ?>
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();
```

## Styling Best Practices

### CSS Variables Usage

```css
/* style.css or base.css */
:root {
  /* Brand colors */
  --primary-color: #00867b;
  --primary-light: rgba(0, 134, 123, 0.1);
  --primary-dark: #006b61;
  --secondary-color: #10b981;
  
  /* Text colors */
  --text-color: #1f2937;
  --text-light: #6b7280;
  --text-white: #ffffff;
  
  /* Background colors */
  --bg-white: #ffffff;
  --bg-light: #f9fafb;
  --bg-dark: #1f2937;
  
  /* Spacing */
  --spacing-xs: 0.5rem;
  --spacing-sm: 1rem;
  --spacing-md: 1.5rem;
  --spacing-lg: 2rem;
  --spacing-xl: 3rem;
  
  /* Fonts */
  --font-primary: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans JP", sans-serif;
  --font-heading: "Noto Sans JP", sans-serif;
  
  /* Others */
  --border-radius: 8px;
  --transition: all 0.3s ease;
}
```

### Responsive Design

```css
/* Mobile-first approach */
.service-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

/* Tablet */
@media (min-width: 768px) {
  .service-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

/* Desktop */
@media (min-width: 1024px) {
  .service-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}
```

## ACF (Advanced Custom Fields) Usage

```php
// Check field existence and output
<?php if( get_field('service_features') ): ?>
    <div class="service-features">
        <?php the_field('service_features'); ?>
    </div>
<?php endif; ?>

// Repeater fields
<?php if( have_rows('pricing_plans') ): ?>
    <div class="pricing-plans">
        <?php while( have_rows('pricing_plans') ): the_row(); ?>
            <div class="pricing-plan">
                <h3><?php the_sub_field('plan_name'); ?></h3>
                <p class="price"><?php the_sub_field('plan_price'); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>
```

## Performance Optimization

1. **Asset Optimization**
   - Proper CSS/JS loading order
   - Exclude unnecessary files
   - Implement conditional loading

2. **Image Optimization**
   ```php
   // Register appropriate image sizes
   add_image_size( 'service-thumbnail', 400, 300, true );
   add_image_size( 'service-featured', 800, 600, true );
   
   // Lazy loading
   <img src="<?php echo esc_url( $image_url ); ?>" 
        loading="lazy" 
        alt="<?php echo esc_attr( $image_alt ); ?>">
   ```

3. **Cache Usage**
   - Use Transients API
   - Implement object caching

## SEO Optimization

1. **Structured Data**
   - Schema.org markup implementation
   - Breadcrumb structured data
   - Article and service structured data

2. **Meta Tag Management**
   ```php
   // OGP and Twitter Cards
   <meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
   <meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
   <meta property="og:image" content="<?php echo esc_url( $image ); ?>">
   ```

3. **Proper Heading Structure**
   - One h1 per page
   - Hierarchical heading structure
   - Proper keyword placement

## Checklist

When developing WordPress themes, check the following items:

- [ ] Brand color #00867b is properly used
- [ ] Follows WordPress Coding Standards
- [ ] All output is escaped
- [ ] Translation functions are properly used
- [ ] Responsive design is implemented
- [ ] Accessibility is considered
- [ ] Performance is optimized
- [ ] SEO measures are implemented
- [ ] Security is considered

## Git Operation Rules

### Required Steps at End of Work Session

**At the end of every work session, always execute the following Git operations:**

```bash
# 1. Check changes
git status

# 2. Stage all changes
git add .

# 3. Commit with meaningful message
git commit -m "feat: Improve service archive page layout"

# 4. Push to remote repository
git push origin main
```

### Commit Message Convention

```
<type>: <subject>

<body> (optional)

<footer> (optional)
```

**Types:**
- `feat:` Add new feature
- `fix:` Bug fix
- `style:` CSS or design changes
- `refactor:` Refactoring
- `perf:` Performance improvements
- `docs:` Documentation changes
- `chore:` Build process or tool changes
- `test:` Add or modify tests

**Good examples:**
```bash
git commit -m "feat: Add mobile menu animation"
git commit -m "fix: Fix tablet layout issues"
git commit -m "style: Unify brand color to #00867b"
```

## Development Tools

### Recommended Development Environment
- Local by Flywheel
- MAMP/XAMPP
- Docker + WordPress

### Recommended Editor Extensions
- PHP Intelephense
- WordPress Snippets
- ACF Snippets
- phpcs (WordPress Coding Standards)

### Debug Tools
```php
// Settings in wp-config.php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', true );
```

By following these guidelines, you can develop maintainable and high-performance WordPress themes.