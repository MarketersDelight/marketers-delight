<<?php echo $article_h; ?> id="<?php echo $post_type; ?>_<?php echo esc_attr( $html_id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
	<div class="stream-byline mb-half">
		<?php if ( in_array( $post_id, get_option( 'sticky_posts' ) ) ) : ?>
			<p class="stream-byline-pinned byline-item"><?php echo md_icon( 'pin' ); ?> <?php echo __( 'Pinned', 'md' ); ?></p>
		<?php endif; ?>
		<?php $this->byline( $post_id, $embed_id, $post_type, array(
			'post_date' => $post_date,
			'post_author' => $post_author,
			'html_id' => $html_id
		) ); ?>
	</div>
	<?php if ( $title || $post_content || $stream_image ) : ?>
		<div class="stream-content mb-single clear">
			<?php if ( $stream_image ) : ?>
				<div class="stream-media">
					<div class="stream-box md-popup-trigger" data-popup="md_popup_<?php echo $post_type; ?>_<?php echo $post_id; ?>">
						<?php echo $stream_image; ?>
						<?php echo md_icon( 'search', array( 'classes' => 'stream-icon' ) ); ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="stream-text">
				<?php if ( $title ) : ?>
					<?php if ( ! is_singular() && ! $has_titles ) : ?>
						<h1 class="stream-title small-title mb-small"><a href="<?php the_permalink(); ?>"><?php echo $title; ?></a></h1>
					<?php elseif ( is_singular() && ! $has_titles ) : ?>
						<h1 class="stream-title small-title mb-small"><?php echo $title; ?></h1>
					<?php endif; ?>
					<?php md_hook_after_headline(); ?>
				<?php endif; ?>
				<?php if ( $post_content ) : ?>
					<?php md_the_content( $post_content ); ?>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ( $embed_id && get_post_status( $embed_id ) ) :
		$embed = array(
			'title' => get_the_title( $embed_id ),
			'link' => get_permalink( $embed_id ),
			'excerpt' => $embed_id != 'page' ? get_the_excerpt( $embed_id ) : '',
			'content' => get_post_field( 'post_content', $embed_id ),
			'date' => get_post_timestamp( $embed_id ),
			'author' => get_post_field( 'post_author', $embed_id ),
			'image' => get_the_post_thumbnail( $embed_id, ( $c == 0 ? 'md-image' : 'thumbnail' ) )
		);
	?>
		<?php if ( isset( $types[$post_type]['template'] ) ) : ?>
			<?php do_action( "md_stream_template_{$post_type}", array_merge( array(
				'post_id' => $embed_id,
				'c' => $c
			), $embed ) ); ?>
		<?php else : ?>
			<div class="stream-embed block-half">
				<div class="stream-embed-content clear">
					<?php if ( ! empty( $embed['image'] ) ) : ?>
						<div class="stream-media">
							<div class="stream-box">
								<a href="<?php echo $embed['link']; ?>" class="clear">
									<?php echo $embed['image']; ?>
									<?php if ( isset( $types[$post_type]['embed_icon'] ) ) : ?>
										<span class="stream-icon <?php echo esc_attr( $types[$post_type]['embed_icon'] ); ?>"></span>
									<?php endif; ?>
								</a>
							</div>
						</div>
					<?php endif; ?>
					<div class="stream-text">
						<p class="stream-title small-title mb-small"><a href="<?php echo $embed['link']; ?>" title="<?php echo $embed['title']; ?>"><?php echo $embed['title']; ?></a></p>
						<?php if ( ! empty( $embed['excerpt'] ) ) : ?>
							<div class="stream-excerpt">
								<?php echo $embed['excerpt']; ?>
							</div>
						<?php endif; ?>
						<?php $this->byline( $post_id, $embed_id, $post_type, array(
							'is_embed' => true
						) ); ?>
					</div>
				</div>
				<?php $this->embed_post_controls( $embed_id, $post_type ); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( ! is_singular() || ( is_singular() && $c == 0 ) ) : ?>
		<?php if ( $has_thread ) : ?>
			<p><a href="<?php echo get_permalink(); ?>#<?php echo esc_attr( $post_type ); ?><?php echo esc_attr( $first_thread ); ?>" class="stream-thread-text"><?php echo sprintf( __( 'Show thread (%s)', 'md' ), count( $thread ) ); ?></a></p>
		<?php endif; ?>
		<?php if ( ! $embed_id ) : ?>
			<?php md_hook_post_actions(); ?>
		<?php endif; ?>
	<?php endif; ?>
</<?php echo $article_h; ?>>