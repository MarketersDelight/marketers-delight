<?php if ( in_array( 'badge', $byline ) ) :
	$time = get_the_time( 'U', $post_id );

	if ( isset( $args['time'] ) )
		$time = $args['time'];

	if ( $time < strtotime( '-7 days' ) )
		return;
?>

	<span class="byline-item byline-badge">
		<span class="badge"><?php echo __( 'New!', 'md' ); ?></span>
	</span>

<?php endif; ?>
