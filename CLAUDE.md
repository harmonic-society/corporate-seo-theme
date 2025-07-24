# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a WordPress theme called "Corporate SEO Pro" - a custom corporate WordPress theme with a strong focus on SEO optimization, performance, and Japanese market support.

## Architecture

The theme follows WordPress best practices with a modular architecture:

- **Custom Post Types**: `service` (services) and `work` (portfolio/case studies)
- **No Build Process**: Direct CSS/JS files without preprocessing
- **ACF Integration**: Uses Advanced Custom Fields for content management
- **Modular PHP**: Functionality separated into `/inc/` files for maintainability
- **Component-based CSS**: Styles split into logical modules in `/assets/css/`

Key architectural patterns:
- Template parts are reused across templates via `get_template_part()`
- Custom Walker classes for navigation menus (desktop/mobile)
- Structured data implementation for SEO
- Performance optimizations including lazy loading and resource hints

## Development Commands

Since this is a traditional WordPress theme without build tools:

```bash
# No build commands needed - edit files directly
# To develop, set up a local WordPress environment and activate the theme
```

## Key Files and Their Purposes

**Theme Entry Points:**
- `functions.php`: Loads all theme functionality from `/inc/` directory
- `style.css`: Theme metadata and base styles

**Functionality Modules (`/inc/`):**
- `theme-setup.php`: Theme support declarations and initial setup
- `post-types.php`: Registers custom post types and taxonomies
- `seo-functions.php`: SEO-related functions and meta tags
- `structured-data.php`: Schema.org structured data implementation
- `acf-fields.php`: ACF field group registration

**Template Hierarchy:**
- `front-page.php`: Homepage template
- `archive-service.php`, `single-service.php`: Service post type templates
- `archive-work.php`, `single-work.php`: Work post type templates
- `page-*.php`: Specific page templates (about, contact, etc.)

## CSS Architecture

The CSS follows a modular approach:
- Uses CSS custom properties for theming
- Mobile-first responsive design
- Separate files for components, layouts, and utilities
- No preprocessor - vanilla CSS with modern features

## JavaScript Structure

- Vanilla JavaScript (no frameworks)
- Modular files for different functionality
- Utilities separated in `/assets/js/utils/`

## Important Conventions

1. **Translation Ready**: Use `__()` and `_e()` with text domain 'corporate-seo-pro'
2. **Escaping**: Always escape output using WordPress functions (`esc_html()`, `esc_url()`, etc.)
3. **ACF Fields**: Check field existence before output: `if( get_field('field_name') )`
4. **Template Parts**: Use `get_template_part()` for reusable components
5. **Assets**: Enqueue scripts/styles properly via `wp_enqueue_*` functions

## SEO Considerations

This theme prioritizes SEO:
- Structured data for all content types
- Proper heading hierarchy
- Meta tag management
- Performance optimizations for Core Web Vitals
- Clean, semantic HTML markup