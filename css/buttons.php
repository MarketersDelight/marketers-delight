<style type="text/css">

/*------------------------------*\
	$BUTTONS
\*------------------------------*/

.link, button, .button, input[type="submit"] {
	display: inline-flex;
	gap: <?php echo $third; ?>px <?php echo $half; ?>px;
	line-height: 1;
}

/* LINKS */

.link, .underline { text-decoration: underline; }

.link:hover, .no-underline { text-decoration: none; }

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

/* BUTTONS */

button, .button, input[type="submit"] {
	align-items: center;
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
	transition: 0.3s;
}

a.button { text-decoration: none; }

button:hover, .button:hover, input[type="submit"]:hover { transform: translateY(1px); }

/* COLORS */

.button.white {
	background-color: #fff;
	color: inherit;
}

.button.button-sec {
	background-color: <?php echo $colors['site']['button-sec']; ?>;
	color: <?php echo $colors['site']['button-sec-text']; ?>;
}

.button.button-outline {
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

.button.width-full { justify-content: center; }

.button-arrow { flex-direction: row; }

.button.button-arrow:after {
	content: '\e80f';
	font-family: 'md-icon';
	transition: 0.3s;
}

.button.button-arrow:hover:after { transform: translateX(4px); }

.button-arrow.down:after { content: '\e80e'; }
.button-arrow.down:hover:after { transform: none; }

.button-arrow.width-full:after { margin-left: auto; }