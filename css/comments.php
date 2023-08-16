<style type="text/css">

/*------------------------------*\
	$COMMENTS
\*------------------------------*/

.comments .comments-list {
	margin-bottom: 0;
	margin-left: 0;
	position: relative;
}

.comments .children {
	margin-left: <?php echo $single; ?>px;
	margin-top: <?php echo $single; ?>px;
}

/* TITLE */

.comments-title {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	margin-bottom: <?php echo $single; ?>px;
	padding-bottom: <?php echo $half; ?>px;
}

.comments-title .comments-title-text {
	flex: 1;
	margin-bottom: 0;
	margin-right: <?php echo $small; ?>px;
}

.comments-title i, .comment-controls i {
	font-size: 0.85em;
	margin-right: <?php echo $small; ?>px;
}

/* COMMENT */

.comment {
	list-style: none;
	position: relative;
}

.comment:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

.comment-details {
	background-color: <?php echo $colors['content']['bg_color']; ?>;
	padding-bottom: <?php echo $half; ?>px;
	position: relative;
	z-index: 10;
}

.comment-details a { text-decoration: none; }

.comment-author, .comment-author a {
	color: <?php echo $colors['site']['text']; ?>;
	font-weight: <?php echo $bold; ?>;
}

.comment-byline {
	flex: 1;
	padding-left: <?php echo $half; ?>px;
}

.comment-byline p { margin-bottom: 0; }

.comment-content {
	margin-bottom: <?php echo $half; ?>px;
	padding-left: <?php echo $single; ?>px;
}

.comment .comment-awaiting-moderation {
	background-color: <?php echo $colors['site']['action']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
	font-style: italic;
	margin-bottom: <?php echo $half; ?>px;
	padding: <?php echo $half; ?>px;
}

.comment-controls { padding-left: <?php echo $single; ?>px; }

.comment-respond + .comment-controls { display: none; }

.comment-controls a {
	border: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-radius: 5px;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
	color: <?php echo $colors['site']['text-sec']; ?>;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	padding: <?php echo $small; ?>px <?php echo $third; ?>px;
	text-decoration: none;
}

.comment-controls a:hover { background-color: rgba(0, 0, 0, 0.05); }

/* TOGGLE */

.comment-toggle {
	color: <?php echo $colors['site']['text-sec']; ?>;
	cursor: pointer;
	display: none;
	float: right;
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: 1;
	margin-top: <?php echo $half + $small; ?>px;
	position: relative;
	z-index: 10;
}

.comment:hover > .comment-details .comment-toggle { display: block; }

.comment.toggle-comment:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

.toggle-comment .comment-controls, .toggle-comment .children,
.show-comment, .toggle-comment .hide-comment { display: none; }

.toggle-comment .show-comment { display: inline; }

.comment.toggle-comment{ opacity: 0.4; }

.toggle-comment .comment-content {
	height: <?php echo $mid; ?>px;
	overflow: hidden;
}

.toggle-comment .comment-content:after {
	background: linear-gradient(to bottom, rgba(254, 254, 254, 0) 0%, #fefefe 80%);
	content: '';
	display: block;
	height: <?php echo $mid; ?>px;
	position: absolute;
		bottom: 0;
		left: 0;
	width: 100%;
}

/* TIMELINE */

.comment-timeline {
	background-color: rgba(0, 0, 0, 0.1);
	display: block;
	height: 100%;
	position: absolute;
		left: <?php echo $small; ?>px;
		top: 0;
	width: 5px;
}

.comment-timeline:hover, .comment:hover > .comment-timeline { background-color: <?php echo $colors['content']['border_color']; ?>; }

.comment-timeline-text { display: none; }

/* FORM */

.comment-form p { margin-bottom: <?php echo $half; ?>px; }

.comment-form-author label, .comment-form-email label, .comment-form-url label { display: block; }

.comment-form input[type="text"] { margin-bottom: 0; }

.comment-form-cookies-consent {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile']; ?>px;
}

.comment-form-cookies-consent label { display: inline; }

/* RESPOND */

.comments-list + .comment-respond { margin-top: <?php echo $single; ?>px; }

.comment .comment-respond {
	margin-bottom: <?php echo $half; ?>px;
	padding-left: <?php echo $single; ?>px;
}

.comment-form .comment-form-comment { margin-bottom: <?php echo $half; ?>px; }

#cancel-comment-reply-link {
	color: <?php echo $colors['site']['links']; ?>;
	float: right;
	font-weight: normal;
	font-size: 0.8em;
}

#cancel-comment-reply-link:before {
	content: '\e810';
	margin-right: <?php echo $small; ?>px;
}

.comment-form .form-submit { margin-bottom: 0; }

@media all and (min-width: 700px) {
	.comment-reply-title, .comments-title-text {
		font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
	}
	.comment-content {
		font-size: <?php echo $typography['body']['font_size']['tablet']; ?>px;
		line-height: <?php echo $typography['body']['line_height']['tablet']; ?>px;
	}
	.comment-content p:not(:last-child) { margin-bottom: <?php echo $typography['body']['line_height']['mobile'] - $small; ?>px; }
	.comment-form-author, .comment-form-email {
		float: left;
		width: 50%;
	}
	.comment-form-url {
		clear: both;
		width: 100%;
	}
	.comment-form-author { padding-right: <?php echo $third; ?>px; }
	.comment-form-email { padding-left: <?php echo $third; ?>px; }
}
