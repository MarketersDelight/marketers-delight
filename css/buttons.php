<style type="text/css">

/*------------------------------*\
	$BUTTONS
\*------------------------------*/

button, input[type="submit"],
.link, .tag, .button, .wp-element-button {
	align-items: center;
	display: inline-flex;
	gap: <?php echo $third; ?>px;
	line-height: 1;
}

.cta, .wp-block-buttons {
	align-items: center;
	display: flex;
	gap: <?php echo $half; ?>px <?php echo $half + $third; ?>px;
	justify-content: center;
}

/* LINKS */

.link:hover { text-decoration: none; }

.link-wrap {
	display: inline-flex;
	flex-direction: column;
	gap: <?php echo $third; ?>px;
	text-align: initial;
}

.link-icon, .trigger-icon, .input-icon {
	font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.3 ); ?>px;
	font-style: normal;
}

.link-subtitle {
	font-size: <?php echo $typography['body']['font_size']['mobile'] - 2; ?>px;
	font-weight: normal;
}

/* TAGS */

.tag {
	background-color: <?php echo $colors['palette']['tertiary']; ?>;
	border-radius: 50px;
	color: <?php echo $colors['site']['text-main']; ?>;
	flex-shrink: 0;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	padding: <?php echo $third; ?>px <?php echo $half; ?>px;
	text-decoration: none;
	transition: 0.3s;
}

.tag:hover {
	background-color: <?php echo $colors['content']['border_color']; ?>;
	color: <?php echo $colors['site']['links']; ?>;
}

/* BUTTONS */

button, input[type="submit"],
.button, .wp-element-button {
	appearance: none;
    background-color: <?php echo $colors['site']['button']; ?>;
	border: 0;
	border-radius: 6px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	color: <?php echo $colors['site']['button-text']; ?>;
	cursor: pointer;
	font-size: inherit;
	font-family: inherit;
	font-style: normal;
	justify-content: center;
	padding: <?php echo $half; ?>px <?php echo $half + $third; ?>px;
	text-decoration: none;
	transition: 0.3s;
}

a.button { text-decoration: none; }

button:hover, input[type="submit"]:hover,
.button:hover, .wp-element-button:hover { transform: translateY(1px); }

/* COLORS */

.button.white {
	background-color: #fff;
	color: inherit;
}

.button.button-sec {
	background-color: <?php echo $colors['site']['button-secondary']; ?>;
	color: <?php echo $colors['site']['button-secondary-text']; ?>;
}

.button.button-outline,
.is-style-outline .wp-element-button {
	background-color: transparent;
	border: 3px solid <?php echo $colors['site']['button']; ?>;
	color: <?php echo $colors['site']['button']; ?>;
}

.button.button-outline.white {
	border-color: #fff;
	color: #fff;
}

.button.button-outline.has-links-color{
	border-color: <?php echo $colors['site']['links']; ?>;
	color: <?php echo $colors['site']['links']; ?>;
}

/* SIZES */

.button.button-small {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	gap: <?php echo $third; ?>px;
	padding: <?php echo $third; ?>px <?php echo $half; ?>px;
}

.button-small .link-icon { font-size: <?php echo $typography['body']['font_size']['desktop'] + 2; ?>px; }

.button.button-large {
	font-size: 1.4em;
	padding: <?php echo $half + $small; ?>px <?php echo $single; ?>px;
}

/* STYLES */

.button.button-cancel { background-color: #ae2525; }

.button.button-disabled {
	background-color: #999;
	cursor: not-allowed;
}

.button.pill { border-radius: 50px; }

.button-arrow { flex-direction: row; }

.button.button-arrow:after {
	content: '\e80f';
	font-family: 'md-icon';
	transition: 0.3s;
}

.button.button-arrow:hover:after { transform: translateX(4px); }

.button-arrow.down:after { content: '\e80e'; }

.button-arrow.down:hover:after { transform: none; }