<div class="columns-4 columns-single md-full-select md-sep-micro">

	<div class="col">
		<?php $this->fields->field( 'loop', array(
			'type' => 'select',
			'label' => __( 'Template', 'md' ),
			'options' => md_loops( 'options' )
		) ); ?>
	</div>

	<div class="col">
		<?php $this->fields->field( 'style', array(
			'type' => 'select',
			'label' => __( 'Style', 'md' ),
			'empty_label' => __( 'Default style', 'md' ),
			'options' => array(
				'box_style' => __( 'Box style', 'md' ),
				'simple' => __( 'No style', 'md' )
			)
		) ); ?>
	</div>

	<div class="col">
		<?php $this->fields->field( 'list', array(
			'type' => 'select',
			'label' => __( 'List', 'md' ),
			'empty_label' => __( 'Use default', 'md' ),
			'options' => array(
				'list' => __( 'Post Listing', 'md' ),
				'timeline' => __( 'Timeline', 'md' ),
				'timeline-left' => __( 'Timeline (left)', 'md' ),
				'numbers' => __( 'Numbered', 'md' )
			)
		) ); ?>
	</div>

	<?php if ( $screen->base !== 'term' ) : ?>
	<div class="col md-sep-top">
		<?php $this->fields->field( 'category_posts', array(
			'type' => 'checkbox',
			'wrap_classes' => 'md-sep-micro',
			'check_class' => 'md-check-val',
			'options' => array(
				'enable' => __( 'List posts by category', 'md' )
			)
		) ); ?>
	</div>
	<?php endif; ?>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'orderby', array(
			'type' => 'select',
			'label' => __( 'Orderby', 'md' ),
			'description' => __( 'Sort order of posts.', 'md' ),
			'options' => array(
				'' => __( 'Date', 'md' ),
				'title' => __( 'Title', 'md' ),
				'modified' => __( 'Last Modified', 'md' ),
				'comment_count' => __( 'Comment Count', 'md' ),
				'rand' => __( 'Random', 'md' )
			)
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'order', array(
			'type' => 'select',
			'label' => __( 'Order', 'md' ),
			'description' => __( 'Lowest/highest value.', 'md' ),
			'empty_label' => __( 'Descending', 'md' ),
			'options' => array(
				'ASC' => __( 'Ascending', 'md' )
			)
		) ); ?>
	</div>

	<?php if ( $screen->base !== 'term' ) : ?>

	<div class="loop-category-field col md-sep-micro">
		<?php $this->fields->field( 'category_per_page', array(
			'type' => 'number',
			'label' => __( 'Categories Per Page', 'md' ),
			'placeholder' => 5,
			'description' => __( 'Category sections to show.', 'md' )
		) ); ?>
	</div>

	<div class="loop-category-field col md-sep-micro">
		<?php $this->fields->field( 'category_columns', array(
			'type' => 'number',
			'label' => __( 'Category Columns', 'md' ),
			'placeholder' => 1,
			'description' => __( 'Categories into columns.', 'md' )
		) ); ?>
	</div>

	<?php endif; ?>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'posts_per_page', array(
			'type' => 'number',
			'label' => __( 'Posts Per Page', 'md' ),
			'placeholder' => get_option( 'posts_per_page' ),
			'description' => __( 'Number of posts to show.', 'md' )
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'featured', array(
			'type' => 'number',
			'label' => __( 'Featured Posts', 'md' ),
			'description' => __( 'Feature the first X posts.', 'md' ),
			'classes' => 'md-num-val'
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'columns', array(
			'type' => 'number',
			'label' => __( 'Post Columns', 'md' ),
			'placeholder' => '1',
			'description' => __( 'Break posts into columns.', 'md' )
		) ); ?>
	</div>

</div>

<hr />

<div class="md-loop-post md-tabs">

	<div class="nav-tab-wrapper">
		<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="md-loop-post-standard"><?php echo __( 'Standard Posts', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-loop-post-featured"><?php echo __( 'Featured Posts', 'md' ); ?></a>
	</div>

	<?php foreach ( array( 'standard', 'featured' ) as $post ) :
		$p = $post == 'featured' ? "{$post}_" : '';
		$active = $post == 'standard' ? ' active' : '';
	?>

	<div class="md-loop-post-<?php echo esc_attr( $post ); ?> md-tab-content<?php echo $active; ?>">

		<div class="columns-3 columns-half md-sep-small">

			<div class="col">
				<?php $this->fields->field( "{$p}featured_image", array(
					'type' => 'select',
					'label' => __( 'Featured Image', 'md' ),
					'empty_label' => __( 'Use default position', 'md' ),
					'options' => $sanitize->values['featured_image'],
					'wrap_classes' => 'md-sep-micro',
				) ); ?>
				<?php $this->fields->field( "{$p}featured_image_size", array(
					'type' => 'select',
					'empty_label' => __( 'Show full size image', 'md' ),
					'options' => array(
						'thumbnail' => __( 'Post Thumbnail 150x150)', 'md' )
					)
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( "{$p}content", array(
					'type' => 'select',
					'label' => __( 'Post Content', 'md' ),
					'style' => 'width: 75%',
					'empty_label' => __( 'Show excerpt', 'md' ),
					'options' => array(
						'full' => __( 'Show full content', 'md' ),
						'hide' => __( 'Hide content', 'md' )
					)
				) ); ?>
			</div>

			<div class="col md-sep-small">
				<?php $this->fields->field( "{$p}remove_byline", array(
					'type' => 'select',
					'label' => __( 'Byline', 'md' ),
					'empty_label' => __( 'Show full byline', 'md' ),
					'wrap_classes' => 'md-sep-micro',
					'options' => array(
						'before_headline' => __( 'Remove Before Headline', 'md' ),
						'after_headline' => __( 'Remove After Headline', 'md' ),
						'remove' => __( 'Remove Byline', 'md' )
					)
				) ); ?>
				<?php $this->fields->field( "{$p}post_footer", array(
					'type' => 'checkbox',
					'options' => array(
						'remove' => __( 'Remove Post Footer', 'md' )
					)
				) ); ?>
			</div>

		</div>

		<div class="columns-4 columns-half md-sep-small">

			<div class="col">
				<?php $this->fields->field( "{$p}read_more", array(
					'type' => 'text',
					'label' => __( 'Read More Text', 'md' ),
					'placeholder' => __( 'Continue reading &rarr;', 'md' )
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( "{$p}read_more_style", array(
					'type' => 'select',
					'label' => __( 'Text Style', 'md' ),
					'style' => 'width: 100%',
					'empty_label' => __( 'Text link', 'md' ),
					'options' => array(
						'button' => __( 'Button', 'md' )
					)
				) ); ?>
			</div>

			<div class="col md-sep-small">
				<?php $this->fields->field( "{$p}excerpt_more", array(
					'type' => 'text',
					'label' => __( 'Excerpt More', 'md' ),
					'placeholder' => '[...]'
				) ); ?>
			</div>

			<div class="col md-sep-small">
				<?php $this->fields->field( "{$p}excerpt_length", array(
					'type' => 'number',
					'label' => __( 'Excerpt Length', 'md' ),
					'unit' => __( 'words', 'md' ),
					'placeholder' => __( '55', 'md' )
				) ); ?>
			</div>

		</div>

	</div>

	<?php endforeach; ?>

</div>

<hr class="md-sep-small" />

<h4><?php echo __( 'Pagination', 'md' ); ?></h4>

<div class="columns-3 columns-half md-sep-small">

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'pagination', array(
			'type' => 'select',
			'empty_label' => __( 'Page Numbers', 'md' ),
			'style' => 'width: 100%',
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

<?php if ( md_has( 'optins' ) ) : ?>

<h4><?php echo __( 'Call to Action', 'md' ); ?></h4>

<div class="columns-2 columns-30-70 columns-single">

	<div class="col col1">
		<?php $this->fields->field( 'x_cta', array(
			'type' => 'select',
			'description' => sprintf( __( 'Display your pre-made <a href="%s">CTA</a>s and <a href="%1s">Block Patterns</a> in this Loop.', 'md' ), admin_url( 'admin.php?page=md_optins&tab=md_cta' ), admin_url( 'edit.php?post_type=wp_block' ) ),
			'empty_label' => __( 'Select call to action...', 'md' ),
			'options' => $cta_options
		) ); ?>
	</div>

	<div class="col col2">
		<?php $this->fields->field( 'cta_x_loop', array(
			'type' => 'number',
			'label' => __( 'Show after Xth post', 'md' )
		) ); ?>
	</div>

</div>

<?php endif; ?>
