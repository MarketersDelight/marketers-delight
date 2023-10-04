<?php if ( in_array( 'last-updated', $byline ) ) : ?>

	<span class="byline-item byline-date-modified">

		<?php echo md_icon( 'clock' ); ?> <?php echo sprintf( __( 'Last updated: %s', 'md' ), get_the_modified_date( '', $post_id ) ); ?>

	</span>

<?php endif; ?>
