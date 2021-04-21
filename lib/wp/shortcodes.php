<?php
/**
 * A collection of shortcodes in Marketers Delight.
 *
 * @since 5.0
 */

class md_shortcodes {

	/**
	 * Add shortcodes to WP environment.
	 *
	 * @since 5.0
	 */

	public function __construct() {
		add_shortcode( 'md_email', array( $this, 'email' ) );
		add_shortcode( 'md_popup', array( $this, 'popup' ) );
		add_shortcode( 'fn', array( $this, 'footnote' ) );
	}

	/**
	 * Calls the global MD email form with override attributes.
	 *
	 * @since 4.3
	 */

	public function email( $atts ) {
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
					'attached' => ( isset( $atts['attached'] ) ? $atts['attached'] == 'true' ? true : false : md_setting( array( 'email', 'email_form_style', 'attached' ) ) ),
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

	/**
	 * Build the [md_popup] shortcode. Can customize trigger to be
	 * a text link, image, or button with attributes.
	 *
	 * @since 4.5
	 */

	public function popup( $atts ) {
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

	<?php
		return ob_get_clean();
	}

	/**
	 * [fn] shortcode template.
	 *
	 * @since 4.5
	 */

	public function footnote( $atts ) {
		extract( shortcode_atts( array(
			'id' => '',
			'align' => ''
		), $atts, 'footnote' ) );
		static $i = 1;
		$id = ! empty( $atts['id'] ) ? $atts['id'] : '';
		$footnotes = md_post_meta( array( 'footnotes' ) );

		if ( empty( $footnotes['footnotes'][$id] ) )
			return;

		$url = get_permalink();
		$align = ( $i % 2 == 0 || ( isset( $atts['align'] ) && $atts['align'] == 'right' ) ? ' right' : '' );
		ob_start();
		include( md_template( 'dropins', 'footnotes/footnote', true ) );
		return ob_get_clean();
	}

}

new md_shortcodes;