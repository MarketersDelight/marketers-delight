<h1><?php echo __( 'Blog Settings', 'md' ); ?></h1>

<hr class="md-sep-small" />

<div class="md-content-wrap-med">

	<?php $this->fields->page_fields(); ?>

	<?php do_action( "{$this->_id}_admin_fields" ); ?>

	<hr class="md-sep-small" />

	<?php $this->fields->save(); ?>

</div>
