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
		$check = isset( $option[$val] ) ? esc_attr( $option[$val] ) : '';
	?>
		<p class="md-checkbox md-checkbox-<?php echo esc_attr( $val ); ?>">
			<input type="checkbox" name="<?php echo $nameval; ?>" id="<?php echo $idval; ?>" value="1"<?php echo $input_class . checked( $check ); ?> />
			<label for="<?php echo $idval; ?>"><?php echo $label; ?></label>
		</p>
	<?php endforeach; ?>
</div>