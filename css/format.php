<style type="text/css">

/*------------------------------*\
	$FORMAT
\*------------------------------*/

.format { word-wrap: break-word; }

.format a { text-decoration: underline; }

.format h1 a, .format h2 a, .format h3 a, .format h4 a, .format h5 a, .format h6 a, .format a:hover { text-decoration: none; }

.format ul, .format ol, .format p, .format hr,
.format table, .format blockquote, .format pre, .format .wp-caption,
.format .wp-block-image, .full .the-content .featured-image { margin-bottom: <?php echo $single; ?>px; }

.format ul { list-style: square; }

.format ul[class^="list"], .format [class^="list"] ul { list-style: none; }

.format li {
	margin-bottom: <?php echo $half; ?>px;
	position: relative;
}

.format ul ul {
	margin-bottom: <?php echo $half; ?>px;
	margin-left: <?php echo $half; ?>px;
}

.text-center [class^="list"], .text-center ul, .text-center ol { text-align: left; }

.the-content [class^="list"] { margin-left: 0; }

.the-content ul, .the-content ol, .the-content .list-check { margin-left: <?php echo $single; ?>px; }

.the-content h1, .the-content h2,
.the-content h3, .the-content h4,
.the-content h5, .the-content h6 {
	<?php if ( $colors['site']['text'] !== $colors['site']['headline'] ) : ?>
	color: <?php echo $colors['site']['headline']; ?>;
	<?php endif; ?>
	margin-bottom: <?php echo $half; ?>px;
	margin-top: <?php echo $mid; ?>px;
}

.the-content h1:first-child, .the-content h2:first-child,
.the-content h3:first-child, .the-content h4:first-child,
.the-content h5:first-child, .the-content h6:first-child { margin-top: 0; }

/* LISTS */

ul[class^="list"], [class^="list"] ul { list-style: none; }

.list > ul:not(:last-child), .list li:not(:last-child) {
	border-bottom: 1px solid rgba(0, 0, 0, 0.15);
	padding-bottom: <?php echo $half; ?>px;
}

.list-check { margin-left: <?php echo $single; ?>px; }

ul.list-check li:before {
	background-color: rgba(0, 0, 0, 0.08);
	border-radius: 50%;
	color: #22a340;
	margin-left: -<?php echo $single + $small + 2; ?>px;
	margin-right: <?php echo $small; ?>px;
	padding: <?php echo $small; ?>px;
}

/* SLIM */

.slim {
	font-size: <?php echo $typography['body']['font_size']['mobile']; ?>px;
	line-height: <?php echo $typography['body']['line_height']['mobile'] - 1; ?>px;
}

.slim ul, .slim ol, .slim p, .slim hr,
.slim table, .slim blockquote, .slim pre, .slim .wp-caption,
.slim .wp-block-image, .slim .the-content .featured-image { margin-bottom: <?php echo $half; ?>px; }

.slim ul, .slim ol { margin-left: <?php echo $half; ?>px; }

@media (min-width: <?php echo $post_width; ?>px) {
	.slim .title {
		font-size: <?php echo $typography['h4']['font_size']['desktop']; ?>px;
		line-height: <?php echo $typography['h4']['line_height']['desktop']; ?>px;
	}
}