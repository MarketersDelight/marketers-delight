<?php
/**
 * Fire this class to properly load popup into the MD Popups
 * environment.
 *
 * Example: md_popup( array( 'id' => 'popup_id' ) );
 *
 * @since 4.5
 */

class md_popup {

	/**
	 * Ensure ID is set to pass to template, then add popup
	 * template(s) to MD Popups hook area.
	 *
	 * @since 4.5
	 */

	public function __construct( $args ) {
		if ( empty( $args['id'] ) )
			return;

		$this->args[] = $args;
		$popups = md_filter_popups();
		$fields = $this->fields( $args['id'] );

		if ( ! empty( $fields['button']['popup'] ) && ! in_array( $fields['button']['popup'], $popups ) )
			$this->args[] = array( 'id' => $fields['button']['popup'] );

		if ( ! empty( $fields['button_sec']['popup'] ) && ! in_array( $fields['button_sec']['popup'], $popups ) )
			$this->args[] = array( 'id' => $fields['button_sec']['popup'] );

		add_filter( 'md_filter_popups', array( $this, 'add_popup' ) );
		add_action( 'md_popups', array( $this, 'template' ) );
	}

	/**
	 * Add popup ID to queue.
	 *
	 * @since 5.0
	 */

	public function add_popup( $popups ) {
		foreach ( $this->args as $popup => $fields )
			$popups[] = $fields['id'];
		return $popups;
	}

	/**
	 * Assign all possible fields to popups template.
	 *
	 * @since 5.0
	 */

