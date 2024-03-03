<style type="text/css">

/*------------------------------*\
	$COLORS
\*------------------------------*/

<?php
	foreach ( md_editor_colors() as $color_group => $color_fields ) {
		$color_slug = $color_fields['slug'];
		$color_val = $color_fields['color'];
		echo
			"div.editor-styles-wrapper .has-$color_slug-background-color { background-color: $color_val; }\n".
			"div.editor-styles-wrapper .has-$color_slug-color { color: $color_val; }\n";
	}
?>



/*------------------------------*\
	$TYPOGRAPHY
\*------------------------------*/

div.editor-styles-wrapper a, div.editor-styles-wrapper a:hover {
	color: <?php echo $colors['site']['links']; ?>;
	text-decoration: underline;
}

div.editor-styles-wrapper a:hover { text-decoration: none; }

/* ICONS */

@font-face {
	font-family: md-icon;
	font-display: swap;
	src: url('<?php echo md_font_icons_url(); ?>') format('woff');
	font-style: normal;
	font-weight: 400;
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

.md-icon.icon-data:before { content: attr(data-md-icon); }

/* WRAPS */

.wp-block { max-width: <?php echo $post_width; ?>px; }

@media all and (min-width: 900px) {
	.editor-styles-wrapper .alignwide, .editor-styles-wrapper .wp-block[data-align="wide"] { max-width: <?php echo $post_width + $triple; ?>px; }
}

/* MAIN TYPE */

div.editor-styles-wrapper {
	<?php if ( md_setting( array( 'content', 'style' ) ) == 'minimal' ) : ?>
		background-color: <?php echo $colors['site']['bg_color']; ?>;
	<?php else : ?>
		background-color: <?php echo $colors['content']['bg_color']; ?>;
	<?php endif; ?>
	color: <?php echo $colors['site']['text']; ?>;
}

#editor .editor-post-title__block .editor-post-title__input,
div.editor-styles-wrapper h1,
div.editor-styles-wrapper h2,
div.editor-styles-wrapper h3,
div.editor-styles-wrapper h4,
div.editor-styles-wrapper h5,
div.editor-styles-wrapper h6 { color: <?php echo $colors['site']['headline']; ?>; }

div.editor-styles-wrapper h2:not(:first-child),
div.editor-styles-wrapper h3:not(:first-child),
div.editor-styles-wrapper h4:not(:first-child),
div.editor-styles-wrapper h5:not(:first-child) { margin-top: <?php echo $mid; ?>px; }

div.editor-styles-wrapper ol, div.editor-styles-wrapper ul { padding-left: <?php echo $double; ?>px; }

div.editor-styles-wrapper li { margin-bottom: <?php echo $third; ?>px; }

div.editor-styles-wrapper p {
	margin-bottom: <?php echo $single; ?>px;
	margin-top: 0;
}

div.editor-styles-wrapper p:last-child, div.editor-styles-wrapper p:empty { margin-bottom: 0; }

.wp-block.wp-block-heading { margin-bottom: <?php echo $half; ?>px; }

.editor-block-list__layout .editor-block-list__block:not([data-align="full"]) {
	padding-left: 0;
	padding-right: 0
}

.edit-post-visual-editor .editor-block-list__block .editor-block-list__block-edit {
	margin-left: 0;
	margin-right: 0
}

div.editor-styles-wrapper p.huge-title,
div.editor-styles-wrapper p.large-title,
div.editor-styles-wrapper p.med-title,
div.editor-styles-wrapper p.mid-title,
div.editor-styles-wrapper p.micro-title,
div.editor-styles-wrapper p.small-title { margin-top: 0; }

.editor-styles-wrapper .intro {
	font-size: <?php echo round( $typography['body']['font_size']['desktop'] * 1.2 ); ?>px;
	line-height: <?php echo round( $typography['body']['line_height']['desktop'] * 1.1 ); ?>px;
}

div.editor-styles-wrapper,
div.editor-styles-wrapper p,
.editor-post-title__block .editor-post-title__input {
	font-size: <?php echo $typography['body']['font_size']['desktop']; ?>px;
	font-family: <?php echo $typography['body']['font_family']; ?>;
	<?php echo ! empty( $typography['body']['font_weight'] ) ? ' font-weight: ' . $typography['body']['font_weight'] . ';' : ''; ?>
	line-height: <?php echo $typography['body']['line_height']['desktop']; ?>px;
}

div.editor-styles-wrapper .has-huge-font-size,
div.editor-styles-wrapper .has-large-font-size,
div.editor-styles-wrapper .has-medium-font-size,
div.editor-styles-wrapper .has-small-font-size { line-height: initial; }

<?php
	$queries = array( 900 => 'tablet', 700 => 'mobile' );
	$titles = array(
		'huge' => 'div.editor-styles-wrapper .huge-title',
		'h1' => '.editor-post-title__block .editor-post-title__input, div.editor-styles-wrapper h1, div.editor-styles-wrapper .large-title',
		'h2' => 'div.editor-styles-wrapper h2, div.editor-styles-wrapper .large-title',
		'h3' => 'div.editor-styles-wrapper h3, div.editor-styles-wrapper .med-title',
		'h4' => 'div.editor-styles-wrapper h4, div.editor-styles-wrapper .mid-title',
		'h5' => 'div.editor-styles-wrapper h5, div.editor-styles-wrapper .small-title',
		'h6' => 'div.editor-styles-wrapper h6, div.editor-styles-wrapper .micro-title'
	);
	$texts = array(
		'huge' => 'div.editor-styles-wrapper .huge-text',
		'h1' => 'div.editor-styles-wrapper .large-text',
		'h2' => 'div.editor-styles-wrapper .large-text',
		'h3' => 'div.editor-styles-wrapper .med-text',
		'h4' => 'div.editor-styles-wrapper .mid-text',
		'h5' => 'div.editor-styles-wrapper .small-text',
		'h6' => 'div.editor-styles-wrapper .micro-text'
	);
	$h1_ff = ! empty( $typography['h1']['font_family'] ) ? $typography['h1']['font_family'] : $font_family;
	$h1_fw = ! empty( $typography['h1']['font_weight'] ) ? $typography['h1']['font_weight'] : $bold;

	foreach ( $titles as $attribute => $selector ) {
		$h_font_family = ! empty( $typography[$attribute]['font_family'] ) ? $typography[$attribute]['font_family'] : $h1_ff;
		$h_font_weight = ! empty( $typography[$attribute]['font_weight'] ) ? $typography[$attribute]['font_weight'] : $h1_fw;
		echo
			"$selector, " . $texts[$attribute] . " {\n".
				"\tfont-size: " . $typography[$attribute]['font_size']['desktop'] . "px;\n".
				"\tline-height: " . $typography[$attribute]['line_height']['desktop'] . "px;\n".
			"}\n";
		echo
			"$selector {\n".
				( ! empty( $typography[$attribute]['font_family'] ) || ! empty( $typography['h1']['font_family'] ) ? "\tfont-family: {$h_font_family};\n" : '' ).
				( ! empty( $typography[$attribute]['font_style'] ) ? "\tfont-style: italic;\n" : '' ).
				"\tfont-weight: {$h_font_weight};\n".
			"}\n";
	}

	foreach ( $queries as $w => $d ) {
		echo "@media all and (max-width: {$w}px) {\n";
		foreach ( $titles as $h => $selector ) {
			if ( ! empty( $typography[$h] ) )
			echo "\t$selector, " . $texts[$h] . " { ".
					( ! empty( $typography[$h]['font_size'][$d] ) ? 'font-size: ' . $typography[$h]['font_size'][$d] . 'px; ' : '' ).
					( ! empty( $typography[$h]['line_height'][$d] ) ? 'line-height: ' . $typography[$h]['line_height'][$d] . 'px; ' : '' ).
				"}\n";
		}
		echo "}\n";
	}
?>



/* SPACERS */

.editor-block-list__layout .editor-default-block-appender > .editor-default-block-appender__content,
.editor-block-list__layout > .editor-block-list__block > .editor-block-list__block-edit,
.editor-block-list__layout > .editor-block-list__layout > .editor-block-list__block > .editor-block-list__block-edit {
	margin-bottom: <?php echo $single; ?>px;
	margin-top: <?php echo $single; ?>px;
}

.block-library-list .editor-rich-text__tinymce,
.block-library-list .editor-rich-text__tinymce ol,
.block-library-list .editor-rich-text__tinymce ul,
.editor-styles-wrapper ul.md-list { margin-left: <?php echo $single; ?>px; }

/* ALIGNMENTS */

.text-center, .aligncenter.wrap-small { text-align: center; }

.alignvertical {
	position: absolute;
		left: 50%;
		top: 50%;
	transform: translate(-50%,-50%);
}

/* FULL WIDTH */

@media all and (min-width: 900px) {
	.md-editor-full .wp-block.editor-post-title__block {
		max-width: <?php echo $site_width; ?>px;
		padding-left: <?php echo $triple; ?>px;
		padding-right: <?php echo $triple; ?>px;
	}
	.md-editor-full .editor-post-title,
	.md-editor-full .editor-post-title__block,
	.md-editor-full .editor-post-title__block .editor-post-title__input {
		padding-left: 0;
		padding-right: 0;
		word-break: normal;
	}
	.md-editor-full .editor-post-title__input { text-align: center; }
}

/* POST STYLES */

.wp-block-image figcaption {
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-style: italic;
	font-size: 0.9em;
	margin-bottom: 0;
	text-align: center;
}

.block-library-list .editor-rich-text__tinymce,
.block-library-list .editor-rich-text__tinymce ol,
.block-library-list .editor-rich-text__tinymce ul,
.editor-styles-wrapper ul.md-list {
	list-style: square;
	padding-left: 0;
}

/* DESIGN / OVERLAYS / SHADOWS */

.wp-block.note { background-color: <?php echo $colors['site']['tertiary']; ?>; }
.wp-block.alert { background-color: <?php echo $colors['site']['accent']; ?>; }

.image-overlay {
	background-position: center top;
	background-size: cover;
	display: block;
	position: relative;
	z-index: 10;
}

.image-overlay:after {
	background-color: rgba(0, 0, 0, 0.5);
	content: '';
	display: block;
	height: 100%;
	position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
		top: 0;
	width: 100%;
	z-index: -1;
}

.has-shadow, .shadow, .wp-block-image.shadow img { box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2); }

