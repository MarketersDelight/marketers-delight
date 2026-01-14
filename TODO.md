# Marketers Delight 6.0 - Modernization TODO

Target: PHP 7.4+ | WordPress 6.0+

---

## Priority 0: Port Features from Local Development (6.3.1)

Features already implemented in `marketers-delight-local` that should be merged.

### Core API Additions

- [x] `lib/api/css-optimizer.php` - Conditional CSS loading system ✓ MIGRATED
  - Categorizes CSS (critical, shared, page-specific)
  - Loads CSS conditionally per page type
  - Reduces unused CSS payload
- [x] `lib/api/theme-json.php` - Dynamic theme.json generator ✓ MIGRATED
  - Generates block editor config from MD settings
  - Replaces static theme.json approach
- [ ] `lib/api/hooks.php` - Centralized hooks definition
- [ ] `lib/api/filters.php` - Centralized filters definition

### New Function Files

- [ ] `lib/functions/build.php` - Build/compilation system
- [ ] `lib/functions/classes.php` - Class management and initialization
- [ ] `lib/functions/conditionals.php` - Conditional checks infrastructure
- [ ] `lib/functions/custom-hooks.php` - Custom theme hooks for site-specific functionality

### Featured Video System

- [ ] `lib/wp/featured-video/featured-video.php` - Video meta box
- [ ] `lib/wp/featured-video/meta-box.php` - Video meta UI
- [ ] `templates/featured-video.php` - Frontend template
- [ ] Supports YouTube, Vimeo, and custom embed codes with position control

### Featured Image Enhancements

- [ ] `lib/wp/featured-image/` - Complete featured image system
  - Admin fields for image options
  - Template functions for flexible display

### WordPress Optimization Module

- [ ] `lib/wp/optimize.php` - Performance optimization module

### Enhanced Upgrader System

- [ ] `lib/wp/upgraders/dropins/` - Three upgrader skin classes
  - Safe drop-in installation/updates
- [ ] `lib/wp/upgraders/md-upgrader/` - Theme upgrader
  - `upgrade.php` and `utilities.php`

### Modular Admin Structure

Reorganize flat admin files into modular structure:

- [ ] `lib/admin/admin.php` + `admin-page.php` (core)
- [ ] `lib/admin/dashboard/` - Dashboard with templates
- [ ] `lib/admin/dropins/` - Drop-in management (admin, page, store)
- [ ] `lib/admin/colors/` - Color settings module
- [ ] `lib/admin/content/` - Content settings module
- [ ] `lib/admin/header/` - Header settings module
- [ ] `lib/admin/icons/` - Icon settings module
- [ ] `lib/admin/integrations/` - Integration settings module
- [ ] `lib/admin/layout/` - Layout settings module
- [ ] `lib/admin/loop/` - Loop settings module
- [ ] `lib/admin/sidebars/` - Sidebar settings module
- [ ] `lib/admin/typography/` - Typography settings module

### New CSS Template Files (11 new)

Block styles:
- [ ] `css/blocks/cta.php` - Call-to-action styling
- [ ] `css/blocks/faq.php` - FAQ block styles
- [ ] `css/blocks/inline-poll.php` - Poll block styling
- [ ] `css/blocks/key-takeaway.php` - Key takeaway box
- [ ] `css/blocks/pull-quote.php` - Pull quote styling
- [ ] `css/blocks/related-content.php` - Related content block
- [ ] `css/blocks/side-promotion.php` - Side promotion styling
- [ ] `css/blocks/steps.php` - Step-by-step block

General CSS:
- [ ] `css/effects.php` - Animation effects (bounce, spin, loading states)
- [ ] `css/format.php` - Text formatting styles
- [ ] `css/spacers.php` - Spacing utility styles
- [ ] `css/columns.php` - Column management

### New General Templates

- [ ] `templates/content-item-404.php` - 404 page content item
- [ ] `templates/email-form.php` - Email form template
- [ ] `templates/footer-columns.php` - Footer column layout
- [ ] `templates/footer-copy.php` - Footer copyright
- [ ] `templates/header-triggers.php` - Header event triggers
- [ ] `templates/post-nav.php` - Post navigation
- [ ] `templates/blocks/callout.php` - Callout block template
- [ ] `templates/blocks/content-upgrade.php` - Content upgrade template

### Enhanced Byline System (7 new templates)

- [ ] `templates/byline/author.php`
- [ ] `templates/byline/badge.php`
- [ ] `templates/byline/category.php`
- [ ] `templates/byline/comments.php`
- [ ] `templates/byline/date.php`
- [ ] `templates/byline/edit.php`
- [ ] `templates/byline/newsletter.php`
- [ ] `templates/byline/post-series.php`
- [ ] `templates/byline/verified-by.php`

