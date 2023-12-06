<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Render an MD font icon.
 *
 * @since 5.2.3
 */

function md_icon( $icon, $args = null ) {
	$style = array();
	$title = '';
	$classes[] = "md-icon-{$icon}";

	if ( isset( $args['classes'] ) )
		$classes[] = $args['classes'];

	if ( isset( $args['color'] ) )
		$style['color'] = $args['color'];

	if ( isset( $args['title'] ) )
		$title = ' title="' . esc_attr( $args['title'] ) . '"';

	$classes = join( ' ', $classes );

	if ( is_bool( $args ) )
		return esc_attr( $classes );

	return '<i class="' . esc_attr( $classes ) . '"' . md_style( $style ) . $title . '></i>';
}

/**
 * Get Icons data in various formats.
 *
 * @since 5.0
 */

function md_get_icons( $sort = null, $show_defaults = null, $prefix = null ) {
	$icons = array();
	$prefix = isset( $prefix ) ? $prefix : '';

	foreach ( md_icons( $show_defaults ) as $icon => $fields ) {
		$icon = "$prefix{$icon}";
		if ( isset( $fields['label'] ) )
			$icons['options'][$icon] = $fields['label'];
		$icons['ids'][] = $icon;
	}

	if ( isset( $sort ) )
		$icons = $icons[$sort];

	return $icons;
}

/**
 * Get MD font icons URL.
 *
 * @since 5.2.3
 */

function md_font_icons_url() {
	$file = MD_URL . 'lib/design/icons/md.woff';

	if ( file_exists( get_stylesheet_directory() . '/md.woff' ) )
		$file = get_stylesheet_directory_uri() . '/md.woff';

	return $file;
}

/**
 * Print icons CSS styles by class names.
 *
 * @since 5.6
 */

function md_icons_css() {
	foreach ( md_icons() as $icon => $fields ) {
		if ( ! isset( $fields['unicode'] ) ) continue;
		$selectors = '';
		if ( isset( $fields['classes'] ) )
			foreach ( $fields['classes'] as $selector )
				$selectors .= ",{$selector}:before";
		echo '.md-icon-' . $icon . ":before{$selectors}{content:'\\" . $fields['unicode'] . '\'}';
	}
}

/**
 * Return a list of MD icons. Read documentation and see how to
 * filter in your own icons:
 * https://marketersdelight.com/font-icons/
 *
 * @since 4.9.3
 */

