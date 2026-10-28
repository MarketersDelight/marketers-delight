<?php

/**
 * Get the plaintext title of the current page based on content type.
 *
 * @since 6.0
 */

function md_get_title( $context = 'post' ) {
	$title = get_the_title();

	// A title inside a loop, like a blog post

	if ( $context == 'post' ) {
		if ( is_404() && ! md_has_custom_404() )
			$title = __( 'Page not found', 'md' );

		return $title;
	}

	// Titles outside a loop like post type archives, category, etc.

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
 * A valet way to get all the needed data about a post/page title.
 *
 * @since 6.1
 */

function md_title_data( $context = 'post', $args = array() ) {
	if ( ! apply_filters( "md_has_{$context}_title", true, $args ) )
		return array();

	$is_page = $context === 'page';

	// Don't show title under conditions

	if ( $is_page && md_module( array( 'layout', 'content', 'headline' ) ) )
		return array();

	if ( ! $is_page && md_is_queried_post() && md_meta( array( 'layout', 'content', 'headline' ) ) )
		return array();

	$title = md_get_title( $context );

	if ( $is_page && ! $title )
		return array();

	$cover = md_cover( $context );
	$has_header_cover = md_has_header_cover( $context, $cover );

	if ( $has_header_cover && in_the_loop() && md_is_queried_post() )
		return array();

	// Ok, show title. Posts in a loop use that loop's settings, like a Query Loop's

	$loop = ! empty( $args['loop'] ) ? $args['loop'] : md_get_loop();

	$media = md_has_media( $context, array( 'loop' => $loop ) );

	// An Author photo takes the title unless the post's own image sits there

	$title_image = $media && in_array( $media['position'], array( 'title_left', 'title_right', 'title_center' ), true );

	if ( ! $title_image && ! $is_page && md_is_queried_post() && ( $author_media = md_get_title_author_media() ) )
		$media = $author_media;

	return array(
		'title' => $title,
		'description' => md_get_description( $context ),
		'media' => $media,
		'cover' => $cover,
		'cta' => md_get_cta( $context ),
		'identity' => $is_page ? md_category_identity() : array(),
		'archive_meta' => $is_page && ! md_module( array( 'archive_meta', 'display', 'hide_page_title' ) ) ? md_get_archive_meta() : array(),
		'header_cover' => $has_header_cover,
		'has_sidebar' => md_has_sidebar(),
		'loop' => $loop
	);
}

/**
 * Render the inner content of a title and heading itself.
 *
 * @since 6.0
 */

function md_the_title( $context = 'post', $args = array() ) {
	$h = ! empty( $args['heading'] ) ? $args['heading'] : 'h1';
	$h_class = ! empty( $args['title_class'] ) ? ' ' . $args['title_class'] : '';
	$h_style = ! empty( $args['title_style'] ) ? md_style( $args['title_style'] ) : '';
	$lede = ! empty( $args['data']['lede'] ) ? $args['data']['lede'] : '';
	$title = apply_filters( "md_{$context}_title", isset( $args['data']['title'] ) ? $args['data']['title'] : md_get_title( $context ) );

	if ( $context !== 'post' && empty( $title ) && ! $lede )
		return;

	if ( $context == 'post' && ! md_is_queried_post() && ! is_404() ) {
		$level = ! empty( $args['loop']['by_category'] ) ? 3 : 2;
		$h = md_loop_heading( $level, $args['loop'] ?? array() );

		if ( $h !== "h$level" )
			$h_class = " h$level" . $h_class;

		if ( ! empty( $title ) )
			$title = '<a href="' . get_permalink() . '">' . $title . '</a>';
	}

	$has_inside = md_has_title_meta( 'inside_title', $context, $args );
	$has_category_icon = ! empty( $args['category_icon'] );

	include md_template( 'features', 'page-title/the-title', true );
}

/**
 * The meta positions a title has, shared by byline and Archive Meta
 * items.
 *
 * @since 6.1
 */

function md_title_meta_positions() {
	return array(
		'before_title' => __( 'Before Title', 'md' ),
		'inside_title' => __( 'Inside Title', 'md' ),
		'after_title' => __( 'After Title', 'md' ),
		'byline_aside' => __( 'Title Aside', 'md' )
	);
}

/**
 * Print a title's meta at one position: byline items on posts, the
 * title's Archive Meta everywhere else.
 *
 * @since 6.1
 */

function md_title_meta( $position, $context, $args = array() ) {
	if ( $context != 'post' )
		echo $args['data']['archive_meta'][$position] ?? '';
	elseif ( isset( $args['byline'] ) )
		md_byline( $position, $args );
}

/**
 * Check if a title has meta at one position.
 *
 * @since 6.1
 */

function md_has_title_meta( $position, $context, $args = array() ) {
	if ( $context != 'post' )
		return ! empty( $args['data']['archive_meta'][$position] );

	return isset( $args['byline'] ) && ! empty( md_get_byline( $position, $args ) );
}

/**
 * Get the title position the CTA is placed in. Titles rendered without
 * data, like bare md_the_title() calls, have no CTA.
 *
 * @since 6.1
 */

function md_get_title_cta_position( $args = array() ) {
	if ( ! isset( $args['data'] ) )
		return '';

	$cta = $args['data']['cta'] ?? array();
	$foot = $args['data']['foot'] ?? '';

	if ( ( empty( $cta['page_cta'] ) || $cta['page_cta'] === 'remove' ) && ! $foot )
		return '';

	return ! empty( $cta['title_position'] ) ? $cta['title_position'] : 'after_title';
}

/**
 * Print everything placed at one title position: its meta, then the CTA
 * and foot text when the CTA is placed there.
 *
 * @since 6.1
 */

function md_title_position( $position, $context, $args = array() ) {
	md_title_meta( $position, $context, $args );

	if ( md_get_title_cta_position( $args ) !== $position )
		return;

	md_cta( $context, $args['data']['cta'] ?? array() );

	if ( ! empty( $args['data']['foot'] ) )
		echo '<p class="foot">' . $args['data']['foot'] . '</p>';
}

/**
 * Check if anything is placed at one title position.
 *
 * @since 6.1
 */

function md_has_title_position( $position, $context, $args = array() ) {
	return md_has_title_meta( $position, $context, $args ) || md_get_title_cta_position( $args ) === $position;
}

/**
 * Call the title template with conditions in place.
 *
 * @since 6.0
 */

function md_title( $context = 'post', $args = array() ) {
	$data = md_title_data( $context, $args );

	if ( ! $data )
		return;

	md_title_template( $context, $data, $args );
}

/**
 * Build the full title template with surrounding markup.
 *
 * @since 6.1
 */

function md_title_template( $context, $data, $args = array() ) {
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
	$media_position = $context == 'post' && empty( $media['author'] ) && ! empty( $loop['featured_image'] ) ? md_loop_media_position( $loop ) : ( $media['position'] ?? '' );

	// An untitled post keeps its title area only to hold an image beside the title

	if ( $context === 'post' && ! $data['title'] && ! ( $media && in_array( $media_position, $title_images, true ) ) )
		return;

	// Anything placed in Title Aside splits the title into main and side regions

	if ( $context == 'post' )
		$args['byline'] = true;

	$args['aside'] = md_has_title_position( 'byline_aside', $context, $args );

	// The regions always sit in .wrap, so full width images stay out of the split

	if ( $args['aside'] )
		$has_wrap = true;

	// Category identity

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

	// Featured media classes

	$mobile = $media['mobile'] ?? '';

	if ( $context === 'post' && ! $mobile && ! empty( $args['loop']['featured_image_mobile'] ) )
		$mobile = $args['loop']['featured_image_mobile'];

	if ( $media && ( $context != 'post' || in_array( $media_position, $title_images, true ) ) )
		$classes = array_merge( $classes, md_get_image_position_classes( $media_position, array( 'mobile' => $mobile ) ) );
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

	// A centered title image keeps its aside stacked under the title

	if ( $args['aside'] && $media_position !== 'title_center' )
		$classes[] = 'has-aside';

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
 * @since 6.1
 */

function md_get_description( $context = 'post' ) {
	$description = '';

	if ( $context == 'post' && md_is_queried_post() ) {
		$excerpt = md_post_type_field( array( 'page_cover', 'display', 'show_excerpt' ) ) && has_excerpt() ? get_the_excerpt() : '';
		$description = md_post_meta( array( 'page_cover', 'title_content' ), null, $excerpt );
	}
	elseif ( $context == 'page' ) {
		if ( is_post_type_archive() || is_home() )
			$description = md_parse_text( md_post_type_field( 'archives_text' ), 'archive' );
		elseif ( ( is_category() || is_tag() || is_tax() ) && get_queried_object() ) {
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

/**
 * Get a category's icon, bg color, and best contrast color.
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
