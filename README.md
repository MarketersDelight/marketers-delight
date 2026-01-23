# Marketers Delight 6.0 Alpha

Years in the making, this is the first commit of MD6.0 to GitHub..

## Known Issues

- [ ] Block Editor and Classic Editor styles missing / incomplete
- [x] Full Header Cover absolute position CSS styling missing
- [ ] Full Header Cover mobile toggles need backgrounds to prevent overlap
- [ ] Header Cover title spacing slightly off with extra `gap` when Title only
- [ ] Breadcrumbs out of place on Full Header Cover
- [ ] Loop `h1` different font size than single `h1`
- [ ] Empty site: Header missing top/bottom padding
- [ ] Layout options: Sidebar meta fields not toggling in all cases
- [ ] 404 page frontend not loading content
- [ ] Blocks / Widgets extracted from theme, need to build as Drop-in

## Build Note

Before zipping MD, the following files should be emptied (not deleted) as they will be generated on a per-site basis:

1. `/style.css` (leave DocBlocks, OK to delete entire of Table of Contents)
2. `/scripts.js`
3. `/css/block-editor.css`
