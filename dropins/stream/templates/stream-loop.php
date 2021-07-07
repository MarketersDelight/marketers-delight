<<?php echo $loop_h; ?> class="stream-loop format">
	<?php do_action( 'md_hook_stream_before_loop' ); ?>
	<?php if ( ! is_singular() && ! md_setting( array( 'stream', 'settings', 'disable_activity' ) ) ) :
		$loop = new WP_Query( array(
			'post_type' => array( 'stream', 'stream_activity' ),
			'posts_per_page' => md_setting( array( 'stream', 'posts_per_page' ), 10 ),
			'fields' => 'ids',
			'paged' => get_query_var( 'paged' )
		) );
		if ( $loop->have_posts() )
			while ( $loop->have_posts() ) {
				$loop->the_post();
				$this->loop_query( $c );
				$c++;
			}
		wp_reset_query(); ?>
	<?php else : ?>
		<?php while ( have_posts() ) {
			the_post();
			$this->loop_query( $c );
			$c++;
		} ?>
	<?php endif; ?>
	<?php if ( is_singular() ) : ?>
		<div class="stream-go-back text-center">
			<p class="stream-bubble"><?php echo md_icon( 'angle-left', array( 'classes' => 'mr-small' ) ); ?> <a href="<?php echo get_post_type_archive_link( 'stream' ); ?>"><?php echo __( 'Go back to Stream', 'md' ); ?></a></p>
		</div>
	<?php endif; ?>
</<?php echo $loop_h; ?>>

<?php if ( is_singular() && ! $disable_comments ) : ?>
	<?php md_comments(); ?>
<?php endif; ?>