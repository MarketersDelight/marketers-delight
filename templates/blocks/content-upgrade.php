<?php
	$title = md_block_field( $attributes, 'title' );
	$text = md_block_field( $attributes, 'text' );
	$url = md_block_field( $attributes, 'url' );
	$icon = md_block_field( $attributes, 'icon' );
	$buttonText = md_block_field( $attributes, 'buttonText' );
	$bgColor = md_block_field( $attributes, 'bgColor' );
	$bgColorClass = md_block_field( $attributes, 'bgColorClass' );
	$bgImage = md_block_field( $attributes, 'bgImage' );
	$textColor = md_block_field( $attributes, 'textColor' );
	$textColorClass = md_block_field( $attributes, 'textColorClass' );
	$buttonColor = md_block_field( $attributes, 'buttonColor' );
	$buttonColorClass = md_block_field( $attributes, 'buttonColorClass' );
	$buttonTextColor = md_block_field( $attributes, 'buttonTextColor' );
	$buttonTextColorClass = md_block_field( $attributes, 'buttonTextColorClass' );
	$popup = md_block_field( $attributes, 'popup' );
	$has_popup = ! empty( $popup ) && empty( $url ) ? true : false;
	$popup_data = $has_popup ? ' data-popup="md_popup_' . esc_attr( $popup ) . '"' : '';
	$align = md_block_field( $attributes, 'align' );
	$alignment = md_block_field( $attributes, 'alignment' );
	$marginBottom = md_block_field( $attributes, 'marginBottom' );
	$padding = md_block_field( $attributes, 'padding' );
	$boxLeftRight = md_block_field( $attributes, 'boxLeftRight' );
	$shadow = md_block_field( $attributes, 'shadow' );
	$className = md_block_field( $attributes, 'className' );
	$href = ! empty( $url ) ? $url : '#';
	$button_text = ! empty( $buttonText ) ? $buttonText : __( 'Download now', 'md' );
	$button_bg = $buttonColor ? 'background-color: ' . esc_attr( $buttonColor ) . ';' : '';
	$button_color = $buttonTextColor ? ' color: ' . esc_attr( $buttonTextColor ) . ';' : '';
	$button_style = $button_bg || $button_color ? ' style="' . $button_bg . $button_color . '"' : '';
	$bg_color = empty( $bgColorClass ) && ! empty( $bgColor ) ? 'background-color: ' . esc_attr( $bgColor ) . '; ' : '';
	$bg_image = ! empty( $bgImage ) ? ' background-image: url(\'' . esc_url( $bgImage ) . '\');' : '';
	$style = $textColor || $bg_color || $bg_image ? ' style="' . $bg_color . ( $bg_image ? $bg_image : '' ) . ( $textColor ? ' color: ' . esc_attr( $textColor ) . ';' : '' ) . '"' : '';
	$classes = $button_classes = array();
	$classes[] = 'content-upgrade';
	$classes[] = ! empty( $align ) ? "align{$align}" : '';
	$classes[] = ! empty( $bgColorClass ) ? $bgColorClass : 'has-secondary-background-color';
	$classes[] = ! empty( $textColorClass ) ? $textColorClass : 'has-white-color';
	$classes[] = ! empty( $marginBottom ) ? $marginBottom : 'mb-mid';
	$classes[] = ! empty( $padding ) ? $padding : 'block-single';
	if ( ! empty( $className ) )
		$classes[] = $className;
	if ( ! empty( $boxLeftRight ) )
		$classes[] = 'box-lr';
	if ( ! empty( $alignment ) )
		$classes[] = 'text-' . esc_attr( $alignment );
	if ( ! empty( $shadow ) )
		$classes[] = 'shadow';
	if ( ! empty( $bgImage ) )
		$classes[] = 'image-overlay';
	$button_classes[] = ! empty( $buttonColorClass ) ? $buttonColorClass : 'has-button-sec-background-color';
	if ( ! empty( $buttonTextColorClass ) )
		$button_classes[] = $buttonTextColorClass;
	if ( $has_popup )
		$button_classes[] = 'md-popup-trigger';
	$classes = join( ' ', $classes );
	$button_classes = join( ' ', $button_classes );
	if ( md_has( 'popups' ) && $has_popup )
		md_popup( array( 'id' => $popup ) );
?>
<div class="<?php echo esc_attr( $classes ); ?>"<?php echo $style; ?>>
	<?php if ( ! empty( $icon ) ) : ?>
		<div class="content-upgrade-icon large-title mr-single">
			<i class="<?php echo esc_attr( $icon ); ?>"></i>
		</div>
	<?php endif; ?>
	<?php if ( $title || $text ) : ?>
		<div class="content-upgrade-text mb-single">
			<?php if ( $title ) : ?>
				<p class="small-title mb-small"><?php echo md_text_field( $title ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $text) ) : ?>
				<?php echo wpautop( $text ); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div class="content-upgrade-action">
		<a href="<?php echo esc_url( $href ); ?>" class="button button-arrow <?php echo esc_attr( $button_classes ); ?>"<?php echo $button_style; ?><?php echo $popup_data; ?>><?php echo esc_html( $button_text ); ?></a>
	</div>
</div>