.shadow-large, .wp-block-image.shadow-large img { box-shadow: 0 5px 55px rgba(0, 0, 0, 0.15); }

.shadow-small, .wp-block-image.shadow-small img { box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15); }

.wp-block-image.shadow, .wp-block-image.shadow-large, .wp-block-image.shadow-small { box-shadow: none; }



/*------------------------------*\
	$BUTTONS
\*------------------------------*/

.md-button, .md-submit {
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
	padding: <?php echo $half; ?>px;
	position: relative;
	text-align: center;
	z-index: 10;
	transition: 0.3s;
}

.md-button:hover, .md-submit:hover { transform: translateY(1px); }

/* SIZES */

.md-button.md-button-large {
	font-size: 1.4em;
	padding: <?php echo $single; ?>px <?php echo $mid; ?>px;
}

/* OUTLINE */

.md-submit.md-submit-outline {
	background-color: transparent;
	border: 3px solid <?php echo $colors['site']['button']; ?>;
	border-radius: 5px;
	color: <?php echo $colors['site']['button']; ?>;
}

/* ARROW */

.md-button.md-button-arrow:after {
	content: '\e80f';
	display: inline-block;
	font-family: 'md-icon';
	margin-left: 13px;
	transition: 0.3s;
}

.md-button.md-button-arrow:hover:after { transform: translateX(4px); }



