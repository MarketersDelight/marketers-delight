<footer class="author-box post-footer item">
	<?php echo ! md_has_sidebar() ? '<div class="wrap">' : ''; ?>

		<div class="author-meta">

			<?php if ( $has_avatar ) : ?>
			<div class="author-avatar">
				<?php echo get_avatar( $author_id, 150 ); ?>
			</div>
			<?php endif; ?>

			<h3 class="author-title"><?php echo esc_html( $author_name ); ?></h3>

		</div>

		<?php if ( ! empty( $description ) ) : ?>
		<div class="author-description">
			<?php echo wpautop( $description ); ?>
		</div>
		<?php endif; ?>

		<div class="author-links">

			<?php if ( ! empty( $twitter ) ) : ?>
			<a href="https://x.com/<?php echo esc_attr( $twitter ); ?>" class="author-link twitter" rel="nofollow" target="_blank">
				<span class="circle-icon micro"><?php echo md_icon( 'twitter' ); ?></span><span class="author-link-label"><?php echo __( 'Follow on X', 'md' ); ?></span>
			</a>
			<?php endif; ?>

			<?php if ( ! empty( $website_url ) ) : ?>
			<a href="<?php echo esc_url( $website_url ); ?>" class="author-link website" target="_blank">
				<span class="circle-icon micro"><?php echo md_icon( 'url' ); ?></span><span class="author-link-label"><?php echo __( 'Visit website', 'md' ); ?></span>
			</a>
			<?php endif; ?>

			<a href="<?php echo esc_url( $author_url ); ?>" class="author-link all-posts">
				<span class="circle-icon micro"><?php echo md_icon( 'pin' ); ?></span><span class="author-link-label"><?php echo __( 'See all posts', 'md' ); ?></span>
			</a>

		</div>

	<?php echo ! md_has_sidebar() ? '</div>' : ''; ?>
</footer>
