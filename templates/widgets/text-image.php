<?php echo $args['before_widget']; ?>

	<?php if ( $title ) : ?>
		<?php echo $args['before_title']; ?><?php echo $title; ?><?php echo $args['after_title']; ?>
	<?php endif; ?>

	<div class="box-style block-mid">
		<?php if ( ! empty( $image ) ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" alt="<?php esc_attr_e( $title ); ?>" class="about-avatar avatar alignright shadow" width="100" height="100" />
		<?php endif; ?>
		<?php if ( $desc ) : ?>
			<?php echo wpautop( $desc ); ?>
		<?php endif; ?>
		<?php if ( $button_text || $button_link ) : ?>
			<p><<?php echo $button_html; ?> class="button width-full text-center"><?php esc_html_e( $button_text ); ?></<?php echo $button_html_c; ?>></p>
		<?php endif; ?>
	</div>

<?php echo $args['after_widget']; ?>