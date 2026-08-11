<style type="text/css">

/*------------------------------*\
	$COMMENTS
\*------------------------------*/

.comments .comments-list {
	margin-block-end: 0;
	margin-inline-start: 0;
	position: relative;
}

.comments .children {
	margin-block-start: var(--md-half);
	margin-inline-start: var(--md-single);
}

/* TITLE */

.content-title {
	align-items: center;
	border-block-end: 1px solid var(--md-border);
	display: flex;
	gap: var(--md-half);
	margin-block-end: var(--md-single);
	padding-block-end: var(--md-half);
}

.content-title .title {
	flex: 1;
	font-size: var(--md-h4);
	line-height: var(--md-h4-line-height);
	margin-block-end: 0;
}

.comments .comment-reply-title {
	font-size: var(--md-h4);
	font-weight: var(--md-bold);
	line-height: var(--md-h4-line-height);
	margin-block-end: var(--md-half);
}

/* COMMENT */

.comment {
	list-style: none;
	position: relative;
}

.comment:not(:last-child) { margin-block-end: var(--md-single); }

.comment-details {
	align-items: center;
	background-color: var(--md-content-main-background);
	display: flex;
	padding-block-end: var(--md-half);
	position: relative;
	z-index: 10;
}

.comment-author, .comment-author a {
	color: var(--md-text);
	font-weight: var(--md-bold);
	text-decoration: none;
}

.comment-byline {
	flex: 1;
	padding-inline-start: var(--md-half);
}

.comment-byline p { margin-block-end: 0; }

.comment-content {
	margin-block-end: var(--md-half);
	padding-inline-start: var(--md-single);
}

.comment .comment-awaiting-moderation {
	background-color: var(--md-color-highlight);
	border-radius: var(--md-border-radius);
	box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
	font-style: italic;
	margin-block-end: var(--md-half);
	padding: var(--md-half);
}

.comment-controls { padding-inline-start: var(--md-single); }

.comment-respond + .comment-controls { display: none; }

.comment-controls a {
	background-color: var(--md-content-box-background);
	border: 1px solid var(--md-border);
	border-radius: var(--md-border-radius);
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
	color: var(--md-content-box-text-muted);
	font-size: var(--md-font-size-sm);
	padding: var(--md-small) var(--md-third);
	text-decoration: none;
}

.comment-controls a:hover { background-color: rgba(0, 0, 0, 0.05); }

.comment-controls i {
	font-size: 0.85em;
	margin-inline-end: var(--md-small);
}

/* TOGGLE */

.comment .toggle {
	color: var(--md-text-muted);
	cursor: pointer;
	display: none;
	float: right;
	font-size: var(--md-font-size-sm);
	line-height: 1;
	margin-block-start: calc(var(--md-half) + var(--md-small));
	position: relative;
	z-index: 10;
}

.comment-details:hover > .comment-byline > .toggle { display: block; }

.comment.toggle-comment:not(:last-child) { margin-block-end: var(--md-half); }

.show-comment, .toggle-comment :is(.comment-controls, .children, .hide-comment) { display: none; }

.toggle-comment .show-comment { display: inline; }

.toggle-comment .comment-content {
	height: var(--md-single);
	overflow: hidden;
}

.toggle-comment .comment-content:after {
	background: linear-gradient(to bottom, rgba(254, 254, 254, 0) 0%, var(--md-content-main-background) 80%);
	content: '';
	display: block;
	height: var(--md-single);
	position: absolute;
		inset-block-end: 0;
		inset-inline-start: 0;
	width: 100%;
}

.box-style .toggle-comment .comment-content:after { background: linear-gradient(to bottom, rgba(254, 254, 254, 0) 0%, var(--md-content-box-background) 80%); }

/* TIMELINE */

.comment-timeline {
	background-color: rgba(0, 0, 0, 0.1);
	display: block;
	height: 100%;
	position: absolute;
		inset-inline-start: var(--md-small);
		inset-block-start: 0;
	width: 5px;
}

.comment-timeline:hover, .comment:hover > .comment-timeline { background-color: var(--md-border); }

.comment-timeline-text { display: none; }

/* FORM */

.comment-form p { margin-block-end: var(--md-half); }

.comment-form-author label, .comment-form-email label, .comment-form-url label { display: block; }

.comment-form input[type="text"] { margin-block-end: 0; }

.comment-form-cookies-consent {
	font-size: var(--md-font-size-sm);
	line-height: var(--md-line-height-sm);
}

.comment-form-cookies-consent label { display: inline; }

/* RESPOND */

.comments-list + .comment-respond { margin-block-start: var(--md-single); }

.comment .comment-respond {
	margin-block-end: var(--md-half);
	padding-inline-start: var(--md-single);
	padding-inline-end: var(--md-third);
}

.comment-form .comment-form-comment { margin-block-end: var(--md-half); }

#cancel-comment-reply-link {
	color: var(--md-links);
	float: right;
	font-weight: normal;
	font-size: 0.8em;
}

#cancel-comment-reply-link:before {
	content: '\e810';
	margin-inline-end: var(--md-small);
}

.comment-form .form-submit { margin-block-end: 0; }

@media all and (min-width: 700px) {
	.comment-content {
		font-size: var(--md-font-size-sm);
		line-height: var(--md-line-height-sm);
	}
	.comment-content p:not(:last-child) { margin-block-end: <?php echo $line_height['mobile'] - $small; ?>px; }
	.comment-form-author, .comment-form-email {
		float: left;
		width: 50%;
	}
	.comment-form-url {
		clear: both;
		width: 100%;
	}
	.comment-form-author { padding-inline-end: var(--md-third); }
	.comment-form-email { padding-inline-start: var(--md-third); }
}
