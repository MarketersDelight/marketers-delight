<?php
	if ( empty( $cover['image'] ) && $featured_image_id ) {
		$cover['position'] = 'headline_cover';
		$cover['image']['id'] = $featured_image_id;
		$cover['image']['url'] = $cover['style']['bg_image'] = $style['bg_image'] = wp_get_attachment_image_url( $featured_image_id, 'full' );
		$style['bg_size'] = 'auto';
	}
	else {
		$cover['position'] = 'headline_cover';
		$style['bg_image'] = $cover['image']['url'];
		$style['bg_size'] = 'auto';
	}

	if ( $cover )
		$classes .= ' ' . md_cover_classes( $cover, true );
?>

<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?><?php echo md_style( $style ); ?>>

	<?php if ( ! empty( $style['bg_image'] ) )
		md_overlay( $cover );
	?>

	<?php md_title( array( 'loop' => $loop ) ); ?>

	<?php md_content_text( $loop ); ?>

</article>
