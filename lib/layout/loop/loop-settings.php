<div class="md-sep-small">
	<?php $this->fields->field( 'archives', array(
		'type' => 'select',
		'label' => __( 'Select Loop', 'md' ),
		'empty_label' => __( 'Use default loop...', 'md' ),
		'options' => md_loops( 'options' )
	) ); ?>
</div>

<div id="content_loop_teasers" style="display: <?php echo $archives_loop == 'teasers' ? 'block' : 'none'; ?>">
	<hr class="md-sep-small" />
	<div class="columns-2 columns-single mb-sep-small">
		<div class="col md-sep-micro">
			<?php $this->fields->field( 'featured', array(
				'type' => 'number',
				'label' => __( 'Featured Posts', 'md' ),
				'description' => __( 'Enter the number of posts to feature before breaking into the standard loop template.', 'md' )
			) ); ?>
		</div>
		<div class="col md-sep-micro">
			<?php $this->fields->field( 'columns', array(
				'type' => 'number',
				'label' => __( 'Columns', 'md' ),
				'placeholder' => '2',
				'description' => __( 'Enter the number of columns to list standard posts.', 'md' )
			) ); ?>
		</div>
	</div>
</div>

<hr class="md-sep-small" />

<h4><?php echo __( 'Byline', 'md' ); ?></h4>

<div class="md-sep-small">
	<?php $this->fields->field( 'byline_position', array(
		'type' => 'select',
		'empty_label' => __( 'Show before headline', 'md' ),
		'options' => array(
			'after_headline' => __( 'Show after headline', 'md' )
		)
	) ); ?>
</div>

<div class="md-sep-small">
	<?php $this->fields->field( 'byline', array(
		'type' => 'checkbox',
		'multi' => true,
		'options' => md_byline_items()
	) ); ?>
</div>

<hr class="md-sep-small" />

<h4><?php echo __( 'Post Content', 'md' ); ?></h4>

<div class="columns-4 columns-half">
	<div class="col md-sep-small">
		<?php $this->fields->field( 'content', array(
			'type' => 'select',
			'label' => __( 'Post Text', 'md' ),
			'style' => 'width: 100%',
			'class' => '',
			'options' => array(
				'' => __( 'Show full text', 'md' ),
				'excerpt' => __( 'Show excerpt', 'md' ),
				'hide' => __( 'Hide text', 'md' )
			)
		) ); ?>
	</div>
	<div class="col md-sep-small">
		<?php $this->fields->field( 'read_more', array(
			'type' => 'text',
			'label' => __( 'Read More Text', 'md' ),
			'placeholder' => __( 'Continue reading &rarr;', 'md' )
		) ); ?>
	</div>
	<div class="col md-sep-small">
		<?php $this->fields->field( 'excerpt_more', array(
			'type' => 'text',
			'label' => __( 'Excerpt More', 'md' ),
			'placeholder' => '[...]'
		) ); ?>
	</div>
	<div class="col md-sep-small">
		<?php $this->fields->field( 'excerpt_length', array(
			'type' => 'number',
			'label' => __( 'Excerpt Length', 'md' ),
			'unit' => __( 'words', 'md' ),
			'placeholder' => __( '55', 'md' )
		) ); ?>
	</div>
</div>

<h4><?php echo __( 'Pagination', 'md' ); ?></h4>

<div class="columns-3 columns-half md-sep-small">
	<div class="col md-sep-micro">
		<?php $this->fields->field( 'pagination', array(
			'type' => 'select',
			'empty_label' => __( 'Page Numbers', 'md' ),
			'options' => array(
				'prev_next' => __( 'Previous/Next Links', 'md' )
			)
		) ); ?>
	</div>
	<div class="col md-sep-micro">
		<?php $this->fields->field( 'previous_label', array(
			'type' => 'text',
			'placeholder' => __( 'Previous', 'md' )
		) ); ?>
	</div>
	<div class="col md-sep-micro">
		<?php $this->fields->field( 'next_label', array(
			'type' => 'text',
			'placeholder' => __( 'Next', 'md' )
		) ); ?>
	</div>
</div>

<hr class="md-sep-small" />

<h4><?php echo __( 'Call to Action', 'md' ); ?></h4>

<div class="columns-2 columns-single">
	<div class="col md-sep-micro">
		<?php $this->fields->field( 'cta_x_loop', array(
			'type' => 'number',
			'label' => __( 'Show After X Post', 'md' ),
			'description' => __( 'Enter the post number to show a call to action after.', 'md' )
		) ); ?>
	</div>
	<div class="col md-sep-micro">
		<?php $this->fields->field( 'x_cta', array(
			'type' => 'select',
			'label' => __( 'Call to Action', 'md' ),
			'description' => sprintf( __( 'Choose one of your pre-made <a href="%s">call to actions</a> from MD Optins to show within this loop.', 'md' ), admin_url( 'admin.php?page=md_optins&tab=md_cta' ) ),
			'empty_label' => __( 'Select call to action...', 'md' ),
			'options' => $cta_options
		) ); ?>
	</div>
</div>
