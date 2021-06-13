<style type="text/css">

/*------------------------------*\
	$FOOTNOTES
\*------------------------------*/

.footnotes {
	border-top: 2px solid rgba(0, 0, 0, 0.2);
	margin-top: <?php echo $mid; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.footnote { transition: 0.3s; }

.footnote.footnote-show .footnote-text, .toggle-footnotes .footnote.footnote-show .footnote-text { display: block; }

.footnote .footnote-number {
	background-color: rgba(0, 0, 0, 0.2);
	border-radius: 2px;
	cursor: pointer;
	display: inline-block;
	font-size: 13px;
	font-weight: bold;
	height: 19px;
	line-height: 1;
	padding-top: 3px;
	position: relative;
		top: 4px;
	text-align: center;
	width: 23px;
}

.footnote-show .footnote-number, .footnote-number:hover { background-color: rgba(0, 0, 0, 0.25); }

.footnote-text {
	color: #444;
	cursor: default;
	font-size: 13px;
	font-style: normal;
	line-height: 21px;
	padding-bottom: 26px;
	padding-top: 26px;
	position: relative;
	text-align: left;
}

.footnote-text:before {
	background-color: rgba(0, 0, 0, 0.2);
	content: "\0020";
	display: block;
	height: 1px;
	margin-bottom: 16px;
	width: 26px;
}

.footnote-text-number { color: <?php echo $colors['site']['text-sec']; ?>; }

.footnote-show .footnote-triggers {
	position: absolute;
		top: 13px;
		right: 13px;
}

.footnote-show .footnote-trigger-list {
	border-bottom: none;
	color: #ccc;
	cursor: default;
	font-size: 16px;
	position: relative;
		top: -4px;
}

.footnote-show .footnote-trigger-close {
	color: #ccc;
	font-size: 28px;
	padding-left: 7px;
}

@media all and (min-width: <?php echo $site_width; ?>px) {
	.toggle-footnotes .footnote .footnote-text {
		background-color: rgba(0, 0, 0, 0.05);
		border-bottom: 1px solid rgba(0, 0, 0, 0.1);
		border-radius: 2px;
		display: none;
		margin-bottom: <?php echo $half; ?>px;
		margin-top: <?php echo $half; ?>px;
		padding-left: <?php echo $single; ?>px;
		padding-right: <?php echo $single; ?>px;
		position: static;
		width: auto;
	}
	.footnote .footnote-text {
		position: absolute;
			left: -<?php echo ( ( $single * 6 ) + $half ); ?>px;
			top: 0;
		width: <?php echo ( $single * 6 ); ?>px;
	}
	.footnote-text.right {
		left: auto;
		right: -<?php echo ( ( $single * 6 ) + $half ); ?>px;
	}
	.footnote-triggers { display: none; }
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.footnote-text { display: none; }
	.footnote-show .footnote-text {
		background-color: rgba(0, 0, 0, 0.05);
		border-bottom: 1px solid rgba(0, 0, 0, 0.1);
		border-radius: 2px;
		margin-bottom: <?php echo $half; ?>px;
		margin-top: <?php echo $half; ?>px;
		padding-left: <?php echo $single; ?>px;
		padding-right: <?php echo $single; ?>px;
	}
}