/*------------------------------*\
	$ARROW_BLOCK
\*------------------------------*/

.wp-block .arrow-down .dashicons { line-height: 1; }



/*------------------------------*\
	$EMAIL_BLOCK
\*------------------------------*/

input.md-input, button.md-submit {
	font-size: 1em;
	line-height: 1;
}

.md-form-field {
	align-items: center;
	border: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-radius: 0;
	box-shadow: none;
	display: flex;
}

.md-form-field-icon {
	color: <?php echo $colors['site']['text']; ?>;
	font-size: 1.2em;
	line-height: 1;
	min-width: <?php echo 50 - $half; ?>px;
	padding: <?php echo $half; ?>px;
	text-align: center;
}

input.md-input {
	border: 0;
	box-shadow: none;
	margin: 0;
	padding: <?php echo $half; ?>px <?php echo $half; ?>px <?php echo $half; ?>px 0;
}

.md-input[disabled] { background-color: #fff; }

.md-email-full .md-form-field, .md-email-full .md-submit { width: 100%; }

.md-email-full .md-form-field { margin-bottom: <?php echo $half; ?>px; }

[class*="md-email-attached"] .md-form-field, [class*="md-email-attached"] .md-submit { float: left; }

[class*="md-email-attached"] .md-submit {
	border-radius: 0 3px 3px 0;
	padding: 17px <?php echo $single; ?>;
	width: 22%;
}

.md-email-attached .md-form-field { width: 78%; }

.md-email-attached-2 .md-form-field { width: 39%; }

.md-email-attached-2 .md-input-name {
	border-right: 0;
	border-radius: 3px 0 0 3px;
}

div.editor-styles-wrapper .md-email-footer {
	font-size: 0.8em;
	font-style: italic;
	line-height: 1.5em;
	text-align: center;
}



/*------------------------------*\
	$CONTENT_UPGRADE_BLOCK
\*------------------------------*/

.md-content-upgrade { border-radius: 5px; }

.md-content-upgrade-action .md-button { width: 100%; }

@media all and (min-width: 900px) {
	.box-lr {
		align-items: center;
		display: flex;
	}
	#editor .box-lr .md-content-upgrade-text {
		margin-bottom: 0;
		width: 65%;
	}
	.box-lr .md-content-upgrade-action {
		padding-left: <?php echo $single; ?>px;
		width: 35%;
	}
}



