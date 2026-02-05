<style type="text/css">

/*------------------------------*\
	$BUTTONS
\*------------------------------*/

.link, .link-wrap,
button, .button, .button a, input[type="submit"] {
	display: inline-flex;
	flex-direction: column;
	gap: <?php echo $third; ?>px <?php echo $half; ?>px;
	line-height: 1;
	text-align: left;
}

.has-icon, .button.has-icon {
	align-items: center;
	flex-direction: row;
}

/* LINKS */

.link, .underline { text-decoration: underline; }

.link:hover, .no-underline { text-decoration: none; }

.link-icon, .trigger-icon, .input-icon {
	font-size: <?php echo round( $typography['header']['font_size']['desktop'] * 1.3 ); ?>px;
	font-style: normal;
}

.link-subtitle {
	font-size: <?php echo $typography['body']['font_size']['mobile'] - 1; ?>px;
	font-weight: normal;
}

/* BUTTONS */

button, .button, a.button, .button a, input[type="submit"], .format .button {
	background-color: <?php echo $colors['site']['button']; ?>;
	border: 0;
	border-radius: 5px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	color: <?php echo $colors['site']['button-text']; ?>;
	cursor: pointer;
	font-size: inherit;
	font-family: inherit;
	font-style: normal;
	padding: <?php echo $half; ?>px <?php echo $half + $small; ?>px;
	text-decoration: none;
	transition: 0.3s;
	-webkit-appearance: none;
}

button:hover, .button:hover, a.button:hover, .button a:hover, input[type="submit"]:hover { transform: translateY(1px); }

/* COLORS */

.button.white {
	background-color: #fff;
	color: inherit;
}

.button.button-outline.white, .button.button-outline.white:hover {
	border-color: #fff;
	color: #fff;
}

.button.button-disabled {
	background-color: #999;
	cursor: not-allowed;
}

/* STYLES */

.button.button-sec, a.button.button-sec, .button.button-sec a {
	background-color: <?php echo $colors['site']['button-sec']; ?>;
	color: <?php echo $colors['site']['button-sec-text']; ?>;
}

.button.button-small {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	padding: <?php echo $third; ?>px <?php echo $half; ?>px;
}

.button-small .link-icon { font-size: <?php echo $typography['body']['font_size']['desktop'] + 2; ?>px; }

.button.button-large {
	font-size: 1.4em;
	padding: <?php echo $half + $small; ?>px <?php echo $single; ?>px;
}

.button.button-outline, .button.button-outline:hover {
	background-color: transparent;
	border: 3px solid <?php echo $colors['site']['button']; ?>;
	border-radius: 10px;
	color: <?php echo $colors['site']['button']; ?>;
}

.button-arrow { flex-direction: row; }

.button.button-arrow:after {
	content: '\e80f';
	font-family: 'md-icon';
	transition: 0.3s;
}

.button.button-arrow:hover:after { transform: translateX(4px); }