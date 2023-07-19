<style type="text/css">

/*------------------------------*\
	$SPACERS
\*------------------------------*/

.mt-none { margin-top: 0 !important; }
.mr-none { margin-right: 0; }
.mb-none { margin-bottom: 0 !important; }
.ml-none { margin-left: 0; }

/* QUAD */

.mt-quad:not(:last-child) { margin-top: <?php echo $quad; ?>px; }
.mb-quad:not(:last-child) { margin-bottom: <?php echo $quad; ?>px; }

/* TRIPLE */

.mt-triple:not(:last-child) { margin-top: <?php echo $triple; ?>px; }
.mb-triple:not(:last-child) { margin-bottom: <?php echo $triple; ?>px; }

/* DOUBLE */

.mt-double { margin-top: <?php echo $double; ?>px; }
.mr-double { margin-right: <?php echo $double; ?>px; }
.mb-double:not(:last-child) { margin-bottom: <?php echo $double; ?>px; }

/* MID */

.mt-mid { margin-top: <?php echo $mid; ?>px; }
.mb-mid:not(:last-child) { margin-bottom: <?php echo $mid; ?>px; }

/* SINGLE */

.mt-single { margin-top: <?php echo $single; ?>px; }
.mr-single { margin-right: <?php echo $single; ?>px; }
.mb-single:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }

/* HALF */

.mt-half { margin-top: <?php echo $half; ?>px; }
.mr-half { margin-right: <?php echo $half; ?>px; }
.mb-half:not(:last-child) { margin-bottom: <?php echo $half; ?>px; }

/* SMALL */

.mt-small { margin-top: <?php echo $small; ?>px; }
.mr-small { margin-right: <?php echo $small; ?>px; }
.mb-small:not(:last-child) { margin-bottom: <?php echo $small; ?>px; }
.ml-small { margin-left: <?php echo $small; ?>px; }

/* WRAPS */

@media all and (min-width: 900px) {
	.aligncenter.wrap, .alignleft.wrap, .alignfull { margin-left: -<?php echo $mid; ?>px; }
	.aligncenter.wrap, .alignright.wrap, .alignfull { margin-right: -<?php echo $mid; ?>px; }

	.aligncenter.wrap-small, .alignleft.wrap-small, .alignwide { margin-left: -<?php echo $single; ?>px; }
	.aligncenter.wrap-small, .alignlright.wrap-small, .alignwide { margin-right: -<?php echo $single; ?>px; }

	.content-full .aligncenter.wrap, .content-full .alignleft.wrap, .content-full .alignfull { margin-left: -<?php echo $breakout; ?>%; }
	.content-full .aligncenter.wrap, .content-full .alignright.wrap, .content-full .alignfull { margin-right: -<?php echo $breakout; ?>%; }

	.content-full .aligncenter.wrap-small, .content-full .alignleft.wrap-small, .content-full .alignwide { margin-left: -<?php echo $quad; ?>px; }
	.content-full .aligncenter.wrap-small, .content-full .alignright.wrap-small, .content-full .alignwide { margin-right: -<?php echo $quad; ?>px; }
}

@media all and (max-width: 900px) {
	/* TRIPLE */
	.mt-quad:not(:last-child) { margin-top: <?php echo $triple; ?>px; }
	.mb-quad:not(:last-child) { margin-bottom: <?php echo $triple; ?>px; }
	/* DOUBLE */
	.mt-triple:not(:last-child) { margin-top: <?php echo $double; ?>px; }
	.mb-triple:not(:last-child) { margin-bottom: <?php echo $double; ?>px; }
	/* WRAPS */
	.alignright.wrap { margin-right: -<?php echo $half; ?>px; }
	.alignleft.wrap { margin-left: -<?php echo $half; ?>px; }
	.alignfull, .aligncenter.wrap, .alignfull {
		margin-left: -<?php echo $half; ?>px;
		margin-right: -<?php echo $half; ?>px;
	}
}

@media all and (max-width: 800px) {
	/* DOUBLE */
	.mt-quad:not(:last-child) { margin-top: <?php echo $double; ?>px; }
	.mb-quad:not(:last-child) { margin-bottom: <?php echo $double; ?>px; }
	/* SINGLE */
	.mt-triple:not(:last-child) { margin-top: <?php echo $single; ?>px; }
	.mb-triple:not(:last-child), .mb-double:not(:last-child) { margin-bottom: <?php echo $single; ?>px; }
	/* ALIGN */
	.alignright.wrap { margin-left: -<?php echo $half; ?>px; }
	.alignleft.wrap { margin-right: -<?php echo $half; ?>px; }
	.alignright, .alignleft {
		clear: both;
		display: block;
		float: none;
		margin-left: auto;
		margin-right: auto;
		text-align: center;
	}
}