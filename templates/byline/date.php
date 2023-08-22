<?php if ( ! in_array( 'date', $byline ) ) : ?>

	<span class="byline-date byline-item">

		<?php echo md_icon( 'clock' ); ?> <time datetime="<?php echo get_the_date( 'c', $post_id ); ?>" itemprop="datePublished"><a href="<?php echo get_permalink( $post_id ); ?>"><?php echo get_the_time( get_option( 'date_format' ), $post_id ); ?></a></time>

		<?php if ( in_array( 'last-updated', $byline ) ) : ?>
			(<?php echo __( 'updated ', 'md' ); ?> <?php the_modified_date(); ?>)
		<?php endif; ?>

	</span>

<?php endif; ?>

<?php if ( in_array( 'last-updated', $byline ) && in_array( 'date', $byline ) ) : ?>

	<span class="byline-date-modified byline-item" itemprop="dateModified" content="<?php echo get_the_modified_date( 'c', $post_id ); ?>">
		<?php echo md_icon( 'clock' ); ?> <?php echo __( 'Last updated:', 'md' ); ?> <?php echo get_the_modified_date( '', $post_id ); ?>
	</span>

<?php endif; ?>

<?php do_action( 'md_hook_byline_after_date' ); ?>
