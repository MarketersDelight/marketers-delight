<style type="text/css">

/*------------------------------*\
	$BUTTONS
\*------------------------------*/

button {
	appearance: none;
	background-color: transparent;
	border: 0;
	font: inherit;
}

input[type="submit"], .link, .tag, .button, .wp-element-button {
	align-items: center;
	display: inline-flex;
	gap: var(--md-third);
	line-height: 1;
}

.button { white-space: nowrap; }

.cta-links, :where(.wp-block-buttons) {
	align-items: center;
	display: flex;
	flex-wrap: wrap;
	gap: var(--md-half) calc(var(--md-half) + var(--md-third));
}

.cta-box {
	display: flex;
	flex-direction: column;
	gap: var(--md-half);
}

.cta-email { width: 100%; }

:where(.wp-block-buttons) { justify-content: center; }

.wp-block-buttons .wp-block-button__link {
	justify-content: center;
	width: 100%;
}

.wp-block-button:is(.wp-block-button__width-100, .width-full) {
	flex-basis: 100%;
	width: 100%;
}

/* LINKS */

.link:hover { text-decoration: none; }

.link-wrap {
	display: inline-flex;
	flex-direction: column;
	gap: var(--md-third);
	text-align: initial;
}

.link-icon, .trigger-icon, .input-icon {
	font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.3 ); ?>px;
	font-style: normal;
}

.link-subtitle {
	font-size: var(--md-font-size-tiny);
	font-weight: normal;
}

/* TAGS */

.tag {
	background-color: var(--md-tag-background);
	border-radius: 50px;
	box-shadow: inset 0 0 0 1px var(--md-border);
	color: var(--md-text);
	flex-shrink: 0;
	padding: var(--md-third) var(--md-half);
	text-decoration: none;
	transition: var(--md-transition);
}

.tag:hover {
	background-color: var(--md-border);
	color: var(--md-links);
}

.tag.active {
	background-color: transparent;
	box-shadow: inset 0 0 0 1px var(--md-links);
	color: var(--md-links);
}

.tag-outline, .tag-outline:hover { background-color: var(--md-content-box-background); }

/* BUTTONS */

input[type="submit"], .button, .wp-element-button {
	appearance: none;
	background-color: var(--md-button-bg);
	border: var(--md-button-border);
	border-radius: var(--md-button-radius);
	box-shadow: var(--md-button-shadow);
	color: var(--md-button-text);
	cursor: pointer;
	font-size: inherit;
	font-family: inherit;
	font-style: normal;
	<?php if ( $buttons['font_weight'] ) : ?>
	font-weight: <?php echo $buttons['font_weight']; ?>;
	<?php endif; ?>
	justify-content: center;
	padding: var(--md-half) calc(var(--md-half) + var(--md-third));
	text-decoration: none;
	<?php if ( $buttons['text_transform'] ) : ?>
	text-transform: <?php echo $buttons['text_transform']; ?>;
	<?php endif; ?>
	transition: var(--md-transition);
}

a.button { text-decoration: none; }

input[type="submit"]:hover, .button:hover, .wp-element-button:hover { transform: translateY(1px); }

/* VARIANTS */

<?php foreach ( md_button_types() as $slug => $type )
	echo join( ', ', $type['selectors'] ) . ' {' . $design->button_vars( $slug ) . " }\n\n"; ?>
.button.button-text {
	padding-inline: 0;
	text-decoration: underline;
}

/* COLORS */

.button.white {
	background-color: var(--md-color-white);
	color: var(--md-text);
}

.button.button-outline.white {
	background-color: transparent;
	border-color: var(--md-color-white);
	color: var(--md-color-white);
}

/* SIZES */

.button.button-small {
	font-size: var(--md-font-size-small);
	gap: var(--md-third);
	padding: var(--md-third) var(--md-half);
}

.button-small .link-icon { font-size: calc(var(--md-font-size) + 2px); }

.button.button-large {
	font-size: 1.4em;
	padding: calc(var(--md-half) + var(--md-small)) var(--md-single);
}

/* STYLES */

.button.button-cancel { background-color: var(--md-color-danger); }

.button.button-disabled {
	background-color: color-mix(in srgb, var(--md-text) 45%, transparent);
	cursor: not-allowed;
}

.button.pill { border-radius: 50px; }

.button-arrow { flex-direction: row; }

.button.button-arrow:after {
	content: '\e80f';
	font-family: 'md-icon';
	transition: var(--md-transition);
}

.button.button-arrow:hover:after { transform: translateX(4px); }

.button-arrow.down:after { content: '\e80e'; }

.button-arrow.down:hover:after { transform: none; }

/* QUERIES */

@media (max-width: 900px) {
	.cta-links .link {
		flex: 1;
		justify-content: center;
	}
}
