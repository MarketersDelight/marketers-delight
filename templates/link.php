<?php
/**
 * Customize the output of a link or button displayed from any MD button settings page
 * or the md_get_link and md_link() function with passed data.
 *
 * @since 6.0
 */

$name = isset( $fields["{$p}name"] ) ? $fields["{$p}name"] : '';
$subtitle = isset( $fields["{$p}subtitle"] ) ? $fields["{$p}subtitle"] : '';
$icon = ! empty( $fields["{$p}icon"] ) ? $fields["{$p}icon"] : '';

if ( empty( $name ) && empty( $subtitle ) && empty( $icon ) )
	return;

$classes = $styles = array();
$h = 'span';
$class = $href = $target = $popup = '';
$parent = isset( $fields["{$p}builder_area"] ) ? $fields["{$p}builder_area"] : '';
$url = isset( $fields["{$p}url"] ) ? $fields["{$p}url"] : '';
$phone = isset( $fields["{$p}phone"] ) ? $fields["{$p}phone"] : '';
$style = isset( $fields["{$p}style"] ) ? $fields["{$p}style"] : 'link';
$type = isset( $fields["{$p}type"] ) ? $fields["{$p}type"] : 'url';
$icon_classes = 'link-icon';

if ( $parent )
	$classes[] = "{$parent}-link";

if ( ! empty( $fields["{$p}display"] ) )
	$classes[] = 'show-' . esc_attr( $fields["{$p}display"] );

if ( in_array( $type, array( 'url', 'link' ) ) && $url ) {
	$h = 'a';
	$href = ' href="' . esc_url( $url ) . '"';
	$target = ( isset( $fields["{$p}target"]['new'] ) ? ' target="_blank"' : '' );
}
elseif ( $type == 'phone' ) {
	$h = 'a';
	$href = ' href="tel:' . esc_attr( $phone ) . '"';
	$classes[] = $fields["{$p}icon"] = 'phone';

	if ( ! $text )
		$text = esc_attr( $phone );
}

if ( $style == 'button' ) {
	$button_color = '';
	$classes[] = 'button';

	if ( ! empty( $fields["{$p}size"] ) )
		$classes[] = 'button-' . $fields["{$p}size"];

	if ( ! empty( $fields["{$p}color"] ) )
		$button_color = $fields["{$p}color"];

	if ( ! empty( $fields["{$p}button_style"] ) ) {
		if ( $fields["{$p}button_style"] == 'outline' ) {
			$classes[] = 'button-outline';

			if ( $button_color )
				$styles['border_color'] = $styles['color'] = esc_attr( $button_color );
		}
	}
	elseif ( $button_color )
		$styles['bg_color'] = esc_attr( $button_color );
}
else {
	$classes[] = 'link';

	if ( ! empty( $fields["{$p}color"] ) )
		$styles['color'] = esc_attr( $fields["{$p}color"] );
}

if ( $type == 'popup' && isset( $fields["{$p}popup"] ) ) {
	$popup = ' data-popup="popup_' . esc_attr( $fields["{$p}popup"] ) . '"';
	$classes[] = 'popup-trigger';

	md_popup( array( 'id' => $fields["{$p}popup"] ) );
}

if ( ! empty( $fields["{$p}toggle"]['hide_label'] ) )
	$classes[] = 'hide-label';

if ( ! empty( $fields["{$p}toggle"]['hide_label_mobile'] ) )
	$classes[] = 'hide-label-mobile';

$style = md_style( $styles );
$title = $name ? ' title="' . strip_tags( $name ) . '"' : '';

if ( isset( $fields["{$p}classes"] ) )
	$classes[] = esc_attr( $fields["{$p}classes"] );

$classes = join( ' ', $classes );

if ( $classes )
	$class = ' class="' . esc_attr( $classes ) . '"';

$html =
	"<$h{$href}{$popup}{$class}{$target}{$style}{$title}>".
	( $icon ? md_icon( $icon, array( 'classes' => $icon_classes ) ) : '' ).
	( $icon ? '<span class="link-wrap">' : '' ).
	( $name || is_customize_preview() ? '<span class="link-name">' . do_shortcode( md_text_field( $name ) ) . '</span>' : '' ).
	( $subtitle || is_customize_preview() ? '<span class="link-subtitle">' . do_shortcode( md_text_field( $subtitle ) ) . '</span>' : '' ) .
	( $icon ? '</span>' : '' ).
	"</$h>";
