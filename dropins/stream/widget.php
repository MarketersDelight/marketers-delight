<?php
/**
 * Show different forum data in tabbed boxes and lists.
 * Rich customization features for creating different combinations
 * of content, all forum data optimally accessed with XFWP.
 *
 * @since 1.0
 */

class md_stream_widget extends WP_Widget {

	/**
	 * List of settings.
	 */

	public $dir = 'dropins';
	public $fields_parsed = array(
		'title' => '',
		'description' => '',
		'posts_per_page' => '',
		'excerpt_length' => '',
		'hide_title' => '',
		'hide_text' => '',
		'excerpt_text' => ''
	);

	/**
	 * Setup Widget.
	 *
	 * @since 1.0
	 */

	public function __construct() {
		parent::__construct( 'md_stream_widget', __( 'MD &rarr; Stream Embed', 'md' ), array(
			'description' => __( 'Display the latest posts from your Stream in a beautiful widget.', 'md' ),
			'customize_selective_refresh' => true
		) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Load Share script only when needed.
	 *
	 * @since 4.9.2
	 */

	public function enqueue() {
		if ( is_active_widget( false, false, $this->id_base, true ) )
			wp_add_inline_script( 'marketers-delight', "\tMD.share.init();" );
	}

	/**
	 * Frontend widget template.
	 *
	 * @since 1.0
	 */

	public function widget( $args, $val ) {
		$c = 0;
		$stream = new WP_Query( array(
			'post_type' => 'stream',
			'posts_per_page' => ( ! empty( $val['posts_per_page'] ) ? $val['posts_per_page'] : 3 )
		) );
		$share = new md_share;
		$stream_templates = new md_stream_templates;
		$excerpt_length = ! empty( $val['excerpt_length'] ) ? $val['excerpt_length'] : 20;
		$excerpt_text = ! empty( $val['excerpt_text'] ) ? $val['excerpt_text'] : __( 'read more', 'md' );
		include( md_template( $this->dir, 'stream/widget-embed', true ) );
	}

	/**
	 * Admin settings template.
	 *
	 * @since 1.0
	 */

	public function form( $val ) {
		$val = wp_parse_args( (array) $val, $this->fields_parsed );
		include( md_template( $this->dir, 'stream/admin/widget-form', true ) );
	}

	/**
	 * Sanitize widget settings on save.
	 *
	 * @since 1.0
	 */

	public function update( $new, $val ) {
		$save = array();
		$sanitize = new md_sanitize;
		$new = wp_parse_args( (array) $new, $this->fields_parsed );

		$save['title'] = $sanitize->text( $new['title'] );
		$save['description'] = $sanitize->text( $new['description'] );
		$save['posts_per_page'] = preg_replace( '/\D/', '', $new['posts_per_page'] );
		$save['excerpt_length'] = preg_replace( '/\D/', '', $new['excerpt_length'] );
		$save['excerpt_text'] = $sanitize->text( $new['excerpt_text'] );
		foreach ( array( 'hide_title', 'hide_text' ) as $check )
			if ( isset( $new[$check] ) )
				$save[$check] = $sanitize->checkbox( $new[$check] );

		return $save;
	}

}