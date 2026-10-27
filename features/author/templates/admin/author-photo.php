<div class="md-<?php echo esc_attr( $this->_clean_id ); ?> md-tab-content md-conditional">

	<?php $this->fields->field( 'display', array(
		'type' => 'checkbox',
		'classes' => 'md-conditional-option',
		'wrap_classes' => 'md-sep-micro',
		'options' => array(
			'show' => __( 'Show the author&rsquo;s photo beside single post titles', 'md' )
		),
		'description' => sprintf( __( 'Uses the Author photo on each author&rsquo;s <a href="%s">profile</a>. A featured image placed beside the title keeps its place, and authors without a photo show none.', 'md' ), esc_url( admin_url( 'profile.php' ) ) )
	) ); ?>

	<div class="md-conditional-item md-conditional-1" style="display: <?php echo $show ? 'block' : 'none'; ?>">

	<div class="columns-3 columns-single md-full-select">

		<div class="col">
			<?php $this->fields->field( 'position', array(
				'type' => 'select',
				'label' => __( 'Position', 'md' ),
				'empty_label' => $positions['title_left'],
				'options' => array(
					'title_right' => $positions['title_right'],
					'title_center' => $positions['title_center']
				)
			) ); ?>
		</div>

		<div class="col">
			<?php $this->fields->field( 'mobile', array(
				'type' => 'select',
				'label' => __( 'Position on mobile', 'md' ),
				'empty_label' => __( 'Stack (default)', 'md' ),
				'options' => array(
					'beside' => __( 'Keep beside', 'md' ),
					'hide' => __( 'Hide', 'md' )
				)
			) ); ?>
		</div>

		<div class="col">
			<?php $this->fields->field( 'image_width', array(
				'type' => 'range',
				'label' => __( 'Max width', 'md' ),
				'unit' => 'px',
				'max' => '550'
			) ); ?>
		</div>

	</div>

	</div>

</div>
