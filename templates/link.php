<?php
/**
 * Customize the output of a link or button displayed from any MD button settings page
 * or the md_get_link and md_link() function with passed data.
 *
 * @since 6.0
 */

$text = isset( $fields["{$p}title"] ) ? $fields["{$p}title"] : '';
$text = isset( $fields["link{$p}_text"] ) ? $fields["link{$p}_text"] : $text;
$icon = ! empty( $fields["link{$p}_icon"] ) ? $fields["link{$p}_icon"] : '';

if ( empty( $text ) && empty ( $icon ) )
	return;

$classes = $styles = array();
$h = 'span';
$class = $href = $target = $popup = '';
$parent = isset( $fields["{$p}area"] ) ? $fields["{$p}area"] : '';
$title = ( $icon && ! $text ) ? ' title="' . strip_tags( $text ) . '"' : '';
$subtext = isset( $fields["link{$p}_subtext"] ) ? $fields["link{$p}_subtext"] : '';
$url = isset( $fields["link{$p}_url"] ) ? $fields["link{$p}_url"] : '';
$phone = isset( $fields["link{$p}_phone"] ) ? $fields["link{$p}_phone"] : '';
$style = isset( $fields["link{$p}_style"] ) ? $fields["link{$p}_style"] : 'link';
$type = isset( $fields["link{$p}_type"] ) ? $fields["link{$p}_type"] : 'url';
$icon_classes = 'link-icon';
$has_wrap = $icon && $text && $subtext ? true : false;

if ( $parent )
	$classes[] = "{$parent}-link";

if ( ! empty( $fields["link{$p}_display"] ) )
	$classes[] = 'show-' . esc_attr( $fields["link{$p}_display"] );

if ( in_array( $type, array( 'url', 'link' ) ) && $url ) {
	$h = 'a';
	$href = ' href="' . esc_url( $url ) . '"';
	$target = ( isset( $fields["link{$p}_target"]['new'] ) ? ' target="_blank"' : '' );
}
elseif ( $type == 'phone' ) {
	$h = 'a';
	$href = ' href="tel:' . esc_attr( $phone ) . '"';
	$classes[] = $fields["link{$p}_icon"] = 'phone';

	if ( ! $text )
		$text = esc_attr( $phone );
}

if ( $style == 'button' ) {
	$button_color = '';
	$classes[] = 'button';

	if ( ! empty( $fields["link{$p}_size"] ) )
		$classes[] = 'button-' . $fields["link{$p}_size"];

	if ( ! empty( $fields["link{$p}_color"] ) )
		$button_color = $fields["link{$p}_color"];

	if ( ! empty( $fields["link{$p}_button_style"] ) ) {
		if ( $fields["link{$p}_button_style"] == 'outline' ) {
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

	if ( ! empty( $fields["link{$p}_color"] ) )
		$styles['color'] = esc_attr( $fields["link{$p}_color"] );
}

if ( $type == 'popup' && isset( $fields["link{$p}_popup"] ) ) {
	$popup = ' data-popup="popup_' . esc_attr( $fields["link{$p}_popup"] ) . '"';
	$classes[] = 'popup-trigger';

	md_popup( array( 'id' => $fields["link{$p}_popup"] ) );
}

if ( ! empty( $fields["link{$p}_toggle"]['hide_label'] ) )
	$classes[] = 'hide-label';

if ( ! empty( $fields["link{$p}_toggle"]['hide_label_mobile'] ) )
	$classes[] = 'hide-label-mobile';

$style = md_style( $styles );

if ( isset( $fields["link{$p}_classes"] ) )
	$classes[] = esc_attr( $fields["link{$p}_classes"] );

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
