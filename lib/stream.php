<?php
/**
 * Create library for Stream templates and Loops.
 *
 * @since 5.6
 */

class md_stream_templates {

	/**
	 * A collection of filtered in post types for use in Stream Embeds.
	 *
	 * @since 4.8.4
	 */

	public function types( $types = array() ) {
		return apply_filters( 'md_stream_types', $types );
	}

	/**
	 * Byline block to reuse in template.
	 *
	 * @since 4.8.4
	 */

	public function byline( $post_id, $embed_id, $post_type, $args = null ) {
		$types = $this->types();
		$is_embed = ! empty( $args['is_embed'] ) ? true : null;
		include( md_template( 'loops/stream/stream-byline', true ) );
	}

	/**
	 * Load custom controls to post embeds.
	 *
	 * @since 5.6
	 */

	public function embed_post_controls( $embed_id, $post_type ) {
		$post_types = md_setting( array( 'share', 'post_types' ), array() );
		if ( md_has( 'share' ) && in_array( $post_type, $post_types ) ) {
			$share = new md_share;
			$share->share_button( array(
				'style' => 'minimal',
				'post_id' => $embed_id,
				'post_type' => $post_type
			) );
		}
	}

	/**
	 * Individual popup template.
	 *
	 * @since 4.8.4
	 */

	public function popup( $atts ) {
		include( md_template( 'loops/stream/stream-popup', true ) );
	}

	/**
	 * Load single template logic into reusable method.
	 *
	 * @since 5.2
	 */

	public function loop_query( $c ) {
		$classes = array( 'stream-item' );
		$loop_h = is_post_type_archive( 'stream' ) || is_tax( 'stream' ) ? 'div' : 'article';
		$article_h = is_singular( 'stream' ) ? 'div' : 'article';
		$types = $this->types();
		$has_titles = md_setting( array( 'stream', 'layout', 'remove_post_titles' ) );
		$thread = md_post_meta( array( 'stream', 'thread' ) );
		$first_thread = '';
		if ( ! empty( $thread ) ) {
			$tc = 0;
			foreach ( $thread as $thread_id => $thread_fields ) {
				if ( $tc >= 1 )
					break;
				$first_thread = $thread_id;
				$tc++;
			}
		}
		$has_thread = ! empty( $thread ) ? true : false;
		$post_id = $html_id = get_the_ID();
		$title = get_the_title();
		$embed_id = md_post_meta( array( 'stream', 'post_id' ) );
		$post_type = get_post_type( $embed_id );
		$post_date = get_post_timestamp();
		$post_author = get_post_field( 'post_author', $post_id );
		$post_content = get_the_content( md_read_more_text() );
		$stream_image = get_the_post_thumbnail( $post_id, ( $c == 0 ? 'md-image' : 'thumbnail' ) );
		if ( get_post_type() == 'stream_activity' )
			$classes[] = 'stream-activity';
		if ( in_array( $post_id, get_option( 'sticky_posts' ) ) )
			$classes[] = 'sticky';
		$classes = join( ' ', $classes );
		include( md_template( 'loops/stream/stream-post', true ) );
		if ( $stream_image )
			if ( class_exists( 'md_popup' ) )
				new md_popup( array(
					'id' => "{$post_type}_{$post_id}",
					'callback' => array( $this, 'popup' ),
					'atts' => array(
						'post_id' => $post_id,
						'post_type' => $post_type
					)
				) );
		if ( is_singular() && $has_thread ) {
			$c = 1;
			foreach ( $thread as $thread_id => $fields ) {
				$html_id = $post_id = $thread_id;
				$embed_id = ! empty( $fields['post_id'] ) ? $fields['post_id'] : '';
				$post_type = get_post_type( $embed_id );
				$post_date = ! empty( $fields['date'] ) ? esc_attr( $fields['date'] ) : '';
				if ( ! empty( $fields['user_id'] ) )
					$post_author = esc_attr( $fields['user_id'] );
				$title = ! empty( $fields['name'] ) ? $fields['name'] : '';
				$post_content = $fields['text'];
				$image_id = ! empty( $fields['image']['id'] ) ? esc_attr( $fields['image']['id'] ) : '';
				$stream_image = wp_get_attachment_image( $image_id, 'thumbnail' );
				include( md_template( 'loops/stream/stream-post', true ) );
				if ( class_exists( 'md_popup' ) && $stream_image )
					new md_popup( array(
						'id' => "{$post_type}_{$post_id}",
						'callback' => array( $this, 'popup' ),
						'atts' => array(
							'post_id'  => $post_id,
							'image_id' => $image_id,
							'post_type' => $post_type
						)
					) );
				$c++;
			}
		}
	}

}

$md_stream_templates = new md_stream_templates;