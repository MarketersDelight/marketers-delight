<?php

$classes = array( 'md-select' );
$style = isset( $args['style'] ) ? ' style="' . esc_attr( $args['style'] ) . '"' : '';
$multiple = isset( $args['multiple'] ) ? ' multiple' : '';
$b = isset( $args['multiple'] ) ? '[]' : '';

if ( isset( $args['classes'] ) )
	$classes[] = $args['classes'];

if ( isset( $args['select2'] ) )
	$classes[] = 'md-select2';

$classes = join( ' ' , $classes );

if ( isset( $args['select2'] ) ) { // Select2 Init field
	$init_label = '';

	if ( ! empty( $args['options'] ) && is_array( $option ) ) {
		$a = 1;
		$select_count = count( $option );
		foreach ( $args['options'] as $author_id => $author_name ) {
			if ( ! in_array( $author_id, $option ) )
				continue;
			$init_label .= $author_name;
			if ( $a < $select_count )
				$init_label .= ', ';
			$a++;
		}
	}
	else $init_label = __( 'Select author(s)...', 'md' );

	echo '<input type="text" class="md-select2-init regular-text" placeholder="' . esc_html( $init_label ) . '" />';
} ?>

<select name="<?php echo esc_attr( $name . $b ); ?>" id="<?php echo $id; ?>" class="<?php echo esc_attr( $classes ); ?>"<?php echo $multiple; ?><?php echo $style; ?>>

	<?php if ( isset( $args['empty_label'] ) ) : ?>
	<option value=""><?php echo esc_html( $args['empty_label'] ); ?></option>
	<?php endif; ?>

	<?php if ( isset( $args['optgroup'] ) ) : ?>

		<?php foreach ( $args['options'] as $group => $items ) : ?>

		<optgroup label="<?php echo esc_html( ucwords( str_replace( '_', ' ', $group ) ) ); ?>">

		<?php foreach ( $items as $list => $fields ) : ?>
			<option value="<?php echo esc_attr( $list ); ?>"<?php echo selected( $option, $list, false ); ?>><?php echo esc_html( $fields['name'] ); ?></option>
		<?php endforeach; ?>

		</optgroup>

		<?php endforeach; ?>

	<?php elseif ( ! empty( $args['options'] ) ) :
		foreach ( $args['options'] as $val => $label ) :
			if ( is_array( $option ) )
				$selected = in_array( $val, $option ) ? ' selected="selected"' : '';
			else
				$selected = selected( $option, $val, false );
		?>

		<option value="<?php echo esc_attr( $val ); ?>"<?php echo $selected; ?>><?php echo esc_html( $label ); ?></option>

		<?php endforeach;
	endif; ?>

</select>

<?php if ( isset( $args['select2'] ) ) {
	wp_enqueue_style( 'md-select2' );
	wp_enqueue_script( 'md-select2' );
} ?>
