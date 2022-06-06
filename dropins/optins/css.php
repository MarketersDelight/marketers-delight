<style type="text/css">

/*------------------------------*\
	$EMAIL FORMS
\*------------------------------*/

.cta {
	background-size: cover;
	box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
}

.content-inner .cta { box-shadow: none; }

.sidebar .cta-small { border-radius: 4px; }

.cta .email-form { overflow: hidden; }

.content-box .content .cta-box.post-box { padding: 0; }

.post-box .cta-box.post-box {
	margin-left: 0;
	margin-right: 0;
}

.cta-title { text-align: center; }

.cta-small .cta-title, .cta-slim .cta-title { margin-bottom: <?php echo $half; ?>px; }

.cta-image {
	margin-left: auto;
	margin-right: auto;
}

.cta-small .alignleft { margin-right: <?php echo $half; ?>px; }
.cta-small .alignright { margin-left: <?php echo $half; ?>px; }

.cta-image img { width: 100%; }

@media all and (min-width: 900px) {
	.content-full .cta-slim .cta-inner, .cta-full .cta-inner { padding: <?php echo $double; ?>px <?php echo $quad; ?>px; }
	.cta-slim .cta-inner { padding: <?php echo $mid; ?>px; }
	.cta-small .cta-inner { padding: <?php echo $single; ?>px; }
	.cta-image.image-left {
		float: left;
		margin-right: <?php echo $single; ?>px;
	}
	.cta-image.image-right {
		float: right;
		margin-left: <?php echo $single; ?>px;
	}
	.cta-small .image-left { margin-right: <?php echo $half; ?>px; }
	.cta-small .image-right { margin-left: <?php echo $half; ?>px; }
}

@media all and (max-width: 900px) {
	.cta-inner { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }
	.cta-image { margin-bottom: <?php echo $single; ?>px; }
}

@media all and (max-width: 600px) {
	.cta-image { width: 50% !important; }
}


/*------------------------------*\
	$POPUPS
\*------------------------------*/

<?php $popup_width = ( $single * $single ) + ( $double * 4 ); ?>

/* DISPLAY */

.md-popups {
	height: 0;
	opacity: 0;
	overflow: scroll;
	position: fixed;
		left: 0;
		top: 0;
	transition: opacity 0.2s ease-in-out;
	visibility: hidden;
	width: 100%;
	z-index: 500;
}

.has-popup .md-popups { height: 100%; }

.has-popup .md-popup-active { display: block; }

.has-popup .md-popups, .has-popup .md-popup-bg {
	opacity: 1;
	visibility: visible;
}

.has-popup, .md-popup-content { overflow: hidden; }

.md-popup {
	background-color: #fff;
	background-size: cover;
	box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
	display: none;
	height: auto;
	position: relative;
	width: 100%;
	z-index: 1000;
}

.md-popup-bg {
	background-color: rgba(0, 0, 0, 0.8);
	visibility: hidden;
	height: 100%;
	position: fixed;
		left: 0;
		top: 0;
	transition: opacity 0.15s ease-in-out;
	opacity: 0;
	width: 100%;
	z-index: 999;
}

@media all and (min-width: <?php echo $popup_width; ?>px) {
	.md-popup {
		margin-left: -<?php echo round( $popup_width / 2 ); ?>px;
		left: 50%;
		top: <?php echo $double; ?>px;
		width: <?php echo $popup_width; ?>px;
	}
}

@media all and (max-width: <?php echo $popup_width; ?>px) {
	.admin-bar .md-popups { top: 46px; }
}

@media all and (min-width: 1200px) {
	.md-popup.md-popup-auto {
		background-color: #010101;
		left: auto;
		margin-left: auto;
		margin-right: auto;
		max-width: 1140px;
		text-align: center;
		top: 5%;
		width: auto;
	}
}

/* TRIGGER */

.md-popup-trigger { cursor: pointer; }

/* CLOSE */

.md-popup-close-corner {
	color: #ae2525;
	cursor: pointer;
	font-size: <?php echo $typography['h3']['font_size']['desktop']; ?>px;
	line-height: 1;
	position: absolute;
		top: <?php echo $half; ?>px;
		right: <?php echo $half; ?>px;
	z-index: 100;
}

.md-popup-close-corner:hover { opacity: 0.95; }

/* ELEMENTS */

.popup-secondary-bg-color { background-color: <?php echo $colors['site']['links']; ?>; }

.popup-title:empty, .popup-subtitle:empty, .popup-text:empty, .popup-buttons-text:empty { display: none; }

.popup-content, .popup-image-wrap { position: relative; }

.has-bg-image, .has-bg-image .popup-image-wrap, .popup-image {
	background-position: center center;
	background-size: cover;
}

