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
			'label' => __( 'Description', 'md' ),
			'description' => __( 'Write a short description to show below the page title.', 'md' ),
			'rows' => 4
		) ); ?>
	</div>

	<?php do_action( 'md_hook_page_title_fields' ); ?>

	<div class="md-field-row md-sep-small">
		<?php $this->fields->field( 'page_cta', array(
			'type' => 'select',
			'label' => __( 'Call to Action', 'md' ),
			'description' => __( 'Show a call to action at the top of this page.', 'md' ),
			'empty_label' => __( 'Select CTA type...', 'md' ),
			'options' => array(
				'buttons' => __( 'Links', 'md' )
			)
		) ); ?>
	</div>

	<div class="md-widget md-widget-secondary md-toggle md-row-space">
		<div class="md-widget-title">
			<?php echo __( 'Primary Link', 'md' ); ?>
		</div>
		<div class="md-widget-item">
			<?php $this->fields->link_fields( array(
				'group' => array( 'link_primary' )
			) ); ?>
		</div>
	</div>

	<div class="md-widget md-widget-secondary md-toggle md-row-space">
		<div class="md-widget-title">
			<?php echo __( 'Secondary Link', 'md' ); ?>
		</div>
		<div class="md-widget-item">
			<?php $this->fields->link_fields( array(
				'group' => array( 'link_secondary' )
			) ); ?>
		</div>
	</div>

	<?php do_action( "{$this->_id}_admin_fields" ); ?>

	<hr class="md-sep-small" />

	<?php $this->fields->save(); ?>

</div>
