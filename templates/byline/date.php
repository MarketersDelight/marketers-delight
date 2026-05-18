<?php
	if ( ! empty( $fields['settings']['alt'] ) ) {
		if ( get_the_modified_time( 'U' ) <= get_the_time( 'U' ) )
			return;

		$key = 'last_updated';
		$post_time = get_the_modified_time( 'U' );
		$date = $post_date = get_the_modified_time( get_option( 'date_format' ) );
		$datetime = get_the_modified_time( 'c' );
		$default_name = __( 'Last updated:', 'md' );
		$icon = 'clock';
	}
	else {
		$key = 'date';
		$post_time = get_post_time();
		$date = $post_date = get_the_time( get_option( 'date_format' ) );
		$datetime = get_the_date( 'c' );
		$default_name = __( 'Published on:', 'md' );
		$icon = 'calendar';
	}

	$permalink = get_permalink();
	$relative = human_time_diff( $post_time, current_time( 'U' ) );

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

	<?php echo md_icon( $icon ); ?>

	<?php if ( ! empty( $fields['settings']['label'] ) )
		echo '<span class="byline-label">' . ( ! empty( $fields['name'] ) ? $fields['name'] : $default_name ) . '</span>'; ?>

	<time datetime="<?php echo $datetime; ?>" title="<?php echo esc_attr( $post_date ); ?>">
		<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_attr( $date ); ?></a>
	</time>

</span>

<?php do_action( "md_hook_byline_after_$key" ); ?>