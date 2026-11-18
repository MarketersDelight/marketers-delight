<style type="text/css">

/* BLOCK LAYOUT */

.is-layout-flex {
	align-items: center;
	display: flex;
	flex-wrap: wrap;
}

.is-layout-grid { display: grid; }

:where(.is-layout-flex, .is-layout-grid) { gap: var(--md-single); }

:is(.is-layout-flex, .is-layout-grid) > * { margin: 0; }

/* BLOCK SUPPORTS */

html :where(.has-border-color, [style*="border-width"]) { border-style: solid; }
html :where([style*="border-top-color"], [style*="border-top-width"]) { border-top-style: solid; }
html :where([style*="border-right-color"], [style*="border-right-width"]) { border-right-style: solid; }
html :where([style*="border-bottom-color"], [style*="border-bottom-width"]) { border-bottom-style: solid; }
html :where([style*="border-left-color"], [style*="border-left-width"]) { border-left-style: solid; }

p.has-background, :where(.wp-block-group.has-background) { padding: 1.25em 2.375em; }

.has-drop-cap:not(:focus)::first-letter {
	float: left;
	font-size: 8.4em;
	font-style: normal;
	font-weight: 100;
	line-height: 0.68;
	margin: 0.05em 0.1em 0 0;
	text-transform: uppercase;
}

/* BLOCKS */

.wp-block-image.is-style-rounded img { border-radius: 9999px; }

.wp-block-columns.are-vertically-aligned-top { align-items: flex-start; }
.wp-block-columns.are-vertically-aligned-center { align-items: center; }
.wp-block-columns.are-vertically-aligned-bottom { align-items: flex-end; }

.wp-block-column.is-vertically-aligned-top { align-self: flex-start; }
.wp-block-column.is-vertically-aligned-center { align-self: center; }
.wp-block-column.is-vertically-aligned-bottom { align-self: flex-end; }
.wp-block-column.is-vertically-aligned-stretch { align-self: stretch; }

.wp-block-button:is(.wp-block-button__width-25, .wp-block-button__width-50, .wp-block-button__width-75) { flex-basis: auto; }
.wp-block-button.wp-block-button__width-25 { width: calc(25% - (var(--md-half) + var(--md-third)) * 0.75); }
.wp-block-button.wp-block-button__width-50 { width: calc(50% - (var(--md-half) + var(--md-third)) * 0.5); }
.wp-block-button.wp-block-button__width-75 { width: calc(75% - (var(--md-half) + var(--md-third)) * 0.25); }

.wp-block-separator:is(.has-background, .has-text-color):not(.is-style-dots) {
	border: 0;
	height: 1px;
}

.wp-block-separator.has-text-color:not(.has-background, .is-style-dots) { background-color: currentColor; }

.wp-block-separator.is-style-dots {
	background: none;
	border: 0;
	height: auto;
	line-height: 1;
	text-align: center;
}

.wp-block-separator.is-style-dots::before {
	content: '\00b7\00b7\00b7';
	font-family: serif;
	font-size: 1.5em;
	letter-spacing: 2em;
	padding-inline-start: 2em;
}

.wp-block-search__inside-wrapper {
	display: flex;
	flex: auto;
	flex-wrap: nowrap;
	max-width: 100%;
}

/* EMBED */

.wp-block-embed { overflow-wrap: break-word; }

.wp-block-embed:is(.alignleft, .alignright) {
	max-width: 360px;
	width: 100%;
}

.wp-block-embed:is(.alignleft, .alignright) .wp-block-embed__wrapper { min-width: 280px; }

.is-layout-flex > .wp-block-embed {
	flex: 1 1 0%;
	min-width: 0;
}

.wp-block-cover .wp-block-embed {
	min-height: 240px;
	min-width: 320px;
}

.wp-block-embed iframe { max-width: 100%; }

.wp-embed-responsive .wp-has-aspect-ratio iframe {
	aspect-ratio: 2 / 1;
	height: auto;
	width: 100%;
}

<?php foreach ( array( '21-9', '16-9', '4-3', '1-1', '9-16', '1-2' ) as $ratio ) : ?>
.wp-embed-responsive .wp-embed-aspect-<?php echo $ratio; ?> iframe { aspect-ratio: <?php echo str_replace( '-', ' / ', $ratio ); ?>; }
<?php endforeach; ?>

/* COVER */

.wp-block-cover {
	--md-headline-links: currentColor;
	--md-headlines: currentColor;
	align-items: center;
	display: flex;
	justify-content: center;
	min-height: 430px;
	overflow: clip;
	padding: 1em;
	position: relative;
}

:where(.wp-block-cover:not(.has-text-color)) { color: var(--md-site-text-contrast); }

:where(.wp-block-cover.is-light:not(.has-text-color)) { color: var(--md-site-text); }

.wp-block-cover:is(.alignleft, .alignright) {
	max-width: 420px;
	width: 100%;
}

.wp-block-cover :is(.wp-block-cover__image-background, .wp-block-cover__video-background, .wp-block-cover__background, .wp-block-cover__embed-background, .wp-block-cover__embed-background .wp-block-embed__wrapper) {
	height: 100%;
	inset: 0;
	margin: 0;
	position: absolute;
	width: 100%;
}

.wp-block-cover :is(.wp-block-cover__image-background, .wp-block-cover__video-background) {
	object-fit: cover;
	z-index: 0;
}

.wp-block-cover :is(.wp-block-cover__background, .wp-block-cover__inner-container) { z-index: 1; }

.wp-block-cover__embed-background { pointer-events: none; }

.wp-block-cover__embed-background iframe {
	height: 100vh;
	left: 50%;
	min-height: 100%;
	min-width: 100%;
	pointer-events: none;
	position: absolute;
	top: 50%;
	translate: -50% -50%;
	width: 100vw;
}

.wp-block-cover__background { opacity: 0.5; }

.wp-block-cover__background:not([class*="-background-color"]) { background-color: var(--md-shade-color); }

.wp-block-cover__background.has-background-gradient { background-color: transparent; }

<?php for ( $dim = 0; $dim <= 100; $dim += 10 ) : ?>
.wp-block-cover__background.has-background-dim-<?php echo $dim; ?> { opacity: <?php echo $dim / 100; ?>; }
<?php endfor; ?>

.wp-block-cover__inner-container {
	position: relative;
	width: 100%;
}

.wp-block-cover[class*="is-position-top"] { align-items: flex-start; }
.wp-block-cover[class*="is-position-bottom"] { align-items: flex-end; }
.wp-block-cover[class*="is-position-"][class*="-left"] { justify-content: flex-start; }
.wp-block-cover[class*="is-position-"][class*="-right"] { justify-content: flex-end; }

.wp-block-cover.has-custom-content-position .wp-block-cover__inner-container { margin: 0; }

.wp-block-cover[class*="is-position-"]:is([class*="-left"], [class*="-right"]) .wp-block-cover__inner-container { width: auto; }

.wp-block-cover__image-background.has-parallax {
	background-attachment: fixed;
	background-position: center;
	background-repeat: no-repeat;
	background-size: cover;
}

.wp-block-cover__image-background.is-repeated {
	background-repeat: repeat;
	background-size: auto;
}

@supports (-webkit-touch-callout: inherit) {
	.wp-block-cover__image-background.has-parallax { background-attachment: scroll; }
}

@media (prefers-reduced-motion: reduce) {
	.wp-block-cover__image-background.has-parallax { background-attachment: scroll; }
}
