<?php
if ( array_key_exists( 'value', $args ) )
	$option = $args['value'];

$areas = $args['areas'];
$elements = $args['elements'];
$key = esc_attr( $args['field'] );
$active_tab = isset( $args['active_tab'] ) ? $args['active_tab'] : '';
$area_counts = array_fill_keys( array_keys( $areas ), 0 );

if ( is_array( $option ) )
	foreach ( $option as $fields )
		if ( is_array( $fields ) && isset( $area_counts[$fields['builder_area'] ?? ''] ) && isset( $elements[$fields['builder_type'] ?? ''] ) )
			$area_counts[$fields['builder_area']]++;
?>

<div class="md-builder-controls">

	<?php if ( isset( $args['devices'] ) ) $this->devices(); ?>

	<h3 class="md-builder-title"><i class="dashicons dashicons-plus-alt"></i> <?php echo isset( $args['title'] ) ? esc_html( $args['title'] ) : __( 'Add Elements', 'md' ); ?></h3>

	<?php if ( isset( $args['description'] ) ) : ?>
	<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
	<?php endif; ?>

	<div class="md-builder-elements" data-canvas="elements">
		<?php foreach ( $elements as $element_id => $element ) {
			$this->builder_field( $key, '{clone}', $element_id, $element, array(), $args );
		} ?>
	</div>

</div>

<?php if ( isset( $args['tabs'] ) && ( count( $args['tabs'] ) > 1 ) ) : ?>
<div class="md-builder-tabs nav-tab-wrapper">
	<?php foreach ( $args['tabs'] as $tab_id => $tab ) :
		$tab_label = is_array( $tab ) ? $tab['label'] : $tab;
		$tab_context = is_array( $tab ) ? ( $tab['context'] ?? '' ) : '';
		$tab_count = 0;

		foreach ( $areas as $area_id => $area_fields )
			if ( ( $area_fields['tab'] ?? '' ) === $tab_id )
				$tab_count += $area_counts[$area_id];
	?>
	<a href="#" class="md-tab nav-tab<?php echo $tab_id == $active_tab ? ' nav-tab-active' : ''; ?>" data-md-tab="md-builder-<?php echo esc_attr( $tab_id ); ?>" data-context="<?php echo esc_attr( $tab_context ); ?>" data-builder-count-tab="<?php echo esc_attr( $tab_id ); ?>"><?php echo esc_html( $tab_label ); ?> <span class="md-builder-tab-count"><?php echo $tab_count ? '(' . absint( $tab_count ) . ')' : ''; ?></span></a>
	<?php endforeach; ?>
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

		<h3 class="md-builder-title"><i class="dashicons dashicons-admin-page"></i> <?php echo sanitize_text_field( $area_fields['title'] ); ?></h3>

		<?php if ( isset( $area_fields['description'] ) ) : ?>
		<p class="description"><?php echo sanitize_text_field( $area_fields['description'] ); ?></p>
		<?php endif; ?>

	</div>

	<div class="md-builder<?php echo empty( $option ) ? ' empty' : ''; ?>" data-canvas="<?php echo esc_attr( $area_id ); ?>"<?php echo isset( $area_fields['tab'] ) ? ' data-builder-tab="' . esc_attr( $area_fields['tab'] ) . '"' : ''; ?>>

		<?php if ( $option ) : ?>
			<?php foreach ( $option as $group => $fields ) {
				$group = esc_attr( $group );
				$area = ! empty( $fields['builder_area'] ) ? esc_attr( $fields['builder_area'] ) : '';
				$type = ! empty( $fields['builder_type'] ) ? esc_attr( $fields['builder_type'] ) : '';

				if ( $area == $area_id && ! empty( $elements[$type] ) )
					$this->builder_field( $key, $group, $type, $elements[$type], $fields, $args );
			} ?>
		<?php endif; ?>

		<p class="md-builder-empty"><i class="dashicons dashicons-move"></i> <?php echo __( 'Drag an element here.', 'md' ); ?></p>

	</div>

</div>

<?php endforeach; ?>

<?php if ( ! empty( $args['save_empty'] ) )
	$this->field( "{$key}_data", array( 'type' => 'text', 'hidden' => true ) ); ?>
