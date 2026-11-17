<div class="md-widget md-toggle md-dashboard-card">

	<h3 class="md-widget-title"><?php echo md_dropin_icon_tile( 'dashicons-align-wide', '', 'small' ); ?><span><?php echo __( 'Site Layout', 'md' ); ?><span class="md-widget-description"><?php echo __( 'Widths for every page, in every style', 'md' ); ?></span></span></h3>

	<?php foreach ( $placeholders as $key => $placeholder ) : ?>

	<div class="md-tools-row">
		<?php $this->fields->field( array( 'width', $key ), array(
			'placeholder' => $placeholder,
			'min' => $placeholder - $line_height * 4,
			'max' => $placeholder + $line_height * 8,
			'description' => $descriptions[$key]
		) ); ?>
	</div>

	<?php endforeach; ?>

</div>
