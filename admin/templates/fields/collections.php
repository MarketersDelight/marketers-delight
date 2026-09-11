<div
	class="md-collection"
	data-md-collection="<?php echo esc_attr( $collection->id ); ?>"
	data-md-collection-endpoint="<?php echo esc_url( $endpoint ); ?>"
	data-md-collection-nonce="<?php echo esc_attr( wp_create_nonce( 'wp_rest' ) ); ?>"
	data-md-collection-error="<?php esc_attr_e( 'The Collection item could not be saved. Please try again.', 'md' ); ?>"
	data-md-collection-confirm="<?php echo esc_attr( sprintf( __( 'Move this %s to Trash?', 'md' ), $singular ) ); ?>"
	data-md-collection-added="<?php echo esc_attr( sprintf( __( '%s added.', 'md' ), $singular ) ); ?>"
	data-md-collection-saved="<?php echo esc_attr( sprintf( __( '%s saved.', 'md' ), $singular ) ); ?>"
	data-md-collection-trashed="<?php echo esc_attr( sprintf( __( '%s moved to Trash.', 'md' ), $singular ) ); ?>"
>

	<?php if ( $can_add ) : ?>

	<div class="md-collection-add">
		<?php $collection->fields(); ?>

		<p class="md-collection-actions">
			<button type="button" class="button button-primary" data-md-collection-action="add"><?php echo esc_html( sprintf( __( 'Add %s', 'md' ), $singular ) ); ?></button>
		</p>
	</div>

	<?php else : ?>

	<p class="notice notice-info inline"><span><?php echo esc_html( sprintf( __( 'Publish this post to begin adding %s.', 'md' ), $plural ) ); ?></span></p>

	<?php endif; ?>

	<div class="md-collection-status" data-md-collection-status role="status" aria-live="polite"></div>

	<div class="md-collection-head">
		<h3><?php echo esc_html( $plural ); ?> <span class="md-collection-count" data-md-collection-count><?php echo esc_html( $query->found_posts ); ?></span></h3>
		<a href="<?php echo esc_url( $manage_url ); ?>"><?php echo esc_html( sprintf( __( 'Manage all %s', 'md' ), $plural ) ); ?></a>
	</div>

	<div class="md-collection-items" data-md-collection-items>
		<?php foreach ( $query->posts as $item )
			echo $collection->render_item( $item ); ?>
	</div>

	<p class="md-collection-empty" data-md-collection-empty<?php echo $query->have_posts() ? ' hidden' : ''; ?>><?php echo esc_html( sprintf( __( 'No %s have been added yet.', 'md' ), $plural ) ); ?></p>

	<?php if ( $query->max_num_pages > 1 ) : ?>

	<p class="md-collection-more">
		<button type="button" class="button" data-md-collection-action="more"><?php echo esc_html( sprintf( __( 'Load more %s', 'md' ), $plural ) ); ?></button>
	</p>

	<?php endif; ?>

</div>
