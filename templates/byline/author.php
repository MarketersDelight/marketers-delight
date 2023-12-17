<?php if ( ! in_array( 'author', $byline ) || in_array( 'avatar', $byline ) ) :
	if ( ! empty( $settings['author_first_name'] ) )
		$name = 'first_name';
	else
		$name = isset( $args['name'] ) ? $args['name'] : 'display_name';
?>

<span class="byline-author byline-item">

	<?php if ( in_array( 'avatar', $byline ) || ( isset( $args['avatar'] ) && $args['avatar'] !== false ) ) {
		$avatar_size = isset( $args['avatar_size'] ) ? $args['avatar_size'] : 30;
		echo get_avatar( $author_id, $avatar_size );
	} ?>

	<?php if ( ! in_array( 'author', $byline ) ) : ?>

		<?php if ( ! isset( $args['prefix'] ) ) : ?>
			<em><?php echo __( 'by', 'md' ); ?></em>
		<?php endif; ?>

		<a href="<?php echo get_author_posts_url( $author_id ); ?>" class="author-link">
			<?php echo get_the_author_meta( $name, $author_id ); ?>
		</a>

		<?php if ( ! empty( $args['hide_links'] ) ) : ?>
			<?php if ( get_the_author_meta( 'twitter', $author_id ) ) : ?>
				<a href="//twitter.com/<?php echo esc_html( get_the_author_meta( 'twitter', $author_id ) ); ?>/" class="byline-twitter" rel="nofollow" target="_blank"><?php echo md_icon( 'twitter' ); ?></a>
			<?php endif; ?>
		<?php endif; ?>

	<?php endif; ?>

</span>

<?php endif; ?>
