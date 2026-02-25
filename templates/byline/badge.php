<?php
	$time = get_the_time( 'U' );
	$label = ! empty( $fields['name'] ) ? $fields['name'] : __( 'New!', 'md' );
	$t = ! empty( $fields['time'] ) ? $fields['time'] : 7;

	if ( isset( $fields['set_time'] ) )
		$time = $fields['set_time'];

	if ( $time < strtotime( "-$t days" ) )
		return;
?>

<span class="byline-item byline-badge badge"><?php echo esc_html( $label ); ?></span>