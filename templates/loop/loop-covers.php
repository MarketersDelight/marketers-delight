<?php
	if ( empty( $cover['image'] ) && isset( $loop['featured_image_id'] ) ) {
		$cover['position'] = 'headline_cover';
		$cover['image']['id'] = $featured_image_id;
		$cover['image']['url'] = $cover['style']['bg_image'] = $style['bg_image'] = wp_get_attachment_image_url( $featured_image_id, 'full' );
	}
	else {
		$cover['position'] = 'headline_cover';
		$style['bg_image'] = $cover['image']['url'];
	}

	if ( $cover )
		$classes .= ' ' . md_cover_classes( $cover, true );
?>

<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?><?php echo md_style( $style ); ?>>

	<?php
		if ( ! empty( $style['bg_image'] ) )
			md_overlay( $cover );

		md_loop_list( $loop );
	?>

	<div class="post-header">

		<?php md_title( array(
			'loop' => $loop
		) ); ?>

		<?php md_the_content( $loop ); ?>

	</div>

	<a href="<?php the_permalink(); ?>" class="clickable"></a>

</article>
