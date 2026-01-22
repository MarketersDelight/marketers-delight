<div class="entry">

	<div class="headline category-headline block wide">
		<div class="wrap">

			<h2 class="title"><a href="<?php echo get_term_link( $category->term_id ); ?>"><?php echo esc_html( $category->name ); ?></a></h2>

			<?php if ( $category_description ) : ?>
			<div class="description">
				<?php echo wpautop( $category_description ); ?>
			</div>
			<?php endif; ?>
		</div>

	</div>

	<div class="<?php echo esc_attr( $loop_classes ); ?>">
		<?php while ( $posts->have_posts() ) :
			$posts->the_post();
			include md_template( 'loop/the-post', true );
		endwhile; ?>
	</div>

</div>
