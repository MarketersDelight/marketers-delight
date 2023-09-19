<div class="md-display">

	<?php foreach ( $types as $type => $pages ) :
		$name = '';
		$icon = 'dashicons-admin-post';
		$post_type = get_post_type_object( $type );

		if ( ! empty( $post_type->labels->name ) )
			$name = $post_type->labels->name;

		if ( ! empty( $post_type->menu_icon ) )
			$icon = $post_type->menu_icon;
	?>

		<div class="col-style md-sep-small">

			<h3 class="md-title normal">
				<i class="md-title-icon dashicons <?php echo esc_attr( $icon ); ?>"></i>
				<?php echo esc_html( $name ); ?>
			</h3>

			<hr class="md-sep-small" />

			<?php foreach ( $pages as $page => $val ) {
				echo '<div class="md-display-fields">';

				if ( $page )
					if ( $page === 'single' )
						$label = $post_type->labels->singular_name;
					elseif ( $page == 'archive' )
						$label = sprintf( __( '%s page', 'md' ), $name );
					else {
						$page_label = str_replace( "{$type}_", '', $page );
						$label = "$name $page_label";
					}

				call_user_func( $args['callback'], $type, $page, $label );

				echo '</div>';
			} ?>

		</div>

	<?php endforeach; ?>

</div>