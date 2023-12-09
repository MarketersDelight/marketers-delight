<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * A procedural function to access Block Editor colors.
 *
 * since 4.9
 */

function md_editor_colors() {
	$design = new md_design;
	return $design->editor_colors();
}

/**
 * Enqueue MD's integrated web font services to <head> when needed.
 *
 * @since 4.8
 */

function md_enqueue_fonts() {
	$typekit = md_setting( array( 'integrations', 'api_keys', 'typekit' ) );

	if ( md_web_fonts( 'google' ) ) {
		$url = md_setting( array( 'typography', 'google_fonts' ) );
		wp_enqueue_style( 'marketers-delight-google-fonts', $url );
	}

	if ( ! empty( $typekit['key'] ) && md_web_fonts( 'typekit' ) )
		wp_enqueue_style( 'marketers-delight-typekit', 'https://use.typekit.net/' . esc_attr( $typekit['key'] ) . '.css' );
}

/**
 * Enqueue MD's integrated web font services to <head> when needed.
 *
 * @since 4.8
 */

function md_webfonts_loader() {
	$typekit = md_setting( array( 'integrations', 'api_keys', 'typekit' ) );
	$fonts = md_web_fonts();
	$has_typekit = ( ! empty( $typekit ) && ! empty( $fonts['typekit'] ) ) ? true : false;
	$has_google = ( ! empty( $fonts['google'] ) ) ? true : false;

	if ( $has_google || $has_typekit )
		return
			"\t<script>WebFontConfig={" . ( $has_google ? 'google:{families:[' .  md_google_fonts( 'ids' ) . ']},' : '' ) . ( $has_typekit ? 'typekit:{id:\'' . esc_attr( $typekit['key'] ) . '\'}' : '' ) . '};(function(d){var wf=d.createElement(\'script\'),s=d.scripts[0];wf.src=\'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js\';wf.async=true;s.parentNode.insertBefore(wf,s);})(document);' . "</script>\n";
}

/**
 * Compile list of Google fonts and weights to load
 * per page based on Typography design selections.
 * Can show only `google_fonts` or `typekit` or all.
 *
 * @since 4.8
 */

function md_web_fonts( $show_type = null ) {
	$font_s = '';
	$fonts = array();
	$headings = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'header', 'sidebar_title', 'footer_title' );
	$areas = array_merge( array( 'body', 'site_title', 'site_tagline', 'sidebar', 'footer' ), $headings );
	$body_t = md_setting( array( 'typography', 'body', 'font_type' ) );
	$body_f = md_setting( array( 'typography', 'body', 'font_family' ) );
	$body_w = md_setting( array( 'typography', 'body', 'font_weight' ) );
	$bold = md_setting( array( 'typography', 'body', 'bold' ) );
	$h1_t = md_setting( array( 'typography', 'h1', 'font_type' ) );
	$h1_f = md_setting( array( 'typography', 'h1', 'font_family' ) );
	$h1_w = md_setting( array( 'typography', 'h1', 'font_weight' ) );

	foreach ( $areas as $area ) {
		$type = md_setting( array( 'typography', $area, 'font_type' ) );
		$family = md_setting( array( 'typography', $area, 'font_family' ) );
		$weight = md_setting( array( 'typography', $area, 'font_weight' ) );
		$style = md_setting( array( 'typography', $area, 'font_style' ) );

		if ( ! empty( $weight ) ) {
			if ( $type == 'google' && ! empty( $style ) )
				$font_s = "{$weight}i";

			if ( empty( $family ) ) {
				// Make hx inherit h1
				if ( in_array( $area, $headings ) && ! empty( $h1_f ) ) {
					$fonts[$h1_t][$h1_f][] = $weight;
//					if ( $font_s )
//						$fonts[$h1_t][$h1_f][] = $font_s;
				}
				// Make font inherit body values
				elseif ( ! empty( $body_f ) ) {
					$fonts[$body_t][$body_f][] = $weight;
//					if ( $font_s )
//						$fonts[$body_t][$body_f][] = $font_s;
				}
			}
			else {
				// Directly assign setting to value
				$fonts[$type][$family][] = $weight;
				$fonts[$type][$family] = array_unique( $fonts[$type][$family] );
//				if ( $font_s )
//					$fonts[$type][$family][] = $font_s;
			}
		}
		elseif ( ! empty( $family ) )
			$fonts[$type][$family] = array();

		if ( $area == 'body' && $bold )
			$fonts[$type][$family][] = $bold;
	}

	if ( isset( $show_type ) )
		$show = ! empty( $fonts[$show_type] ) ? $fonts[$show_type] : '';
	else
		$show = $fonts;

	return $show;
}

