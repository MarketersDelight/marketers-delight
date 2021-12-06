<?php

class md_quote_widget extends WP_Widget {

	public function __construct() {
		parent::__construct( 'md_quote_widget', __( 'MD &rarr; Testimonial', 'md' ), array(
			'description'                 => __( 'Add a testimonial/quote to any designated widget area!', 'md' ),
			'customize_selective_refresh' => true
		) );

		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
	}

	public function scripts( $hook ){
		if ( $hook == 'widgets.php' || is_customize_preview() ) {
			wp_enqueue_media();
			wp_enqueue_script( 'md-media' );
		}
	}

	public function widget( $args, $val ) {
		$title  = $val['title'];
		$quote  = $val['quote'];
		$author = $val['author'];
		$image  = $val['image'];
		include( md_template( 'widgets/quote', true ) );
	}

	public function update( $new, $val ) {
		$val['title']  = sanitize_text_field( $new['title'] );
		$val['quote']  = esc_textarea( $new['quote'] );
		$val['author'] = sanitize_text_field( $new['author'] );
		$val['image']  = ! empty( $new['image'] ) ? esc_url( $new['image'] ) : '';

		return $val;
	}

	public function form( $val ) {
		$val = wp_parse_args( (array) $val, array(
			'title'  => '',
			'quote'  => '',
			'author' => '',
			'image'  => ''
		) );
		$display = ! empty( $val['image'] ) ? 'block' : 'none';
		include( 'templates/quote.php' );
	}

}