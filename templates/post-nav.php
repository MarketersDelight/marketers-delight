<div class="post-nav<?php echo ! md_has_sidebar() ? ' inner' : ''; ?>">

	<?php if ( get_previous_post_link() ) : ?>

	<div class="post-nav-previous">

		<?php echo md_icon( 'angle-left' ); ?>

		<span class="post-nav-direction"><?php echo __( 'Previous', 'md' ); ?></span>

		<?php echo get_previous_post_link( '%link' ); ?>

	</div>

	<?php endif; ?>

	<?php if ( get_next_post_link() ) : ?>

	<div class="post-nav-next">

		<span class="post-nav-direction"><?php echo __( 'Next', 'md' ); ?></span>

		<?php echo md_icon( 'angle-right' ); ?>

		<?php echo get_next_post_link( '%link' ); ?>

	</div>

	<?php endif; ?>

</div>