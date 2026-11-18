<?php do_action( 'md_admin_before_page_title' ); ?>

<?php $this->title_field( array(
	'title' => 'archives_title',
	'size' => 'archives_title_size',
	'description' => __( 'The main <code>h1</code> title displayed prominently on this page. Also used as the default browser title.', 'md' ),
	'inherit' => true,
	'row' => true
) ); ?>

<?php do_action( 'md_admin_after_page_title' ); ?>

<div class="md-field-row mb-single">
	<?php $this->field( 'archives_text', array(
		'type' => 'editor',
		'init' => true,
		'label' => __( 'Content', 'md' ),
		'description' => __( 'Add short or longform content to show below the page title.', 'md' ),
		'rows' => 4
	) ); ?>
</div>
