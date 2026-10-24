<?php

/**
 * Render the inner content of a title.
 *
 * @since 6.0
 */

function md_the_title( $context = 'post', $args = array() ) {
	$h = ! empty( $args['heading'] ) ? $args['heading'] : 'h1';
	$h_class = ! empty( $args['title_class'] ) ? ' ' . $args['title_class'] : '';
	$h_style = ! empty( $args['title_style'] ) ? md_style( $args['title_style'] ) : '';
	$lede = ! empty( $args['data']['lede'] ) ? $args['data']['lede'] : '';
	$title = apply_filters( "md_{$context}_title", isset( $args['data']['title'] ) ? $args['data']['title'] : md_get_title( $context ) );

	// Outside of posts, an empty title leaves no empty wrappers behind

	if ( $context !== 'post' && empty( $title ) && ! $lede )
		return;

	if ( $context == 'post' && ! is_singular() && ! is_404() ) {
		$level = ! empty( $args['loop']['by_category'] ) ? 3 : 2;
		$h = md_loop_heading( $level, $args['loop'] ?? array() );

		// A lowered heading keeps its original level's look, such as <h3 class="h2">.

		if ( $h !== "h$level" )
			$h_class = " h$level" . $h_class;

		if ( ! empty( $title ) )
			$title = '<a href="' . get_permalink() . '">' . $title . '</a>';
	}

	$has_inside = isset( $args['byline'] ) && ! empty( md_get_byline( 'inside_title', $args ) );
	$has_category_icon = ! empty( $args['category_icon'] );

	include md_template( 'features', 'page-title/the-title', true );
}

/**
 * Get the plaintext title of the current page based on content type.
 *
 * @since 6.0
 */

function md_get_title( $context = 'post' ) {
	$title = get_the_title();

	if ( $context == 'post' ) {
		if ( is_404() && ! md_has_custom_404() )
			$title = __( 'Page not found', 'md' );

		return $title;
	}

	if ( is_home() || is_post_type_archive() || is_singular( 'post' ) ) {
		$post_type_title = post_type_archive_title( '', false );
		$title = md_parse_text( md_post_type_field( 'archives_title', $post_type_title ), 'archive' );
	}
	elseif ( is_tax() || is_category() || is_tag() ) {
		$title = md_term_meta( array( 'hero', 'archives_title' ) );

		if ( ! $title )
			$title = md_module( 'archives_title', single_term_title( '', false ), array( 'inherit_post_type' => false ) );

		$title = md_parse_text( $title, 'term' );
	}
	elseif ( is_search() )
		$title = sprintf( esc_html__( 'Search Results For: %s', 'md' ), '<span class="search-query">' . get_search_query() . '</span>' );
	elseif ( is_author() ) {
		$title = md_post_type_field( 'archives_title', get_the_author_meta( 'display_name', get_queried_object_id() ), 'author' );
		$title = md_parse_text( $title, 'archive' );
	}
	elseif ( is_year() )
		$title = get_the_date( 'Y' ) ?: get_query_var( 'year' );
	elseif ( is_month() )
		$title = get_the_date( 'F Y' ) ?: date_i18n( 'F Y', mktime( 0, 0, 0, (int) get_query_var( 'monthnum' ), 1, (int) get_query_var( 'year' ) ) );
	elseif ( is_day() )
		$title = get_the_date( 'F j, Y' ) ?: date_i18n( 'F j, Y', mktime( 0, 0, 0, (int) get_query_var( 'monthnum' ), (int) get_query_var( 'day' ), (int) get_query_var( 'year' ) ) );

	return wp_kses_post( trim( (string) $title ) );
}

/**
 * Get a category's validated icon and color, plus the text color that
 * reads best on that color.
 *
 * @since 6.0
 */

