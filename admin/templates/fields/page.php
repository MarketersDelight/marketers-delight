<?php
$cta_type = $this->get_field( 'page_cta' );
$prefix = $this->_prefix;
?>

<div class="md-field-row md-sep">
	<?php $this->field( 'archives_title', array(
		'type' => 'text',
		'label' => __( 'Page Title', 'md' ),
		'description' => __( 'Add an <code>h1</code> title tag to the top of the page.', 'md' )
	) ); ?>
</div>

<div class="md-field-row md-sep">
	<?php $this->field( 'archives_text', array(
		'type' => 'editor',
		'init' => true,
		'label' => __( 'Description', 'md' ),
		'description' => __( 'Write a short description to show below the page title.', 'md' ),
		'rows' => 4
	) ); ?>
</div>