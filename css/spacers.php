<style type="text/css">

/*------------------------------*\
	$SPACERS
\*------------------------------*/

/*--------------------*\
	$ALIGNMENTS
\*--------------------*/

.alignleft, .alignright, .aligncenter, .alignnone {
	display: block;
	position: relative;
	margin-bottom: <?php echo $single; ?>px;
}

.alignleft {
	float: left;
	margin-right: <?php echo $half; ?>px;
}

.alignright {
	float: right;
	margin-left: <?php echo $half; ?>px;
}

.alignwide, .alignfull { max-width: initial; }

.alignwide img, .alignfull img { width: 100%; }

.aligncenter {
	clear: both;
	float: none;
	margin-left: auto;
	margin-right: auto;
	text-align: center;
}

.alignnone {
	clear: both;
	float: none;
}

.width-full {
	clear: both;
	display: block;
	width: 100%;
}

.display-block { display: block; }

.auto {
	margin-left: auto;
	margin-right: auto;
}


/*--------------------*\
	$SMALL
\*--------------------*/

/* SPACERS */

.mt-small { margin-top: <?php echo $small; ?>px; }
.mr-small { margin-right: <?php echo $small; ?>px; }
.mb-small:not(:last-child) { margin-bottom: <?php echo $small; ?>px; }
.ml-small { margin-left: <?php echo $small; ?>px; }


/*--------------------*\
	$HALF
\*--------------------*/

/* SPACERS */

.mt-half { margin-top: <?php echo $half; ?>px; }

.mr-half { margin-right: <?php echo $half; ?>px; }

.mb-half:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

/* BLOCKS */

.block-half { padding: <?php echo $half; ?>px; }

.block-half-top { padding-top: <?php echo $half; ?>px; }

.block-half-bot { padding-bottom: <?php echo $half; ?>px; }

.block-half-tb {
	padding-bottom: <?php echo $half; ?>px;
	padding-top: <?php echo $half; ?>px;
}

.block-half-lr {
	padding-left: <?php echo $half; ?>px;
	padding-right: <?php echo $half; ?>px;
}


/*--------------------*\
	$SINGLE
\*--------------------*/

/* SPACERS */

.mt-single { margin-top: <?php echo $single; ?>px; }

.mr-single { margin-right: <?php echo $single; ?>px; }

.mb-single:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

/* BLOCKS */

.block-single, .tagcloud, .note, .alert { padding: <?php echo $single; ?>px; }

