<div class="md-widget md-toggle md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Post Settings', 'md' ); ?></h3>

	<div class="md-widget-item">

		<p class="description md-sep-micro"><span class="dashicons dashicons-editor-help"></span> <?php echo __( 'The settings below will apply to single blog posts only.', 'md' ); ?></p>

		<hr class="md-sep-small" />

		<h4><?php echo __( 'Byline', 'md' ); ?></h4>

		<div class="md-sep-small">
			<?php $this->fields->field( array( 'single', 'byline_position' ), array(
				'type' => 'select',
				'empty_label' => __( 'Select byline position...', 'md' ),
				'options' => array(
					'before_headline' => __( 'Show before headline', 'md' ),
					'after_headline' => __( 'Show after headline', 'md' )
				)
			) ); ?>
		</div>

		<div class="md-sep-small">
			<?php $this->fields->field( array( 'single', 'byline' ), array(
				'type' => 'checkbox',
				'multi' => true,
				'options' => md_byline_items()
			) ); ?>
		</div>

		<hr class="md-sep-small" />

		<h4><?php echo __( 'Author Box', 'md' ); ?></h4>

		<div class="md-sep-small">
			<?php $this->fields->field( array( 'single', 'author_box' ), array(
				'type' => 'checkbox',
				'options' => array(
					'enable' => __( 'Enable author box after blog posts', 'md' ),
					'all_posts' => __( 'Link to author archives page', 'md' )
				)
			) ); ?>
		</div>

	</div>

</div>
