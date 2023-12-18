<div class="md-widget md-toggle md-sep-small">

	<h3 class="md-widget-title"><?php echo __( 'Single', 'md' ); ?></h3>

	<div class="md-widget-item">

		<p class="description md-sep-micro"><span class="dashicons dashicons-editor-help"></span> <?php echo __( 'The settings below will apply to single posts only.', 'md' ); ?></p>

		<hr class="md-sep-small" />

		<h4><?php echo __( 'Byline', 'md' ); ?></h4>

		<div class="columns-2 columns-25-75 columns-single">

			<div class="col col1 md-sep-micro">
				<?php $this->fields->field( 'byline_position', array(
					'type' => 'select',
					'empty_label' => __( 'Show in default position...', 'md' ),
					'options' => array(
						'before_headline' => __( 'Show before headline', 'md' ),
						'after_headline' => __( 'Show after headline', 'md' )
					)
				) ); ?>
			</div>

			<div class="col col2">
				<?php $this->fields->field( 'byline_settings', array(
					'type' => 'checkbox',
					'inline' => true,
					'options' => array(
						'relative_date' => __( 'Show relative dates', 'md' ),
						'author_first_name' => __( 'Show author first name', 'md' )
					)
				) ); ?>
			</div>

		</div>

		<div class="md-sep-small">
			<?php $this->fields->field( 'byline', array(
				'type' => 'checkbox',
				'multi' => true,
				'options' => md_byline_items()
			) ); ?>
		</div>

		<hr class="md-sep-small" />

		<div class="columns-3 columns-single">

			<div class="col">

				<h4><?php echo __( 'Author Box', 'md' ); ?></h4>

				<?php $this->fields->field( 'author_box', array(
					'type' => 'checkbox',
					'options' => array(
						'enable' => __( 'Enable author box', 'md' ),
						'all_posts' => __( 'Hide links to author page', 'md' )
					)
				) ); ?>

			</div>

			<div class="col">

				<h4><?php echo __( 'Post Nav', 'md' ); ?></h4>

				<?php $this->fields->field( 'post_nav', array(
					'type' => 'checkbox',
					'options' => array(
						'disable' => __( 'Disable <strong>Post Nav</strong>', 'md' )
					)
				) ); ?>

			</div>

		</div>

	</div>

</div>
