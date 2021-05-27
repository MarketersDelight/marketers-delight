<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Calls the global MD email form with override attributes.
 *
 * @since 4.3
 */

function md_email_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'title' => '',
		'desc' => '',
		'list' => '',
		'attached' => '',
		'email_input_name' => '',
		'name_label' => '',
		'email_label' => '',
		'submit_text' => '',
		'bg_color' => '',
		'bg_image' => '',
		'text_color' => '',
		'footer' => '',
		'classes' => ''
	), $atts, 'md_email' ) );
	ob_start();
	md_email_form(
		array(
			'email_title' => ! empty( $atts['title'] ) ? $atts['title'] : md_setting( array( 'email', 'email_title' ) ),
			'email_desc' => ! empty( $atts['desc'] ) ? $atts['desc'] : md_setting( array( 'email', 'email_desc' ) ),
			'email_code' => md_setting( array( 'email', 'email_code' ) ),
			'email_list' => ! empty( $atts['list'] ) ? $atts['list'] : md_setting( array( 'optins', 'cta', 'email_list' ) ),
			'email_name_label' => ! empty( $atts['name_label'] ) ? $atts['name_label'] : md_setting( array( 'email', 'email_name_label' ) ),
			'email_email_label' => ! empty( $atts['email_label'] ) ? $atts['email_label'] : md_setting( array( 'email', 'email_email_label' ) ),
			'email_submit_text' => ! empty( $atts['submit_text'] ) ? $atts['submit_text'] : md_setting( array( 'email', 'email_submit_text' ) ),
			'email_form_footer' => ! empty( $atts['footer'] ) ? $atts['footer'] : md_setting( array( 'email', 'email_form_footer' ) ),
			'email_input' => array(
				'name' => ! empty( $atts['email_input_name'] ) ? $atts['email_input_name'] : md_setting( array( 'email', 'email_input', 'name' ) )
			),
			'email_form_style' => array(
				'attached' => isset( $atts['attached'] ) && in_array( $atts['attached'], array( '1', 'true' ) ) ? true : md_setting( array( 'email', 'email_form_style', 'attached' ), false ),
			),
			'email_bg_color' => ! empty( $atts['bg_color'] ) ? $atts['bg_color'] : md_setting( array( 'email', 'bg_color' ) ),
			'email_image' => ! empty( $atts['bg_image'] ) ? $atts['bg_image'] : md_setting( array( 'email', 'bg_image' ) ),
			'email_text_color' => ! empty( $atts['text_color'] ) ? $atts['text_color'] : md_setting( array( 'email', 'text_color_scheme' ) ),
			'email_classes' => ! empty( $atts['classes'] ) ? $atts['classes'] : md_setting( array( 'email', 'email_classes' ) )
		),
		array(
			'before_title' => '<div class="med-title mb-half">',
			'after_title'  => '</div>'
		)
	);
	return ob_get_clean();
}

add_shortcode( 'md_email', 'md_email_shortcode' );

/**
 * Build the [md_popup] shortcode. Can customize trigger to be
 * a text link, image, or button with attributes.
 *
 * @since 4.5
 */

function md_popup_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'id' => '',
		'type' => '',
		'text' => '',
		'image' => '',
		'classes' => ''
	), $atts, 'md_popup' ) );

	if ( empty( $atts['id'] ) && empty( $atts['type'] ) )
		return;

	ob_start();

	if ( ! in_array( $atts['id'], md_filter_popups() ) )
		md_popup( array( 'id' => $atts['id'] ) );

	$type = ! empty( $atts['type'] ) ? $atts['type'] : 'button';
	$text = ! empty( $atts['text'] ) ? $atts['text'] : __( 'Open popup', 'md' );
	$custom = ! empty( $atts['classes'] ) ? ' ' . $atts['classes'] : '';
	$html = $type == 'link' ? 'a href="#"' : 'span';
	$html_c = $type == 'link' ? 'a' : 'span';
	$classes = ( $type == 'button' ? ' button' : '' ) . $custom;
?>

	<?php if ( $type != 'image' ) : ?>
		<<?php echo $html; ?> data-popup="md_popup_<?php echo esc_html( $atts['id'] ); ?>" class="md-popup-trigger<?php echo $classes; ?>"><?php echo $text; ?></<?php echo $html_c; ?>>
	<?php else : ?>
		<img src="<?php echo esc_url( $atts['image'] ); ?>" data-popup="md_popup_<?php echo esc_attr( $atts['id'] ); ?>" class="md-popup-trigger<?php echo $classes; ?>" alt="<?php echo esc_attr( $text ); ?>" />
	<?php endif; ?>

<?php return ob_get_clean(); }

add_shortcode( 'md_popup', 'md_popup_shortcode' );