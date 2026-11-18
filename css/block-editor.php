<style type="text/css">

/*------------------------------*\
	$BLOCK_EDITOR
\*------------------------------*/

/* TYPOGRAPHY */

.editor-styles-wrapper {
	background-color: var(--md-content-main-background);
	color: var(--md-text);
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-size: var(--md-font-size);
	line-height: var(--md-line-height);
	<?php echo $design->letters( $typography['body'] ); ?>
}

.is-box-style .editor-styles-wrapper {
	--md-text: var(--md-content-box-text);
	--md-text-muted: var(--md-content-box-text-muted);
	--md-links: var(--md-content-box-links);
	--md-links-muted: var(--md-content-box-text-muted);
	--md-headlines: var(--md-content-box-text);
	--md-headline-links: var(--md-content-box-text);
	--md-border: var(--md-content-box-border);
	background-color: var(--md-content-box-background);
	color: var(--md-text);
}

.edit-post-visual-editor__post-title-wrapper { margin-block-end: <?php echo $mid; ?>px; }

/* SPACING */

<?php if ( $advanced_blocks ) : ?>
:root :where(.is-layout-flow) > *,
<?php endif; ?>
.md-builder .editor-styles-wrapper .wp-block-post-content .wp-block-heading:not(:first-child) { margin-block-start: 0; }

.editor-styles-wrapper .wp-block-post-content > :is(ul, ol, p, hr, table, blockquote, pre, .wp-block-image, .wp-block-table):not([class*="mb-"]) { margin-block-end: <?php echo $single; ?>px; }

.editor-styles-wrapper .block-editor-block-list__block.absolute { position: absolute; }

<?php if ( $advanced_blocks ) : ?>
.editor-styles-wrapper :is(.wp-block-post-content, .wp-block-post-content :not(.fl, .stack, .columns, [class*="columns-"])) > .wp-block-heading:not([class*="mb-"]):not(:last-child) { margin-block-end: <?php echo $half; ?>px; }

html:not(.md-builder) .editor-styles-wrapper :is(.wp-block-post-content, .wp-block-post-content :not(.fl, .stack, .columns, [class*="columns-"])) > .wp-block-heading:not(:first-child) { margin-block-start: <?php echo $mid; ?>px; }
<?php else : ?>
.editor-styles-wrapper .is-root-container > * { margin-block-start: 0; }

.editor-styles-wrapper .wp-block-post-content > .wp-block-heading:not([class*="mb-"]):not(:last-child) { margin-block-end: <?php echo $half; ?>px; }

html:not(.md-builder) .editor-styles-wrapper .wp-block-post-content > .wp-block-heading:not(:first-child) { margin-block-start: <?php echo $mid; ?>px; }
<?php endif; ?>

:where(.editor-styles-wrapper .wp-block-post-content) :is(ul, ol):where(.wp-block-list:not(.list, .list-check)) {
	list-style-position: outside;
	padding-inline-start: var(--md-single);
}

.editor-styles-wrapper .wp-block-post-content ul ul.wp-block-list:not(.list, .list-check) { padding-inline-start: var(--md-half); }

.editor-styles-wrapper .list-check li > .rich-text { display: inline; }

.editor-styles-wrapper .is-layout-flow > .alignleft { margin-inline-end: <?php echo $single; ?>px; }

.editor-styles-wrapper .is-layout-flow > .alignright { margin-inline: <?php echo $single; ?>px 0; }

@media (min-width: 900px) {
	.expanded .editor-styles-wrapper .wp-block-group .alignright {
		margin-inline-end: -50vw;
		right: 50%;
	}
	.expanded .editor-styles-wrapper .wp-block-group .alignleft {
		left: 50%;
		margin-inline-start: -50vw;
	}
}

/* BLOCK/PULL QUOTES */

<?php if ( $advanced_blocks ) : ?>
.editor-styles-wrapper .wp-block-pullquote {
	font-size: inherit;
	padding: 0;
}
<?php else : ?>
.editor-styles-wrapper .is-root-container > .wp-block-pullquote { margin-block-end: 1em; }
<?php endif; ?>

.editor-styles-wrapper .wp-block-quote p,
.editor-styles-wrapper .wp-block-pullquote p { margin-block: 0 <?php echo $half; ?>px; }

.editor-styles-wrapper .wp-block-quote p:last-child,
.editor-styles-wrapper .wp-block-pullquote p:last-child { margin-block-end: 0; }

/* MD BLOCKS */

.editor-styles-wrapper .banner-wrap p { margin-block: 0; }

.editor-styles-wrapper .feature-title { margin-block: 0 <?php echo $small; ?>px; }

.editor-styles-wrapper .feature-description { margin-block-start: 0; }

/* WIDTHS */

.md-builder .editor-styles-wrapper .is-root-container > :not(.alignwide, .alignfull) { max-width: 100%; }

.expanded .editor-styles-wrapper .edit-post-visual-editor__post-title-wrapper { max-width: 100%; }

:is(.expanded, .md-builder) .editor-styles-wrapper .wp-block-post-title {
	max-width: var(--md-width-site);
	text-align: center;
}

.editor-styles-wrapper .is-root-container .inner {
	max-width: <?php echo $site_width; ?>px;
	width: auto;
}

.editor-styles-wrapper .is-root-container .content-width { max-width: <?php echo $content_width; ?>px; }

.editor-styles-wrapper .is-root-container .post-width { max-width: <?php echo $post_width; ?>px; }

.editor-styles-wrapper .is-root-container .sidebar-width { max-width: <?php echo $sidebar_width; ?>px; }

.editor-styles-wrapper .is-root-container .wide-width { max-width: var(--md-width-alignwide); }

.md-builder .editor-styles-wrapper .alignwide { max-width: var(--md-width-alignwide); }

.editor-styles-wrapper .is-root-container { padding-inline: clamp(0px, calc((100vw - var(--md-width-site)) / -2), var(--md-half)); }

.editor-styles-wrapper .is-root-container > .alignfull { margin-inline: calc(-1 * clamp(0px, calc((100vw - var(--md-width-site)) / -2), var(--md-half))); }

@media (max-width: <?php echo $post_width; ?>px) {
	.compact .editor-styles-wrapper .is-root-container > .alignwide {
		inset-inline-start: calc(-1 * clamp(0px, calc((100vw - var(--md-width-site)) / -2), var(--md-half)));
		max-width: none;
		position: relative;
		width: 100vw;
	}
}

@media (width <= <?php echo $content_width + ( $quad * 2 ); ?>px) {
	.expanded .editor-styles-wrapper .is-root-container > .alignwide {
		inset-inline-start: calc(-1 * clamp(0px, calc((100vw - var(--md-width-site)) / -2), var(--md-half)));
		max-width: none;
		position: relative;
		width: 100vw;
	}
}

@media (min-width: 782px) {
	.editor-styles-wrapper .wp-block-details:not(.is-style-plain) { margin-inline: calc(-1 * var(--md-half)); }

	.editor-styles-wrapper .is-root-container > .wp-block-details:not(.is-style-plain) { max-width: calc(var(--wp--style--global--content-size) + 2 * var(--md-half)); }
}