/**
 * Compile the needed Google Fonts by associated font weights.
 * Returns Google Font URL by default, set $format to 'ids'
 * to list font and weights by ID only.
 *
 * @since 4.8
 */

function md_google_fonts( $format = null ) {
	$string = '';
	$f = 1;
	$fonts = md_web_fonts( 'google' );
	$total_fonts = count( $fonts );
	$google = 'https://fonts.googleapis.com/css?family=';

	foreach ( $fonts as $name => $weights ) {
		$string .= $format == 'ids' ? "'" : '';
		$string .= $name;

		if ( ! empty( $weights ) ) {
			$w             = 1;
			$total_weights = count( $weights );
			$string        .= ':';
			foreach ( $weights as $weight ) {
				$string .= $weight . ( $w < $total_weights ? ',' : '' );
				$w++;
			}
		}

		$string .= $format == 'ids' ? "'" : '';

		if ( $f < $total_fonts )
			$string .= $format == 'ids' ? ',' : '|';

		$f++;
	}

	if ( $format == 'ids' )
		return $string;
	else
		return $google . urlencode( $string ) . '&display=swap';
}

/**
 * Build custom inline CSS on the fly with data from
 * custom values and disperse into media queries.
 *
 * Uses a "hack" to auto print styles to head.
 *
 * @since 5.6
 */

function md_post_css( $args = array() ) {  }

function md_inline_css( $handle, $args ) {
	$css = '';
	$queries = array();

	foreach ( $args as $selector => $properties ) {
		$css .= "$selector { ";

		foreach ( $properties as $property => $value ) {
			if ( isset( $value['query'] ) ) {
				$unit = isset( $value['unit'] ) ? $value['unit'] : '';

				foreach ( $value['query'] as $query_device => $query_val ) {
					if ( $query_device == 'desktop' )
						continue;

					$queries[$query_device][] = "$selector { {$property}: " . $value['query'][$query_device] . $unit . "; }";
				}
			}

			$css .= "{$property}: ";

			if ( isset( $value['query']['desktop'] ) )
				$css .= $value['query']['desktop'] . $unit;
			else
				$css .= $value;

			$css .= ';';
		}

		$css .= " }\n";
	}

	if ( ! empty( $queries ) ) {
		$devices = array( 'tablet' => 900, 'mobile' => 700 );

		foreach ( $queries as $device => $selectors ) {
			$css .= '@media all and (max-width: ' . $devices[$device] . "px) {\n";

			foreach ( $selectors as $key => $print )
				$css .= "\t$print\n";

			$css .= "}\n";
		}
	}

	wp_register_style( $handle, false );
	wp_enqueue_style( $handle );
	wp_add_inline_style( $handle, $css );
}

/**
 * Return HTML for Page Lead background color/image.
 *
 * @since 5.0
 */

function md_style( $fields ) {
	$style = '';
	$attributes = array();

	if ( ! empty( $fields['bg_color'] ) )
		$attributes['bg_color'] = 'background-color:' . esc_attr( $fields['bg_color'] ) . ';';

	if ( ! empty( $fields['bg_image'] ) )
		$attributes['bg_image'] = 'background-image:url(' . esc_url( $fields['bg_image'] ) . ');';

	if ( ! empty( $fields['bg_size'] ) )
		$attributes['bg_size'] = 'background-size:' . esc_attr( $fields['bg_size'] ) . ';';

	if ( ! empty( $fields['border_color'] ) )
		$attributes['border_color'] = 'border-color:' . esc_attr( $fields['border_color'] ) . ';';

	if ( isset( $fields['border'] ) && ! empty( $fields['border'][2] ) ) {
		$border_width = ! empty( $fields['border'][0] ) ? $fields['border'][0] : 1;
		$border_style = ! empty( $fields['border'][1] ) ? $fields['border'][1] : 'solid';
		$border_color = ! empty( $fields['border'][2] ) ? $fields['border'][2] : '#1e1e1e';
		$attributes['border'] = 'border:' . esc_attr( $border_width ) . 'px ' . esc_attr( $border_style ) . ' ' . esc_attr( $border_color ) . ';';
	}

	if ( ! empty( $fields['color'] ) )
		$attributes['color'] = 'color:' . esc_attr( $fields['color'] ) . ';';

	if ( ! empty( $fields['width'] ) )
		$attributes['width'] = 'width:' . esc_attr( $fields['width'] ) . ( isset( $fields['width_unit'] ) ? $fields['width_unit'] : 'px' ) . ';';

	if ( ! empty( $fields['max_width'] ) )
		$attributes['max_width'] = 'max-width:' . esc_attr( $fields['max_width'] ) . ( isset( $fields['width_unit'] ) ? $fields['width_unit'] : 'px' ) . ';';

	if ( ! empty( $fields['flex'] ) )
		$attributes['flex'] = 'flex:' . esc_attr( $fields['flex'] ) . ';';

	if ( ! empty( $fields['flex_basis'] ) )
		$attributes['flex_basis'] = 'flex-basis:' . esc_attr( $fields['flex_basis'] ) . ';';

	if ( ! empty( $fields['height'] ) )
		$attributes['height'] = 'height:' . esc_attr( $fields['height'] ) . 'px;';

	if ( ! empty( $attributes ) )
		$style = ' style="' . join( '', $attributes ) . '"';

	return $style;
}

