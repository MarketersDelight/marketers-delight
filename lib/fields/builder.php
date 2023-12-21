<div class="md-builder-controls">

	<?php if ( isset( $args['devices'] ) ) $this->devices(); ?>

	<h3 class="md-builder-title"><i class="dashicons dashicons-plus-alt"></i> <?php echo isset( $args['title'] ) ? esc_html( $args['title'] ) : __( 'Add Elements', 'md' ); ?></h3>

	<?php if ( isset( $args['description'] ) ) : ?>
		<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
	<?php endif; ?>

	<div class="md-builder-elements" data-canvas="elements">
		<?php foreach ( $elements as $element_id => $element ) {
			$this->builder_fields( $key, '{clone}', $element_id, $element );
		} ?>
	</div>

</div>

<?php if ( isset( $args['tabs'] ) && ( count( $args['tabs'] ) > 1 ) ) : ?>
	<div class="md-builder-tabs nav-tab-wrapper">
		<?php $t = 0; foreach ( $args['tabs'] as $tab_id => $tab_name ) : ?>
			<a href="#" class="md-tab nav-tab<?php echo $tab_id == $active_tab ? ' nav-tab-active' : ''; ?>" data-md-tab="md-builder-<?php echo esc_attr( $tab_id ); ?>"><?php echo esc_html( $tab_name ); ?></a>
		<?php $t++; endforeach; ?>
	</div>
<?php endif; ?>

<?php foreach ( $areas as $area_id => $area_fields ) :
	$tab_classes = '';

	if ( isset( $area_fields['tab'] ) ) {
		$tab = $area_fields['tab'];
		$tab_classes .= " md-tab-content md-builder-$tab";

		if ( $active_tab == $tab )
			$tab_classes .= ' active';
	}
?>

	<div class="md-builder-<?php echo esc_attr( $area_id ); ?> md-builder-row<?php echo esc_attr( $tab_classes ); ?>">

		<div class="md-builder-head">

			<h3 class="md-builder-title"><i class="dashicons dashicons-admin-page"></i> <?php echo md_text_field( $area_fields['title'] ); ?></h3>

			<?php if ( isset( $area_fields['description'] ) ) : ?>
				<p class="description"><?php echo md_text_field( $area_fields['description'] ); ?></p>
			<?php endif; ?>

		</div>

		<div class="md-builder<?php echo empty( $option ) ? ' empty' : ''; ?>" data-canvas="<?php echo esc_attr( $area_id ); ?>">

			<?php if ( $option ) : ?>
				<?php foreach ( $option as $group => $fields ) {
					$group = esc_attr( $group );
					$area = ! empty( $fields['area'] ) ? esc_attr( $fields['area'] ) : '';
					$type = ! empty( $fields['type'] ) ? esc_attr( $fields['type'] ) : '';

					if ( $area == $area_id )
						$this->builder_fields( $key, $group, $type, $elements[$type] );
				} ?>
			<?php endif; ?>

			<p class="md-builder-empty"><i class="dashicons dashicons-move"></i> <?php echo __( 'Drag an element here.', 'md' ); ?></p>

		</div>

	</div>

<?php endforeach; ?>

<?php $this->field( "{$key}_data", array( 'type' => 'text', 'hidden' => true ) ); ?>
