<?php
	$author_id = isset( $fields['user_id'] ) ? $fields['user_id'] : get_post_field( 'post_author', get_the_ID() );
	$author_name = get_the_author_meta( 'display_name', $author_id );

	if ( ! empty( $fields['settings']['first_name'] ) ) {
		$first_name = get_the_author_meta( 'first_name', $author_id );
		$author_name = ! empty( $first_name ) ? $first_name : $author_name;
	}
?>

<span class="byline-author byline-item">

	<?php if ( ! empty( $fields['settings']['avatar'] ) ) {
		$avatar_size = isset( $fields['image_size'] ) ? $fields['image_size'] : 30;
		echo get_avatar( $author_id, $avatar_size );
	} ?>

	<?php if ( ! isset( $args['prefix'] ) ) : ?>
		<em><?php echo __( 'by', 'md' ); ?></em>
	<?php endif; ?>

	<a href="<?php echo get_author_posts_url( $author_id ); ?>" class="author-link"><?php echo esc_html( $author_name ); ?></a>

</span>
