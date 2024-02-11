<div class="<?php echo esc_attr( $category_classes ); ?>">

	<div class="section-header post-header layout">

		<div class="title-wrap">

			<h2 class="title"><?php echo esc_html( $category->name ); ?></h2>

			<?php if ( $category_description ) : ?>
			<div class="description">
				<?php echo wpautop( $category_description ); ?>
			</div>
			<?php endif; ?>

		</div>

	</div>

	<div class="category-posts loop<?php echo esc_attr( $wrap_classes ); ?>">

	<?php while ( $posts->have_posts() ) {
		$posts->the_post();

		include( md_template( 'loop/the-post', true ) );
	} ?>

	</div>

</div>
