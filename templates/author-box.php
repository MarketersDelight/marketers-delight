<div class="author-box">
	<div class="content-inner">
		<?php if ( $has_avatar ) : ?>
			<p class="author-avatar"><?php echo get_avatar( get_the_author_meta( 'user_email' ), 130 ); ?></p>
		<?php endif; ?>
		<div class="author-content">
			<<?php echo $html; ?> class="author-title small-title mb-half"><?php echo __( 'About', 'md' ); ?> <strong><?php the_author_meta( 'display_name' ); ?></strong></<?php echo $html; ?>>
			<div class="author-bio mb-single">
				<?php if ( ! empty( $desc ) ) : ?>
					<?php echo wpautop( $desc ); ?>
				<?php endif; ?>
			</div>
			<div class="author-meta">
				<?php if ( ! empty( $archive ) && ! is_author() ) : ?>
					<span class="author-link all-posts">
						<span class="circle-icon micro"><?php echo md_icon( 'pin' ); ?></span>
						<a href="<?php echo esc_url( $author ); ?>" target="_blank">
							<?php echo __( 'See all posts', 'md' ); ?>
						</a>
					</span>
				<?php endif; ?>
				<?php if ( ! empty( $url ) ) : ?>
					<span class="author-link website">
						<span class="circle-icon micro"><?php echo md_icon( 'url' ); ?></span>
						<a href="<?php echo esc_url( $url ); ?>" target="_blank">
							<?php echo __( 'Visit website', 'md' ); ?>
						</a>
					</span>
				<?php endif; ?>
				<?php if ( ! empty( $twitter ) ) : ?>
					<span class="author-link twitter">
						<span class="circle-icon micro"><?php echo md_icon( 'twitter' ); ?></span>
						<a href="https://twitter.com/<?php echo esc_attr( $twitter ); ?>" rel="nofollow" target="_blank">
							<?php echo __( 'Follow on Twitter', 'md' ); ?>
						</a>
					</span>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>