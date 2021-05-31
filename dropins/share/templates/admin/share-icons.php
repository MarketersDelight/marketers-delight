<div class="md-widgets-group md-group-boxes md-sep-small">
	<div class="md-widgets-active">
		<h3><?php echo __( 'Active', 'md' ); ?></h3>
		<div class="md-widgets-canvas" data-canvas="active">
			<?php foreach ( $icons['active'] as $share ) :
				$args = $data[$share];
				$fields = $args['fields'];
				include( md_template( 'dropins', 'share/admin/share-fields', true ) );
			endforeach; ?>
		</div>
	</div>
	<div class="md-widgets-inactive">
		<h3><?php echo __( 'Inactive', 'md' ); ?></h3>
		<div class="md-widgets-canvas" data-canvas="inactive">
			<?php foreach ( $icons['inactive'] as $share ) :
				$args = $data[$share];
				$fields = $args['fields'];
				include( md_template( 'dropins', 'share/admin/share-fields', true ) );
			endforeach; ?>
		</div>
	</div>
</div>