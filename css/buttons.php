<style type="text/css">

/*------------------------------*\
	$BUTTONS
\*------------------------------*/

button, .button, a.button, .button a, input[type="submit"], .format .button,
.header .button, .header .button:hover, .header .button a, .header .button a:hover {
	background-color: <?php echo $colors['site']['button']; ?>;
	border: 0;
	border-radius: 5px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	color: <?php echo $colors['site']['button-text']; ?>;
	cursor: pointer;
	display: inline-block;
	flex-shrink: 0;
	line-height: 1;
	padding: <?php echo $half; ?>px;
	position: relative;
	text-align: center;
	text-decoration: none;
	transition: 0.3s;
	-webkit-appearance: none;
}

button:hover, .button:hover, a.button:hover, .button a:hover, input[type="submit"]:hover { transform: translateY(1px); }

/* STYLES */

.button.button-sec, a.button.button-sec, .button.button-sec a {
	background-color: <?php echo $colors['site']['button-sec']; ?>;
	color: <?php echo $colors['site']['button-sec-text']; ?>;
}

.button.button-small {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	padding: <?php echo $third; ?>px <?php echo $half; ?>px;
}

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

.button.button-arrow:after {
	content: '\e80f';
	display: inline-block;
	font-family: 'md-icon';
	margin-left: <?php echo $half; ?>px;
	transition: 0.3s;
}

.button.button-arrow:hover:after { transform: translateX(4px); }

/* ELEMENTS */

.link-icon + .link-text { margin-left: <?php echo $third; ?>px; }

.link-subtext {
	display: block;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.link-subtext:not(:empty) { margin-top: <?php echo $small; ?>px; }

/* BADGE */

.badge {
    background-color: #f58f2a;
    border-radius: 5px;
    color: #fff;
	font-size: <?php echo $typography['body']['font_size']['tablet']; ?>px;
	font-weight: normal;
	padding: 4px 7px;
    position: relative;
    text-transform: uppercase;
}
