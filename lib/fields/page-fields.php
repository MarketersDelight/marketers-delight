<div class="md-field-row md-sep">
	<?php $this->field( 'archives_title', array(
		'type' => 'text',
		'label' => __( 'Page Title', 'md' ),
		'description' => __( 'Add an <code>h1</code> title tag to the top of the page.', 'md' )
	) ); ?>
</div>

<div class="md-field-row md-sep">
	<?php $this->field( 'archives_text', array(
		'type' => 'textarea',
		'label' => __( 'Description', 'md' ),
		'description' => __( 'Write a short description to show below the page title.', 'md' ),
		'rows' => 4
	) ); ?>
</div>

<?php do_action( 'md_hook_page_title_fields' ); ?>

<div class="md-page-cta md-conditional">

	<div class="md-field-row md-sep-small">
		<?php $this->field( 'page_cta', array(
			'type' => 'select',
			'label' => __( 'Call to Action', 'md' ),
			'description' => __( 'Show a call to action at the top of this page.', 'md' ),
			'empty_label' => __( 'Select CTA type...', 'md' ),
			'classes' => 'md-conditional-option',
			'options' => array(
				'links' => __( 'Links', 'md' ),
				'custom' => __( 'Custom HTML', 'md' )
			)
		) ); ?>
	</div>

	<div id="<?php echo $prefix; ?>_page_cta_links" class="md-conditional-item md-conditional-links" style="display: <?php echo $cta_type == 'links' ? 'block' : 'none'; ?>">

		<div class="md-widget md-widget-secondary md-toggle md-row-space">
			<div class="md-widget-title">
				<?php echo __( 'Secondary Link', 'md' ); ?>
			</div>
			<div class="md-widget-item">
				<?php $this->link_fields( array(
					'group' => array( 'link_secondary' )
				) ); ?>
			</div>
		</div>

		<div class="md-widget md-widget-secondary md-toggle md-row-space">
			<div class="md-widget-title">
				<?php echo __( 'Primary Link', 'md' ); ?>
			</div>
			<div class="md-widget-item">
				<?php $this->link_fields( array(
					'group' => array( 'link_primary' )
				) ); ?>
			</div>
		</div>

	</div>

	<div id="<?php echo $prefix; ?>_page_cta_custom" class="md-conditional-item md-conditional-custom md-row-space" style="display: <?php echo $cta_type == 'custom' ? 'block' : 'none'; ?>">

		<?php $this->field( 'custom_html', array(
			'type' => 'code',
			'label' => __( 'Custom HTML', 'md' ),
		) ); ?>

	</div>

</div>
