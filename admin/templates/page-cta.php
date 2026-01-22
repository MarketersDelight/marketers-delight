<div class="md-conditional">

	<?php $this->fields->field( 'page_cta', array(
		'type' => 'select',
		'empty_label' => __( 'Select CTA type...', 'md' ),
		'classes' => 'md-conditional-option',
		'wrap_classes' => 'md-sep-small',
		'options' => array(
			'links' => __( 'Links', 'md' ),
			'custom' => __( 'Custom HTML', 'md' )
		)
	) ); ?>

	<div id="<?php echo $prefix; ?>_page_cta_links" class="md-conditional-item md-conditional-links" style="display: <?php echo $cta_type == 'links' ? 'block' : 'none'; ?>">

		<?php $this->fields->field( 'links', array(
			'type' => 'group',
			'sort' => true,
			'style' => 'boxes',
			'secondary' => true,
			'subtitle' => true,
			'callback' => array( $this, 'link_fields' ),
			'elements' => array(
				'primary' => array(
					'label' => __( 'Primary Link', 'md' )
				),
				'secondary' => array(
					'label' => __( 'Secondary Link', 'md' )
				)
			)
		) ); ?>

	</div>

	<div id="<?php echo $prefix; ?>_page_cta_custom" class="md-conditional-item md-conditional-custom" style="display: <?php echo $cta_type == 'custom' ? 'block' : 'none'; ?>">
		<?php $this->fields->field( 'custom_html', array(
			'type' => 'code',
			'label' => __( 'Custom HTML', 'md' ),
		) ); ?>
	</div>

</div>