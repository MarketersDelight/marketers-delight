<div class="byline">

	<?php if ( ! empty( $embed_id ) && ! $is_embed ) :
		$label = '';
		$post_date = isset( $args['post_date'] ) ? $args['post_date'] : get_post_timestamp( $post_id );
		$post_author = isset( $args['post_author'] ) ? $args['post_author'] : get_post_field( 'post_author', $post_id );
		$html_id = ! empty( $args['html_id'] ) && $post_id !== $args['html_id'] ? '#stream_' . $args['html_id'] : '';
		$date = human_time_diff( $post_date );
		if ( $post_date > strtotime( '-4 weeks' ) )
			$date = $date . __( ' ago', 'md' );
		else {
			$date = get_the_date( '', $post_id );
			$label = __( 'on ', 'md' );
		}
	?>

		<?php if ( get_post_type() == 'stream_activity' ) : ?>
			<?php echo md_icon( 'plus', array( 'classes' => 'mr-small' ) ); ?> <?php echo get_the_author_meta( 'first_name', $post_author ); ?> <?php echo sprintf( __( ' published a new %s', 'md' ), '<b>' . ( isset( $types[$post_type] ) ? $types[$post_type]['label'] : $post_type ) . '</b>' ); ?> <?php echo $label; ?>
		<?php else : ?>
			<?php echo md_icon( 'share', array( 'classes' => 'mr-small' ) ); ?> <?php echo get_the_author_meta( 'first_name', $post_author ); ?> <?php echo sprintf( __( ' shared a %s', 'md' ), '<b>' . ( isset( $types[$post_type] ) ? $types[$post_type]['label'] : $post_type ) . '</b>' ); ?> <?php echo $label; ?><a href="<?php echo get_permalink( $post_id ); ?><?php echo $html_id; ?>"><?php echo $date; ?></a>
		<?php endif; ?>

	<?php else :
		$post_id = $is_embed ? $embed_id : $post_id;
		$post_date = isset( $args['post_date'] ) ? $args['post_date'] : get_post_timestamp( $post_id );
		$post_author = isset( $args['post_author'] ) ? $args['post_author'] : get_post_field( 'post_author', $post_id );
		$html_id = ! empty( $args['html_id'] ) && $post_id !== $args['html_id'] ? '#stream_' . $args['html_id'] : '';
		$date = human_time_diff( $post_date );
		if ( $post_date > strtotime( '-4 weeks' ) )
			$date = $date . ' ago';
		else
			$date = get_the_date( '', $post_id );
	?>

		<span class="stream-byline-avatar mr-small"><?php echo get_avatar( $post_author, 20 ); ?></span>
		<span class="stream-byline-author middot"><?php echo get_the_author_meta( 'display_name', $post_author ); ?></span>
		<span class="stream-byline-date"><a href="<?php echo get_permalink( $post_id ); ?><?php echo $html_id; ?>"><?php echo $date; ?></a></span>

	<?php endif; ?>

	<?php if ( current_user_can( 'edit_posts' ) ) : ?>
		<?php echo edit_post_link( '<i class="' . md_icon( 'pencil', true ) . '"></i>', '', '', $post_id ); ?>
	<?php endif; ?>

</div>