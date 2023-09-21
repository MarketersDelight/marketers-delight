<?php if ( in_array( 'last-updated', $byline ) ) : ?>

	<span class="byline-date-modified byline-item" itemprop="dateModified" content="<?php echo get_the_modified_date( 'c', $post_id ); ?>">

		<?php echo md_icon( 'clock' ); ?> <?php echo __( 'Updated on:', 'md' ); ?> <?php echo get_the_modified_date( '', $post_id ); ?>

	</span>

<?php endif; ?>