.md-popup .email-form-wrap { clear: both; }

.popup-buttons { text-align: center; }

.md-popup .button-text, .md-popup .button-subtext { display: block; }

.popup-buttons-text { margin-top: <?php echo $half; ?>px; }

@media all and (max-width: 700px) {
	.popup-default .popup-image, .popup-border-box .popup-image { width: 50% !important; }
	.md-popup .button-text {
		font-size: <?php echo $typography['h4']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['h4']['line_height']['mobile']; ?>px;
	}
	.md-popup .button-subtext {
		font-size: <?php echo $typography['h6']['font_size']['mobile']; ?>px;
		line-height: <?php echo $typography['h6']['line_height']['mobile']; ?>px;
	}
}

/* GRAIL */

.popup-grail .popup-secondary-bg-color { background-color: #1e1e1e; }

.popup-grail .popup-image-wrap { text-align: center; }

.popup-grail .button {
	margin-left: 0;
	width: 100%;
}

.popup-grail .button + .button { margin-top: <?php echo $half; ?>px; }

@media all and (min-width: 800px) {
	.popup-grail.image-alignleft .popup-inner, .popup-grail.image-alignright .popup-inner {
		display: flex;
		height: 100%;
	}
	.popup-grail.image-alignleft .popup-content, .popup-grail.image-alignright .popup-content { width: 60%; }
	.popup-grail.image-alignleft .popup-image-wrap, .popup-grail.image-alignright .popup-image-wrap { width: 40%; }
	.popup-grail.image-aligncenter .popup-image { margin-top: -<?php echo $single; ?>px; }
	.popup-grail.image-alignleft .popup-content { padding-left: <?php echo $triple; ?>px; }
	.popup-grail.image-alignright .popup-image-wrap { order: 2; }
	.popup-grail.image-alignleft .popup-image { right: -<?php echo $mid; ?>px; }
	.popup-grail.image-alignright .popup-content { padding-right: <?php echo $triple; ?>px; }
	.popup-grail.image-alignright .popup-image { left: -<?php echo $mid; ?>px; }
	.popup-grail.image-alignleft .popup-image, .popup-grail.image-alignright .popup-image {
		margin: 0;
		position: absolute;
			top: 50%;
		transform: translate(0, -50%);
	}
}

@media all and (max-width: 800px) {
	.popup-grail .popup-image {
		margin-bottom: -<?php echo $single; ?>px;
		margin-top: <?php echo $single; ?>px;
		width: 30% !important;
	}
	.popup-grail.has-image .popup-content { padding-top: <?php echo $double; ?>px; }
}

/* BORDER BOX */

.popup-border-box { background-color: #3093d0; }

/* BOLD OFFER */

.popup-bold-offer .popup-subtitle, .popup-bold-offer .md-popup-close-corner { color: #fff; }

.popup-bold-offer .button {
	margin-right: 0;
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.popup-bold-offer .button .button-text {
	font-size: <?php echo $typography['h2']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h2']['line_height']['desktop']; ?>px;
}

.popup-bold-offer .form-full.form-multi-fields .form-input { float: left; }

.popup-bold-offer .form-full .form-input {
	border-width: 4px;
	font-style: italic;
}

@media all and (min-width: 700px) {
	.popup-bold-offer .button, .popup-bold-offer .form-full.form-multi-fields .form-input {
		display: block;
		float: left;
		width: 49%;
	}
	.popup-bold-offer .button + .button, .popup-bold-offer .form-full.form-multi-fields .form-input + .form-input { margin-left: 2%; }
	.popup-bold-offer .form-full .form-input {
		font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
	}
	.popup-bold-offer .form-full .form-submit {
		font-size: <?php echo $typography['h3']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h3']['line_height']['desktop']; ?>px;
	}
}

/* NOTIFICATION */

<?php $notification_width = $double * 10; ?>

.popup-notification { border-radius: 5px; }

.popup-notification .popup-content { border-radius: 5px 5px 0 0; }

.popup-notification .popup-inner-bg {
	background-position: center center;
	background-size: cover;
	border-radius: 3px 3px 0 0;
	display: block;
	height: <?php echo $triple * 2; ?>px;
}

.popup-notification.image-aligncenter { text-align: center; }

.popup-notification .popup-image.aligncenter {
	margin-bottom: <?php echo $half; ?>px;
	margin-top: -<?php echo $triple; ?>px;
}

.popup-notification .button { margin-bottom: 0; }

@media all and (min-width: <?php echo $notification_width; ?>px) {
	.popup-notification {
		left: 50%;
		margin-left: -<?php echo round( $notification_width / 2 ); ?>px;
		width: <?php echo $notification_width; ?>px;
	}
}

@media all and (max-width: 500px) {
	.popup-notification .popup-image { width: 30% !important; }
}



/*------------------------------*\
	$CTA BAR
\*------------------------------*/

.cta-bar.floating, .cta-bar.static {
	height: 0;
	opacity: 0;
	visibility: hidden;
	position: fixed;
		bottom: 0;
		left: 0;
	transition: all 0.2s ease-in-out;
}

.cta-bar.top.static { position: absolute; }

.cta-bar.top { bottom: auto; top: 0; }

.cta-bar a:hover { border-bottom: 0; }

.admin-bar .cta-bar.floating.top, .has-md-admin-bar .cta-bar.floating.top { top: 32px; }

.cta-bar.bottom { bottom: 0; }

.cta-bar {
	background-color: <?php echo $colors['site']['secondary']; ?>;
	box-shadow: 0 -5px 9px rgba(0, 0, 0, 0.3);
	color: #fff;
	padding: <?php echo $half; ?>px;
	width: 100%;
	z-index: 100;
}

.cta-bar.active {
	height: auto;
	opacity: 1;
	visibility: visible;
}

.cta-bar-content a {
	border-bottom: 1px solid <?php echo $colors['footer']['links']; ?>;
	color: <?php echo $colors['footer']['links']; ?>
}

.cta-bar-desc { color: #ddd; }

/* LAYOUT */

.cta-bar-full .inner { max-width: 100%; }

.cta-bar-single .cta-bar-media, .cta-bar-single .cta-bar-text {
	display: inline-block;
	vertical-align: middle;
}

.cta-bar-media { padding-right: <?php echo $half; ?>px; }

/* CLOSE */

.cta-bar-corner {
	cursor: pointer;
	line-height: 1;
	position: absolute;
		top: <?php echo $single; ?>px;
		right: <?php echo $half; ?>px;
	transform: translateY(-50%);
}

.cta-bar-full .cta-bar-corner { right: <?php echo $small; ?>px; }

/* ICON */

.cta-bar-icon {
	background-color: <?php echo $colors['site']['primary']; ?>;
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
	color: #fff;
	display: block;
	font-size: 31px;
	height: 60px;
	line-height: 1;
	padding-top: <?php echo $half; ?>px;
	text-align: center;
	width: 60px;
}

/* IMAGE */

.cta-bar-image { width: 100px; }

.cta-bar-image img { width: 100%; }

.cta-bar-media.breakout { vertical-align: bottom; }

.cta-bar-media.has-shadow .cta-bar-image { box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2); }

/* EMAIL */

.cta-bar .form-input { border-width: 0; }

.cta-bar .form-input-name { border-right-width: 1px; }

/* QUERIES */

@media all and (min-width: <?php echo $site_width; ?>px) {
	.cta-bar-full { padding: <?php echo $single; ?>px; }
}

@media all and (min-width: 900px) {
	.cta-bar-inner {
		display: table;
		width: 100%;
	}
	.cta-bar-media { padding-right: <?php echo ( $half + $third ); ?>px; }
	.cta-bar-columns .cta-bar-content,
	.cta-bar-columns .cta-bar-action,
	.cta-bar-columns .cta-bar-media,
	.cta-bar-columns .cta-bar-text {
		display: table-cell;
		vertical-align: middle;
	}
	.cta-bar-columns .cta-bar-content {
		padding-right: <?php echo $half; ?>px;
		width: 60%;
	}
	.cta-bar-columns .cta-bar-action {
		text-align: right;
		width: 40%;
	}
	.cta-bar-corner {
		top: 50%;
		right: <?php echo $single; ?>px;
		transform: translateY(-50%);
	}
}

@media all and (min-width: 700px) {
	.breakout .cta-bar-image {
		margin-bottom: -<?php echo $single; ?>px;
		margin-top: -<?php echo $double; ?>px;
	}
}

@media all and (max-width: 900px) {
	.cta-bar-media { margin-bottom: <?php echo $half; ?>px; }
	.cta-bar-content, .cta-bar-action { width: 100% !important; }
	.cta-bar-columns .cta-bar-content {
		display: table;
		margin-bottom: <?php echo $half; ?>px;
	}
	.cta-bar-columns .cta-bar-media, .cta-bar-columns .cta-bar-text {
		display: table-cell;
		vertical-align: top;
	}
	.cta-bar .button { width: 100%; }
}

@media all and (max-width: 700px) {
	.cta-bar-image { width: auto !important; }
	.cta-bar .form-input {
		margin-bottom: <?php echo $small; ?>px;
		padding-bottom: <?php echo $small; ?>px;
		padding-top: <?php echo $small; ?>px;
		padding-right: <?php echo $small; ?>px;
	}
	.cta-bar .form-submit { padding: <?php echo $small; ?>px; }
}