### PWA & Offline Support

- [ ] `offline.php` - Offline fallback page for PWA official plugin.
  - Animated offline icon
  - Connection status
  - Cached content suggestions

### FluentCart E-Commerce Integration

- [ ] `single-fluent-products.php` - Single product template
- [ ] `archive-fluent-products.php` - Product archive template
- [ ] `taxonomy-product-categories.php` - Product category template
- [ ] `taxonomy-product-brands.php` - Product brand template
- [ ] `fluent-cart.css` - FluentCart styling (18KB)
  - CSS variables integration
  - Product gallery, cart UI, checkout form
  - Brand and category display
  - Responsive product cards
- [ ] Dequeue/enqueue logic for FluentCart CSS
- [ ] Template include filters
- [ ] Rank Math breadcrumb support

### Enhanced Widget System

Reorganize widgets into class-based structure:
- [ ] `lib/wp/widgets/accordion.php`
- [ ] `lib/wp/widgets/content-spotlight.php`
- [ ] `lib/wp/widgets/quote.php`
- [ ] `lib/wp/widgets/text-image.php`
- [ ] Admin templates for each widget

### Enhanced JavaScript (scripts.js)

- [ ] `button()` - Loading state management for forms
- [ ] `mainMenu()` - Enhanced main menu with mobile support
- [ ] Mobile menu trigger with submenu stack tracking
- [ ] Back navigation for nested menus
- [ ] Menu state management
- [ ] Cookie management for persistent state

### Custom Menu Walker

- [ ] `class-gt-menu-walker.php` - Advanced menu rendering
  - Description support
  - Custom markup

### WordPress 6.7+ Compatibility

- [ ] Move `load_textdomain` to `init` hook (WP 6.7+ standard)
- [ ] Update initialization patterns

### Build System

- [ ] `build.sh` - Build script with version management
- [ ] Enhanced `md_compile()` and `md_compile_js()` functions
- [ ] Compile debug logging

---

## Priority 1: Type Safety (PHP 7.4+)

### Add Type Declarations to Core Classes

- [ ] `lib/api/api.php` - Add parameter and return types to `md_api` class
- [ ] `lib/api/sanitize.php` - Type all sanitization methods
- [ ] `lib/api/fields.php` - Type field generation methods
- [ ] `lib/api/design.php` - Type design API methods
- [ ] `lib/api/css.php` - Type CSS generation methods
- [ ] `lib/api/js.php` - Type JavaScript API methods
- [ ] `lib/api/requests.php` - Type AJAX handler methods
- [ ] `lib/api/files.php` - Type file operation methods
- [ ] `marketers-delight.php` - Type main theme class methods
- [ ] `lib/admin.php` - Type admin handler methods

### Add Type Declarations to Functions

- [ ] `lib/functions/api-functions.php` - Type `md_template()`, `md_css()`, `md_setting()`, etc.
- [ ] `lib/functions/post-functions.php` - Type post rendering functions
- [ ] `lib/functions/loop-functions.php` - Type archive/loop functions
- [ ] `lib/functions/layout-functions.php` - Type layout/grid functions
- [ ] `lib/functions/boxes-functions.php` - Type box rendering functions
- [ ] `lib/functions/cover-functions.php` - Type cover image functions
- [ ] `lib/functions/sidebar-functions.php` - Type sidebar functions
- [ ] `lib/functions/widgets-functions.php` - Type widget helper functions
- [ ] `lib/functions/buttons-functions.php` - Type button rendering functions
- [ ] `lib/functions/forms-functions.php` - Type form functions
- [ ] `lib/functions/icons-functions.php` - Type icon functions
- [ ] `lib/functions/responsive-functions.php` - Type responsive functions
- [ ] `lib/functions/block-functions.php` - Type block functions

### Add Strict Types Declaration

- [ ] Add `declare(strict_types=1);` to all PHP files
- [ ] Run PHPStan at level 6+ after adding types
- [ ] Fix all type-related issues found by PHPStan

---

## Priority 2: WordPress 6.0+ Features

### Block Editor Improvements

- [ ] Convert custom blocks to `block.json` metadata format
  - [ ] `wp/block-arrow.js` → `blocks/arrow/block.json`
  - [ ] `wp/block-callout.js` → `blocks/callout/block.json`
  - [ ] `wp/block-content-upgrade.js` → `blocks/content-upgrade/block.json`
- [ ] Add block patterns as JSON files in `patterns/` directory
- [ ] Use `register_block_type_from_metadata()` instead of manual registration
- [ ] Add `viewScript` and `viewScriptModule` support for frontend interactivity
- [ ] Implement Block Variations for common configurations

