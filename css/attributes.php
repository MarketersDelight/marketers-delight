<style type="text/css">

    <?php
    // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
    esc_html_e(
    '/*
        Theme Name: Marketers Delight
        Version: 5.3.0
        Author: Alex Mangini
        Description: Marketers Delight is your smart website design system for the future. Built on a foundation of typography, MD\'s features and layout all work together to help you create stunning content on the web. Use the Site Designer to design your site, capture subscribers with MD\'s email and popups tools, and enable power features like the Stream and Bookshelf to deliver unique kinds of content to your audience. All of that and more in Marketers Delight.
        Theme URI: https://marketersdelight.com/
        Author URI: https://alexmangini.com/
        Text Domain: md
        Requires at least: 5.3
        Tested up to: 7.4
        Requires PHP: 5.6
        License: GNU General Public License v2 or later
        License URI: http://www.gnu.org/licenses/gpl-2.0.html
        Tags: one-column, accessibility-ready, custom-colors, custom-menu, custom-logo, editor-style, featured-images, footer-widgets, block-patterns, rtl-language-support, sticky-post, threaded-comments, translation-ready
    */', 'md') ;
    ?>

    /*------------------------------*\
        $ATTRIBUTES
    \*------------------------------*/

    *, *:before, *:after {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    @font-face {
        font-family: md-icon;
        src: url('<?php echo md_font_icons_url(); ?>') format('woff');
        font-style: normal;
        font-weight: 400;
    }

    body {
        background-color: <?php echo $colors['site']['bg_color']; ?>;
        color: <?php echo $colors['site']['text']; ?>;
        font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
        font-family: <?php echo $typography['body']['font_family']; ?>;
        font-weight: <?php echo $font_weight; ?>;
        line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
        position: relative;
    }

    b, strong, .bold {
        font-weight: <?php echo $bold; ?>;
    }

    [class*="md-icon"] {
        display: inline-block;
    }

    [class*="md-icon"]:before {
        display: inline-block;
        font-family: md-icon;
        font-style: normal;
        font-variant: normal;
        font-weight: 400;
        line-height: 1;
        speak: none;
        text-align: center;
        text-decoration: inherit;
        text-transform: none;
    }

    .md-icon.icon-data:before {
        content: attr(data-md-icon);
    }

    .small {
        font-size: 0.85em;
        line-height: 1.5em;
    }

    <?php
        foreach ( md_editor_colors() as $color_group => $color_fields ) {
            $color_slug = $color_fields['slug'];
            $color_val = $color_fields['color'];
            echo
                ".has-$color_slug-background-color { background-color: $color_val; }\n".
                ( $color_slug !== 'text' ? ".has-$color_slug-color, .format .has-$color_slug-color { color: $color_val; }\n" : '' );
        }
    ?>

    .has-text-color.has-white-color {
        color: #fff;
    }

    #cancel-comment-reply-link:before, .menu-icon a, .list-check li:before {
        display: inline-block;
        font-family: md-icon;
        font-style: normal;
        font-weight: normal;
        line-height: 1;
    }

    main {
        display: block;
    }

    ul {
        list-style: square;
    }

    p {
        position: relative;
    }

    a {
        color: <?php echo $colors['site']['links']; ?>;
        text-decoration: none;
    }

    img, a img, .size-auto, .size-full, .size-large, .size-medium, .size-thumbnail {
        height: auto;
        max-width: 100%;
        vertical-align: top;
    }

    iframe, video, object {
        max-width: 100%;
    }

    sup {
        line-height: 1;
    }

    hr {
        border: 0;
        height: 0;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }

    pre, code {
        background-color: #ddd;
        color: #3e3e3e;
        font-family: Consolas, Monaco, Menlo, Courier, Verdana, sans-serif;
        font-size: 0.9em;
    }

    code a, .format code a {
        border-bottom: 0;
        color: #3e3e3e;
    }

    pre {
        overflow: auto;
        padding: 26px;
    }

    code {
        border-radius: 3px;
        padding: 2px 5px;
    }

    abbr, acronym {
        border-bottom: 1px dotted #777;
        cursor: help;
        text-decoration: none;
    }

    a abbr, a acronym {
        border-bottom: none;
    }