/**
 * Easily output a button with different kind of action.
 *
 * @since 4.3.5
 */

function md_link( $fields ) {
	$classes = $styles = array();
	$html = 'span';
	$class = $href = $target = $popup = '';
	$parent = isset( $fields['area'] ) ? $fields['area'] : '';
	$text = isset( $fields['title'] ) ? $fields['title'] : '';
	$text = isset( $fields['link_text'] ) ? $fields['link_text'] : $text;
	$url = isset( $fields['url'] ) ? $fields['url'] : '';
	$phone = isset( $fields['phone'] ) ? $fields['phone'] : '';
	$style = isset( $fields['link_style'] ) ? $fields['link_style'] : 'link';
	$type = isset( $fields['link_type'] ) ? $fields['link_type'] : 'url';
	$icon_classes = 'link-icon';

	if ( $parent )
		$classes[] = "{$parent}-link";

	if ( isset( $fields['classes'] ) )
		$classes[] = esc_attr( $fields['classes'] );

	if ( $type == 'url' && $url ) {
		$html = 'a';
		$href = ' href="' . esc_url( $url ) . '"';
		$target = ( isset( $fields['link_target']['new'] ) ? ' target="_blank"' : '' );
	}
	elseif ( $type == 'phone' ) {
		$html = 'a';
		$href = ' href="tel:' . esc_attr( $phone ) . '"';
		$classes[] = $fields['icon'] = 'phone';

		if ( ! $text )
			$text = esc_attr( $phone );
	}

	if ( $style == 'button' ) {
		$button_color = '';
		$classes[] = 'button';

		if ( ! empty( $fields['button_color'] ) )
			$button_color = $fields['button_color'];

		if ( ! empty( $fields['button_style'] ) ) {
			if ( $fields['button_style'] == 'outline' ) {
				$classes[] = 'button-outline';

				if ( $button_color )
					$styles['border_color'] = $styles['color'] = esc_attr( $button_color );
			}
		}
		elseif ( $button_color )
			$styles['bg_color'] = esc_attr( $button_color );
	}
	else
		$classes[] = 'link';

	if ( $type == 'popup' && isset( $fields['popup'] ) ) {
		$popup = ' data-popup="md_popup_' . esc_attr( $fields['popup'] ) . '"';
		$classes[] = 'md-popup-trigger';
		md_popup( array( 'id' => esc_attr( $fields['popup'] ) ) );
	}

	if ( ! empty( $fields['toggle']['hide_label'] ) )
		$classes[] = 'hide-label';

	if ( ! empty( $fields['toggle']['hide_label_mobile'] ) )
		$classes[] = 'hide-label-mobile';

	$style = md_style( $styles );

	$classes = join( ' ', $classes );

	if ( $classes )
		$class = ' class="' . esc_attr( $classes ) . '"';
?>

	<<?php echo $html . $href . $popup . $class . $target . $style; ?>>
		<?php echo ( isset( $fields['icon'] ) ? md_icon( $fields['icon'], array( 'classes' => $icon_classes ) ) : '' ); ?>
		<?php echo ( $text ? '<span class="link-text">' . md_text_field( $text ) . '</span>' : '' ); ?></<?php echo $html; ?>>

<?php }
