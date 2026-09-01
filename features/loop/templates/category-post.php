<section id="<?php echo esc_attr( $category->slug ); ?>" class="<?php echo esc_attr( $loop['category_classes'] ); ?>">

	<?php md_byline( 'before_post', array(
		'context' => 'category_entry',
		'category' => $category
	) ); ?>

	<div class="category-title entry-title">

		<?php md_byline( 'before_title', array(
			'context' => 'category_entry',
			'category' => $category
		) ); ?>

		<h2 class="title">
			<a href="<?php echo get_term_link( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></a>
		</h2>

		<?php md_byline( 'after_title', array(
			'context' => 'category_entry',
			'category' => $category
		) ); ?>

		<?php if ( $category_description && empty( $loop['category']['hide_description'] ) ) : ?>
		<div class="description">
			<?php echo wpautop( $category_description ); ?>
		</div>
		<?php endif; ?>

	</div>

	<?php if ( isset( $posts ) ) : // Show subcategories on "list posts by category" view
		if ( $show_subcategory ) {
			$term = $category;

			include md_template( 'features', 'loop/subcategory', true );
		}
	?>

	<div class="<?php echo esc_attr( $loop_classes ); ?>"<?php echo $loop_columns_style; ?>>

		<?php while ( $posts->have_posts() ) {
			$posts->the_post();

			include md_template( 'features', 'loop/the-post', true );
		} ?>

		<?php if ( $posts->post_count >= $posts->query_vars['posts_per_page'] ) : ?>
		<div class="category-more item byline">
			<a href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>">
				<?php echo sprintf( esc_html__( 'View all posts &rarr;', 'md' ), esc_html( $category->name ) ); ?>
			</a>
		</div>
		<?php endif ?>

	</div>

	<?php else : // Show listing of subcategories
		$term = $category;

		include md_template( 'features', 'loop/subcategory', true );

	endif; ?>

	<?php md_byline( 'after_post', array(
		'context' => 'category_entry',
		'category' => $category,
		'classes' => 'post-footer'
	) ); ?>

</section>
