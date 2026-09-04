<nav class="post-nav" aria-label="<?php esc_attr_e( 'Previous and next posts', 'md' ); ?>">

	<?php if ( $previous ) : ?>

	<div class="post-nav-previous">

		<?php if ( $previous_media ) : ?>
		<div class="post-nav-media">
			<?php echo $previous_media; ?>
		</div>
		<?php endif; ?>

		<div class="post-nav-text">

			<span class="post-nav-direction">
				<?php echo md_icon( 'angle-left' ); ?> <?php echo esc_html__( 'Previous', 'md' ); ?>
			</span>

			<?php echo get_previous_post_link( '%link', '<span class="post-nav-title">%title</span>' ); ?>

		</div>

	</div>

	<?php endif; ?>

	<?php if ( $next ) : ?>

	<div class="post-nav-next">

		<div class="post-nav-text">

			<span class="post-nav-direction">
				<?php echo esc_html__( 'Next', 'md' ); ?> <?php echo md_icon( 'angle-right' ); ?>
			</span>

			<?php echo get_next_post_link( '%link', '<span class="post-nav-title">%title</span>' ); ?>

		</div>

		<?php if ( $next_media ) : ?>
		<div class="post-nav-media">
			<?php echo $next_media; ?>
		</div>
		<?php endif; ?>

	</div>

	<?php endif; ?>

</nav>
