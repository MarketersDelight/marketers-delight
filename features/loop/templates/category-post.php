<?php
$category_identity = md_category_identity( $category );
$category_style = $category_identity['color'] ? md_style( array(
	'vars' => array( 'md-category-color' => $category_identity['color'] )
) ) : '';
?>
<section id="<?php echo esc_attr( $category->slug ); ?>" class="<?php echo esc_attr( $loop['category_classes'] ); ?>">

	<?php md_byline( 'entry_top', array(
		'context' => 'category_entry',
		'category' => $category
	) ); ?>

	<div class="category-title entry-title"<?php echo $category_style; ?>>

		<div class="category-title-wrap fl items-start">

		<?php if ( $category_identity['icon'] ) : ?>
		<span class="category-title-icon square-icon mid" aria-hidden="true"><?php echo md_icon( $category_identity['icon'] ); ?></span>
		<?php endif; ?>

		<div class="entry-title grow<?php echo $has_inside_entry ? ' title-inline' : ''; ?>">

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

		<?php md_byline( 'inside_title', array(
			'context' => 'category_entry',
			'category' => $category
		) ); ?>

		</div>

		</div>

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

	<?php md_byline( 'entry_footer', array(
		'context' => 'category_entry',
		'category' => $category,
		'classes' => 'post-footer'
	) ); ?>

</section>