.block-single-tb {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.block-single-lr {
	padding-left: <?php echo $single; ?>px;
	padding-right: <?php echo $single; ?>px;
}

.block-single-top { padding-top: <?php echo $single; ?>px; }

.block-single-bot { padding-bottom: <?php echo $single; ?>px; }


/*--------------------*\
	$MID
\*--------------------*/

/* BLOCKS */

.block-mid { padding: <?php echo $mid; ?>px; }

.block-mid-tb {
	padding-bottom: <?php echo $mid; ?>px;
	padding-top: <?php echo $mid; ?>px;
}

.block-mid-lr {
	padding-left: <?php echo $mid; ?>px;
	padding-right: <?php echo $mid; ?>px;
}

.block-mid-top { padding-top: <?php echo $mid; ?>px; }

.block-mid-bot { padding-bottom: <?php echo $mid; ?>px; }

/* SPACERS */

.mt-mid { margin-top: <?php echo $mid; ?>px; }
.mb-mid:not(:last-child) { margin-bottom: <?php echo $mid; ?>px; }


/*--------------------*\
	$DOUBLE
\*--------------------*/

/* BLOCKS */

.block-double { padding: <?php echo $double; ?>px; }

.block-double-tb {
	padding-bottom: <?php echo $double; ?>px;
	padding-top: <?php echo $double; ?>px;
}

.block-double-lr {
	padding-left: <?php echo $double; ?>px;
	padding-right: <?php echo $double; ?>px;
}

.block-double-top { padding-top: <?php echo $double; ?>px; }

.block-double-bot { padding-bottom: <?php echo $double; ?>px; }

/* SPACERS */

.mt-double { margin-top: <?php echo $double; ?>px; }
.mr-double { margin-right: <?php echo $double; ?>px; }
.mb-double:not(:last-child) { margin-bottom: <?php echo $double; ?>px; }


/*--------------------*\
	$TRIPLE
\*--------------------*/

/* BLOCKS */

.block-triple { padding: <?php echo $triple; ?>px; }

.block-triple-tb {
	padding-bottom: <?php echo $triple; ?>px;
	padding-top: <?php echo $triple; ?>px;
}

.block-triple-lr {
	padding-left: <?php echo $triple; ?>px;
	padding-right: <?php echo $triple; ?>px;
}

.block-triple-top { padding-top: <?php echo $triple; ?>px; }

.block-triple-bot { padding-bottom: <?php echo $triple; ?>px; }

/* SPACERS */

.mt-triple:not(:last-child) { margin-top: <?php echo $triple; ?>px; }
.mb-triple:not(:last-child) { margin-bottom: <?php echo $triple; ?>px; }


/*--------------------*\
	$QUAD
\*--------------------*/

/* BLOCKS */

.block-quad { padding: <?php echo $quad; ?>px; }

.block-quad-tb {
	padding-bottom: <?php echo $quad; ?>px;
	padding-top: <?php echo $quad; ?>px;
}

.block-quad-lr {
	padding-left: <?php echo $quad; ?>px;
	padding-right: <?php echo $quad; ?>px;
}

.block-quad-top { padding-top: <?php echo $quad; ?>px; }

.block-quad-bot { padding-bottom: <?php echo $quad; ?>px; }

/* SPACERS */

.mt-quad:not(:last-child) { margin-top: <?php echo $quad; ?>px; }
.mb-quad:not(:last-child) { margin-bottom: <?php echo $quad; ?>px; }


/*--------------------*\
	$NONE
\*--------------------*/

/* BLOCKS */

.pt-none { padding-top: 0; }
.pr-none { padding-right: 0; }
.pb-none { padding-bottom: 0; }
.pl-none { padding-left: 0; }

/* SPACERS */

.mt-none { margin-top: 0 !important; }
.mr-none { margin-right: 0; }
.mb-none { margin-bottom: 0 !important; }
.ml-none { margin-left: 0; }






@media all and (min-width: 900px) {
	[class*="block-full"] {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
}

@media all and (max-width: 900px) {
	/* TRIPLE */
	.block-quad { padding: <?php echo $triple; ?>px; }
	.block-quad-tb {
		padding-bottom: <?php echo $triple; ?>px;
		padding-top: <?php echo $triple; ?>px;
	}
	.block-quad-top { padding-top: <?php echo $triple; ?>px; }
	.block-quad-bot { padding-bottom: <?php echo $triple; ?>px; }
	[class*="block-full"], .block-quad-lr {
		padding-left: <?php echo $triple; ?>px;
		padding-right: <?php echo $triple; ?>px;
	}
	/* DOUBLE */
	.block-triple { padding: <?php echo $double; ?>px; }
	.block-triple-tb {
		padding-bottom: <?php echo $double; ?>px;
		padding-top: <?php echo $double; ?>px;
	}
	.block-triple-lr {
		padding-left: <?php echo $double; ?>px;
		padding-right: <?php echo $double; ?>px;
	}
	.block-triple-top { padding-top: <?php echo $double; ?>px; }
	.block-triple-bot { padding-bottom: <?php echo $double; ?>px; }
}

@media all and (min-width: 800px) {
	.aligncenter.wrap, .alignleft.wrap, .alignfull { margin-left: -<?php echo $mid; ?>px; }
	.aligncenter.wrap, .alignright.wrap, .alignfull { margin-right: -<?php echo $mid; ?>px; }

	.aligncenter.wrap-small, .alignleft.wrap-small, .alignwide { margin-left: -<?php echo $single; ?>px; }
	.aligncenter.wrap-small, .alignlright.wrap-small, .alignwide { margin-right: -<?php echo $single; ?>px; }

	.content-full .aligncenter.wrap, .content-full .alignleft.wrap, .content-full .alignfull { margin-left: -<?php echo $breakout; ?>%; }
	.content-full .aligncenter.wrap, .content-full .alignright.wrap, .content-full .alignfull { margin-right: -<?php echo $breakout; ?>%; }

	.content-full .aligncenter.wrap-small, .content-full .alignleft.wrap-small, .content-full .alignwide { margin-left: -<?php echo $quad; ?>px; }
	.content-full .aligncenter.wrap-small, .content-full .alignright.wrap-small, .content-full .alignwide { margin-right: -<?php echo $quad; ?>px; }
}

@media all and (max-width: 800px) {
	/* ALIGNMENTS */
	.alignright, .alignleft, .wp-block-image .alignleft, .wp-block-image .alignright {
		clear: both;
		display: block;
		float: none;
		margin-left: auto;
		margin-right: auto;
		text-align: center;
	}
	.wp-block-image .aligncenter > figcaption, .wp-block-image .alignleft > figcaption, .wp-block-image .alignright > figcaption { display: block; }
	/* TRIPLE */
	.mt-quad:not(:last-child) { margin-top: <?php echo $triple; ?>px; }
	.mb-quad:not(:last-child) { margin-bottom: <?php echo $triple; ?>px; }
	/* DOUBLE */
	.mt-triple:not(:last-child) { margin-top: <?php echo $double; ?>px; }
	.mb-triple:not(:last-child) { margin-bottom: <?php echo $double; ?>px; }
	/* WRAPS */
	.alignwide, .alignfull,
	.alignleft.wrap, .alignright.wrap, .aligncenter.wrap {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (max-width: 700px) {
	/* SINGLE + HALF */
	.block-quad { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }
	.block-triple, .block-double, .block-mid { padding: <?php echo $single; ?>px; }
	.block-quad-tb, .block-triple, .block-triple-tb, .block-double-tb, .block-double {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
	[class*="block-full"], .block-quad-lr, .block-triple, .block-triple-lr, .block-double, .block-double-lr, .block-single-lr, .block-single, .tagcloud, .note, .alert {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
	.block-quad-top, .block-triple-top, .block-double-top { padding-top: <?php echo $single; ?>px; }
	.block-quad-bot, .block-triple-bot, .block-double-bot { padding-bottom: <?php echo $single; ?>px; }
	/* DOUBLE */
	.mt-quad:not(:last-child) { margin-top: <?php echo $double; ?>px; }
	.mb-quad:not(:last-child) { margin-bottom: <?php echo $double; ?>px; }
	/* SINGLE */
	.mt-triple:not(:last-child) { margin-top: <?php echo $single; ?>px; }
	.mb-triple:not(:last-child), .mb-double:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
	/* ALIGN */
	.alignright, .alignleft {
		clear: both;
		display: block;
		float: none;
		margin-left: auto;
		margin-right: auto;
		text-align: center;
	}
}
