<?php
	$cover = md_cover();
	$cover['position'] = 'headline_cover';

	if ( empty( $cover['photo'] ) && isset( $loop['featured_image_id'] ) ) {
		$cover['photo']['id'] = get_post_thumbnail_id();
		$cover['photo']['url'] = $cover['style']['bg_image'] = $style['bg_image'] = get_the_post_thumbnail_url( null, 'full' );
	}
	else
		$style['bg_image'] = $cover['photo']['url'];

	if ( $cover )
		$classes .= ' ' . md_cover_classes( $cover, true );
?>

<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?><?php echo md_style( $style ); ?>>

	<?php
		if ( ! empty( $style['bg_image'] ) )
			md_overlay( $cover );
	?>

	<div class="post-header">

		<?php md_title( array(
			'loop' => $loop
		) ); ?>

		<?php md_the_content( $loop ); ?>

	</div>

	<a href="<?php the_permalink(); ?>" class="clickable"></a>

</article>
