# Marketers Delight 6.0 Alpha

MD6.0 is the next evolution of the Marketers Delight WordPress Theme and Drop-ins Suite for WordPress.

The MD Theme Framework turns WordPress into a content publishing and web development machine. With its own simple admin options, MD fills in the missing pieces of WordPress that gives users a truly simply and intuitive way to build comprehensive content websites and blogs. Under-the-hood, MD adds a rich and approachable development environment that makes it easy for developers to build their own custom features without sacrificing performance or patching together a variety of 3rd party plugins.

Due to the extended development time of MD6.0, the initial release is meant to deliver a solid foundation for building websites in the modern era. The true WOW factors will follow. New features have been developed since the 5.x series such as the Header/Byline builder, transportable Post Type options, unified post meta, Hero and Page Cover controls, dynamic Loop settings, and a greatly enhanced `MDAPI` that ties it all together. All the way down to truly  semantic markup, the fundamentals of MD6.0 pave a smooth road for shipping modern features through Drop-ins and future Theme updates.

MD6.0 adopts the Hybrid approach to WordPress, meaning support for both Classic and Blocks methodologies are baked into the source code. The data shows that while Block Editor adoption is the present/future and development is maturing, Classic solutions suchas the _Classic Editor_ plugin and small but dedicated WordPress forks suchas _ClassicPress_ are growing, and have merit when considering development approaches with WordPress.

Why alienate entire market sections when MD has what it takes to thrive in both environments? By handling the outer shell and fundamentals of a website such as Colors, Typography, Header, and Footer to name a few, MD can merge with the opt-in features of FSE (features like `theme.json` will be adopted post 6.0) and still utilize both the Classic and Block Editor within the content area, which opens up the usage of Block plugins like GenerateBlocks and other Block solutions.

Combine this with the gold rush of AI, and the vision of a vast Drop-ins libray and fast development pace can be achieved with the near complete foundation of MD6.0.

This repository marks the final stages of the rollout of MD6.0 and if you're reading this, you are the first to have access to this code base. Please submit any issues and improvements you find in your own testing. The core issues to look out for at this stage generally revolve around frontend spacing, admin UI completeness, and options delivering what they promise. Feature requests are welcome, but are mostly on lock as the #1 priority to SHIP MD ALREADY!

Much love to you who are here, as you have been the true believers even during my low points over the years of this project. Maybe I will tell the story later, but for now, it is time to get MD out there!

See Alpha notes below:

---

## Development Notes
- `md_compile()` is turned on in `functions.php`
- CHILD THEMES: The `lib` folder has been removed in 6.0, please load the MD library to your child themes with:
```
require_once get_template_directory() . '/marketers-delight.php';
```

## Known Issues

- [ ] Block Editor and Classic Editor styles missing / incomplete
- [ ] Blocks / Widgets extracted from theme, need to build as Drop-in
- [x] Page CTA > Links > Edit any link > URL is default value, but URL text field not shown until manually select option
- [x] Header Cover title spacing slightly off with extra `gap` when Title only
- [x] Test page title on author pages
- [x] Layout options: Sidebar meta fields not toggling in all cases
- [x] 404 page frontend not loading content
- [x] Full Header Cover absolute position CSS styling missing
- [x] Full Header Cover mobile toggles need backgrounds to prevent overlap
- [x] Header Cover - apply `alt` styling
- [x] Breadcrumbs out of place on Full Header Cover
- [x] Titles in Loop full column needs to match font size on single posts (uses `h2` within loops)
- [x] Empty site: Header missing top/bottom padding


## Build Notes

Before zipping MD, the following files should be emptied (not deleted) as they will be generated on a per-site basis:

1. `/style.css` (leave DocBlocks, OK to delete entire of Table of Contents)
2. `/scripts.js`
3. `/css/block-editor.css`
