# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Marketers Delight is a premium WordPress theme (v6.0) with a custom framework for extending WordPress functionality. It includes a proprietary API system for options/settings, dynamic CSS/JS compilation from PHP, and a custom drop-ins extensibility mechanism.

## Development Commands

```bash
# Validate composer configuration
composer validate --strict

# Install dependencies
composer install --prefer-dist --no-progress

# Run PHPCS (WordPress Coding Standards)
# Configured via .github/workflows/phpcs.yml - runs automatically on push/PR

# Run PHPStan static analysis
# Configured via .github/workflows/phpstan.yml - runs automatically on push/PR
```

**No npm/Node.js build process** - work directly with PHP/CSS/JS files.

## Architecture

### Entry Point

Single main class `marketers_delight` in `marketers-delight.php`:
- `init()` method bootstraps everything
- `includes()` loads 40+ required files
- `setup()` configures WordPress theme support
- `wp_init()` handles post-initialization logic

### Core Systems

**API System** (`lib/api/`):
- `md_api` is the base class for all settings/options handling
- `md_fields` renders admin form fields
- `md_fields_data` handles field data persistence
- `md_design`, `md_css`, `md_js` handle output generation

**Function Helpers** (`lib/functions/`):
- 112+ global functions prefixed with `md_`
- Key functions: `md_setting()`, `md_template()`, `md_compile()`

**Dynamic JS/CSS Compilation**:
- `scripts.php` is a PHP template that compiles to `js/scripts.js`
- Triggered when user saves MD admin options or theme activates
- `md_compile()` and `md_compile_js()` handle regeneration

### Directory Purposes

| Directory | Purpose |
|-----------|---------|
| `lib/api/` | Core API system - custom field/option handling |
| `lib/functions/` | Global `md_*` helper functions |
| `wp/` | WordPress integrations: widgets, blocks, hooks |
| `templates/` | Theme template parts + admin UI |
| `templates/admin/` | Admin panel pages (20+ setting pages) |
| `css/` | Stylesheets (includes PHP config files) |
| `js/` | Frontend + admin JavaScript |

### Key Constants

```php
MD_VERSION          // '6.0'
MD_DIR              // Theme directory path
MD_URL              // Theme directory URI
MD_PLUGIN_DIR       // Empty (not a plugin)
MD_DROPINS_DIR      // Theme's dropins directory
MD_INSTALLED_DROPINS // wp-content/md-dropins/
```

## Conventions

- **No PHP namespaces** - all code uses global scope or class-based architecture
- **Function prefix**: All global helpers use `md_` prefix
- **Template loading**: Use `md_template()` for template parts with override support
- **WordPress Coding Standards** enforced via PHPCS
- **EditorConfig**: Tabs for indentation (except YAML = 2 spaces)

## Drop-ins System

Custom extensibility mechanism (not standard WordPress drop-ins):
- Installed to `wp-content/md-dropins/`
- Managed via `/wp/dropin-upgrader.php`
- Admin UI at `/templates/admin/dropins.php`

## Workflow Notes

- GitHub Actions auto-fix PHPCS issues and commit them
- POT translation files auto-generated on main branch pushes
- PHPStan runs basic WordPress-aware static analysis
