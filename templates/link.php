<?php
/**
 * Customize the output of a link or button displayed from any MD button settings page
 * or the md_get_link and md_link() function with passed data.
 *
 * @since 6.0
 */

$text = isset( $fields["{$p}title"] ) ? $fields["{$p}title"] : '';
$text = isset( $fields["{$p}text"] ) ? $fields["{$p}text"] : $text;

$icon = ! empty( $fields["{$p}icon"] ) ? $fields["{$p}icon"] : '';

if ( empty( $text ) && empty( $icon ) )
	return;

$classes = $styles = array();
$h = 'span';
$class = $href = $target = $popup = '';
$parent = isset( $fields["{$p}area"] ) ? $fields["{$p}area"] : '';
$title = ( $icon && ! $text ) ? ' title="' . strip_tags( $text ) . '"' : '';
$subtext = isset( $fields["{$p}subtext"] ) ? $fields["{$p}subtext"] : '';
$url = isset( $fields["{$p}url"] ) ? $fields["{$p}url"] : '';
$phone = isset( $fields["{$p}phone"] ) ? $fields["{$p}phone"] : '';
$style = isset( $fields["{$p}style"] ) ? $fields["{$p}style"] : 'link';
$type = isset( $fields["{$p}type"] ) ? $fields["{$p}type"] : 'url';
$icon_classes = 'link-icon';
$has_wrap = $icon && $text && $subtext ? true : false;

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

if ( isset( $fields["{$p}classes"] ) )
	$classes[] = esc_attr( $fields["{$p}classes"] );

if ( $has_wrap )
	$classes[] = 'link-style';

$classes = join( ' ', $classes );

if ( $classes )
	$class = ' class="' . esc_attr( $classes ) . '"';

$html =
	"<$h{$href}{$popup}{$class}{$target}{$style}{$title}>".
	( $icon ? md_icon( $icon, array( 'classes' => $icon_classes ) ) : '' ).
	( $has_wrap ? '<span class="link-wrap">' : '' ).
	( $text || is_customize_preview() ? '<span class="link-text">' . do_shortcode( md_text_field( $text ) ) . '</span>' : '' ).
	( $subtext || is_customize_preview() ? '<span class="link-subtext">' . do_shortcode( md_text_field( $subtext ) ) . '</span>' : '' ) .
	( $has_wrap ? '</span>' : '' ).
	"</$h>";
