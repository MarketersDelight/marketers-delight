<style type="text/css">

/*------------------------------*\
	$COMMENTS
\*------------------------------*/

ol.comments-list, ul.children {
	margin-bottom: 0;
	margin-left: 0;
}

.comments .children {
	border-left: 1px solid <?php echo $colors['content']['border_color']; ?>;
	padding-left: <?php echo $single; ?>px;
	margin-top: <?php echo $single; ?>px;
}

.comments .comments-title { margin-bottom: <?php echo $single; ?>px; }

.comments-title span a {
	border-bottom: 1px solid <?php echo $colors['site']['links']; ?>;
	color: <?php echo $colors['site']['links']; ?>;
	font-size: 0.8em;
	font-weight: 400;
}

.comments-title span a:hover { border-bottom: 0; }

/* TABS */

.md-tab-content { display: none; }
.md-tab-content.active { display: block; }

.comments-tabs {
	background-color: rgba(0, 0, 0, 0.03);
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	line-height: 1;
	padding-top: <?php echo $half; ?>px;
	text-align: center;
}

.comment-tab {
	border: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-radius: 4px 4px 0 0;
	border-width: 1px 1px 0;
	color: <?php echo $colors['site']['text-sec']; ?>;
	cursor: pointer;
	display: inline-block;
	font-weight: <?php echo $bold; ?>;
	margin-right: <?php echo $small; ?>px;
	padding: <?php echo $half; ?>px;
}

.comment-tab.active {
	background-color: #fff;
	border-bottom-color: #fff;
	border-bottom-width: 1px;
	color: <?php echo $colors['site']['text']; ?>;
	margin-bottom: -1px;
}

.comment-tab i { margin-right: 2px; }

/* COMMENT */

.comments-list > .comment {
	background-color: #fff;
	border: 1px solid <?php echo $colors['content']['border_color']; ?>;
	border-radius: 3px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
	padding: <?php echo $single; ?>px;
}

.comment { list-style: none; }

.comment:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

/* BYLINE */

.comment-byline {
	border-bottom: 1px solid <?php echo $colors['content']['border_color']; ?>;
	margin-bottom: <?php echo $half; ?>px;
	padding-bottom: <?php echo $half; ?>px;
}

.comment-byline .byline-author {
	color: <?php echo $colors['site']['text']; ?>;
	font-weight: bold;
}

.comment-byline .byline-author a { color: <?php echo $colors['site']['headline-links']; ?>; }

.comment-byline .byline-avatar {
	float: left;
	position: relative;
	width: 10%;
}

.comment-byline .byline-avatar-author {
	background-color: <?php echo $colors['site']['secondary']; ?>;
	border-radius: 50%;
	color: #fff;
	display: block;
	font-size: 13px;
	height: 23px;
	line-height: 1;
	padding-top: 5px;
	position: absolute;
		bottom: -3px;
		left: -3px;
	text-align: center;
	width: 23px;
}

.comment-byline-meta {
	float: left;
	padding-left: <?php echo $half; ?>px;
	width: 90%;
}

.comment-byline .byline-edit { float: right; }

.comment-reply { margin-right: 7px; }

/* COMMENTFORM */

.comment-reply-title {
	font-size: <?php echo $typography['h5']['font_size']['desktop']; ?>px;
	line-height: <?php echo $typography['h5']['line_height']['desktop']; ?>px;
	margin-bottom: <?php echo $half; ?>px;
}

.comment-form input[type="text"] { width: 75%; }

.comment-respond:not(:first-child) { margin-top: <?php echo $single; ?>px; }

.comment-form-author label, .comment-form-email label, .comment-form-url label { display: block; }

#cancel-comment-reply-link {
	color: <?php echo $colors['site']['links']; ?>;
	float: right;
	font-size: 0.8em;
}

#cancel-comment-reply-link:before {
	content: '\e810';
	margin-right: <?php echo $small; ?>px;
}

.comment-form .form-submit { margin-bottom: 0; }

/* PAGINATION */

.comments .pagination { border-top: 1px solid #ddd; }

/* QUERIES */

@media all and (max-width: <?php echo $post_width; ?>px) {
	.comments-list > .comment { padding: <?php echo $half; ?>px; }
	.comments .children { padding-left: <?php echo $half; ?>px; }
}