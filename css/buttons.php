<style type="text/css">

/*------------------------------*\
	$BUTTONS
\*------------------------------*/

button, .button, a.button, .button a, input[type="submit"], .format .button {
	background-color: <?php echo $colors['site']['button']; ?>;
	border: 0;
	border-radius: 5px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	color: <?php echo $colors['site']['button-text']; ?>;
	cursor: pointer;
	font-size: inherit;
	font-family: inherit;
	display: inline-block;
	font-style: normal;
	line-height: 1;
	padding: <?php echo $half; ?>px;
	position: relative;
	text-align: center;
	text-decoration: none;
	transition: 0.3s;
	-webkit-appearance: none;
}

button:hover, .button:hover, a.button:hover, .button a:hover, input[type="submit"]:hover, .format .button:hover { transform: translateY(1px); }

.button-subtext { font-weight: <?php echo $font_weight; ?>; }

.button-subtext:empty { display: none; }

/* COLORS */

.button.button-sec, a.button.button-sec,
.button.button-sec a {
	background-color: <?php echo $colors['site']['button-sec']; ?>;
	color: <?php echo $colors['site']['button-sec-text']; ?>;
}

button.green a, button.green a, .button.green, .button.green a { background-color: #22A340; }
button.green:hover, button.green a:hover, .button.green:hover, .button.green a:hover { background-color: #128D2E; }
.button.button-outline.green, .button.button-outline.green a, .button.button-outline.green:hover, .button.button-outline.green a:hover { background-color: transparent; border-color: #22A340; color: #22A340; }

button.orange a, button.orange a, .button.orange, .button.orange a { background-color: #f58f2a; }
button.orange:hover, button.orange a:hover, .button.orange:hover, .button.orange a:hover { background-color: #EB8928; }
.button.button-outline.orange, .button.button-outline.orange a, .button.button-outline.orange:hover, .button.button-outline.orange a:hover { background-color: transparent; border-color: #f58f2a; color: #f58f2a; }

button.yellow a, button.yellow a, .button.yellow, .button.yellow a {
	background-color: #fbcb3a;
	text-shadow: 0 2px 2px rgba(0, 0, 0, 0.2);
}
button.yellow:hover, button.yellow a:hover, .button.yellow:hover, .button.yellow a:hover { background-color: #ecb81c; }
.button.button-outline.yellow, .button.button-outline.yellow a, .button.button-outline.yellow:hover, .button.button-outline.yellow a:hover { background-color: transparent; border-color: #fbcb3a; color: #fbcb3a; }

button.red a, button.red a, .button.red, .button.red a { background-color: #ae2525; }
button.red:hover, button.red a:hover, .button.red:hover, .button.red a:hover { background-color: #9C2121; }
.button.button-outline.red, .button.button-outline.red a, .button.button-outline.red:hover, .button.button-outline.red a:hover { background-color: transparent; border-color: #ae2525; color: #ae2525; }

button.blue a, button.blue a, .button.blue, .button.blue a { background-color: #299efd; }
button.blue:hover, button.blue a:hover, .button.blue:hover, .button.blue a:hover { background-color: #2389dc; }
.button.button-outline.blue, .button.button-outline.blue a, .button.button-outline.blue:hover, .button.button-outline.blue a:hover { background-color: transparent; border-color: #299efd; color: #299efd; }

button.purple a, button.purple a, .button.purple, .button.purple a { background-color: #9850f7; }
button.purple:hover, button.purple a:hover, .button.purple:hover, .button.purple a:hover { background-color: #803cd8; }
.button.button-outline.purple, .button.button-outline.purple a, .button.button-outline.purple:hover, .button.button-outline.purple a:hover { background-color: transparent; border-color: #9850f7; color: #9850f7; }

button.gray a, button.gray a, .button.gray, .button.gray a { background-color: #999; }
button.gray:hover, button.gray a:hover, .button.gray:hover, .button.gray a:hover { background-color: #666; }
.button.button-outline.gray, .button.button-outline.gray a, .button.button-outline.gray:hover, .button.button-outline.gray a:hover { background-color: transparent; border-color: #999; color: #999; }

button.white a, button.white a, .button.white, .button.white a { background-color: #fff; color: #1e1e1e; }
button.white:hover, button.white a:hover, .button.white:hover, .button.white a:hover { background-color: #eee; }
.button.button-outline.white, .button.button-outline.white a, .button.button-outline.white:hover, .button.button-outline.white a:hover { background-color: transparent; border-color: #fff; color: #fff; }

button.dark a, button.dark a, .button.dark, .button.dark a { background-color: #2e2e2e; }
button.dark:hover, button.dark a:hover, .button.dark:hover, .button.dark a:hover { background-color: #1e1e1e; }
.button.button-outline.dark, .button.button-outline.dark a, .button.button-outline.dark:hover, .button.button-outline.dark a:hover { background-color: transparent; border-color: #2e2e2e; color: #2e2e2e; }

/* SIZES */

.button.button-small {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	padding: <?php echo $third; ?>px <?php echo $half; ?>px;
}

.button.button-large {
	font-size: 1.4em;
	padding: <?php echo $single; ?>px <?php echo $mid; ?>px;
}

.button-text {
	font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
}

/* OUTLINE */

.button.button-outline, .button.button-outline:hover {
	background-color: transparent;
	border: 2px solid <?php echo $colors['site']['button']; ?>;
	color: <?php echo $colors['site']['button']; ?>;
}

.menu .button.button-outline a { background-color: transparent; }

/* ARROW */

.button.button-arrow:after,
.button.button-arrow.button-text:after,
.menu .button-arrow a:after,
.woocommerce ul.products li.product .button:after {
	content: '\e80f';
	display: inline-block;
	font-family: 'md-icon';
	margin-left: 13px;
	-o-transition: 0.3s;
	-ms-transition: 0.3s;
	-moz-transition: 0.3s;
	-webkit-transition: 0.3s;
	transition: 0.3s;
}

.button.button-arrow:hover:after,
.button.button-arrow.button-text:after,
.menu .button-arrow a:hover:after,
.woocommerce ul.products li.product .button:after {
	-moz-transform: translateX(4px);
	-ms-transform: translateX(4px);
	-webkit-transform: translateX(4px);
	transform: translateX(4px);
}

.menu .button-arrow:after { display: none; }

/* BADGE */

.button.button-badge {
	border-radius: 0 2px 2px 0;
	padding-right: 84px;
	position: relative;
}

.button.button-badge .badge {
	border-radius: 0 2px 2px 0;
	font-size: 20px;
	height: 100%;
	padding: 16px;
	position: absolute;
		top: 0;
		right: 0;
}

/* QUERIES */

@media all and (min-width: 700px) {
	.button + .button { margin-left: <?php echo $half; ?>px; }
}

@media all and (max-width: 700px) {
	.button + .button { margin-top: <?php echo $half; ?>px; }
}
