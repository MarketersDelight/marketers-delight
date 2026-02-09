<div class="md-field-row md-sep-small">
	<?php $this->field( 'archives_title', array(
		'type' => 'text',
		'label' => __( 'Page Title', 'md' ),
		'description' => __( 'Add an <code>h1</code> title tag to the top of the page.', 'md' )
	) ); ?>
</div>

<div class="md-field-row md-sep-small">
	<?php $this->field( 'archives_text', array(
		'type' => 'editor',
		'init' => true,
		'label' => __( 'Page Content', 'md' ),
		'description' => __( 'Add short or longform content to show below the page title.', 'md' ),
		'rows' => 4
	) ); ?>
</div>