<?php
	$permalink = get_permalink();
	$date = $post_date = get_the_time( get_option( 'date_format' ) );

	if ( isset( $fields['url_params'] ) )
		$permalink .= $fields['url_params'];

	if ( ! empty( $fields['settings']['relative'] ) || isset( $fields['relative_date'] ) ) {
		$relative = isset( $fields['relative_date'] ) ? $fields['relative_date'] : get_the_time( 'U' );
		$time = human_time_diff( $relative, current_time( 'U' ) );
		$date = sprintf( __( '%s ago', 'md' ), $time );
	}
?>

<span class="byline-date byline-item">

	<?php echo md_icon( 'clock' ); ?>

	<time datetime="<?php echo get_the_date( 'c' ); ?>" title="<?php echo esc_attr( $post_date ); ?>">
		<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_attr( $date ); ?></a>
	</time>

</span>

<?php do_action( 'md_hook_byline_after_date' ); ?>
