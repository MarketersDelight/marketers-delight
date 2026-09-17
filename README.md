# Marketers Delight

**A website that feels like yours, loads fast front and back, and gets out of your way so you can focus on publishing what matters to you.**

[![Version](https://img.shields.io/badge/version-6.0-brightgreen.svg)](https://github.com/kolakube/marketers-delight/releases)
[![License](https://img.shields.io/badge/license-GPL--2.0-blue.svg)](LICENSE)
[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-8.0%2B-8892BF.svg)](https://php.net/)
[![Since](https://img.shields.io/badge/since-2011-lightgrey.svg)](https://marketersdelight.com/)

Marketers Delight is the missing operating system for WordPress — a free theme plus a modular Drop-ins ecosystem that lets you stream your thoughts, track your reading, build a glossary, and publish longform, all without a page builder or plugin roulette.

<!-- SCREENSHOT: hero shot of an MD-powered site homepage -->

---

## Install

1. [Download the latest release](https://github.com/kolakube/marketers-delight/releases/latest)
2. In WordPress, go to **Appearance → Themes → Add New → Upload Theme**
3. Upload the zip, click **Activate**

That's it. Your blog is ready to publish to immediately — design and typography defaults are already filled in.

**Requirements:** WordPress 6.0+ · PHP 8.0+

---

## What's included

- **Full theme, no feature limits** — nothing held back, nothing locked
- **Admin options panel** — brand colors and typography as a simple form
- **Header Builder** — drag-and-drop navigation, logos, and menus
- **Panel system** — a sliding layout primitive for navigation, table of contents, search, or any widget
- **Custom sidebars** — per-post, per-page, and per-category without a plugin
- **Post type level settings** — control archives, categories, and tags independently
- **Block and Classic editor support** — featured image alignment, page covers, layout options
- **Free Drop-ins** — included and ready to install

<!-- SCREENSHOT: MD admin options panel -->
<!-- SCREENSHOT: Panel system in action on the frontend -->

---

## Drop-ins

Drop-ins are modular features built exclusively for MD — think plugins that never have to answer *"will this work with my theme?"*

They let you add real content types to your site without the rigidity that has long plagued WordPress sites trying to publish more than longform posts.

| Drop-in | What it does |
|---|---|
| [Stream](https://marketersdelight.com/library/stream/) | A microblog that publishes your site activity |
| [Bookshelf](https://marketersdelight.com/library/bookshelf/) | Share the books you're reading, with quotes attached |
| [Docs](https://marketersdelight.com/library/docs/) | A documentation hub that lives inside your site |
| [Glossary](https://marketersdelight.com/library/glossary/) | An A–Z library of terms and definitions |

**17 Drop-ins and counting**, ready for use on all MD websites.

[**Browse the full Drop-ins catalog →**](https://marketersdelight.com/library/category/dropins/)

<!-- SCREENSHOT: Drop-ins dashboard inside WP admin -->

---

## For developers

MD is built on a modular architecture where every Drop-in extends a shared `md_api` base class. Implement the right methods and your hooks, admin pages, meta boxes, and templates wire themselves up automatically.

```php
class my_dropin extends md_api {

    protected function register() {
        // CPTs, taxonomies, fields
    }

    protected function actions() {
        // hooks and filters
    }
}
```

Meaningful filter hooks sit throughout the layout system, and a dedicated deprecation layer keeps backwards compatibility across major versions.

[**Read the developer docs →**](https://marketersdelight.com/docs/developers/)

---

## Why isn't this on WordPress.org?

MD's architecture doesn't meet the WordPress.org theme directory's coding standards — the Drop-in system, custom fields API, and admin structure all live outside what the directory allows. So MD lives here instead, fully open source and free to download.

---

## Why MD exists

The internet is growing in reach and size every year, yet somehow it feels smaller than ever. The cost of accessing the most incredible information in the world has been to endure a constant attack on your attention and a harvesting of your data — and we let it happen as a matter of convenience.

Not too long ago, experts and crude ranking bots started to define the rules of how we used our own websites. The message now is that it isn't worth creating with your own two hands any longer; that you should just prompt it, and that your work will never be found unless it goes viral or appeases a faceless algorithm.

But when you publish to your own platform, you keep what you make. Every post, page, note, and subscriber is yours, rent-free. Your website can be a tool for learning, a place to think in public, a reason to read more, and a way to reach people in ways analytics will never capture.

WordPress is still the best independent publishing platform on the web — nothing else has survived the test of time quite like it. MD takes that untapped power and focuses it atop a rock-solid design foundation, with modular Drop-in features and intuitive user-level controls that let you personalize your site in all the ways WordPress promised but never made easy.

---

## About Marketers Delight

Since 2011, the [**Marketers Delight WordPress Theme**](https://marketersdelight.com/) and ecosystem has helped thousands of bloggers, writers, and marketers publish beautiful landing pages, grow their email lists, create engaging blogs, and build a meaningful home on the internet.

After years of development and a philosophical rebirth, MD 6.0 is released completely free here on GitHub. Formerly a paid-only theme, 6.0 unveils more features and capabilities than ever.

Out of the box, MD ships with a simple admin options panel that makes fine-tuning your brand colors and typography as easy as filling out a form. Armed with an intuitive Header Builder, logo customizer, and native menus, you can get your site navigation knocked out in no time.

Your blog is ready to go as soon as you install the theme, and with your design and typography settings already filled out, you'll feel right at home writing in your post editor. To enhance your posts, you can align your featured image in many ways, create beautiful hero page covers, customize layout options, and more — from both the Block and Classic editors.

Once you've created a few posts, the next natural step is customizing how they're listed on your blog homepage and category pages: the number of columns, which author meta appears in the byline, adding a sidebar, editing the main title and on-page description. With post type level settings in MD 6.0, you can personalize these changes and rapidly build extensive archives, category, and tag pages with minimal effort.

The promise to anyone who uses Marketers Delight is to help you create a website you actually love publishing to — and to deliver content in a way that makes your readers crave coming back for more.

---

## Links

- [Marketers Delight](https://marketersdelight.com/) — homepage
- [Documentation](https://marketersdelight.com/docs/)
- [Drop-ins catalog](https://marketersdelight.com/library/category/dropins/)
- [Changelog](https://marketersdelight.com/changelog/)
- [Community forum](https://kolakube.com/community/)

## License

GPL-2.0-or-later. See [LICENSE](LICENSE).
