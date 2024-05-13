<?php

/**
 * Calculate classes to apply to post box.
 * Returns a string ($classes) to be used in post_class( $classes ) in template.
 *
 * @since 6.0
 */

function md_post_class( $loop, $c = 0 ) {
	$classes = array( 'entry' );
	$cover = md_cover();

	// Posts with Covers outside of content box add image to post content center
//	if ( is_singular() && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
//		$loop['featured_image'] = 'center';

	if ( isset( $loop['featured'] ) )
		if ( isset( $loop['is_featured'] ) )
			$classes[] = 'featured';
		else
			$classes[] = 'standard';

	if ( ! empty( $loop['is_query'] ) || ( ! is_singular() && empty( $loop['is_query'] ) ) )
		$classes[] = $c % 2 == 0 ? 'even' : 'odd';

	if ( isset( $loop['featured_image_id'] ) && $loop['featured_image'] !== 'remove' ) {
		$image_class = $loop['featured_image'];

		if ( $loop['featured_image'] == 'above_headline' )
			$image_class = 'before';
		elseif ( $loop['featured_image'] == 'below_headline' )
			$image_class = 'after';

		$classes[] = 'image-' . str_replace( '_', '-', $image_class );
	}

	if ( ! empty( $cover['position'] ) )
		$classes[] = 'has-cover';

	return join( ' ', $classes );
}

/**
 * Checks if headline is enabled.
 *
 * @since 4.1
 */

function md_has_headline() {
	if ( ! md_meta( array( 'layout', 'content', 'headline' ) ) )
		return true;
}

/**
 * Checks for headline with cover.
 *
 * @since 4.1
 */

function md_has_headline_cover() {
	$cover = md_cover();

	return is_singular() && in_the_loop() && ! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) ? true : false;
}

/**
 * Displays the headline of any post/page.
 *
 * @since 4.1
 */

