<?php
/**
 * Would you believe all the things a link can do? Customize the output
 * of a link or button displayed from any MD button settings page or
 * the md_get_link and md_link() function with passed data.
 *
 * @since 6.0
 */

$h = 'span';
$classes = array( 'link' );
$has_wrap = $fields['name'] && $fields['subtitle'];

// Plain link

if ( $fields['type'] == 'url' && $fields['url'] ) {
	$h = 'a';
	$attrs .= ' href="' . esc_url( $fields['url'] ) . '"';
	$attrs .= isset( $fields['settings']['new'] ) ? ' target="_blank"' : '';
}

// Phone number

elseif ( $fields['type'] == 'phone' ) {
	$h = 'a';
	$attrs .= ' href="tel:' . esc_html( $fields['phone'] ) . '"';
	$classes[] = $fields['icon'] = 'phone';

	if ( empty( $fields['name'] ) )
		$fields['name'] = esc_html( $phone );
}

// Popup

elseif ( $fields['type'] == 'popup' && $fields['popup'] && function_exists( 'md_popup' ) ) {
	$attrs .= ' data-popup="' . $fields['popup'] . '"';
	$classes[] = 'popup-trigger';
	md_popup( array( 'id' => $fields['popup'] ) );
}

// Turn link into a button

if ( $fields['style'] == 'button' ) {
	$classes[] = 'button';

	if ( $fields['size'] )
		$classes[] = 'button-' . $fields['size'];

	if ( $fields['button_style'] )
		foreach ( $fields['button_style'] as $button_style => $val )
			if ( $val )
				$classes[] = "button-$button_style";

	if ( $fields['color'] ) {
		if ( ! empty( $fields['button_style']['outline'] ) ) {
			if ( $class = md_color_class( $fields['color'], 'color' ) ) {
				$classes[] = $class;
				$classes[] = md_color_class( $fields['color'], 'border_color' );
			}
			elseif ( $hex = md_color_hex( $fields['color'] ) )
				$styles['color'] = $styles['border_color'] = $hex;
		}
		else {
			if ( $class = md_color_class( $fields['color'], 'bg_color' ) )
				$classes[] = $class;
			elseif ( $hex = md_color_hex( $fields['color'] ) )
				$styles['bg_color'] = $hex;
		}
	}

}

// Set a color on regular links

elseif ( $fields['color'] ) {
	if ( $class = md_color_class( $fields['color'] ) )
		$classes[] = $class;
	elseif ( $hex = md_color_hex( $fields['color'] ) )
		$styles['color'] = $hex;
}

// Visibility options

if ( ! empty( $fields['toggle']['hide_label'] ) )
	$classes[] = 'hide-label';

if ( ! empty( $fields['toggle']['hide_label_mobile'] ) )
	$classes[] = 'hide-label-mobile';

foreach ( md_get_visibility_classes( $fields['visibility'] ) as $class )
	$classes[] = $class;

if ( $fields['icon'] )
	$classes[] = 'has-icon';

if ( ! empty( $fields['settings']['icon_end'] ) )
	$classes[] = 'reverse';

if ( $fields['classes'] )
	$classes = array_merge( $classes, (array) $fields['classes'] );

// Finally, render link with its final attributes

$attrs .= ' class="' . esc_attr( join( ' ', $classes ) ) . '"';
$attrs .= md_style( $styles );

$html =
	"<$h{$attrs}>".
	( $fields['icon'] ? md_icon( $fields['icon'], array( 'classes' => 'link-icon' ) ) : '' ).
	( $has_wrap ? '<span class="link-wrap">' : '' ).
	( $fields['name'] ? '<span class="link-name">' . wp_kses_post( $fields['name'] ) . '</span>' : '' ).
	( $fields['subtitle'] ? '<span class="link-subtitle">' . wp_kses_post( $fields['subtitle'] ) . '</span>' : '' ).
	( $has_wrap ? '</span>' : '' ).
	"</$h>";