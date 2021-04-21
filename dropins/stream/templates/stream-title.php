<div class="stream-head<?php echo esc_attr( $classes ); ?> mb-single">

	<?php if ( ! empty( $archives_photo ) ) : ?>
		<div class="stream-head-image col col1 mb-small text-center">
			<a href="<?php echo get_post_type_archive_link( 'stream' ); ?>"><?php echo wp_get_attachment_image( $archives_photo, 'thumbnail', false, array( 'class' => 'stream-head-image avatar' ) ); ?></a>
		</div>
	<?php endif; ?>

	<div class="stream-head-content<?php echo esc_attr( $col2 ); ?>">

		<?php if ( empty( $context ) ) : ?>

			<?php if ( is_tax( $taxonomy_name ) ) : ?>
				<h1 class="stream-head-title<?php echo esc_attr( $title_classes ); ?>"><?php echo single_cat_title(); ?></h1>
				<?php if ( ! empty( $cat_desc ) ) : ?>
					<div class="stream-head-text mb-half">
						<?php echo $cat_desc; ?>
					</div>
				<?php endif; ?>
			<?php elseif ( $archives_title ) : ?>
				<h1 class="stream-head-title<?php echo esc_attr( $title_classes ); ?>"><?php echo md_text_field( $archives_title ); ?></h1>
			<?php endif; ?>

		<?php elseif ( $context == 'widget' && $archives_title ) : ?>

			<?php if ( ! is_post_type_archive( 'stream' ) ) : ?>
				<p class="stream-head-title<?php echo esc_attr( $title_classes ); ?>"><a href="<?php echo get_post_type_archive_link( 'stream' ); ?>"><?php echo md_text_field( $archives_title ); ?></a></p>
			<?php else : ?>
				<p class="stream-head-title<?php echo esc_attr( $title_classes ); ?>"><?php echo md_text_field( $archives_title ); ?></p>
			<?php endif; ?>

		<?php endif; ?>

		<?php if ( ! is_tax( $taxonomy_name ) && $archives_desc && $context != 'widget' ) : ?>
			<div class="stream-head-text mb-half">
				<?php echo wpautop( $archives_desc ); ?>
			</div>
		<?php endif; ?>

		<div class="stream-stats">
			<span class="stream-stat stat-posts mr-half">
				<?php echo md_icon( 'pin', array( 'classes' => 'stat-icon' ) ); ?>
				<b class="stat-count"><?php echo esc_attr( $posts_count ); ?></b> <span class="stat-text"><?php echo __( 'Posts', 'md' ); ?></span>
			</span>
			<span class="stream-stat stat-likes">
				<?php echo md_icon( 'heart-empty', array( 'classes' => 'stat-icon' ) ); ?>
				<b class="share-total" data-share-total="stream"><?php echo esc_attr( $likes_count ); ?></b> <span class="stat-text"><?php echo __( 'Likes', 'md' ); ?></span>
			</span>
		</div>
	</div>
</div>