function md_headline( $args = array() ) {
	$context = isset( $args['context'] ) ? $args['context'] : 'post';

	if ( ! md_has_headline() )
		return;

//	if ( ! md_get_title( $context ) )
//		return;

	$style = '';
	$is_inline = false;
	$cover = md_cover( $context );
	$description = array( 'text' => md_get_description( $context ) );
	$cta = md_cta( $context );

	if (
		isset( $args['inline'] ) ||
		( in_the_loop() || ! empty( $args['loop'] ) ) ||
		( $context == 'page' && md_has_sidebar() && ! md_module( array( 'layout', 'hero_inline' ) ) )
	)
		$is_inline = true;

	if ( is_singular() && in_the_loop() ) {
		$context = 'page';

		if ( in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
			$is_inline = false;
	}

	if ( $cta && $is_inline )
		$description['after'] = $cta;

	$classes = array( 'headline', "$context-headline", 'block' );
	$classes[] = $is_inline ? 'inline' : 'wide';
	$classes = array_merge( $classes, md_cover_classes( $cover ) );

	if ( isset( $args['classes'] ) )
		$classes[] = $args['classes'];

	$classes = join( ' ', $classes );

	if ( isset( $cover['style'] ) && empty( $cover['display']['hide_cover'] ) )
		$style = md_style( $cover['style'] );

	include( md_template( 'headline', true ) );
}

/**
 * Show Page Title of current page.
 *
 * @since 6.0
 */

function md_get_title( $context = 'post' ) {
	$title = get_the_title();

	if ( $context == 'page' )
		if ( is_post_type_archive() ) {
			$post_type_title = post_type_archive_title( '', false );
			$title = md_post_type_field( 'archives_title', $post_type_title );
		}
		elseif ( is_home() || is_singular( 'post' ) )
			$title = md_post_type_field( 'archives_title' );
		elseif ( is_tax() && get_queried_object() ) {
			$term_title = single_term_title( '', false );
			$title = md_term_meta( array( get_post_type(), 'archives_title' ), null, $term_title );
		}
		elseif ( is_category() )
			$title = single_cat_title( '', false );
		elseif ( is_tag() )
			$title = single_tag_title( '', false );
		elseif ( is_author() )
			$title = get_the_author();
		elseif ( is_year() )
			$title = get_the_date( 'Y' );
		elseif ( is_month() )
			$title = get_the_date( 'F Y' );
		elseif ( is_day() )
			$title = get_the_date( 'F j, Y' );

	return $title;
}

/**
 * Render the Post/Page Title with title wrap classes,
 * optional permalink, byline, hooks, and $loop flexibility.
 *
 * @since 6.0
 */

if ( ! function_exists( 'md_title' ) ) :

function md_title( $args = array() ) {
	$context = isset( $args['context'] ) ? $args['context'] : 'post';
	$title = md_get_title( $context );
	$title_html = '';
	$permalink = null;
	$byline_args = array();
	$h = is_singular() || $context == 'page' ? 'h1' : 'h2';

	if ( ( ! is_singular() && $context == 'post' ) || ! empty( $args['loop']['is_query'] ) )
		$permalink = get_permalink();

	if ( isset( $args['loop'] ) )
		$byline_args['loop'] = $args['loop'];

	if ( $context == 'post' && md_module( array( 'loop', 'category_posts', 'enable' ) ) )
		$h = 'h3';

	if ( isset( $args['loop']['is_query'] ) )
		$h = 'h4';

	if ( $permalink )
		$title_html .= '<a href="' . esc_url( $permalink ) . '">';

	$title_html .= apply_filters( "md_{$context}_title", md_text_field( $title ) );

	if ( $permalink )
		$title_html .= '</a>';

	do_action( "md_hook_before_{$context}_title" );

	if ( $context == 'post' )
		md_byline( 'before_headline', $byline_args );

	if ( $title )
		echo "<$h class=\"title\">$title_html</$h>";

	if ( $context == 'post' )
		md_byline( 'after_headline', $byline_args );

	do_action( "md_hook_after_{$context}_title" );
}

endif;

/**
 * Show Description of current page.
 *
 * @since 6.0
 */

function md_get_description( $context = 'post' ) {
	$description = '';

	if ( $context == 'post' && has_excerpt() )
		$description = get_the_excerpt();
	elseif ( $context == 'page' )
		if ( is_post_type_archive() || is_home() )
			$description = md_post_type_field( 'archives_text' );
		elseif ( is_page() )
			$description = get_the_excerpt();
		elseif ( ( is_category() || is_tax() ) && get_queried_object() )
			$description = category_description();
		elseif ( is_author() )
			$description = get_the_author_meta( 'description' );

	return $description;
}

function md_description( $args = array() ) {
	$description = md_get_description();

	if ( isset( $args['text'] ) )
		$description = $args['text'];

	if ( empty( $description ) && empty( $args['after'] ) )
		return;

	return
		'<div class="description">'.
		( $description ? wpautop( $description ) : '' ).
		( isset( $args['after'] ) ? $args['after'] : '' ).
		'</div>';
}

/**
 * Get Hero/inline CTA of any given page. A CTA can be a
 * link group, email form, custom HTML, or more.
 *
 * @since 6.0
 */

function md_cta( $context = 'post' ) {
	if ( $context == 'post' )
		$hero = md_meta( 'hero' );
	else
		$hero = md_module( 'hero' );

	if ( empty( $hero['page_cta'] ) )
		return;

	$html = '';

	if ( $hero['page_cta'] == 'links' ) {
		$html .= '<div class="cta">';

		if ( ! empty( $hero['link_secondary'] ) ) {
			$hero['link_secondary']['link_classes'] = 'cta-link';
			$html .= md_get_link( $hero['link_secondary'] );
		}

		if ( ! empty( $hero['link_primary'] ) ) {
			$hero['link_primary']['link_classes'] = 'cta-link';
			$html .= md_get_link( $hero['link_primary'] );
		}

		$html .= '</div>';
	}
	elseif ( $hero['page_cta'] == 'custom' )
		$html .= md_module( 'custom_html' );

	return $html;
}

/**
 * Show full content or excerpt of any given page.
 *
 * @since 5.1
 */

function md_the_content( $loop ) {
	if ( isset( $loop['content'] ) && $loop['content'] == 'hide' )
		return;

	if ( md_post_meta( array( 'layout', 'content', 'wpautop' ) ) )
		echo get_the_content( $loop['read_more'] );
	else
		the_content( $loop['read_more'] );

	if ( ! isset( $loop['is_query'] ) )
		wp_link_pages();
}

/**
 * Create our own Excerpt with native WP functions so
 * we can modify length and more without use of filters.
 *
 * @since 6.0
 */

function md_the_excerpt( $loop ) {
	if ( isset( $loop['content'] ) && $loop['content'] == 'hide' )
		return;

	$link_class = '';

	if ( ! empty( $loop['read_more_style'] ) )
		$link_class = ' button';

	$excerpt = wp_trim_words( get_the_excerpt(), $loop['excerpt_length'], $loop['excerpt_more'] );

	echo
		wpautop( $excerpt ).
		'<p class="read-more"><a href="' . get_permalink() . '" class="more-link' . $link_class . '">' . esc_html( $loop['read_more'] ) . '</a></p>';
}

/**
 * Displays post/page content text.
 *
 * @since 4.1
 * @renamed 6.0 md_content_text()
 */

function md_content( $loop ) {
	md_hook_before_the_content();

	$loop['content'] = isset( $loop['content'] ) ? $loop['content'] : '';

	if ( get_the_content() && $loop['content'] !== 'hide' ) {
		echo '<div class="the-content">';

		md_hook_the_content_top();

		if ( ( is_singular() && in_the_loop() ) || $loop['content'] == 'full' )
			md_the_content( $loop );
		else
			md_the_excerpt( $loop );

		md_hook_the_content_bottom();

		echo '</div>';
	}

	md_hook_after_the_content();

	if ( ! isset( $loop['post_footer']['remove'] ) )
		md_byline( 'after_post', array(
			'loop' => $loop,
			'classes' => 'post-footer'
		) );
}

/**
 * Checks if author box.
 *
 * @since 4.5
 */

function md_has_author_box() {
	$enable = md_post_type_field( array( 'layout', 'content', 'add_author_box' ) );

	if ( is_singular() ) {
		$add = md_post_meta( array( 'layout', 'content', 'add_author_box' ) );
		$remove = md_post_meta( array( 'layout', 'content', 'author_box' ) );

		if ( ( $enable && ! $remove ) || $add )
			return true;
	}
}

/**
 * Theme author box. This box is used on top of author archive pages.
 *
 * It pulls information from the author's profile in the WordPress dashboard.
 * This way, all user's can show their bio after each post.
 *
 * @since 4.0
 */

function md_author_box() {
	if ( ! md_has_author_box() )
		return;

	$h = is_author() ? 'h1' : 'h3';
	$twitter = get_the_author_meta( 'twitter' );
	$url = get_the_author_meta( 'url' );
	$author = get_author_posts_url( get_the_author_meta( 'ID' ) );
	$desc = get_the_author_meta( 'description' );
	$has_avatar = get_option( 'show_avatars' );

	include( md_template( 'author-box', true ) );
}

/**
 * Outputs the standard WordPress password form with the
 * .form-attached class added to it.
 *
 * @since 4.0
 * @revised 4.3.5
 */

if ( ! function_exists( 'md_password_form' ) ) :

function md_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o =
		'<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post" class="form-attached">'.
			'<p>' . __( 'To view this protected post, enter the password below:', 'md' ) . '</p>'.
			'<input name="post_password" id="' . $label . '" class="form-input" type="password" placeholder="' . __( 'Enter the password&hellip;', 'md' ) . '" size="20" maxlength="20" />'.
			'<input type="submit" name="submit" class="form-submit" value="' . esc_attr__( 'Get Access', 'md' ) . '" />'.
		'</form>';

	return $o;
}

endif;

add_filter( 'the_password_form', 'md_password_form' );
