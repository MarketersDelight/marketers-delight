<?php if ( ! in_array( 'author', $byline ) || in_array( 'avatar', $byline ) ) : ?>

	<span class="byline-author byline-item">

		<?php if ( in_array( 'avatar', $byline ) ) {
			$avatar_size = isset( $args['avatar_size'] ) ? $args['avatar_size'] : 30;
			echo get_avatar( get_the_author_meta( 'ID' ), $avatar_size );
		} ?>

		<?php if ( ! in_array( 'author', $byline ) ) : ?>

			<em><?php echo __( 'by', 'md' ); ?></em>

			<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="author-link"><?php echo esc_html( get_the_author() ); ?></a>

			<?php if ( ! in_array( 'hide_links', $args ) ) : ?>
				<?php if ( get_the_author_meta( 'twitter' ) ) : ?>
					<a href="//twitter.com/<?php echo esc_html( get_the_author_meta( 'twitter' ) ); ?>/" class="byline-twitter byline-icon" rel="nofollow" target="_blank"><?php echo md_icon( 'twitter' ); ?></a>
				<?php endif; ?>
			<?php endif; ?>

		<?php endif; ?>

	</span>

<?php endif; ?>