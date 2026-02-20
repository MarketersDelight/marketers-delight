<section class="author-box post-footer">
	<div class="wrap">

		<div class="author-meta">

			<?php if ( $has_avatar ) : ?>
			<div class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 150 ); ?>
			</div>
			<?php endif; ?>

			<<?php echo $h; ?> class="author-title"><?php the_author_meta( 'display_name' ); ?></<?php echo $h; ?>>

		</div>

		<?php if ( ! empty( $desc ) ) : ?>
			<div class="author-description">
				<?php echo wpautop( $desc ); ?>
			</div>
		<?php endif; ?>

		<div class="author-links">

			<?php if ( ! empty( $twitter ) ) : ?>
				<a href="https://twitter.com/<?php echo esc_attr( $twitter ); ?>" class="author-link twitter" rel="nofollow" target="_blank">
					<span class="circle-icon micro"><?php echo md_icon( 'twitter' ); ?></span><span class="author-link-label"><?php echo __( 'Follow on X', 'md' ); ?></span>
				</a>
			<?php endif; ?>

			<?php if ( ! empty( $url ) ) : ?>
				<a href="<?php echo esc_url( $url ); ?>" class="author-link website" target="_blank">
					<span class="circle-icon micro"><?php echo md_icon( 'url' ); ?></span><span class="author-link-label"><?php echo __( 'Visit website', 'md' ); ?></span>
				</a>
			<?php endif; ?>

			<?php if ( empty( $show_posts ) && ! is_author() ) : ?>
				<a href="<?php echo esc_url( $author ); ?>" class="author-link all-posts">
					<span class="circle-icon micro"><?php echo md_icon( 'pin' ); ?></span><span class="author-link-label"><?php echo __( 'See all posts', 'md' ); ?></span>
				</a>
			<?php endif; ?>

		</div>

	</div>
</section>
