<div class="columns-2 columns-single md-sep-small">

	<div class="col col1">

		<div class="columns-2 columns-half mb-half">

			<div class="col">
				<?php $this->fields->field( 'position', array(
					'type' => 'select',
					'label' => __( 'Position', 'md' ),
					'empty_label' => $position_label,
					'options' => $position_options
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( 'bg_color', array(
					'type' => 'color',
					'label' =>  __( 'Overlay Color', 'md' ),
					'default' => md_setting( array( 'colors', 'content', 'page_cover' ), 'rgba(0, 0, 0, 0.5)' )
				) ); ?>
			</div>

		</div>

		<?php $this->fields->field( 'display', array(
			'type' => 'checkbox',
			'label' => __( 'Settings', 'md' ),
			'inline' => true,
			'options' => $display_options
		) ); ?>

	</div>

	<div class="col col2">
		<?php $this->fields->field( 'photo', array(
			'type' => 'upload',
			'label' => __( 'Upload Image', 'md' ),
			'upload_type' => 'media'
		) ); ?>
	</div>

</div>

<?php if ( $is_post ) {
	echo '<hr class="md-sep-small" />';
	$this->fields->field( 'title_content', array(
		'type' => 'editor',
		'init' => true,
		'label' => __( 'Page Content', 'md' ),
		'description' => __( 'Overwrite the page excerpt or display formatted content to show below the page title.', 'md' ),
		'rows' => 4
	) );
} ?>