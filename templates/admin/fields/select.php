<?php if ( isset( $args['select2'] ) ) : // Select2 Init field
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
	else
		$init_label = __( 'Select author(s)...', 'md' );
?>
	<input type="text" class="md-select2-init regular-text" placeholder="<?php echo esc_html( $init_label ); ?>" />
<?php endif; ?>

<select name="<?php echo $name . $b; ?>" id="<?php echo $id; ?>" class="<?php echo esc_attr( $classes ); ?>"<?php echo $multiple; ?><?php echo $style; ?>>

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