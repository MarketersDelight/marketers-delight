<?php if ( in_array( 'badge', $byline ) && get_the_time( 'U', $post_id ) > strtotime( '-7 days' ) ) : ?>
	<span class="byline-item byline-badge">
		<span class="badge"><?php echo __( 'New!', 'md' ); ?></span>
	</span>
<?php endif; ?>