### theme.json Enhancements (WP 6.0+)

- [ ] Create comprehensive `theme.json` schema (version 3)
- [ ] Define color palette with CSS custom properties
- [ ] Define typography presets (font families, sizes, weights)
- [ ] Define spacing scale (padding, margin, gap)
- [ ] Configure block-level appearance tools
- [ ] Add custom template definitions
- [ ] Add style variations for different design presets

### Block Hooks API (WP 6.4+)

- [ ] Migrate custom header hooks to Block Hooks API
- [ ] Use `hooked_block_types` filter for automatic block insertion
- [ ] Document migration path for drop-ins using old hook system

### Interactivity API (WP 6.5+)

- [ ] Evaluate dropdown menus for Interactivity API conversion
- [ ] Evaluate accordion widget for Interactivity API
- [ ] Add `@wordpress/interactivity` for interactive components
- [ ] Use `data-wp-*` directives instead of custom JavaScript

---

## Priority 3: Missing Core WordPress Theme Files

### Standard Template Files (Add to root)

- [ ] `404.php` - Custom 404 error page
- [ ] `archive.php` - Default archive template
- [ ] `author.php` - Author archive template
- [ ] `category.php` - Category archive template
- [ ] `date.php` - Date-based archive template
- [ ] `front-page.php` - Static front page template
- [ ] `home.php` - Blog posts index template
- [ ] `page.php` - Single page template
- [ ] `search.php` - Search results template
- [ ] `single.php` - Single post template
- [ ] `singular.php` - Fallback for single posts/pages
- [ ] `tag.php` - Tag archive template
- [ ] `taxonomy.php` - Custom taxonomy archive template
- [ ] `attachment.php` - Attachment/media template
- [ ] `image.php` - Image attachment template
- [ ] `privacy-policy.php` - Privacy policy page template (WP 4.9.6+)

### Block Theme Files (WP 6.0+)

- [ ] `theme.json` - Block editor configuration (version 3)
- [ ] `patterns/` directory - Block patterns as PHP files
- [ ] `parts/` directory - Template parts (header.html, footer.html)
- [ ] `templates/` directory - Block templates (index.html, single.html, etc.)
- [ ] `styles/` directory - Style variations

### Enhance Existing Loop Templates

Instead of new templates, enhance existing ones in `templates/loop/`:
- [ ] `loop.php` - Add more layout options, grid support
- [ ] `loop-blocks.php` - Block-based loop rendering
- [ ] `loop-covers.php` - Cover image variations
- [ ] `loop-list.php` - List layout enhancements
- [ ] `the-post.php` - Enhanced post card component
- [ ] `query.php` - Advanced query parameters

---

## Priority 4: Deprecation Cleanup

### Review deprecated.php (21.5 KB)

- [ ] Audit all deprecated functions for actual usage
- [ ] Add proper `_deprecated_function()` notices to each
- [ ] Document removal timeline in changelog
- [ ] Remove functions deprecated before MD 5.0
- [ ] Create migration guide for drop-in developers

### Deprecated Patterns

- [ ] Replace legacy Customizer controls with block-based alternatives
- [ ] Migrate remaining widget areas to block-based widget areas
- [ ] Update legacy shortcodes to block equivalents

---

## Priority 5: Performance Improvements

### CSS Generation

- [ ] Cache generated CSS using transients
- [ ] Add cache invalidation on settings change
- [ ] Consider static CSS file generation instead of runtime
- [ ] Implement Critical CSS extraction for above-fold content
- [ ] Add CSS minification for production

### JavaScript

- [ ] Convert to ES modules with proper bundling
- [ ] Add `defer` and `async` attributes appropriately
- [ ] Implement lazy loading for non-critical scripts
- [ ] Remove jQuery dependency where possible (use vanilla JS)

### Database

- [ ] Audit `get_option()` calls for autoload optimization
- [ ] Batch meta queries where possible
- [ ] Add object caching hints for expensive operations

---

## Priority 6: Build Tools & Asset Pipeline

### Add npm/Webpack Setup

- [ ] Create `package.json` with build scripts
- [ ] Configure Webpack for JavaScript bundling
- [ ] Add Sass compilation for CSS source files
- [ ] Set up hot module replacement for development
- [ ] Add production build with minification

### Recommended package.json Scripts

```json
{
  "scripts": {
    "build": "wp-scripts build",
    "start": "wp-scripts start",
    "lint:js": "wp-scripts lint-js",
    "lint:css": "wp-scripts lint-style",
    "format": "wp-scripts format"
  }
}
```

