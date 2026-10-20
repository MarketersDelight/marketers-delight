<?php
$h = md_loop_heading( 2, $loop );
$h_class = $h !== 'h2' ? ' h2' : '';
$category_identity = md_category_identity( $category );
$category_style = $category_identity['color'] ? md_style( array( 'vars' => array( 'md-category-color' => $category_identity['color'] ) ) ) : '';
$category_description = term_description( $category->term_id );
$has_inside_entry = ! empty( md_get_byline( 'inside_title', array( 'context' => 'category_entry', 'category' => $category ) ) );
?>

<section id="<?php echo esc_attr( $category->slug ); ?>" class="<?php echo esc_attr( $loop['category_classes'] ); ?>">

	<?php md_byline( 'entry_top', array(
		'context' => 'category_entry',
		'category' => $category
	) ); ?>

	<div class="category-title category-title-wrap fl items-start"<?php echo $category_style; ?>>

		<?php if ( $category_identity['icon'] ) : ?>

		<a href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>" class="category-title-icon square-icon mid" aria-hidden="true" tabindex="-1"><?php echo md_icon( $category_identity['icon'] ); ?></a>

		<?php endif; ?>

		<div class="entry-title grow<?php echo $has_inside_entry ? ' title-inline' : ''; ?>">

			<?php md_byline( 'before_title', array(
				'context' => 'category_entry',
				'category' => $category
			) ); ?>

			<<?php echo $h; ?> class="title<?php echo $h_class; ?>">
			<a href="<?php echo get_term_link( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></a>
			</<?php echo $h; ?>>

			<?php
			md_byline( 'after_title', array(
				'context' => 'category_entry',
				'category' => $category
			) );

			md_byline( 'inside_title', array(
				'context' => 'category_entry',
				'category' => $category
			) );
			?>

		</div>

	</div>

	<?php if ( $category_description && empty( $loop['category']['hide_description'] ) ) : ?>

	<div class="description lede">
		<?php echo wpautop( $category_description ); ?>
	</div>

	<?php endif; ?>

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

		<?php if ( $posts->query_vars['posts_per_page'] > 0 && $posts->post_count >= $posts->query_vars['posts_per_page'] ) : ?>

		<div class="category-more item byline mt-half">
			<a href="<?php echo esc_url( get_term_link( $category->term_id ) ); ?>">
				<?php echo sprintf( esc_html__( 'View all posts &rarr;', 'md' ), esc_html( $category->name ) ); ?>
			</a>
		</div>

		<?php endif ?>

	</div>

	<?php else : // Show listing of subcategories
		$term = $category;
		include md_template( 'features', 'loop/subcategory', true );

	endif;

	md_byline( 'entry_footer', array(
		'context' => 'category_entry',
		'category' => $category,
		'classes' => 'post-footer'
	) ); ?>

</section>
