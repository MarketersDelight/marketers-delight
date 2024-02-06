<div class="md-tabs md-conditional">

	<div class="md-alignright md-label-inline">
		<?php $this->fields->field( array( $group, $field, 'position' ), array(
			'type' => 'select',
			'label' => __( 'Position on page', 'md' ),
			'empty_label' => __( 'Do not show', 'md' ),
			'options' => array(
				'before_content_box' => __( 'After Header', 'md' ),
				'before_content' => __( 'Before Content', 'md' ),
				'content' => __( 'After Content', 'md' ),
				'before_footer' => __( 'Before Footer', 'md' )
			)
		) ); ?>
	</div>

	<div class="nav-tab-wrapper">
		<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="md-loop-query"><?php echo __( 'Query', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-loop-post-content"><?php echo __( 'Post', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-loop-content"><?php echo __( 'Content', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-loop-style"><?php echo __( 'Style', 'md' ); ?></a>
	</div>

	<div class="md-loop-query md-tab-content active">

		<?php $this->fields->field( array( $group, $field, 'category_posts' ), array(
			'type' => 'checkbox',
			'wrap_classes' => 'md-sep-micro',
			'check_class' => 'md-check-val',
			'options' => array(
				'enable' => __( 'List posts by category', 'md' )
			)
		) ); ?>

		<div class="columns-4 columns-half md-sep-micro">

			<div class="col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'post_type' ), array(
					'type' => 'select',
					'label' => __( 'Post Type', 'md' ),
					'empty_label' => __( 'Detect post type', 'md' ),
					'options' => $post_types
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'orderby' ), array(
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
				<?php $this->fields->field( array( $group, $field, 'order' ), array(
					'type' => 'select',
					'label' => __( 'Order', 'md' ),
					'description' => __( 'Lowest/highest value.', 'md' ),
					'empty_label' => __( 'Descending', 'md' ),
					'options' => array(
						'ASC' => __( 'Ascending', 'md' )
					)
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'offset' ), array(
					'type' => 'number',
					'label' => __( 'Offset', 'md' ),
					'description' => __( 'Skip a number of posts.', 'md' )
				) ); ?>
			</div>

			<div class="loop-category-field col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'category_per_page' ), array(
					'type' => 'number',
					'label' => __( 'Categories Per Page', 'md' ),
					'placeholder' => 5,
					'description' => __( 'Category sections to show.', 'md' )
				) ); ?>
			</div>

			<div class="loop-category-field col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'category_columns' ), array(
					'type' => 'number',
					'label' => __( 'Category Columns', 'md' ),
					'description' => __( 'Categories into columns.', 'md' ),
					'placeholder' => 1
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'posts_per_page' ), array(
					'type' => 'number',
					'label' => __( 'Posts Per Page', 'md' ),
					'placeholder' => get_option( 'posts_per_page' ),
					'description' => __( 'Number of posts to show.', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'featured' ), array(
					'type' => 'number',
					'label' => __( 'Featured Posts', 'md' ),
					'description' => __( 'Feature the first X posts.', 'md' ),
					'classes' => 'md-num-val'
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'columns' ), array(
					'type' => 'number',
					'label' => __( 'Post Columns', 'md' ),
					'placeholder' => '1',
					'description' => __( 'Break posts into columns.', 'md' )
				) ); ?>
			</div>

			<?php if ( $cta_options ) : ?>
			<div class="col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'cta_x_loop' ), array(
					'type' => 'number',
					'label' => __( 'Call to Action', 'md' ),
					'description' => __( 'Show CTA after the Xth post.', 'md' ),
				) ); ?>
			</div>
			<?php endif; ?>

		</div>

		<hr class="md-sep-small" />

		<div class="columns-2 columns-single">

			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'tags' ), array(
					'type' => 'text',
					'label' => __( 'Include tags', 'md' ),
					'description' => __( 'Separate tags by a comma <code>,</code>', 'md' ),
					'placeholder' => 'tag1, tag2, tag3',
					'wrap_classes' => 'md-sep-micro'
				) ); ?>

				<?php $this->fields->field( array( $group, $field, 'include_cats' ), array(
					'label' => __( 'Include categories', 'md' ),
					'type' => 'terms'
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'author' ), array(
					'label' => __( 'By author(s)', 'md' ),
					'type' => 'select',
					'empty_label' => __( 'Select author(s)...', 'md' ),
					'options' => $args['authors'],
					'group' => true,
					'select2' => true,
					'multiple' => true,
					'style' => 'width: 100%;',
					'wrap_classes' => 'md-sep-small'
				) ); ?>

				<?php $this->fields->field( array( $group, $field, 'exclude_cats' ), array(
					'label' => __( 'Exclude categories', 'md' ),
					'type' => 'terms'
				) ); ?>
			</div>

		</div>

	</div>

	<div class="md-loop-post md-loop-post-inline md-loop-post-content md-tab-content">

		<?php foreach ( array( 'standard', 'featured' ) as $post ) :
			$p = $post == 'featured' ? "{$post}_" : '';
			$active = $post == 'standard' ? ' active' : '';
		?>

		<div class="md-loop-post-<?php echo esc_attr( $post ); ?> md-sep-small">

			<h4 class="md-loop-post-title"><?php echo sprintf( __( '%s Posts', 'md' ), ucwords( $post ) ); ?></h4>

			<div class="columns-3 columns-half md-sep-small">

				<div class="col">
					<?php $this->fields->field( array( $group, $field, "{$p}featured_image" ), array(
						'type' => 'select',
						'label' => __( 'Featured Image', 'md' ),
						'empty_label' => __( 'Use default position', 'md' ),
						'options' => $sanitize->values['featured_image'],
						'wrap_classes' => 'md-sep-micro'
					) ); ?>
					<?php $this->fields->field( array( $group, $field, "{$p}featured_image_size" ), array(
						'type' => 'select',
						'empty_label' => __( 'Show full size image', 'md' ),
						'options' => array(
							'thumbnail' => __( 'Post Thumbnail 150x150)', 'md' )
						)
					) ); ?>
				</div>

				<div class="col">
					<?php $this->fields->field( array( $group, $field, "{$p}content" ), array(
						'type' => 'select',
						'label' => __( 'Post Content', 'md' ),
						'empty_label' => __( 'Show excerpt', 'md' ),
						'style' => 'width: 75%',
						'options' => array(
							'full' => __( 'Show full content', 'md' ),
							'hide' => __( 'Hide content', 'md' )
						)
					) ); ?>
				</div>

				<div class="col">
					<?php $this->fields->field( array( $group, $field, "{$p}remove_byline" ), array(
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
					<?php $this->fields->field( array( $group, $field, "{$p}post_footer" ), array(
						'type' => 'checkbox',
						'options' => array(
							'remove' => __( 'Remove Post Footer', 'md' )
						)
					) ); ?>
				</div>

			</div>

			<div class="columns-4 columns-half">

				<div class="col">
					<?php $this->fields->field( array( $group, $field, "{$p}read_more" ), array(
						'type' => 'text',
						'label' => __( 'Read More Text', 'md' ),
						'placeholder' => __( 'Continue reading &rarr;', 'md' )
					) ); ?>
				</div>

				<div class="col">
					<?php $this->fields->field( array( $group, $field, "{$p}read_more_style" ), array(
						'type' => 'select',
						'label' => __( 'Text Style', 'md' ),
						'style' => 'width: 100%',
						'empty_label' => __( 'Text link', 'md' ),
						'options' => array(
							'button' => __( 'Button', 'md' )
						)
					) ); ?>
				</div>

				<div class="col">
					<?php $this->fields->field( array( $group, $field, "{$p}excerpt_more" ), array(
						'type' => 'text',
						'label' => __( 'Excerpt More', 'md' ),
						'placeholder' => '[...]'
					) ); ?>
				</div>

				<div class="col">
					<?php $this->fields->field( array( $group, $field, "{$p}excerpt_length" ), array(
						'type' => 'number',
						'label' => __( 'Excerpt Length', 'md' ),
						'unit' => __( 'words', 'md' ),
						'placeholder' => __( '55', 'md' )
					) ); ?>
				</div>

			</div>

			<?php if ( $post == 'featured' ) : ?>
				<hr class="md-sep-small-top" />
			<?php endif; ?>

		</div>

		<?php endforeach; ?>

	</div>

	<div class="md-loop-style md-tab-content">

		<div class="md-radio-fields md-clear md-sep-micro">
			<?php $this->fields->field( array( $group, $field, 'loop' ), array(
				'type' => 'radio',
				'label' => __( 'Select Loop', 'md' ),
				'svg' => md_svg( 'query' ),
				'layout' => 'banner',
				'columns' => 5,
				'tooltip' => 'large',
				'options' => md_loops()
			) ); ?>
		</div>

		<div class="columns-4 columns-half md-sep-small md-full-select">

			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'style' ), array(
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
				<?php $this->fields->field( array( $group, $field, 'size' ), array(
					'type' => 'select',
					'label' => __( 'Font Size', 'md' ),
					'empty_label' => __( 'Inherit', 'md' ),
					'options' => array(
						'large' => __( 'Large', 'md' ),
						'medium' => __( 'Medium', 'md' ),
						'small' => __( 'Small', 'md' )
					)
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'list' ), array(
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

		</div>

	</div>

	<div class="md-loop-content md-tab-content">

		<div class="columns-2 columns-65-35 columns-single">

			<div class="col col1">

				<?php $this->fields->field( array( $group, $field, 'title' ), array(
					'type' => 'text',
					'label' => __( 'Title', 'md' ),
					'wrap_classes' => 'md-sep-small'
				) ); ?>

				<?php $this->fields->field( array( $group, $field, 'description' ), array(
					'type' => 'editor',
					'init' => true,
					'label' => __( 'Description', 'md' )
				) ); ?>

			</div>

			<div class="col col2 md-full-select">

				<?php $this->fields->field( array( $group, $field, 'sidebar' ), array(
					'type' => 'checkbox',
					'wrap_classes' => 'md-sep-micro',
					'label' => __( 'Sidebar', 'md' ),
					'inline' => true,
					'options' => array(
						'enable' => __( '<strong>Add</strong> sidebar', 'md' ),
						'sticky' => __( 'Sticky', 'md' )
					)
				) ); ?>

				<?php $this->fields->field( array( $group, $field, 'custom_sidebar' ), array(
					'type' => 'select',
					'empty_label' => __( 'Use Main sidebar', 'md' ),
					'wrap_classes' => 'md-sep-micro',
					'options' => $sidebars
				) ); ?>

				<?php $this->fields->field( array( $group, $field, 'content_layout' ), array(
					'type' => 'select',
					'empty_label' => __( 'Content / Sidebar', 'md' ),
					'wrap_classes' => 'md-sep-micro',
					'options' => array(
						'sidebar_content' => __( 'Sidebar / Content', 'md' )
					)
				) ); ?>

				<?php if ( $cta_options )
					$this->fields->field( array( $group, $field, 'x_cta' ), array(
						'type' => 'select',
						'label' => __( 'Call to Action', 'md' ),
						'description' => __( 'The CTA to show after the Xth post.', 'md' ),
						'empty_label' => __( 'Select call to action...', 'md' ),
						'options' => $cta_options
					) ); ?>

			</div>

		</div>

	</div>

</div>
