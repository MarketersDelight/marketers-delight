<h1><?php echo __( 'Blog Settings', 'md' ); ?></h1>

<hr class="md-sep-small" />

<div class="md-content-wrap-med">

	<div class="md-field-row md-sep">
		<?php $this->fields->field( 'archives_title', array(
			'type' => 'text',
			'label' => __( 'Page Title', 'md' ),
			'description' => __( 'Add an <code>h1</code> title tag to the top of the page.', 'md' )
		) ); ?>
	</div>

	<div class="md-field-row md-sep">
		<?php $this->fields->field( 'archives_text', array(
			'type' => 'textarea',
			'label' => __( 'Page Description', 'md' ),
			'description' => __( 'Write a short description to show below the page title.', 'md' ),
			'rows' => 4
		) ); ?>
	</div>

	<?php do_action( 'md_hook_page_title_fields' ); ?>

	<?php do_action( "{$this->_id}_admin_fields" ); ?>

	<?php $this->fields->save(); ?>

</div>
