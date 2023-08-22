<?php if ( ! in_array( 'author', $byline ) || in_array( 'avatar', $byline ) ) : ?>

	<span class="byline-author byline-item">

		<?php if ( in_array( 'avatar', $byline ) ) {
			$avatar_size = isset( $args['avatar_size'] ) ? $args['avatar_size'] : 30;
			echo get_avatar( $author_id, $avatar_size );
		} ?>

		<?php if ( ! in_array( 'author', $byline ) ) : ?>

			<em><?php echo __( 'by', 'md' ); ?></em>

			<a href="<?php echo get_author_posts_url( $author_id ); ?>" class="author-link"><?php echo get_the_author_meta( 'display_name', $author_id ); ?></a>

			<?php if ( ! empty( $args['hide_links'] ) ) : ?>
				<?php if ( get_the_author_meta( 'twitter', $author_id ) ) : ?>
					<a href="//twitter.com/<?php echo esc_html( get_the_author_meta( 'twitter', $author_id ) ); ?>/" class="byline-twitter byline-icon" rel="nofollow" target="_blank"><?php echo md_icon( 'twitter' ); ?></a>
				<?php endif; ?>
			<?php endif; ?>

		<?php endif; ?>

	</span>

<?php endif; ?>
