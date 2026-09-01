<style type="text/css"><?php
if ( ! empty( $style_header ) )
	echo $style_header . "\n";

echo '
/*
	Table of contents:' . $style_guide .
'*/';

if ( locate_template( 'css/fonts.php' ) )
	include locate_template( 'css/fonts.php' );

if ( locate_template( 'css/font-icons.php' ) )
	include locate_template( 'css/font-icons.php' );

include locate_template( 'css/--vars.php' );
?>

*, *:before, *:after {
	box-sizing: border-box;
	margin: 0;
	padding: 0;
}

body {
	background-color: var(--md-site-background);
	color: var(--md-text);
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-size: var(--md-font-size);
	font-weight: <?php echo $font_weight; ?>;
	line-height: var(--md-line-height);
}
