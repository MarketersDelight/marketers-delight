<?php

$classes = array( 'md-checkboxes' );

if ( isset( $args['multi'] ) )
	$classes[] = 'md-multi-checkbox';

if ( isset( $args['inline'] ) )
	$classes[] = 'md-inline-checkbox';

if ( isset( $args['classes'] ) )
	$classes[] = $args['classes'];

$classes = join( ' ', $classes );

$check_class = isset( $args['check_class'] ) ? ' class="' . $args['check_class'] . '"' : '';

?>

<div class="<?php echo esc_attr( $classes ); ?>">
	<?php foreach ( $args['options'] as $val => $label ) :
		$nameval = esc_attr( "{$name}[$val]" );
		$idval = esc_attr( "{$id}_$val" );
		$check = isset( $option[$val] ) ? esc_attr( $option[$val] ) : '';
	?>
		<p class="md-checkbox md-checkbox-<?php echo esc_attr( $val ); ?>">
			<input type="checkbox" name="<?php echo $nameval; ?>" id="<?php echo $idval; ?>" value="1"<?php echo $check_class . checked( $check ); ?> />
			<label for="<?php echo $idval; ?>"><?php echo $label; ?></label>
		</p>
	<?php endforeach; ?>
</div>