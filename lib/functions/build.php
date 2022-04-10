<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The functions below load template HTML from the /templates/ folder.
 * These functions are then hooked into their respective locations throughout
 * the theme by hooks below. These can be unhooked with a child theme, OR:
 *
 * You can edit any of these templates (without unhooking) by opening any file from
 * the /templates/ folder and copying and pasting the contents into a child
 * theme with the same file path.
 */

// Build header
add_action( 'md_hook_header', 'md_logo' );
add_action( 'md_hook_header_aside', 'md_menu' );
add_action( 'md_hook_header_triggers', 'md_header_triggers' );
add_action( 'md_hook_before_content_box', 'md_main_menu', 2 );

// Build content box
add_action( 'md_hook_content', 'md_archives_title' );
add_action( 'md_hook_content', 'md_loop' );
add_action( 'md_hook_content', 'md_pagination', 30 );

// Build content item (post / page structure)
add_action( 'md_hook_content_item', 'md_featured_image_above_headline', 10 );
add_action( 'md_hook_content_item', 'md_headline', 20 );
add_action( 'md_hook_content_item', 'md_featured_image_below_headline', 30 );
add_action( 'md_hook_content_item', 'md_content_text', 40 );
add_action( 'md_hook_content_item', 'md_author', 50 );
add_action( 'md_hook_content_item', 'md_comments', 60 );
add_action( 'md_hook_content_item', 'md_post_nav', 70 );

// Build footer
add_action( 'md_hook_footer', 'md_footer_columns_template' );
add_action( 'md_hook_footer_bottom', 'md_footer_copy' );

/**
 * Experimental template loader.
 *
 * @since 5.1
 */

function md_templates() {
	$hook = 'md_hook_before_headline';
	$byline_position = md_get_loop( array( 'byline_position' ) );

	if ( md_has_breadcrumbs() )
		add_action( 'md_hook_content_box_top', 'md_breadcrumbs' );

	if ( $byline_position == 'after_headline' )
		$hook = 'md_hook_after_headline';

	if ( ! is_404() )
		add_action( $hook, 'md_byline' );
}

add_action( 'template_redirect', 'md_templates' );

/**
 * Displays the logo, used in header by default.
 *
 * @since 4.1
 */

function md_logo() {
	md_template( 'logo' );
}

function md_secondary_logo() {
	$secondary_logo_id = md_setting( array( 'header', 'logo_alt', 'id' ) );
	$secondary_logo_url = md_setting( array( 'header', 'logo_alt', 'url' ) ); // if logo set pre-5.0
	echo '<span class="secondary-logo"><a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link" rel="home">';
	if ( ! empty( $secondary_logo_id ) )
		echo wp_get_attachment_image( $secondary_logo_id, 'full' );
	elseif ( ! empty( $secondary_logo_url ) )
		echo '<img src="' . esc_url( $secondary_logo ) . '" alt="' . get_bloginfo( 'name' ) . '" />';
	echo '</a></span>';
}

function md_the_logo() {
	if ( md_has_custom_logo() ) {
		$position = md_featured_image_position();
		$logo_id = md_setting( array( 'header', 'logo', 'id' ) );
		$secondary_logo = md_setting( array( 'header', 'logo_alt', 'url' ) );
		$text_global = md_setting( array( 'content', 'featured_image', 'styles', 'text_color' ) );
		$text_single = md_post_meta( array( 'featured_image', 'text_color', 'alternate' ) );

		if ( ( is_singular() || is_category() || is_tax() ) && $position == 'header_cover_full' && ! empty( $secondary_logo ) || apply_filters( 'md_filter_logo_alt', false ) ) {
			if ( ( ! empty( $logo_id ) && $secondary_logo ) && ( ( empty( $text_global ) && empty( $text_single ) ) || ( ! empty( $text_global ) && ! empty( $text_single ) ) ) )
				md_secondary_logo();
			else
				echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'custom-logo-link' ) );
		}
		else
			echo wp_get_attachment_image( $logo_id, 'full', false, array( 'class' => 'custom-logo-link' ) );
	}
}

/**
 * Displays the header menu.
 *
 * @since 4.1
 */

function md_menu() {
	md_template( 'menus/menu' );
}

/**
 * Load main menu template file.
 *
 * @since 4.1
 */

function md_main_menu() {
	if ( md_has_main_menu() )
		md_template( 'menus/main-menu' );
}

/**
 * Displays the header menu trigger
 *
 * @since 4.8
 */

function md_header_triggers() {
	md_template( 'header-triggers' );
}

/**
 * Displays titles for various types of archives.
 *
 * @since 4.0
 */

function md_content_box() {
	if ( md_has_content_box() )
		md_template( 'content-box' );
}

/**
 * Displays titles for various types of archives.
 *
 * @since 4.0
 */

function md_archives_title() {
	md_template( 'archives-title' );
}

/**
 * Render breadcrumbs template.
 *
 * @since 5.2.2
 */

function md_breadcrumbs() {
	$post_type_title = $category_url = $category_title = '';
	$post_id = get_the_ID();
	$post_type = get_post_type();
	$post_type_obj = get_post_type_object( $post_type );
	if ( ! empty( $post_type_obj ) )
		$post_type_title = md_text_field( $post_type_obj->labels->name );
	$front_page = get_option( 'show_on_front' );
	if ( $post_type == 'post' ) {
		$blog_id = get_option( 'page_for_posts' );
		if ( ! empty( $blog_id ) )
			$post_type_title = get_the_title( $blog_id );
		else
			$post_type_title = __( 'Blog', 'md' );
	}
	if ( is_category() )
		$terms = get_the_category();
	elseif ( is_tag() )
		$terms = get_tag( get_queried_object_id() );
	else {
		$taxonomies = get_taxonomies( array( 'public' => true ) );
		$terms = wp_get_post_terms( $post_id, $taxonomies );
	}
	if ( ! empty( $terms ) )
		if ( is_tag() ) {
			$category_url = get_term_link( $terms->term_id );
			$category_title = $terms->name;
		}
		else {
			$category_url = get_term_link( $terms[0]->term_id );
			$category_title = $terms[0]->name;
		}
	include( md_template( 'breadcrumbs', true ) );
}

