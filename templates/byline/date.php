<?php if ( ! in_array( 'date', $byline ) ) :
	$permalink = get_permalink( $post_id );

	if ( isset( $args['url_params' ] ) )
		$permalink .= $args['url_params'];

	if ( ! empty( $settings['relative_date'] ) || isset( $args['relative'] ) ) {
		$relative = isset( $args['relative'] ) ? $args['relative'] : get_the_time( 'U' );
		$time = human_time_diff( $relative, current_time( 'U' ) );
		$date = sprintf( __( '%s ago', 'md' ), $time );
	}
	else
		$date = get_the_time( get_option( 'date_format' ), $post_id );
?>

	<span class="byline-date byline-item">

		<?php if ( ! isset( $args['hide_icon'] ) ) : ?>
			<?php echo md_icon( 'clock' ); ?>
		<?php endif; ?>

		<?php if ( isset( $args['prefix'] ) ) : ?>
			<?php echo md_text_field( $args['prefix'] ); ?>
		<?php endif; ?>

		<time datetime="<?php echo get_the_date( 'c', $post_id ); ?>">
			<a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_attr( $date ); ?></a>
		</time>

	</span>

<?php endif; ?>

<?php do_action( 'md_hook_byline_after_date' ); ?>
