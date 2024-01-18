<?php
	if ( empty( $cover['image'] ) && $featured_image_id ) {
		$cover['position'] = 'headline_cover';
		$cover['image']['id'] = $featured_image_id;
		$cover['image']['url'] = $cover['style']['bg_image'] = $style['bg_image'] = wp_get_attachment_image_url( $featured_image_id, 'full' );
		$style['bg_size'] = 'auto';
	}
	else {
		$cover['position'] = 'headline_cover';
		$style['bg_image'] = $cover['style']['bg_image'];
		$style['bg_size'] = 'auto';
	}

	if ( $cover )
		$classes .= ' ' . md_cover_classes( $cover, true );
?>

<article id="post_<?php the_ID(); ?>" <?php post_class( $classes ); ?><?php echo md_style( $style ); ?>>

	<?php if ( ! empty( $style['bg_image'] ) )
		md_overlay( $cover );
	?>

	<?php do_action( 'md_hook_post_header_top' ); ?>

	<div class="title-wrap">
		<h2 class="title"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
	</div>

	<?php do_action( 'md_hook_post_header_bottom' ); ?>

	<?php md_content_text( $loop ); ?>

</article>