/**
 * The Main Loop used on all posts, pages, and archives.
 *
 * @since 4.1
 */

function md_loop() {
	$c = 1;
	$type = md_get_loop();
	$path = $type == 'default' ? '' : "-{$type}";
	$loop = md_get_loop( 'fields' );
	$byline_position = md_get_loop( array( 'byline_position' ) );
	$content = md_get_loop( array( 'content' ) );
	include( md_template( "loops/loop{$path}", true ) );
}

/**
 * Call custom 404 content box template.
 *
 * @since 5.1
 */

function md_404_template() {
	$page_id = md_setting( array( 'settings', '404_page' ) );
	if ( empty( $page_id ) )
		md_template( 'content-item-404' );
	else {
		$page404 = new WP_Query( array(
			'post_type' => 'page',
			'p' => $page_id,
			'post_status' => array( 'publish' ),
			'fields' => 'ids'
		) );
		if ( $page404->have_posts() )
			while ( $page404->have_posts() ) {
				$page404->the_post();
				md_template( 'content-item' );
			}
		else
			md_template( 'content-item-404' );
		wp_reset_query();
	}
}

/**
 * Create pagination for use on home and archives pages.
 *
 * @since 4.0
 */

function md_pagination() {
	md_template( 'pagination' );
}

/**
 * Displays full comments template.
 *
 * @since 4.1
 */

function md_comments() {
	if ( ! is_404() && md_has_comments() && is_singular() )
		comments_template( '/templates/comments/comments.php' );
}

/**
 * Creates previous/next post links at the end of a
 * single entry.
 *
 * @since 4.0
 */

function md_post_nav() {
	if ( is_singular( 'post' ) || is_singular( 'docs' ) )
		md_template( 'post-nav' );
}

/**
 * This function accounts for the output of an individual comment, and is referenced
 * in wp_list_comments(). see comments.php.
 *
 * @since 4.1
 */

function md_comment( $comment, $args, $depth ) {
	global $post;
	$GLOBALS['comment'] = $comment;
	include( md_template( 'comments/comment', true ) );
}

/**
 * Displays the headline of any post/page.
 *
 * @since 4.1
 */

function md_headline() {
	if ( md_has_headline() && ! md_has_headline_cover() )
		include( md_template( 'headline', true ) );
}

/**
 * Show full content or excerpt of any given page.
 *
 * @since 5.1
 */

function md_the_content() {
	$archives = md_get_loop( array( 'loop', 'archives' ) );
	$content = md_get_loop( array( 'loop', 'content' ) );
	$read_more = md_read_more_text();
?>
	<?php if ( ! is_singular() && $content == 'excerpt' ) : ?>
		<?php the_excerpt(); ?>
		<?php if ( empty( $archives ) || $archives == 'default' ) : ?>
			<a href="<?php the_permalink(); ?>" class="more-link"><?php echo esc_html( $read_more ); ?></a>
		<?php endif; ?>
	<?php else : ?>
		<?php the_content( $read_more ); ?>
		<?php if ( is_singular() ) : ?>
			<?php wp_link_pages(); ?>
		<?php endif; ?>
	<?php endif; ?>
<?php }

/**
 * Displays post/page content text.
 *
 * @since 4.1
 */

function md_content_text() {
	$content = md_get_loop( array( 'loop', 'content' ) );
	$read_more = md_read_more_text();
	if ( $content !== 'hide' || is_singular() || is_404() )
		include( md_template( 'text', true ) );
}

/**
 * Display read more text.
 *
 * @since 5.1
 */

function md_read_more_text() {
	$read_more = md_get_loop( array( 'loop', 'read_more' ) );
	return ! empty( $read_more ) ? $read_more : __( 'Continue reading &rarr;', 'md' );
}

/**
 * Output post byline template.
 *
 * @since 4.0
 */

function md_byline() {
	if ( md_has_byline() )
		md_template( 'byline/byline' );
}

/**
 * Display template of individual byline items with
 * optional arguments.
 *
 * @since 5.1
 */

function md_byline_item( $item, $args = array() ) {
	$template = locate_template( "templates/byline/$item.php" );
	$byline = md_get_byline();
	if ( $template )
		include( md_template( "byline/$item", true ) );
}

/**
 * Theme author box. This box is used on top of author archive pages.
 *
 * It pulls information from the author's profile in the WordPress dashboard.
 * This way, all user's can show their bio after each post.
 *
 * @since 4.0
 */

function md_author() {
	if ( md_has_author_box() )
		md_author_box();
}

function md_author_box() {
	$html = is_author() ? 'h1' : 'p';
	$twitter = get_the_author_meta( 'twitter' );
	$desc = get_the_author_meta( 'description' );
	$url = get_the_author_meta( 'url' );
	$author = get_author_posts_url( get_the_author_meta( 'ID' ) );
	$archive = md_setting( array( 'content', 'author_box', 'all_posts' ) );
	$has_avatar = get_option( 'show_avatars' );
	include( md_template( 'author-box', true ) );
}

/**
 * Add widgetized footer columns to footer.
 *
 * @since 4.5
 */

function md_footer_columns_template() {
	md_template( 'footer-columns' );
}

/**
 * Add widgetized footer copyright text to footer.
 *
 * @since 4.5
 */

function md_footer_copy() {
	md_template( 'footer-copy' );
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