<?php
	$permalink = get_permalink();
	$post_time = get_post_time();
	$relative = human_time_diff( $post_time, current_time( 'U' ) );
	$date = $post_date = get_the_time( get_option( 'date_format' ) );

	if ( isset( $fields['url_params'] ) )
		$permalink .= $fields['url_params'];

	if ( ! empty( $fields['settings']['relative'] ) || isset( $fields['relative_date'] ) ) {
		if ( isset( $fields['relative_date'] ) ) {
			$post_time = $fields['relative_date'];
			$relative = human_time_diff( $post_time );
		}

		$date = sprintf( __( '%s ago', 'md' ), $relative );
	}
?>

<span class="byline-date byline-item">

	<?php echo md_icon( 'clock' ); ?>

	<?php if ( ! empty( $fields['settings']['label'] ) )
		echo '<span class="byline-label">' . ( ! empty( $fields['name'] ) ? $fields['name'] : __( 'Published on:', 'md' ) ) . '</span>'; ?>

	<time datetime="<?php echo get_the_date( 'c' ); ?>" title="<?php echo esc_attr( $post_date ); ?>">
		<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_attr( $date ); ?></a>
	</time>

</span>

<?php do_action( 'md_hook_byline_after_date' ); ?>