---

## Priority 7: Testing Infrastructure

### PHPUnit Setup

- [ ] Create `tests/` directory structure
- [ ] Add `phpunit.xml.dist` configuration
- [ ] Set up WordPress test framework integration
- [ ] Write unit tests for `md_sanitize` class
- [ ] Write unit tests for template functions
- [ ] Write integration tests for CSS generation

### Test Coverage Goals

- [ ] Core API classes: 80% coverage
- [ ] Sanitization: 100% coverage
- [ ] Template rendering: 70% coverage
- [ ] Settings retrieval: 70% coverage

---

## Priority 8: Static Analysis Improvements

### PHPStan Configuration

- [ ] Create `phpstan.neon` with WordPress stubs
- [ ] Set up baseline for existing issues
- [ ] Target level 6 initially, work toward level 8
- [ ] Add PHPStan extensions for WordPress

### Psalm Configuration

- [ ] Create `psalm.xml` configuration
- [ ] Enable strict mode for new code
- [ ] Add WordPress plugin for better inference

---

## Priority 9: Documentation

### Code Documentation

- [ ] Add PHPDoc blocks to all public methods
- [ ] Document expected parameter types and return values
- [ ] Add `@since` tags for version tracking
- [ ] Add `@deprecated` tags with migration instructions

### Developer Documentation

- [ ] Create `docs/` directory
- [ ] Document drop-in development API
- [ ] Document available hooks and filters
- [ ] Create block development guide
- [ ] Add code contribution guidelines

---

## Priority 10: Security Hardening

### Input Validation

- [ ] Audit all `$_POST`, `$_GET`, `$_REQUEST` usage
- [ ] Ensure all AJAX handlers verify nonces
- [ ] Add capability checks to all admin operations
- [ ] Validate file paths in template loading

### Output Escaping

- [ ] Audit all `echo` statements for proper escaping
- [ ] Use `wp_kses_post()` for HTML content output
- [ ] Ensure all attributes use `esc_attr()`
- [ ] Ensure all URLs use `esc_url()`

---

## Quick Wins (Start Here)

These require minimal effort and provide immediate benefits:

### From Local Version (Copy/Merge)

1. [x] Copy `lib/api/css-optimizer.php` - Instant CSS performance boost ✓ DONE
2. [x] Copy `lib/api/theme-json.php` - Dynamic block editor settings ✓ DONE
3. [ ] Copy `lib/wp/optimize.php` - Performance optimizations
4. [ ] Copy `css/effects.php` - Animation utilities
5. [ ] Copy enhanced `scripts.js` with button() and mainMenu()
6. [ ] Move `load_textdomain` to `init` hook (WP 6.7+ compat)

### Core Template Files

7. [ ] Add `404.php` - Most visible missing template
8. [ ] Add `single.php` - Single post template
9. [ ] Add `page.php` - Single page template
10. [ ] Add `archive.php` - Archive fallback template
11. [ ] Add `search.php` - Search results template

### Code Quality

12. [ ] Add `declare(strict_types=1);` to new files only
13. [ ] Add return types to simple getter methods
14. [ ] Create `theme.json` with basic settings
15. [ ] Convert one block to `block.json` format as template
16. [ ] Add PHPDoc return types to core functions
17. [ ] Set up baseline PHPStan configuration

---

## Breaking Changes to Avoid

These changes would break backwards compatibility and should be avoided or done with major version bump:

- Removing public class methods without deprecation period
- Changing function signatures without optional defaults
- Removing hooks without providing alternatives
- Changing option storage keys
- Removing template files without fallbacks

---

## Version Targets

| Feature | Target Version |
|---------|----------------|
| Port local features (Priority 0) | 6.1 |
| Type hints (new code) | 6.1 |
| theme.json v3 | 6.1 |
| Block.json conversion | 6.1 |
| Modular admin structure | 6.1 |
| CSS optimizer | 6.1 |
| Missing core template files | 6.1 |
| Full type coverage | 6.2 |
| PWA support | 6.2 |
| FluentCart integration | 6.2 |
| Block theme hybrid support | 6.2 |
| Deprecated removal | 7.0 |

---

## References

- [PHP 7.4 New Features](https://www.php.net/manual/en/migration74.new-features.php)
- [WordPress Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [theme.json Reference](https://developer.wordpress.org/block-editor/reference-guides/theme-json-reference/theme-json-living/)
- [Block Hooks API](https://make.wordpress.org/core/2023/10/15/introducing-block-hooks-for-dynamic-blocks/)
- [Interactivity API](https://developer.wordpress.org/block-editor/reference-guides/interactivity-api/)
