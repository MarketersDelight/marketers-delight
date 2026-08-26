<?php do_action( 'md_admin_before_page_title' ); ?>

<div class="md-field-row md-sep-small">
	<?php $this->field( 'archives_title', array(
		'type' => 'text',
		'label' => __( 'Page Title', 'md' ),
		'description' => __( 'The main <code>h1</code> title that displays prominently on this page.', 'md' ),
		'placeholder' => $this->module( 'archives_title' )
	) ); ?>
</div>

<?php do_action( 'md_admin_after_page_title' ); ?>

<div class="md-field-row md-sep-small">
	<?php $this->field( 'archives_text', array(
		'type' => 'editor',
		'init' => true,
		'label' => __( 'Content', 'md' ),
		'description' => __( 'Add short or longform content to show below the page title.', 'md' ),
		'rows' => 4
	) ); ?>
</div>
