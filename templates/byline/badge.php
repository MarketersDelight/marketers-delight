<?php
	$time = get_the_time( 'U' );
	$label = ! empty( $fields['title'] ) ? $fields['title'] : __( 'New!', 'md' );
	$t = ! empty( $fields['time'] ) ? $fields['time'] : 7;

	if ( isset( $args['time'] ) )
		$time = $args['time'];

	if ( $time < strtotime( "-$t days" ) )
		return;
?>

<span class="byline-item byline-badge">
	<span class="badge"><?php echo esc_html( $label ); ?></span>
</span>
