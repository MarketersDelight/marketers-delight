<?php
/**
 * The Page Title template is used to load titles for non-Single pages,
 * such as Post Type Archive pages, Category/Term Pages, Author Pages, etc.
 *
 * To edit the title for standard pages within the Loop, including the Single
 * page types, please edit post-title.php template file.
 */

echo '<header class="page-title entry-title ' . esc_attr( $classes ) . "\"$style>";

md_overlay( 'page' );

echo $has_header_cover || ! md_has_sidebar() ? '<div class="inner">' : '';

md_featured_media( 'page', array( 'show_image' => array( 'above_headline' ) ) );

do_action( 'md_hook_page_title_top' );

if ( $media && in_array( $media['position'], $title_images ) ) { // Render when image is aligned left/right inside title

	echo '<div class="wrap">';

	md_the_title( 'page', array( 'wrap' => true ) );

	md_featured_media( 'page' );

	echo '</div>';

	md_description( 'page' );

	md_cta( 'page' );

}

elseif ( $is_inline ) { // Render when loaded into a narrow width container

	md_the_title( 'page' );

	md_featured_media( 'page', array( 'show_image' => array( 'below_headline' ) ) );

	if ( $has_wrap )
		echo '<div class="wrap">';

	md_description( 'page', array( 'show_cta' => true ) );

	md_featured_media( 'page', array( 'show_image' => array( 'left', 'right', 'center' ) ) );

	if ( $has_wrap )
		echo '</div>';

}

else { // Default view (Wide)

	if ( $has_wrap )
		echo '<div class="wrap">';

	md_the_title( 'page' );

	md_featured_media( 'page', array( 'show_image' => array( 'below_headline' ) ) );

	md_description( 'page' );

	md_cta( 'page' );

	if ( $has_wrap )
		echo '</div>';

	md_featured_media( 'page', array( 'show_image' => array( 'left', 'right', 'center' ) ) );

}

do_action( 'md_hook_page_title_bottom' );

echo $has_header_cover || ! md_has_sidebar() ? '</div>' : '';

echo '</header>';