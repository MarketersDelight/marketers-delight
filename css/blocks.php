<style type="text/css">

/*------------------------------*\
	$BLOCKS
\*------------------------------*/

.block { padding: <?php echo $single; ?>px <?php echo $double; ?>px; }

/* HALF */

.block-half, .frame { padding: <?php echo $half; ?>px; }

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

/* SINGLE */

.block-single, .tagcloud, .note, .alert { padding: <?php echo $single; ?>px; }

.block-single-tb {
	padding-bottom: <?php echo $single; ?>px;
	padding-top: <?php echo $single; ?>px;
}

.block-single-lr {
	padding-left: <?php echo $single; ?>px;
	padding-right: <?php echo $single; ?>px;
}

.block-single-top, .block-full-content { padding-top: <?php echo $single; ?>px; }

.block-single-bot { padding-bottom: <?php echo $single; ?>px; }

/* MID */

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

/* DOUBLE */

.block-double { padding: <?php echo $double; ?>px; }

.block-double-tb {
	padding-bottom: <?php echo $double; ?>px;
	padding-top: <?php echo $double; ?>px;
}

.block-double-lr {
	padding-left: <?php echo $double; ?>px;
	padding-right: <?php echo $double; ?>px;
}

.block-double-top, .block-full-top { padding-top: <?php echo $double; ?>px; }

.block-double-bot, .block-full-content { padding-bottom: <?php echo $double; ?>px; }

.block-double-content { padding: <?php echo $single; ?>px <?php echo $double; ?>px <?php echo $double; ?>px; }

/* TRIPLE */

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

.block-triple-double { padding: <?php echo $triple; ?>px <?php echo $double; ?>px; }

/* QUAD */

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

.block-full-quad {
	padding-bottom: <?php echo $triple; ?>px;
	padding-top: <?php echo $quad; ?>px;
}

/* NONE */

.pt-none { padding-top: 0; }
.pr-none { padding-right: 0; }
.pb-none { padding-bottom: 0; }
.pl-none { padding-left: 0; }

@media all and (min-width: <?php echo $site_width; ?>px) {
	.close-on-desktop { display: none !important; }
}

@media all and (min-width: 900px) {
	.block-full {
		padding-bottom: <?php echo $double; ?>px;
		padding-top: <?php echo $double; ?>px;
	}
	[class*="block-full"] {
		padding-left: <?php echo $breakout_full; ?>%;
		padding-right: <?php echo $breakout_full; ?>%;
	}
}

@media all and (max-width: <?php echo $site_width; ?>px) {
	.close-on-max { display: none; }
	.block-full-top { padding-top: <?php echo $double; ?>px; }
}

@media all and (max-width: 900px) {
	/* TRIPLE */
	.block-full, .block-quad { padding: <?php echo $triple; ?>px; }
	.block-quad-tb, .block-full-quad {
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
	.block-triple, .block-triple-double { padding: <?php echo $double; ?>px; }
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
	/* SINGLE */
	.sidebar {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
}

@media all and (max-width: 700px) {
	/* SINGLE + HALF */
	.block-quad { padding: <?php echo $single; ?>px <?php echo $half; ?>px; }
	.block-triple, .block-triple-double, .block-double, .block-mid, .block-double-content, .block { padding: <?php echo $single; ?>px; }
	.block-quad-tb, .block-triple, .block-triple-tb, .block-double-tb, .block-double {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
	.block-full {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $single; ?>px;
	}
	.block-full-quad {
		padding-bottom: <?php echo $single; ?>px;
		padding-top: <?php echo $double; ?>px;
	}
	[class*="block-full"], .block-quad-lr, .block-triple, .block-triple-lr, .block-triple-double, .block-double, .block-double-content, .block-double-lr, .block-single-lr, .block, .block-single, .tagcloud, .note, .alert {
		padding-left: <?php echo $half; ?>px;
		padding-right: <?php echo $half; ?>px;
	}
	.block-quad-top, .block-triple-top, .block-double-top { padding-top: <?php echo $single; ?>px; }
	.block-quad-bot, .block-triple-bot, .block-double-bot { padding-bottom: <?php echo $single; ?>px; }
}