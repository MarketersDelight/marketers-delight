<article id="post_404" <?php post_class( array( 'post-box' ) ); ?>>

	<div class="<?php echo md_headline_classes(); ?>">

		<?php md_hook_before_headline(); ?>

		<h1 class="headline"><?php echo __( 'Nothing Found', 'md' ); ?></h1>

		<?php md_hook_after_headline(); ?>

	</div>

	<div class="content-text">

		<?php if ( md_has_inline_featured_image() ) : ?>
			<?php md_featured_image(); ?>
		<?php endif; ?>

		<p><?php echo __( 'It looks like the page you\'re looking for isn\'t here! See if the tools below can help you find what you\'re looking for:', 'md' ); ?></p>

		<div class="content-404-search mb-double">
			<h4><?php echo __( 'Search the Website', 'md' ); ?></h4>
			<p><?php echo __( 'If you have an idea of what you\'re looking for, try typing in a few of the best keywords you can think of to get the most relevant search results possible.', 'md' ); ?></p>
			<?php get_search_form(); ?>

		</div>

		<?php
			/* Latest posts */
			$instance = array(
				'title' => __( 'Browse Latest Posts', 'md' )
			);
			$args = array(
				'before_widget' => '<div class="content-item-404-recent-posts mb-double">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4>',
				'after_title'   => '</h4>'
			);
			the_widget( 'WP_Widget_Recent_Posts', $instance, $args );

			// Browse by month
			$instance = array(
				'title'    => __( 'Browse By Month', 'md' ),
				'dropdown' => 1
			);
			$args = array(
				'before_widget' => '<div class="content-item-404-archives mb-double">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4>',
				'after_title'   => '</h4>'
			);
			the_widget( 'WP_Widget_Archives', $instance, $args );
		?>

	</div>

</article>