function md_icons( $show_defaults = null ) {
	$icons = array(
		'angle-down' => array(
			'unicode' => 'e80e',
			'label' => __( 'Arrow Down', 'md' )
		),
		'angle-left' => array(
			'unicode' => 'e816',
			'label' => __( 'Arrow Left', 'md' )
		),
		'angle-right' => array(
			'unicode' => 'e80f',
			'label' => __( 'Arrow Right', 'md' )
		),
		'angle-up' => array(
			'unicode' => 'e817',
			'label' => __( 'Arrow Up', 'md' )
		),
		'bell' => array(
			'unicode' => 'f0f3',
			'label' => __( 'Bell', 'md' )
		),
		'bolt' => array(
			'unicode' => 'e823',
			'label' => __( 'Bolt', 'md' )
		),
		'book' => array(
			'unicode' => 'e828',
			'label' => __( 'Book', 'md' )
		),
		'cancel' => array(
			'unicode' => 'e810',
			'label' => __( 'Cancel (X)', 'md' )
		),
		'cart' => array(
			'unicode' => 'e825',
			'label' => __( 'Cart', 'md' )
		),
		'chat' => array(
			'unicode' => 'e811',
			'label' => __( 'Chat', 'md' )
		),
		'clock' => array(
			'unicode' => 'e812',
			'label' => __( 'Clock', 'md' )
		),
		'location' => array(
			'unicode' => 'e947',
			'label' => __( 'Location', 'md' )
		),
		'code' => array(
			'unicode' => 'f121',
			'label' => __( 'Code', 'md' )
		),
		'download' => array(
			'unicode' => 'e822',
			'label' => __( 'Download', 'md' )
		),
		'dribbble' => array(
			'unicode' => 'e80c',
			'label' => __( 'Dribbble', 'md' )
		),
		'exclamation' => array(
			'unicode' => 'f12a',
			'label' => __( 'Exclamation (!)', 'md' )
		),
		'eye' => array(
			'unicode' => 'e81e',
			'label' => __( 'Eye', 'md' )
		),
		'facebook' => array(
			'unicode' => 'f09a',
			'label' => __( 'Facebook', 'md' ),
			'classes' => array( '.md-icon-facebook-squared' )
		),
		'flickr' => array(
			'unicode' => 'e80a',
			'label' => __( 'Flickr', 'md' )
		),
		'github' => array(
			'unicode' => 'e802',
			'label' => __( 'GitHub', 'md' ),
			'classes' => array( '.md-icon-github-squared' )
		),
		'hand' => array(
			'unicode' => 'f256',
			'label' => __( 'Hand', 'md' )
		),
		'heart' => array(
			'unicode' => 'e821',
			'label' => __( 'Heart', 'md' )
		),
		'heart-empty' => array(
			'unicode' => 'e801',
			'label' => __( 'Heart Empty', 'md' )
		),
		'instagram' => array(
			'unicode' => 'e805',
			'label' => __( 'Instagram', 'md' ),
		),
		'key' => array(
			'unicode' => 'e82a',
			'label' => __( 'Key', 'md' )
		),
		'like' => array(
			'unicode' => 'e820',
			'label' => __( 'Like', 'md' )
		),
		'url' => array(
			'unicode' => 'e827',
			'label' => __( 'Link', 'md' )
		),
		'linkedin' => array(
			'unicode' => 'f0e1',
			'label' => __( 'LinkedIn', 'md' ),
			'classes' => array( '.md-icon-linkedin-squared' )
		),
		'loading' => array(
			'unicode' => 'e832',
			'label' => __( 'Loading', 'md' )
		),
		'mail-alt' => array(
			'unicode' => 'e814',
			'label' => __( 'Mail', 'md' )
		),
		'medium' => array(
			'unicode' => 'f23a',
			'label' => __( 'Medium', 'md' )
		),
		'menu' => array(
			'unicode' => 'e815',
			'label' => __( 'Menu', 'md' )
		),
		'ok' => array(
			'unicode' => 'e804',
			'label' => __( 'OK', 'md' ),
			'classes' => array( '.md-icon-ok-circled', '.list-check li' )
		),
		'pencil' => array(
			'unicode' => 'e80d',
			'label' => __( 'Pencil', 'md' )
		),
		'phone' => array(
			'unicode' => 'e806',
			'label' => __( 'Phone', 'md' )
		),
		'pin' => array(
			'unicode' => 'e803',
			'label' => __( 'Pin', 'md' )
		),
		'pinterest' => array(
			'unicode' => 'f231',
			'label' => __( 'Pinterest', 'md' ),
			'classes' => array( '.md-icon-pinterest-squared' )
		),
		'plus' => array(
			'unicode' => 'ea0a',
			'label' => __( 'Plus', 'md' )
		),
		'quote' => array(
			'unicode' => 'f10e',
			'label' => __( 'Quote', 'md' )
		),
		'reply' => array(
			'unicode' => 'f112',
			'label' => __( 'Reply', 'md' )
		),
		'rss' => array(
			'unicode' => 'f09e',
			'label' => __( 'RSS', 'md' )
		),
		'search' => array(
			'unicode' => 'e813',
			'label' => __( 'Search', 'md' )
		),
		'share' => array(
			'unicode' => 'e81c',
			'label' => __( 'Share', 'md' )
		),
		'skype' => array(
			'unicode' => 'f17e',
			'label' => __( 'Skype', 'md' )
		),
		'slack' => array(
			'unicode' => 'e83c',
			'label' => __( 'Slack', 'md' )
		),
		'speakerdeck' => array(
			'unicode' => 'e81f',
			'label' => __( 'SpeakerDeck', 'md' )
		),
		'star' => array(
			'unicode' => 'e81b',
			'label' => __( 'Star', 'md' )
		),
		'tags' => array(
			'unicode' => 'e829',
			'label' => __( 'Tags', 'md' )
		),
		'telegram' => array(
			'unicode' => 'e839',
			'label' => __( 'Telegram', 'md' ),
		),
		'tiktok' => array(
			'unicode' => 'e83a',
			'label' => __( 'TikTok', 'md' )
		),
		'trophy' => array(
			'unicode' => 'e824',
			'label' => __( 'Trophy', 'md' )
		),
		'tumblr' => array(
			'unicode' => 'e808',
			'label' => __( 'Tumblr', 'md' ),
			'classes' => array( '.md-icon-tumblr-squared' )
		),
		'twitter' => array(
			'unicode' => 'e800',
			'label' => __( 'Twitter', 'md' )
		),
		'user' => array(
			'unicode' => 'e819',
			'label' => __( 'User', 'md' )
		),
		'user-add' => array(
			'unicode' => 'e818',
			'label' => __( 'User Add', 'md' )
		),
		'vimeo' => array(
			'unicode' => 'e809',
			'label' => __( 'Vimeo', 'md' ),
			'classes' => array( '.md-icon-vimeo-squared' )
		),
		'volume-up' => array(
			'unicode' => 'e826',
			'label' => __( 'Volume Up', 'md' )
		),
		'whatsapp' => array(
			'unicode' => 'f232',
			'label' => __( 'WhatsApp', 'md' )
		),
		'wordpress' => array(
			'unicode' => 'e80b',
			'label' => __( 'WordPress', 'md' )
		),
		'youtube' => array(
			'unicode' => 'e807',
			'label' => __( 'YouTube', 'md' ),
			'classes' => array( '.md-icon-youtube-play' )
		)
	);

	$data = md_setting( array( 'icons', 'data' ), array() );
	$custom = md_setting( 'custom_icons', array() );

	if ( $show_defaults !== true && ! empty( $custom ) ) {
		foreach ( $icons as $icon => $fields )
			if ( ! in_array( $icon, $custom ) )
				unset( $icons[$icon] );
		$icons = array_merge( $icons, $data );
	}

	return $icons;
}
