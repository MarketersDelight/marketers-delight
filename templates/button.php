<?php if ( $button['action'] == 'edd_button' && class_exists( 'Easy_Digital_Downloads' ) ) : ?>

	<?php echo edd_get_purchase_link( array(
		'download_id' => $button['edd_id'],
		'price' => false,
		'text' => $button['text'],
		'class' => $button['classes']
	) ); ?>

<?php elseif ( $button['action'] == 'woo_button' && class_exists( 'WooCommerce' ) ) : ?>

	<a href="<?php echo do_shortcode( '[add_to_cart_url id="' . $button['woo_id'] . '"]' ); ?>" class="button<?php echo $button['classes']; ?>"><?php esc_html_e( $button['text'] ); ?></a>

<?php else :
	$html = in_array( $button['action'], array( 'url', 'link' ) ) && ! empty( $button['link'] ) ? 'a' : 'span';
	$href = in_array( $button['action'], array( 'url', 'link' ) ) && ! empty( $button['link'] ) ? ' href="' . esc_url( $button['link'] ) . '"' : '';
	$popup = $button['action'] == 'popup' && ! empty( $button['popup'] ) ? ' data-popup="md_popup_' . esc_attr( $button['popup'] ) . '"' : '';
	$classes .= $button['action'] == 'popup' && ! empty( $button['popup'] ) ? ' md-popup-trigger' : '';
	$button['close_class'] = ! empty( $button['close_class'] ) ? $button['close_class'] : 'md-popup-close';
	$classes .= $button['action'] == 'close' ? ' ' . $button['close_class'] : '';
?>

	<<?php echo $html . $href . $popup; ?> class="button<?php echo esc_attr( $button['classes'] . $classes ); ?>"<?php echo md_style( array( 'bg_color' => $button['bg_color'], 'color' => $button['color'] ) ); ?>>

		<span class="button-text"><?php echo esc_html( $button['text'] ); ?></span>

		<?php if ( ! empty( $button['subtext'] ) || is_customize_preview() ) : ?>
			<span class="button-subtext"><?php echo ! empty( $button['subtext'] ) ? esc_html( $button['subtext'] ) : ''; ?></span>
		<?php endif; ?>

	</<?php echo $html; ?>>

	<?php if ( ! empty( $button['popup'] ) ) : ?>
		<?php md_popup( array( 'id' => $button['popup'] ) ); ?>
	<?php endif; ?>

<?php endif; ?>