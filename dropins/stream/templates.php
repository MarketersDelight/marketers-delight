<?php
/**
 * Load frontend Stream templates and actions.
 *
 * @since 5.0
 */

class md_stream_templates extends md_api {

	/**
	 * Run actions and filters.
	 *
	 * @since 5.0
	 */

	public function actions() {
		$this->dir = 'dropins';
		$this->taxonomy_label = 'stream_categories';
	}

	/**
	 * Manipulate frontend templates.
	 *
	 * @since 4.8.4
	 */

	public function template() {
		$archives_sidebar = md_setting( array( 'stream', 'layout', 'add_archives_sidebar' ) );
		$single_sidebar = md_setting( array( 'stream', 'layout', 'add_single_sidebar' ) );
		$add_title = md_setting( array( 'stream', 'layout', 'add_stream_title' ) );
		if ( is_post_type_archive( 'stream' ) || is_tax( $this->taxonomy_label ) || is_singular( 'stream' ) ) {
			add_filter( 'md_filter_content_classes', array( $this, 'content_classes' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_share' ) );
			if ( have_posts() ) {
				add_filter( 'md_filter_loop_type', array( $this, 'loop_type' ) );
				remove_action( 'md_hook_content', 'md_loop' );
				add_action( 'md_hook_content', array( $this, 'loop' ), 8 );
			}
		}
		if ( is_post_type_archive( 'stream' ) || is_tax( $this->taxonomy_label ) ) {
			add_action( 'md_hook_content', array( $this, 'title' ), 5 );
			if ( get_query_var( 'paged', 1 ) < 1 )
				add_action( 'md_hook_stream_before_loop', array( $this, 'loop_sticky' ) );
			remove_action( 'md_hook_content', 'md_archives_title' );
		}
		if ( is_post_type_archive( 'stream' ) && $archives_sidebar )
			add_filter( 'md_filter_has_sidebar', '__return_true' );
		if ( is_singular( 'stream' ) ) {
			if ( $add_title )
				add_action( 'md_hook_content', array( $this, 'title' ), 5 );
			if ( $single_sidebar )
				add_filter( 'md_filter_has_sidebar', '__return_true' );
		}
	}

	/**
	 * A simple way to override the post count loop for archive.
	 *
	 * @since 4.9.2
	 */

	public function parse_query( $wp ) {
		if ( isset( $wp->query['post_type'] ) && $wp->query['post_type'] == 'stream' && $wp->is_main_query() && $wp->is_post_type_archive ) {
			$custom = md_setting( array( 'stream', 'posts_per_page' ) );
			$wp->query_vars['posts_per_page'] = $custom ? preg_replace( '/\D/', '', $custom ) : 10;
			$wp->set( 'post__not_in', get_option( 'sticky_posts' ) );
		}
		return $wp;
	}

	/**
	 * Load Share script only when needed.
	 *
	 * @since 4.9.2
	 */

	public function enqueue_share() {
		wp_add_inline_script( 'marketers-delight', "\tMD.share.init();" );
	}

	/**
	 * A collection of filtered in post types for use in Stream Embeds.
	 *
	 * @since 4.8.4
	 */

	public function types( $types = array() ) {
		return apply_filters( 'md_stream_types', $types );
	}

	/**
	 * Add content width class for full-width layout. Always
	 * remove .shadow class.
	 *
	 * @since 4.9.2
	 */

	public function content_classes( $classes ) {
		if ( ! md_has_sidebar() )
			$classes[] = 'content-width';
		return $classes;
	}

	/**
	 * Load title with passed data.
	 *
	 * @since 4.9.2
	 */

	public function title( $args = null ) {
		$taxonomy_name = 'stream_categories';
		$archives_title = md_setting( array( 'stream', 'archives_title' ) );
		$archives_desc = md_setting( array( 'stream', 'archives_text' ) );
		$archives_photo = md_setting( array( 'stream', 'archives_photo', 'id' ) );
		$posts_count = wp_count_posts( 'stream' )->publish;
		$likes_count = md_setting( array( 'share', 'stream_likes' ) ) ? md_setting( array( 'share', 'stream_likes' ) ) : '0';
		$cat_desc = category_description();
		$classes = ( ! empty( $archives_photo ) ? ' stream-columns clear' : '' );
		$col2 = ! empty( $archives_photo ) ? ' col col2' : '';
		$context = isset( $args['context'] ) ? $args['context'] : '';
		$title_classes = isset( $args['title_classes'] ) ? ' ' . $args['title_classes'] : ' med-title';
		if ( $archives_title || $archives_desc )
			include( md_template( $this->dir, 'stream/stream-title', true ) );
	}

	/**
	 * Change Loop type for Stream pages.
	 *
	 * @since 5.0.9
	 */

	public function loop_type() {
		return 'stream';
	}

	/**
	 * Load loop with passed data.
	 *
	 * @since 4.8.4
	 */

	public function loop() {
		$c = 0;
		$disable_comments = md_setting( array( 'stream', 'layout', 'disable_comments' ) );
		$loop_h = is_post_type_archive( 'stream' ) || is_tax( 'stream' ) ? 'div' : 'article';
		$article_h = is_singular( 'stream' ) ? 'div' : 'article';
		include( md_template( $this->dir, 'stream/stream-loop', true ) );
	}

	/**
	 * Load single template logic into reusable method.
	 *
	 * @since 5.2
	 */

	public function loop_query( $c ) {
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
		$classes = in_array( $post_id, get_option( 'sticky_posts' ) ) ? ' sticky' : '';
		include( md_template( $this->dir, 'stream/stream-post', true ) );
		if ( $stream_image )
			if ( class_exists( 'md_popup' ) )
				new md_popup( array(
					'id' => "stream_{$post_id}",
					'callback' => array( $this, 'popup' ),
					'atts' => array(
						'post_id' => $post_id
					)
				) );
		if ( is_singular() && $has_thread ) {
			$c = 1;
			foreach ( $thread as $thread_id => $fields ) {
				$html_id = $thread_id;
				$embed_id = ! empty( $fields['post_id'] ) ? $fields['post_id'] : '';
				$post_type = get_post_type( $embed_id );
				$post_date = ! empty( $fields['date'] ) ? esc_attr( $fields['date'] ) : '';
				if ( ! empty( $fields['user_id'] ) )
					$post_author = esc_attr( $fields['user_id'] );
				$post_content = $fields['text'];
				$image_id = ! empty( $fields['image']['id'] ) ? esc_attr( $fields['image']['id'] ) : '';
				$stream_image = wp_get_attachment_image( $image_id, 'thumbnail' );
				include( md_template( $this->dir, 'stream/stream-post', true ) );
				if ( class_exists( 'md_popup' ) && $stream_image )
					md_popup( array(
						'id' => "stream_{$post_id}",
						'callback' => array( $this, 'popup' ),
						'atts' => array(
							'post_id'  => $post_id,
							'image_id' => $image_id
						)
					) );
				$c++;
			}
		}
	}

	/**
	 * Load sticky stream posts.
	 *
	 * @since 5.2
	 */

	public function loop_sticky() {
		$c = 0;
		$sticky_posts = get_option( 'sticky_posts' );
		if ( empty( $sticky_posts ) )
			return;
		$sticky = new WP_Query( array(
			'post_type' => 'stream',
			'post__in' => $sticky_posts,
		) );
		if ( $sticky->have_posts() )
			while ( $sticky->have_posts() ) {
				$sticky->the_post();
				$this->loop_query( $c );
			}
		wp_reset_query();
	}

	/**
	 * Byline block to reuse in template.
	 *
	 * @since 4.8.4
	 */

	public function byline( $post_id, $embed_id, $post_type, $args = null ) {
		$types = $this->types();
		$is_embed = ! empty( $args['is_embed'] ) ? true : null;
		include( md_template( $this->dir, 'stream/stream-byline', true ) );
	}

	/**
	 * Individual popup template.
	 *
	 * @since 4.8.4
	 */

	public function popup( $atts ) {
		include( md_template( $this->dir, 'stream/stream-popup', true ) );
	}

}

new md_stream_templates;