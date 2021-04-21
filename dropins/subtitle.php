<?php
/**
 * Dropin Name: MD Subtitle
 * Dropin Author: Alex Mangini
 * Dropin Description: Add subtitles to posts and post type entries.
 * Dropin Version: 1.0
 * @since MD5.2.3
 */

class md_subtitle extends md_api {

	/**
	 * Load general actions and filters.
	 *
	 * @since 5.2.3
	 */
	
	public function actions() {
		add_action( 'edit_form_before_permalink', array( $this, 'subtitle_meta' ) );
	}

	/**
	 * Register Subtitle settings.
	 *
	 * @since 5.2.3
	 */

	public function register() {
		return array(
			'meta_box' => array(
				'name' => __( 'Subtitle', 'md' ),
				'context' => 'side',
				'priority' => 'high',
				'show_on_block_editor' => true,
				'callback' => array( $this, 'subtitle_meta' ),
				'fields' => array(
					'text' => array( 'type' => 'text' )
				)
			)
		);
	}
	
	/**
	 * Create subtitle field.
	 *
	 * @since 5.2.3
	 */
	
	public function subtitle_meta() {
		$this->fields->field( 'text', array(
			'type' => 'text',
			'placeholder' => __( 'Add subtitle', 'md' ),
			'classes' => 'md-input-full'
		) );
	}

	/**
	 * Run template actions and filters.
	 *
	 * @since 5.2.3
	 */
	
	public function template() {
		add_action( 'md_hook_after_headline', array( $this, 'html' ) );
	}

	/**
	 * Subtitle template code.
	 *
	 * @since 5.2.3
	 */
	
	public function html() {
		$subtitle = md_post_meta( array( 'subtitle', 'text' ) );
		if ( $subtitle )
			echo apply_filters( 'md_subtitle', '<p class="subtitle">' . md_text_field( $subtitle ) . '</p>' );
	}

}

new md_subtitle;