<div class="author-box">

	<div class="author-title">

		<?php if ( $has_avatar ) : ?>
			<div class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), 130 ); ?>
			</div>
		<?php endif; ?>

		<<?php echo $html; ?> class="author-headline"><?php the_author_meta( 'display_name' ); ?></<?php echo $html; ?>>

	</div>

	<?php if ( ! empty( $desc ) ) : ?>
		<div class="author-bio">
			<?php echo wpautop( $desc ); ?>
		</div>
	<?php endif; ?>

	<div class="author-meta">

		<?php if ( ! empty( $archive ) && ! is_author() ) : ?>
			<a href="<?php echo esc_url( $author ); ?>" class="author-link all-posts">
				<span class="circle-icon micro"><?php echo md_icon( 'pin' ); ?></span><?php echo __( 'See all posts', 'md' ); ?>
			</a>
		<?php endif; ?>

		<?php if ( ! empty( $url ) ) : ?>
			<a href="<?php echo esc_url( $url ); ?>" class="author-link website" target="_blank">
				<span class="circle-icon micro"><?php echo md_icon( 'url' ); ?></span><?php echo __( 'Visit website', 'md' ); ?>
			</a>
		<?php endif; ?>

		<?php if ( ! empty( $twitter ) ) : ?>
			<a href="https://twitter.com/<?php echo esc_attr( $twitter ); ?>" class="author-link twitter" rel="nofollow" target="_blank">
				<span class="circle-icon micro"><?php echo md_icon( 'twitter' ); ?></span><?php echo __( 'Follow on Twitter', 'md' ); ?>
			</a>
		<?php endif; ?>

	</div>

</div>
