<<?php echo $article_h; ?> id="stream_<?php echo esc_attr( $html_id ); ?>" class="stream-item<?php echo esc_attr( $classes ); ?>">
	<div class="stream-byline mb-half">
		<?php if ( in_array( $post_id, get_option( 'sticky_posts' ) ) ) : ?>
			<p class="stream-byline-pinned byline-item"><i class="md-icon-pin"></i> <?php echo __( 'Pinned', 'md' ); ?></p>
		<?php endif; ?>
		<?php $this->byline( $post_id, $embed_id, $post_type, array(
			'post_date' => $post_date,
			'post_author' => $post_author,
			'html_id' => $html_id
		) ); ?>
	</div>
	<div class="stream-content mb-single clear">
		<?php if ( $stream_image ) : ?>
			<div class="stream-media">
				<div class="stream-box md-popup-trigger" data-popup="md_popup_stream_<?php echo $post_id; ?>">
					<?php echo $stream_image; ?>
					<span class="stream-icon md-icon-search"></span>
				</div>
			</div>
		<?php endif; ?>
		<div class="stream-text">
			<?php if ( ! is_singular() && ! $has_titles ) : ?>
				<h1 class="stream-title small-title mb-half"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<?php elseif ( is_singular() && ! $has_titles && $c == 0 ) : ?>
				<h1 class="stream-title small-title mb-half"><?php the_title(); ?></h1>
			<?php endif; ?>
			<?php echo apply_filters( 'the_content', $post_content ); ?>
		</div>
	</div>
	<?php if ( $embed_id ) :
		$embed = array(
			'title' => get_the_title( $embed_id ),
			'link' => get_permalink( $embed_id ),
			'excerpt' => $embed_id != 'page' ? get_post_field( 'post_excerpt', $embed_id ) : '',
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
			<div class="stream-embed block-half mb-single clear">
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
						<div class="mb-small">
							<?php echo $embed['excerpt']; ?>
						</div>
					<?php endif; ?>
					<?php $this->byline( $post_id, $embed_id, $post_type, array(
						'is_embed' => true
					) ); ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ( ! is_singular() || ( is_singular() && $c == 0 ) ) : ?>
		<?php if ( $has_thread ) : ?>
			<p><a href="<?php echo get_permalink(); ?>#stream_<?php echo esc_attr( $first_thread ); ?>" class="stream-thread-text"><?php echo sprintf( __( 'Show thread (%s)', 'md' ), count( $thread ) ); ?></a></p>
		<?php endif; ?>
		<?php if ( md_has( 'share' ) ) {
			$share = new md_share;
			$share->share_button( array( 'style' => 'minimal' ) );
		} ?>
	<?php endif; ?>
</<?php echo $article_h; ?>>