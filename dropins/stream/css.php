<style type="text/css">

	/*------------------------------*\
		$STREAM
	\*------------------------------*/

	.loop-stream .content-width {
		margin-left: auto;
		margin-right: auto;
	}

	.loop-stream .share {
		border-bottom: 0;
		border-top: 0;
	}

	.stream-columns > .col1 {
		float: left;
		width: 25%;
	}

	.stream-columns > .col2 {
		float: left;
		padding-left: <?php echo $half; ?>px;
		width: 75%;
	}

	.stream-head .stream-head-title {
		margin-bottom: <?php echo $small; ?>px;
	}

	.stream-head .stream-head-image a, .stream-head .stream-head-title a {
		border-bottom: 0;
	}

	.stream-head-image a {
		display: block;
	}

	.stream-loop {
		position: relative;
	}

	.stream-loop:before {
		background-color: <?php echo $colors['site']['links']; ?>;
		content: '';
		display: block;
		height: 100%;
		margin-left: -2px;
		position: absolute;
		bottom: 0;
		left: 50%;
		width: 4px;
	}

	.stream-item, .single-stream .comment-respond, .single-stream .forum-comments {
		background-color: #fff;
		border-radius: 3px;
		box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
		margin-bottom: <?php echo $single; ?>px;
		padding: <?php echo $single; ?>px;
		position: relative;
	}

	.stream-item:first-child:before {
		background-color: <?php echo $colors['site']['links']; ?>;
		border-radius: 50%;
		content: '';
		display: block;
		height: 20px;
		margin-left: -10px;
		position: absolute;
		bottom: -10px;
		left: 50%;
		width: 20px;
	}

	.stream-item.sticky {
		padding-top: <?php echo $half; ?>px;
	}

	.stream-media {
		float: left;
		position: relative;
		width: 20%;
		z-index: 10;
	}

	.stream-media + .stream-text {
		float: left;
		padding-left: 16px;
		width: 80%;
	}

	.stream-embed {
		border: 1px solid #ddd;
		box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
	}

	.stream-embed .stream-text {
		font-size: 0.95em;
		line-height: 1.45em;
	}

	.stream-loop .stream-title a {
		border-bottom: 0;
		color: <?php echo $colors['site']['text']; ?>;
		font-weight: 700;
	}

	.stream-list li {
		font-size: 0.9em;
	}

	.stream-list li:not(:last-child) {
		margin-bottom: 0;
	}

	.stream-bubble {
		background-color: #fff;
		border-radius: 3px;
		box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
		display: inline-block;
		padding: <?php echo $half; ?>px;
	}

	.stream-box {
		cursor: pointer;
		position: relative;
		-moz-transition: 0.2s;
		-ms-transition: 0.2s;
		-o-transition: 0.2s;
		-webkit-transition: 0.2s;
		transition: 0.2s;
	}

	.stream-box:hover {
		opacity: 0.8;
	}

	.stream-icon {
		background-color: rgba(0, 0, 0, 0.5);
		border-radius: 50%;
		box-shadow: 0 2px 25px rgba(0, 0, 0, 0.5);
		color: #fff;
		display: block;
		font-size: 27px;
		height: 60px;
		line-height: 1;
		margin-left: -30px;
		margin-top: -30px;
		padding-top: .6em;
		position: absolute;
		left: 50%;
		top: 50%;
		text-align: center;
		width: 60px;
	}

	.stream-byline {
		border-bottom: 1px solid<?php echo $colors['content']['border_color']; ?>;
		padding-bottom: 4px;
	}

	.stream-byline .md-icon-plus {
		color: #22a340;
	}

	.stream-byline-avatar {
		position: relative;
		top: 3px;
	}

	.stream-byline .md-icon-share {
		color: green;
	}

	.stream-loop .post-edit-link {
		border-bottom: 0;
		float: right;
	}

	.stream-byline .stream-byline-pinned {
		color: <?php echo $colors['site']['button']; ?>;
		font-size: 0.85em;
		font-weight: <?php echo $bold; ?>;
		margin-bottom: <?php echo $small; ?>px;
		text-transform: uppercase;
	}

	/* ACTIVITY */

	.stream-activity .stream-byline {
		font-style: italic;
	}

	.stream-activity .share {
		border-top: 1px solid<?php echo $colors['content']['border_color']; ?>;
		margin-top: <?php echo $half; ?>px;
		padding-top: <?php echo $half; ?>px;
	}

	/* SINGLE */

	.single-stream .comments {
		max-width: 100%;
		padding-bottom: 0;
	}

	.single-stream .comment-form {
		margin-bottom: 0;
	}

	/* WIDGET */

	<?php
		$widget_fs = round( $typography['sidebar']['font_size']['desktop'] * 0.9 );
		$widget_lh = round( $typography['sidebar']['line_height']['desktop'] * 0.85 );
	?>

	.stream-widget {
		background-color: <?php echo $colors['content']['bg_color']; ?>;
		border-radius: 3px;
		box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
		color: <?php echo $colors['site']['text']; ?>;
	}

	.stream-widget .stream-columns > .col1 {
		width: 20%;
	}

	.stream-widget .stream-columns > .col2 {
		width: 80%;
	}

	.stream-widget-head {
		border-bottom: 1px solid<?php echo $colors['content']['border_color']; ?>;
		margin-bottom: <?php echo $half; ?>px;
		padding: <?php echo $half; ?>px;
	}

	.stream-widget-head .stream-stats {
		color: <?php echo $colors['site']['text-sec']; ?>;
		font-size: 0.9em;
	}

	.stream-widget .stream-loop:before {
		background-color: <?php echo $colors['content']['border_color']; ?>;
		margin-left: 0;
		left: <?php echo $half + $small; ?>px;
	}

	.stream-widget-post {
		padding: <?php echo $third; ?>px;
		padding-left: <?php echo $mid + $small; ?>px;
		position: relative;
	}

	.stream-widget-post:first-child {
		padding-top: 0;
	}

	.stream-widget-post:not(:last-child) {
		border-bottom: 1px solid<?php echo $colors['content']['border_color']; ?>;
	}

	.stream-widget-icon {
		background-color: <?php echo $colors['site']['primary']; ?>;
		border-radius: 50%;
		display: block;
		color: #fff;
		font-size: 16px;
		height: 30px;
		line-height: 1;
		padding-top: 6px;
		position: absolute;
		left: 8px;
		top: <?php echo $half; ?>px;
		text-align: center;
		width: 30px;
	}

	.stream-widget-post:first-child .stream-widget-icon {
		top: 0;
	}

	.stream-widget-title {
		font-weight: <?php echo $bold; ?>
	}

	.widget_md_stream_widget .sidebar-title {
		margin-bottom: <?php echo $small; ?>px;
	}

	.stream-widget .stream-widget-title a, .stream-widget .stream-byline-date a {
		border-bottom: 0;
	}

	.stream-widget-text {
		font-size: <?php echo $widget_fs; ?>px;
		line-height: <?php echo $widget_lh; ?>px;
	}

	.stream-widget-text ul, .stream-widget-text ol, .stream-widget-text p {
		margin-bottom: <?php echo $widget_lh; ?>px;
	}

	.stream-widget-text ul, .stream-widget-text ol {
		margin-left: <?php echo $widget_lh; ?>px;
	}

	.stream-widget-byline {
		border-top: 1px solid<?php echo $colors['content']['border_color']; ?>;
		font-size: <?php echo round( $widget_fs * 0.9 ); ?>px;
		line-height: <?php echo round( $widget_lh * 1.1 ); ?>px;
		padding-top: 6px;
	}

	.stream-widget .share-button {
		line-height: 1;
	}

	.stream-widget .share-text {
		font-size: 0.9em;
	}

	.stream-widget .share-icon {
		height: auto;
		font-size: 0.8em;
	}

	.stream-widget-more {
		background-color: rgba(0, 0, 0, 0.02);
		border-top: 1px solid<?php echo $colors['content']['border_color']; ?>;
		line-height: 1;
		padding: <?php echo $half; ?>px;
		text-align: right;
	}

	/* QUERIES */

	@media all and (max-width: 900px) {
		.loop-stream .breadcrumbs {
			padding-left: 0;
			padding-top: 0;
			padding-right: 0;
		}

		.stream-widget .stream-columns > .col1 {
			width: 12%;
		}

		.stream-widget .stream-columns > .col2 {
			width: 88%;
		}

		.loop-stream {
			padding: <?php echo $half; ?>px;
		}

		.stream-head-text {
			font-size: 0.85em;
			line-height: 1.5em;
		}

		.stream-item {
			margin-bottom: <?php echo $half; ?>px;
			padding: <?php echo $half; ?>px;
		}
	}