	public function fields( $id ) {
		$template = md_setting( array( 'popups_data', $id, 'template' ) );
		$featured_image_id = md_setting( array( 'popups_data', $id, 'featured_image', 'id' ) );
		$border_color = md_setting( array( 'popups_data', $id, 'design', 'border_color' ) );
		$bg_image = md_setting( array( 'popups_data', $id, 'design', 'bg_image' ) );
		$bg_repeat = md_setting( array( 'popups_data', $id, 'design', 'bg_repeat' ) );
		$alignment = md_setting( array( 'popups_data', $id, 'featured_image', 'alignment' ) );
		$width = md_setting( array( 'popups_data', $id, 'featured_image', 'width' ) );
		$enable_custom_template = md_setting( array( 'popups_data', $id, 'custom_template', 'show_custom_template' ) );

		$classes[] = 'md-popup';
		$classes[] = 'popup-' . ( ! empty( $template ) ? esc_attr( $template ) : 'default' );
		if ( ! empty( $bg_image ) )
			$classes[] = 'has-bg-image';
		if ( ! empty( $border_color ) && is_customize_preview() )
			$classes[] = 'popup-border';
		if ( ! empty( $featured_image_id ) ) {
			$classes[] = 'has-image';
			if ( ! empty( $alignment ) )
				$classes[] = "image-$alignment";
		}
		if ( ! empty( $enable_custom_template ) && is_customize_preview() )
			$classes[] = 'popup-custom';
		$classes[] = md_setting( array( 'popups_data', $id, 'custom_template', 'classes' ) );
		$classes = join( ' ', $classes );

		return array(
			'template' => $template,
			'subtitle' => md_setting( array( 'popups_data', $id, 'content', 'subtitle' ) ),
			'headline' => md_setting( array( 'popups_data', $id, 'content', 'headline' ) ),
			'description' => md_setting( array( 'popups_data', $id, 'content', 'description' ) ),
			'featured_image' => array(
				'id' => $featured_image_id,
				'alignment' => array(
					'left' => ! empty( $alignment ) ? $alignment : 'alignleft',
					'center' => ! empty( $alignment ) ? $alignment : 'aligncenter',
					'right' => ! empty( $alignment ) ? $alignment : 'alignright'
				),
				'width' => ! empty( $width ) ? $width : '',
				'style' => array(
					'width' => ! empty( $width ) ? "width: {$width}%;" : ''
				),
				'classes' => md_setting( array( 'popups_data', $id, 'featured_image', 'classes' ) )
			),
			'email' => array(
				'email_list' => md_setting( array( 'popups_data', $id, 'email', 'email_list' ) ),
				'email_input' => array(
					'name' => md_setting( array( 'popups_data', $id, 'email', 'email_show_name' ) ),
				),
				'email_name_label' => md_setting( array( 'popups_data', $id, 'email', 'email_name_label' ) ),
				'email_email_label' => md_setting( array( 'popups_data', $id, 'email', 'email_email_label' ) ),
				'email_submit_text' => md_setting( array( 'popups_data', $id, 'email', 'email_submit_label' ) ),
				'email_form_style' => array(
					'attached' => md_setting( array( 'popups_data', $id, 'email', 'email_form_attached' ) )
				),
				'email_form_footer' => md_setting( array( 'popups_data', $id, 'email', 'email_footer' ) ),
				'email_classes' => md_setting( array( 'popups_data', $id, 'email', 'classes' ) ),
				'atts' => array(
					'email_submit_bg_color' => md_setting( array( 'popups_data', $id, 'design', 'button_color' ) ),
					'email_submit_color' => md_setting( array( 'popups_data', $id, 'design', 'button_text_color' ) )
				)
			),
			'button' => array(
				'enable' => md_setting( array( 'popups_data', $id, 'button', 'enable' ) ),
				'text' => md_setting( array( 'popups_data', $id, 'button', 'text' ) ),
				'subtext' => md_setting( array( 'popups_data', $id, 'button', 'subtext' ) ),
				'action' => md_setting( array( 'popups_data', $id, 'button', 'action' ) ),
				'link' => md_setting( array( 'popups_data', $id, 'button', 'link' ) ),
				'popup' => md_setting( array( 'popups_data', $id, 'button', 'popup' ) ),
				'footer_text' => md_setting( array( 'popups_data', $id, 'button', 'footer_text' ) ),
				'bg_color' => md_setting( array( 'popups_data', $id, 'design', 'button_color' ) ),
				'color' => md_setting( array( 'popups_data', $id, 'design', 'button_text_color' ) ),
				'classes' => 'button-main'
			),
			'button_sec' => array(
				'enable' => md_setting( array( 'popups_data', $id, 'button_sec', 'enable' ) ),
				'text' => md_setting( array( 'popups_data', $id, 'button_sec', 'text' ) ),
				'subtext' => md_setting( array( 'popups_data', $id, 'button_sec', 'subtext' ) ),
				'action' => md_setting( array( 'popups_data', $id, 'button_sec', 'action' ) ),
				'link' => md_setting( array( 'popups_data', $id, 'button_sec', 'link' ) ),
				'popup' => md_setting( array( 'popups_data', $id, 'button_sec', 'popup' ) ),
				'bg_color' => md_setting( array( 'popups_data', $id, 'design', 'button_sec_color' ) ),
				'color' => md_setting( array( 'popups_data', $id, 'design', 'button_sec_text_color' ) ),
				'classes' => 'button-sec'
			),
			'bg_color' => md_setting( array( 'popups_data', $id, 'design', 'bg_color' ) ),
			'secondary_color' => md_setting( array( 'popups_data', $id, 'design', 'secondary_color' ) ),
			'border_color' => $border_color,
			'headline_color' => md_setting( array( 'popups_data', $id, 'design', 'headline_color' ) ),
			'text_color' => md_setting( array( 'popups_data', $id, 'design', 'text_color' ) ),
			'links_color' => md_setting( array( 'popups_data', $id, 'design', 'links_color' ) ),
			'close_color' => md_setting( array( 'popups_data', $id, 'design', 'close_color' ) ),
			'button_color' => md_setting( array( 'popups_data', $id, 'design', 'button_color' ) ),
			'bg_image' => $bg_image,
			'bg_repeat' => ! empty( $bg_repeat ) ? 'auto' : '',
			'classes' => trim( $classes ),
			'enable_custom_template' => $enable_custom_template,
			'custom_template' => md_setting( array( 'popups_data', $id, 'custom_template', 'custom_template' ) ),
			'custom_template_filter' => md_setting( array( 'popups_data', $id, 'custom_template', 'custom_template_filter' ) ),
			'custom_css' => md_setting( array( 'popups_data', $id, 'custom_template', 'custom_css' ) )
		);
	}

	/**
	 * Load popup template from either plugin or child theme.
	 *
	 * @since 4.5
	 */

	public function template() {
		foreach ( $this->args as $order => $args ) {
			if ( isset( $args['callback'] ) )
				call_user_func( $args['callback'], $args['atts'] );
			else {
				$fields = array_merge( $args, $this->fields( $args['id'] ) );
				$fields['id'] = $args['id'];
				$template = ! empty( $fields['template'] ) ? '-' . $fields['template'] : '';
				if ( $template = md_template( 'dropins', 'optins/popup' . esc_attr( $template ), true ) )
					include( $template );
				if ( ! empty( $fields['custom_css'] ) && ! is_customize_preview() )
					echo '<style type="text/css">' . $fields['custom_css'] . '</style>';
			}
		}
	}

}