<?php
	$post_type = md_get_post_type();
	$taxonomies = get_object_taxonomies( $post_type );
	$taxonomy = ! empty( $taxonomies[0] ) ? $taxonomies[0] : '';
	$terms = get_terms( $taxonomy );
?>

<div class="category-posts">

	<?php if ( ! empty( $terms ) ) : ?>

		<?php foreach ( $terms as $term ) :
			$articles = new WP_Query( array(
				'post_type' => $post_type,
				'posts_per_page' => 5,
				'tax_query' => array( array(
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $term->slug
				) )
			) );
		?>

			<?php if ( $articles->have_posts() ) : ?>

				<div class="category-post">

					<div class="category-post-head">

						<h2 class="category-post-title">
							<a href="<?php echo get_term_link( $term->term_id ); ?>"><?php echo md_text_field( $term->name ); ?></a>
							<span class="badge"><?php echo esc_html( $term->count ); ?>
						</h2>

						<?php if ( $term->description ) : ?>
							<?php echo wpautop( $term->description ); ?>
						<?php endif; ?>

					</div>

					<div class="category-post-content">

						<ul class="list">

							<?php while ( $articles->have_posts() ) : $articles->the_post(); ?>
								<li>
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> <?php md_byline_item( 'badge' ); ?>
								</li>
							<?php endwhile; ?>

							<li><a href="<?php echo get_term_link( $term->term_id ); ?>"><?php echo sprintf( __( 'Browse all (%s)', 'md' ), $term->count ); ?></a></li>

						</ul>

					</div>

				</div>

			<?php endif; ?>

			<?php wp_reset_query(); ?>

		<?php endforeach; ?>

	<?php else : ?>

		<?php md_template( 'content-item-404' ); ?>

	<?php endif; ?>

</div>