function md_category_identity( $term = null ) {
	$term = $term ?: get_queried_object();

	if ( ! $term instanceof WP_Term )
		return array( 'icon' => '', 'color' => '', 'text' => '' );

	$icon = md_term_meta( array( 'hero', 'archives_icon' ), $term->term_id, '' );
	$color = md_color_hex( md_term_meta( array( 'hero', 'archives_color' ), $term->term_id, '' ) );

	return array(
		'icon' => $icon && isset( md_icons()[$icon] ) ? $icon : '',
		'color' => $color,
		'text' => md_contrast_text( $color )
	);
}

/**
 * Resolve everything the Post or Page title shows for the current page:
 * title, description, featured media, cover, CTA, category identity, and
 * whether to show Archive Meta, with all of their setting inheritance. Returns an empty array when the
 * title shouldn't render. The title is the raw value; md_the_title()
 * applies the md_{$context}_title filter.
 *
 * @since 6.0.4
 */

function md_title_data( $context = 'post', $args = array() ) {
	if ( ! apply_filters( "md_has_{$context}_title", true, $args ) )
		return array();

	if ( $context === 'page' && md_module( array( 'layout', 'content', 'headline' ) ) )
		return array();

	$title = md_get_title( $context );

	if ( ! $title && $context !== 'post' )
		return array();

	if ( $context === 'post' && is_singular() && get_the_ID() === get_queried_object_id() && md_meta( array( 'layout', 'content', 'headline' ) ) )
		return array();

	$has_header_cover = md_has_header_cover( $context );

	if ( $has_header_cover && in_the_loop() )
		return array();

	$loop = md_get_loop();

	return array(
		'title' => $title,
		'description' => md_get_description( $context ),
		'media' => md_has_media( $context, array( 'loop' => $loop ) ),
		'cover' => md_cover( $context ),
		'cta' => md_get_cta( $context ),
		'identity' => $context === 'page' ? md_category_identity() : array(),
		'archive_meta' => $context === 'page' && ! md_module( array( 'archive_meta', 'display', 'hide_page_title' ) ),
		'header_cover' => $has_header_cover,
		'has_sidebar' => md_has_sidebar(),
		'loop' => $loop
	);
}

/**
 * Render the layout markup of the Post or Page title with supporting
 * elements like image, byline, etc. A title used within a loop
 * has key differences than when rendered as the main Page Title.
 *
 * $context accepts 'post' by default or 'page'
 *
 * @since 6.0
 */

function md_title( $context = 'post', $args = array() ) {
	$data = md_title_data( $context, $args );

	if ( ! $data )
		return;

	md_render_title( $context, $data, $args );
}

/**
 * Render title markup from resolved data. Reads nothing from the page
 * itself, so any caller can pass its own data in the shape
 * md_title_data() returns, plus an optional 'lede' printed above the
 * title and 'foot' printed after the CTA.
 *
 * $args also takes 'tag' and 'heading' for the wrapper and title tags,
 * 'id', 'classes' and 'style' for the wrapper, 'title_class' and
 * 'title_style' for the title, 'top' for HTML printed first inside the
 * wrapper (like a close button), and 'inner' to wrap the content in
 * .inner without a Header Cover.
 *
 * @since 6.0.4
 */

