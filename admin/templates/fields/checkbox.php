<?php

$classes = array( 'md-checkboxes' );

if ( isset( $args['multi'] ) )
	$classes[] = 'md-multi-checkbox';

if ( isset( $args['inline'] ) )
	$classes[] = 'md-inline-checkbox';

$classes = join( ' ', $classes );

$input_class = isset( $args['classes'] ) ? ' class="' . esc_attr( $args['classes'] ) . '"' : '';

?>

<div class="<?php echo esc_attr( $classes ); ?>">
	<?php foreach ( $args['options'] as $val => $label ) :
		$nameval = esc_attr( "{$name}[$val]" );
		$idval = esc_attr( "{$id}_$val" );
		$inherited = $args['_inherit'][$val] ?? array();
		$value = array_key_exists( 'value', $inherited ) ? $inherited['value'] : 1;
		$label = isset( $inherited['label'] ) ? $inherited['label'] : $label;
		$check = array_key_exists( 'checked', $inherited ) ? $inherited['checked'] : ( isset( $option[$val] ) ? $option[$val] : false );
		$inherit_attr = array_key_exists( 'parent', $inherited ) ? ' data-md-inherit-parent="' . esc_attr( $inherited['parent'] ) . '"' : '';
	?>
		<p class="md-checkbox md-checkbox-<?php echo esc_attr( $val ); ?>">
			<input type="checkbox" name="<?php echo $nameval; ?>" id="<?php echo $idval; ?>" value="<?php echo esc_attr( $value ); ?>"<?php echo $inherit_attr . $input_class . checked( $check ); ?> />
			<label for="<?php echo $idval; ?>"><?php echo $label; ?></label>
		</p>
	<?php endforeach; ?>
</div>
