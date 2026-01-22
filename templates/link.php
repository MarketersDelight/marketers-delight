<?php
/**
 * Customize the output of a link or button displayed from any MD button settings page
 * or the md_get_link and md_link() function with passed data.
 *
 * @since 6.0
 */

$h = 'span';
$classes = array( 'link' );
$has_wrap = $fields['name'] && $fields['subtitle'] && $fields['icon'] ? true : false;

if ( $fields['type'] == 'url' && $fields['url'] ) {
	$h = 'a';
	$attrs .= ' href="' . esc_url( $fields['url'] ) . '"';
	$attrs .= isset( $fields['target']['new'] ) ? ' target="_blank"' : '';
}
elseif ( $fields['type'] == 'phone' ) {
	$h = 'a';
	$attrs .= ' href="tel:' . esc_html( $fields['phone'] ) . '"';
	$classes[] = $fields['icon'] = 'phone';

	if ( empty( $fields['name'] ) )
		$fields['name'] = esc_html( $phone );
}
elseif ( $fields['type'] == 'popup' && $fields['popup'] && function_exists( 'md_popup' ) ) {
	$attrs .= ' data-popup="' . $fields['popup'] . '"';
	$classes[] = 'popup-trigger';
	md_popup( array( 'id' => $fields['popup'] ) );
}

if ( $fields['style'] == 'button' ) {
	$classes[] = 'button';

	if ( $fields['size'] )
		$classes[] = 'button-' . $fields['size'];

	if ( $fields['button_style'] )
		foreach ( $fields['button_style'] as $button_style => $val )
			$classes[] = 'button-' . $button_style;

	if ( $fields['color'] )
		if ( in_array( 'outline', $fields['button_style'] ) )
			$styles['color'] = $styles['border_color'] = $fields['color'];
		else
			$styles['bg_color'] = $fields['color'];

}
elseif ( $fields['color'] )
	$styles['color'] = $fields['color'];

if ( $fields['toggle']['hide_label'] )
	$classes[] = 'hide-label';

if ( $fields['toggle']['hide_label_mobile'] )
	$classes[] = 'hide-label-mobile';

$attrs .= md_style( $styles );

if ( $has_wrap )
	$classes[] = 'link-wrap';

if ( $fields['display'] )
	$classes[] = 'show-' . $fields['display'];

if ( $fields['classes'] )
	$classes[] = $fields['classes'];

$classes = join( ' ', $classes );

$attrs .= ' class="' . esc_attr( $classes ) . '"';

$html =
	"<$h{$attrs}>".
	( $fields['icon'] ? md_icon( $fields['icon'], array( 'classes' => 'link-icon' ) ) : '' ).
	( $has_wrap ? '<span class="wrap">' : '' ).
	( $fields['name'] ? '<span class="link-name">' . md_text_field( $fields['name'] ) . '</span>' : '' ).
	( $fields['subtitle'] ? '<span class="link-subtitle">' . md_text_field( $fields['subtitle'] ) . '</span>' : '' ).
	( $has_wrap ? '</span>' : '' ).
	"</$h>";