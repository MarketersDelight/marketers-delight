<<?php echo $loop_h; ?> class="stream-loop format">
	<?php do_action( 'md_hook_stream_before_loop' ); ?>
	<?php while ( have_posts() ) {
		the_post();
		$this->loop_query( $c );
		$c++;
	} ?>
	<?php if ( is_singular() ) : ?>
		<div class="stream-go-back text-center">
			<p class="stream-bubble"><?php echo md_icon( 'angle-left', array( 'classes' => 'mr-small' ) ); ?> <a href="<?php echo get_post_type_archive_link( 'stream' ); ?>"><?php echo __( 'Go back to Stream', 'md' ); ?></a></p>
		</div>
	<?php endif; ?>
</<?php echo $loop_h; ?>>

<?php if ( is_singular() && ! $disable_comments ) : ?>
	<?php md_comments(); ?>
<?php endif; ?>