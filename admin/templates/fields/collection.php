<article class="md-collection-item" data-md-collection-item="<?php echo esc_attr( $item->ID ); ?>">

	<div data-md-collection-summary>

		<p class="md-collection-preview"><?php echo esc_html( $preview ); ?></p>

		<p class="md-collection-summary-meta">
			<span class="md-collection-post-status status-<?php echo esc_attr( $item->post_status ); ?>"><?php echo esc_html( $status ); ?></span>
			<span><?php echo esc_html( $date ); ?></span>
		</p>

		<p class="md-collection-actions">

			<button type="button" class="button-link dashicons-before dashicons-edit" data-md-collection-action="edit"><?php esc_html_e( 'Quick edit', 'md' ); ?></button>

			<?php if ( $edit_link ) : ?>
			<a href="<?php echo esc_url( $edit_link ); ?>" class="dashicons-before dashicons-external" target="_blank" rel="noopener"><?php esc_html_e( 'Edit', 'md' ); ?></a>
			<?php endif; ?>

			<?php if ( $item->post_status === 'publish' && $permalink ) : ?>
			<a href="<?php echo esc_url( $permalink ); ?>" class="dashicons-before dashicons-visibility" target="_blank" rel="noopener"><?php esc_html_e( 'View', 'md' ); ?></a>
			<?php endif; ?>

			<button type="button" class="button-link button-link-delete dashicons-before dashicons-trash" data-md-collection-action="trash"><?php esc_html_e( 'Trash', 'md' ); ?></button>

		</p>

	</div>

	<div data-md-collection-editor hidden>
		<?php $collection->render_fields( $item->ID ); ?>

		<p class="md-collection-actions">
			<button type="button" class="button button-primary" data-md-collection-action="save"><?php echo esc_html( sprintf( __( 'Save %s', 'md' ), $singular ) ); ?></button>
			<button type="button" class="button" data-md-collection-action="cancel"><?php esc_html_e( 'Cancel', 'md' ); ?></button>
		</p>
	</div>

</article>
