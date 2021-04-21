<?php echo $args['before_widget']; ?>

	<?php if ( ! empty( $title ) ) : ?>
		<?php echo $args['before_title']; ?><?php echo $title; ?><?php echo $args['after_title']; ?>
	<?php endif; ?>

	<div class="quote-box">
		<?php if ( ! empty( $image ) ) : ?>
			<img src="<?php echo esc_url( $image ); ?>" class="quote-box-image alignright circle shadow" alt="<?php echo $title; ?>" width="80" height="80" />
		<?php endif; ?>
		<?php echo wpautop( $quote ); ?>
	</div>

	<?php if ( ! empty( $author ) ) : ?>
		<p class="quote-box-author"><?php esc_html_e( $author ); ?></p>
	<?php endif; ?>

<?php echo $args['after_widget']; ?>