function md_render_title( $context, $data, $args = array() ) {
	$args['data'] = $data;
	$classes = $style = array();
	$inline_images = array( 'left', 'right' );
	$title_images = array( 'title_left', 'title_right', 'title_center' );
	$full_width = array( 'center', 'above_headline', 'below_headline' );
	$loop = $data['loop'];
	$media = $data['media'];
	$cover = $data['cover'];
	$has_header_cover = $data['header_cover'];
	$has_inner = $has_header_cover || ! empty( $args['inner'] );
	$has_sidebar = $data['has_sidebar'];
	$has_wrap = $context != 'post' && $media && ! in_array( $media['position'], $full_width, true );
	$args['loop'] = ! empty( $args['loop'] ) ? $args['loop'] : array();
	$media_position = $context == 'post' && ! empty( $loop['featured_image'] ) ? md_loop_media_position( $loop ) : ( $media['position'] ?? '' );

	// An untitled post keeps its title area only to hold an image beside the title

	if ( $context === 'post' && ! $data['title'] && ! ( $media && in_array( $media_position, $title_images, true ) ) )
		return;

	// Category identity.

	if ( $data['identity'] ) {
		$category_identity = $data['identity'];

		if ( $category_identity['icon'] )
			$args['category_icon'] = $category_identity['icon'];

		if ( $category_identity['color'] ) {
			$style['vars']['md-category-color'] = $category_identity['color'];
			$style['vars']['md-category-text'] = $category_identity['text'];
		}
	}

	// Layout type classes

	if ( ! $has_header_cover && ( ( $context !== 'post' && $has_sidebar ) || ( $context === 'post' && ! empty( $loop['is_slim'] ) ) ) )
		$classes[] = 'inline';
	else
		$classes[] = 'wide';

	// Featured image related classes

	if ( $media && ( $context != 'post' || in_array( $media_position, $title_images, true ) ) )
		$classes = array_merge( $classes, md_get_image_position_classes( $media_position, array( 'mobile' => $media['mobile'] ?? '' ) ) );
	elseif ( ! $media && $context == 'post' && empty( $cover['position'] ) )
		$classes[] = 'no-media';

	// Page cover classes

	if ( $context == 'post' )
		$classes[] = 'item';

	if ( ! empty( $cover['position'] ) ) {
		$classes[] = md_cover_classes( $context, $cover );

		if ( ! empty( $cover['photo']['id'] ) )
			$style['bg_image'] = wp_get_attachment_image_url( $cover['photo']['id'], 'full' );
		elseif ( ! empty( $cover['bg_color'] ) )
			$style['bg_color'] = $cover['bg_color'];
	}

	// Caller's own wrapper attributes

	if ( ! empty( $args['classes'] ) )
		$classes[] = $args['classes'];

	if ( ! empty( $args['style'] ) )
		$style = array_merge( $style, $args['style'] );

	$id = ! empty( $args['id'] ) ? ' id="' . esc_attr( $args['id'] ) . '"' : '';
	$classes = join( ' ', $classes );
	$style = md_style( $style );

	// Render title

	do_action( "md_hook_{$context}_title_before" );

	include md_template( 'features', 'page-title/title', true );

	do_action( "md_hook_{$context}_title_after" );
}

/**
 * Show Description of current page.
 *
 * @since 6.0
 */

function md_description( $context = 'post', $args = array() ) {
	if ( ! apply_filters( "md_has_{$context}_title_description", true ) )
		return;

	$description = array_key_exists( 'description', $args ) ? $args['description'] : md_get_description( $context );

	if ( empty( $description ) )
		return;

	include md_template( 'features', 'page-title/description', true );
}

/**
 * Get the description of the current page title: a single post's cover
 * text or excerpt, or the archive, term, or author description.
 *
 * @since 6.0.4
 */

function md_get_description( $context = 'post' ) {
	$description = '';

	if ( $context == 'post' && is_singular() ) {
		$excerpt = md_post_type_field( array( 'page_cover', 'display', 'show_excerpt' ) ) && has_excerpt() ? get_the_excerpt() : '';
		$description = md_post_meta( array( 'page_cover', 'title_content' ), null, $excerpt );
	}
	elseif ( $context == 'page' ) {
		if ( is_post_type_archive() || is_home() )
			$description = md_parse_text( md_post_type_field( 'archives_text' ), 'archive' );
		elseif ( ( is_category() || is_tax() ) && get_queried_object() ) {
			$description = md_term_meta( array( 'hero', 'archives_text' ) );

			if ( ! $description )
				$description = category_description();

			if ( ! $description )
				$description = md_taxonomy_field( 'archives_text' );

			$description = md_parse_text( $description, 'term' );
		}
		elseif ( is_author() ) {
			$description = get_the_author_meta( 'description', get_queried_object_id() );

			if ( empty( $description ) )
				$description = md_post_type_field( 'archives_text', '', 'author' );

			$description = md_parse_text( $description, 'archive' );
		}
	}

	return $description;
}
