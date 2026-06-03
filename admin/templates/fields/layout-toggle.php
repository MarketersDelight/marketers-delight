<?php if ( $context['is_admin'] ) {

	$this->fields->field( $id, array(
		'type' => 'checkbox',
		'wrap_classes' => 'md-sep-micro',
		'options' => array( 'global' => __( 'Enable on all pages', 'md' ) )
	) );

	foreach ( $page_types as $type => $label ) {
		echo '<div class="md-flex-columns md-sep-micro">'.
			 '<div class="md-flex-column">';

		$this->fields->field( "{$id}_{$type}_show", array(
			'type' => 'checkbox',
			'inline' => true,
			'options' => array(
				'enable' => sprintf( __( 'Enable on <strong>%s</strong>', 'md' ), $label ),
				'disable' => sprintf( __( 'Disable on <strong>%s</strong>', 'md' ), $label )
			)
		) );

		echo '</div>'.
			 '<div class="md-flex-column">';

		$this->fields->field( "{$id}_$type", array(
			'type' => 'select',
			'empty_label' => sprintf( __( 'Use default %s', 'md' ), $context['toggles'][$id]['title'] ),
			'options' => $layout['areas']
		) );

		echo '</div>'.
			 '</div>';
	}

	$this->fields->field( $id, array(
		'type' => 'checkbox',
		'label' => __( 'Settings', 'md' ),
		'wrap_classes' => 'md-sep-micro',
		'options' => $this->layout_labels( $id, $context['toggles'][$id]['settings'], $context, true )
	) );

	return;
}

$toggle_action = $layout['has'] ? 'remove' : 'add';
$toggle_label = $layout['has'] ? __( 'Remove', 'md' ) : __( 'Add', 'md' );

$this->fields->field( $id, array(
	'type' => 'checkbox',
	'options' => array(
		$toggle_action => sprintf( __( '%s <b>%s</b>', 'md' ), $toggle_label, $context['toggles'][$id]['title'] )
	)
) );

if ( empty( $layout['areas'] ) )
	return;

echo '<div id="' . esc_attr( "{$id}_options" ) . '" class="md-sep-micro" style="display: ' . esc_attr( $layout['display'] ) . ';">';

$this->fields->field( $id, array(
	'type' => 'checkbox',
	'options' => $this->layout_labels( $id, $context['toggles'][$id]['settings'], $context, false )
) );

echo '<div id="' . esc_attr( "{$this->_id}_custom_{$id}_option" ) . '" class="md-sep-micro">';

$this->fields->field( "custom_$id", array(
	'type' => 'select',
	'empty_label' => sprintf( __( 'Use default %s', 'md' ), $context['toggles'][$id]['title'] ),
	'options' => $layout['areas']
) );

echo '</div>';

if ( $context['is_term'] )
	$this->fields->field( "entries_$id", array(
		'type' => 'select',
		'empty_label' => __( 'Posts in this category...', 'md' ),
		'wrap_classes' => 'md-sep-micro',
		'options' => $layout['areas']
	) );

echo
	'<p class="description">' . sprintf( __( '<a href="%s" target="_blank">Edit %s</a>', 'md' ), admin_url( 'admin.php?page=md_settings' ), $context['toggles'][$id]['title'] ) . '</p>'.
	 '</div>';