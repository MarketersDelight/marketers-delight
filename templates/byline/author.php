<?php if ( ! in_array( 'author', $byline ) || in_array( 'avatar', $byline ) ) : ?>
	<span class="byline-author byline-item">
		<?php if ( in_array( 'avatar', $byline ) && ! isset( $args['hide_avatar'] ) ) :
			$avatar_size = isset( $args['avatar_size'] ) ? $args['avatar_size'] : 30;
		?>
			<?php echo get_avatar( get_the_author_meta( 'ID' ), $avatar_size, '', false, array(
				'class' => 'circle'
			) ); ?>
		<?php endif; ?>
		<?php if ( ! in_array( 'author', $byline ) ) : ?>
			<em><?php echo __( 'by', 'md' ); ?></em>
			<span class="author vcard mr-small">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><span class="byline-author-name fn" itemprop="name"><?php esc_html( the_author() ); ?></span></a>
			</span>
			<?php if ( get_the_author_meta( 'twitter' ) ) : ?>
				<a href="//twitter.com/<?php echo esc_html( get_the_author_meta( 'twitter' ) ); ?>/" class="byline-twitter byline-icon" rel="nofollow" target="_blank"><?php echo md_icon( 'twitter' ); ?></a>
			<?php endif; ?>
		<?php endif; ?>
	</span>
<?php endif; ?>