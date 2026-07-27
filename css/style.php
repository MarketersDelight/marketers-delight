<style type="text/css"><?php echo '
/*
	Theme Name: Marketers Delight
	Theme URI: https://marketersdelight.com/
	Author: Alex, Kolakube
	Author URI: https://kolakube.com/
	Description: Start a website that delights. Marketers Delight adds powerful content marketing and design tools to make publishing on your WordPress website fun and productive. Extend your website\'s features with Drop-ins and develop your own layouts with MD development tools.
	GitHub Theme URI: https://github.com/MarketersDelight/marketers-delight
	Text Domain: md
	Version: ' . MD_VERSION . '
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
	background-color: var(--md-color-background);
	color: var(--md-color-text);
	font-family: <?php echo $typography['body']['font_family']; ?>;
	font-size: var(--md-font-size);
	font-weight: <?php echo $font_weight; ?>;
	line-height: var(--md-line-height);
}