/*------------------------------*\
	$SHARE_NOTICE_BLOCK
\*------------------------------*/

.share-notice {
	border-radius: 3px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
	position: relative;
}

.share-notice.alignfull {
	border-radius: 0;
	border-width: 3px 0;
}

.share-notice-outline {
	border-style: solid;
	border-width: 3px;
}

.share-notice-full, .share-notice-full .share-notice-button, .share-notice-full .share-notice-icon {
	border-color: #fff;
	color: #fff;
}

.share-notice-full .md-submit-full { background-color: #fff; }

.share-notice-button { font-weight: bold; }

.share-notice-icon {
	float: right;
	font-size: 42px;
	line-height: 1;
	position: relative;
		top: -3px;
}

.share-notice-twitter.share-notice-outline,
.share-notice-twitter.share-notice-outline .md-submit-outline {
	border-color: #1da1f2;
	color: #1da1f2;
}
.share-notice-twitter.share-notice-full,
.share-notice-twitter.share-notice-outline .md-submit-full { background-color: #1da1f2; }
.share-notice-twitter.share-notice-outline .share-notice-icon,
.share-notice-twitter.share-notice-full .md-submit-full { color: #1da1f2; }

.share-notice-facebook.share-notice-outline,
.share-notice-facebook.share-notice-outline .md-submit-outline {
	border-color: #3b5998;
	color: #3b5998;
}
.share-notice-facebook.share-notice-full,
.share-notice-facebook.share-notice-outline .md-submit-full { background-color: #3b5998; }
.share-notice-facebook.share-notice-outline .share-notice-icon,
.share-notice-facebook.share-notice-full .md-submit-full { color: #3b5998; }

.share-notice-pinterest.share-notice-outline,
.share-notice-pinterest.share-notice-outline .md-submit-outline {
	border-color: #bd081c;
	color: #bd081c;
}
.share-notice-pinterest.share-notice-full,
.share-notice-pinterest.share-notice-outline .md-submit-full { background-color: #bd081c; }
.share-notice-pinterest.share-notice-outline .share-notice-icon,
.share-notice-pinterest.share-notice-full .md-submit-full { color: #bd081c; }

.share-notice-linkedin.share-notice-outline,
.share-notice-linkedin.share-notice-outline .md-submit-outline {
	border-color: #0077b5;
	color: #0077b5;
}
.share-notice-linkedin.share-notice-full,
.share-notice-linkedin.share-notice-outline .md-submit-full { background-color: #0077b5; }
.share-notice-linkedin.share-notice-outline .share-notice-icon,
.share-notice-linkedin.share-notice-full .md-submit-full { color: #0077b5; }



/*------------------------------*\
	$CALLOUT
\*------------------------------*/

.md-callout {
	border: 4px solid rgba(0, 0, 0, 0.1);
	border-radius: 5px;
	position: relative;
}

.md-callout.has-icon { padding-top: 0 }

.md-callout-title, .md-callout-action { text-align: center; }

.md-callout .md-callout-icon {
	border-radius: 50%;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
	color: #fff;
	display: block;
	line-height: 1;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

.md-callout .md-callout-icon.icon {
	background-color: #1e1e1e;
	font-size: 40px;
	height: 80px;
	line-height: 77px;
	margin-top: -25px;
	width: 80px;
}

.md-callout .md-callout-icon.image {
	height: 100px;
	margin-top: -35px;
	width: 100px;
}

.md-callout .md-callout-icon.image img {
	border-radius: 50%;
	height: 100px;
	width: 100px;
}

.md-callout-list + .md-callout-action { margin-top: <?php echo $single; ?>px; }

.md-callout-action .md-button { width: 100%; }

<?php md_block_editor_css(); ?>
