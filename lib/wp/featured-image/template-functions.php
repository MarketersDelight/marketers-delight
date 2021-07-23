<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Featured Image HTML output.
 *
 * @since 4.0
 */

function md_featured_image( $position = null, $size = null, $atts = null ) {
	$position = isset( $position ) ? $position : md_featured_image_position();
	$size = isset( $size ) ? $size : md_featured_image_size();
	$style = '';

	if ( in_array( $position, array( 'headline_cover', 'header_cover', 'header_cover_full' ) ) )
		$style = md_featured_image_style();
?>

	<?php if ( ( $position == 'header_cover' || $position == 'header_cover_full' ) && have_posts() ) : ?>

		<?php while ( have_posts() ) : the_post(); ?>
			<?php md_template( 'headline' ); ?>
		<?php endwhile; ?>

	<?php else : ?>

		<div class="featured-image<?php echo ! empty( $position ) ? ' image-' . esc_attr( $position ) : ''; ?><?php echo md_featured_image_alignment_classes( $position, $atts ); ?>" <?php echo $style; ?>>

			<?php if ( ! is_singular() ) : ?><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'md' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php endif; ?>

				<?php the_post_thumbnail( $size ); ?>

			<?php if ( ! is_singular() ) : ?></a><?php endif; ?>

			<?php md_featured_image_caption(); ?>

		</div>

	<?php endif; ?>

	<?php md_hook_after_featured_image(); ?>

<?php }

/**
 * HTML for MD featured image on taxonomy pages.
 *
 * @since 4.3.5
 */

function md_featured_image_tax( $location ) {
	$position = md_term_meta( array( 'featured_image', 'position' ) );
	$image = md_term_meta( array( 'featured_image', 'image', 'url' ) );
	$size = md_featured_image_size( $position, 'md-thumbnail' );
?>

	<?php if ( in_array( $position, array( 'header_cover', 'header_cover_full' ) ) ) : ?>
		<?php md_archives_title(); ?>
	<?php else :
		$atts['wrap'] = false;
		$align = md_featured_image_alignment_classes( $position, $atts );
		$class = in_array( $position, array( '', 'left', 'right', 'center' ) ) ? ' class="circle"' : '';
		$dim = in_array( $position, array( '', 'left', 'right', 'center' ) ) ? 100 : '';
	?>

		<div class="featured-image-tax<?php echo $align; ?>">
			<img src="<?php echo esc_url( $image ); ?>"<?php echo $class; ?> width="<?php echo esc_attr( $dim ); ?>" height="<?php echo esc_attr( $dim ); ?>" />
		</div>

	<?php endif; ?>

<?php }

/**
 * Checks for inline Featured Image.
 *
 * @since 4.1
 */

function md_has_inline_featured_image() {
	$position = md_featured_image_position();
	if ( has_post_thumbnail() && in_array( $position, array( '', 'left', 'right', 'center' ) ) )
		return true;
}

/**
 * Overlay caption over image.
 *
 * @since 4.0
 */

function md_featured_image_caption() {
	$caption = get_the_post_thumbnail_caption();
	if ( ! empty( $caption ) )
		echo '<span class="featured-image-caption">' . $caption . '</span>';
}

function md_featured_image_caption_load() {
	$position = md_featured_image_position();
	if ( ! in_array( $position, array( 'above_headline', 'below_headline' ) ) )
		md_featured_image_caption();
}
add_action( 'md_hook_headline_top', 'md_featured_image_caption_load' );

/**
 * If image is set above headline, add Featured Image above headline.
 *
 * @since 4.1
 */

function md_featured_image_above_headline() {
	$position = md_featured_image_position();
	if ( has_post_thumbnail() && $position == 'above_headline' )
		md_featured_image();
}

/**
 * If image is set below headline, add Featured Image below headline.
 *
 * @since 4.1
 */

function md_featured_image_below_headline() {
	$position = md_featured_image_position();
	if ( has_post_thumbnail() && $position == 'below_headline' )
		md_featured_image();
}

/**
 * If image is set to header cover, add Featured Image below header.
 *
 * @since 4.1
 */

function md_featured_image_header_cover() {
	if ( is_singular() && md_has_headline() && md_featured_image_position() == 'header_cover' )
		md_featured_image();

	if ( is_category() || is_tax() ) {
		$position = md_term_meta( array( 'featured_image', 'position' ) );

		if ( $position == 'header_cover' )
			md_featured_image_tax( 'header' );
	}
}
add_action( 'md_hook_before_content_box', 'md_featured_image_header_cover', 10 );

function md_featured_image_full_header_cover() {
	if ( is_singular() && md_has_headline() && md_featured_image_position() == 'header_cover_full' )
		md_featured_image();

	if ( is_category() || is_tax() ) {
		$position = md_term_meta( array( 'featured_image', 'position' ) );

		if ( $position == 'header_cover_full' )
			md_featured_image_tax( 'header' );
	}
}
add_action( 'md_hook_after_header', 'md_featured_image_full_header_cover', 10 );

/**
 * Returns position meta value.
 *
 * @since 4.1
 */

function md_featured_image_position( $position = null ) {
	$default = md_setting( array( 'content', 'featured_image', 'position' ) );
	$post_meta = md_post_meta( array( 'featured_image', 'position' ) );
	$term_meta = md_term_meta( array( 'featured_image', 'position' ) );

	if ( isset( $position ) )
		return $position;

	if ( has_filter( 'md_filter_featured_image_position' ) )
		return apply_filters( 'md_filter_featured_image_position', '' );

	if ( ( is_category() || is_tax() ) && ! in_the_loop() && ! empty( $term_meta ) )
		return $term_meta;

	if ( ( is_singular() || in_the_loop() ) && ! empty( $post_meta ) )
		return $post_meta;

	if ( ! empty( $default ) )
		return $default;

	return false;
}

/**
 * Returns image size. Use anywhere you need to set a the_post_thumbnail size.
 *
 * @since 4.1
 */

function md_featured_image_size( $pos = null, $thumb = null ) {
	$position = md_featured_image_position( $pos );

	if ( $position == '' || $position == 'left' || $position == 'right' )
		return ( ! isset( $thumb ) ? 'md-image' : $thumb );

	if ( $position == 'center' )
		return ( ! isset( $thumb ) ? 'full' : $thumb );

	return 'full';
}

/**
 * Featured Image position class.
 *
 * @since 4.1
 */

function md_featured_image_alignment_classes( $position, $atts ) {
	$wrap = ! isset( $atts['wrap'] ) ? ' wrap' : '';

	if ( $position == '' || $position == 'right' )
		return " alignright$wrap";
	elseif ( $position == 'left' )
		return " alignleft$wrap";
	elseif ( $position == 'center' )
		return ' aligncenter';
}

/**
 * Outputs inline style CSS of featured image.
 *
 * @since 4.1
 */

function md_featured_image_style( $url = null, $size = null ) {
	$size = isset( $size ) ? $size : 'md-full';
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), $size );

	if ( empty( $url ) && empty( $image[0] ) )
		return;

	$url = ! empty( $url ) ? $url : $image[0];

	return ' style="background-image: url(\'' . $url . '\');"';
}

/**
 * Outputs inline style CSS to add featured image to element if
 * image position is set to header cover or headline cover.
 *
 * @since 4.1
 */

function md_featured_image_cover( $url = null, $pos = null ) {
	$position = md_featured_image_position( $pos );

	if ( in_array( $position, array( 'headline_cover', 'header_cover' ) ) || ( in_the_loop() && ! is_singular() && $position == 'header_cover_full' ) )
		return md_featured_image_style( $url );
}