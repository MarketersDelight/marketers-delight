<span class="byline-author byline-item">

	<em><?php echo __( 'by', 'md' ); ?></em>

	<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="author-link">
		<?php echo esc_html( get_the_author_meta( 'display_name', get_the_author_meta( 'ID' ) ) ); ?>
	</a>

</span>
