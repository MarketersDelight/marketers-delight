<?php
	$taxonomies = get_object_taxonomies( $post_type );
	$taxonomy = ! empty( $taxonomies[0] ) ? $taxonomies[0] : '';
	$terms = get_terms( $taxonomy );
?>

<div class="category-posts">

<?php if ( ! empty( $terms ) ) :

foreach ( $terms as $term ) :
	$articles = new WP_Query( array(
		'post_type' => $post_type,
		'posts_per_page' => 5,
		'tax_query' => array( array(
			'taxonomy' => $taxonomy,
			'field' => 'slug',
			'terms' => $term->slug
		) )
	) );

	if ( $articles->have_posts() ) :
		$term_id = $term->term_id;
		$category_id = 'category_posts_' . esc_attr( $term_id );
		$category_title_classes = array( 'page-title' );
		$term_image_id = md_term_meta( array( 'featured_image', 'image', 'id' ), $term_id );
		$image_position = md_term_meta( array( 'featured_image', 'position' ), $term_id );
		$image_size = md_term_meta( array( 'featured_image', 'image_width' ), $term_id );

		if ( $term_image_id && $image_position !== 'remove' )
			$category_title_classes[] = 'layout-' . esc_attr( $image_position );

		$category_title_classes = join( ' ', $category_title_classes );
	?>

		<article id="<?php echo esc_attr( $category_id ); ?>" class="category-post">
			<div class="post-box">

				<div class="<?php echo esc_attr( $category_title_classes ); ?>">

					<h2 class="category-post-headline">
						<a href="<?php echo get_term_link( $term_id ); ?>"><?php echo md_text_field( $term->name ); ?></a>
						<span class="badge"><?php echo esc_html( $term->count ); ?>
					</h2>

					<?php if ( $term->description ) : ?>
						<div class="page-description">
							<?php echo wpautop( $term->description ); ?>
						</div>
					<?php endif; ?>

					<?php if ( $term_image_id && $image_position !== 'remove' ) : ?>
						<div class="page-image">
							<a href="<?php echo get_term_link( $term_id ); ?>">
								<?php echo wp_get_attachment_image( $term_image_id, 'full' ); ?>
							</a>
							<?php if ( $image_size )
								md_post_css( array(
									'selector' => "#$category_id .page-image",
									'image' => array(
										'size' => $image_size
									)
								) );
							?>
						</div>
					<?php endif; ?>

				</div>

				<?php while ( $articles->have_posts() ) : $articles->the_post(); ?>

					<div class="category-list">

						<h3 class="category-list-headline">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
								<?php md_byline_item( 'badge' ); ?>
							</a>
						</h3>

					</div>

				<?php endwhile; ?>

				<div class="category-footer">
					<a href="<?php echo get_term_link( $term_id ); ?>" class="category-list-read-more"><?php echo sprintf( __( 'See all (%s)', 'md' ), $term->count ); ?><?php echo md_icon( 'angle-right' ); ?></a>
				</div>

			</div>
		</article>

	<?php endif; wp_reset_query();  ?>

<?php endforeach; ?>

<?php else : ?>

<?php md_template( 'content-item-404' ); ?>

<?php endif; ?>

</div>
