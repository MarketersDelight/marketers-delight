<section id="<?php echo esc_attr( $category->slug ); ?>" class="entry">

	<div class="category-title">

		<h2 class="title">
			<a href="<?php echo get_term_link( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></a>
		</h2>

		<?php if ( $category_description ) : ?>
		<div class="description">
			<?php echo wpautop( $category_description ); ?>
		</div>
		<?php endif; ?>

	</div>

	<?php if ( $show_subcategory ) {
		$term = $category;
		include md_template( 'loop/subcategory', true );
	} ?>

	<div class="<?php echo esc_attr( $loop_classes ); ?>">
		<?php while ( $posts->have_posts() ) {
			$posts->the_post();
			include md_template( 'loop/the-post', true );
		} ?>
	</div>

	<?php if ( $posts->post_count >= $posts->query_vars['posts_per_page'] ) : ?>
	<div class="category-more">
		<a href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>"><?php printf( esc_html__( 'View all %s posts &rarr;', 'md' ), esc_html( $category->name ) ); ?></a>
	</div>
	<?php endif; ?>

</section>