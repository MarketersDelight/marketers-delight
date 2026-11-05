<?php

$classes = array( 'md-select' );
$style = isset( $args['style'] ) ? ' style="' . esc_attr( $args['style'] ) . '"' : '';
$multiple = isset( $args['multiple'] ) ? ' multiple' : '';
$b = isset( $args['multiple'] ) ? '[]' : '';
$attributes = '';

foreach ( $args['attributes'] ?? array() as $key => $value )
	$attributes .= $value === true ? ' ' . esc_attr( $key ) : ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';

if ( isset( $args['classes'] ) )
	$classes[] = $args['classes'];

$classes = join( ' ' , $classes );
?>

<?php if ( $b ) : ?>
<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="" />
<?php endif; ?>

<select name="<?php echo esc_attr( $name . $b ); ?>" id="<?php echo $id; ?>" class="<?php echo esc_attr( $classes ); ?>"<?php echo $multiple . $style . $attributes; ?>>

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
		$sections = array();

		// Groups list option keys under a label; options left out follow them

		if ( ! empty( $args['groups'] ) ) {
			$grouped = array();

			foreach ( $args['groups'] as $group_label => $keys ) {
				$sections[$group_label] = $keys;
				$grouped = array_merge( $grouped, $keys );
			}

			$sections[''] = array_diff( array_keys( $args['options'] ), $grouped );
		}
		else
			$sections[''] = array_keys( $args['options'] );

		foreach ( $sections as $group_label => $keys ) :
			if ( $group_label !== '' )
				echo '<optgroup label="' . esc_attr( $group_label ) . '">';

			foreach ( $keys as $val ) :
				if ( ! array_key_exists( $val, $args['options'] ) )
					continue;

				if ( is_array( $option ) )
					$selected = in_array( $val, $option ) ? ' selected="selected"' : '';
				else
					$selected = selected( $option, $val, false );
			?>

			<option value="<?php echo esc_attr( $val ); ?>"<?php echo $selected; ?>><?php echo esc_html( $args['options'][$val] ); ?></option>

			<?php endforeach;

			if ( $group_label !== '' )
				echo '</optgroup>';
		endforeach;
	endif; ?>

</select>
