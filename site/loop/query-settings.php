<div class="md-tabs md-conditional">

	<?php $this->fields->field( array( $group, $field, 'archives' ), array(
		'type' => 'select',
		'label' => __( 'Select a Template...', 'md' ),
		'empty_label' => __( 'Use default loop', 'md' ),
		'wrap_classes' => 'md-sep-small',
		'options' => $loops_options
	) ); ?>

	<div class="nav-tab-wrapper">
		<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="md-loop-query"><?php echo __( 'Query', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-loop-post"><?php echo __( 'Post', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-loop-layout"><?php echo __( 'Layout', 'md' ); ?></a>
	</div>

	<div class="md-loop-query md-tab-content active">

		<div class="columns-3 columns-half md-sep-micro">

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
					'description' => __( 'Order by lowest/highest value.', 'md' ),
					'empty_label' => __( 'Descending', 'md' ),
					'options' => array(
						'ASC' => __( 'Ascending', 'md' )
					)
				) ); ?>
			</div>

			<div id="loop_category_posts" class="col md-sep-micro" style="display: <?php echo $category_posts ? 'inline-block' : 'none'; ?>">
				<?php $this->fields->field( array( $group, $field, 'category_per_page' ), array(
					'type' => 'number',
					'label' => __( 'Categories Per Page', 'md' ),
					'placeholder' => 5,
					'description' => __( 'Number of categories to show.', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'posts_per_page' ), array(
					'type' => 'number',
					'label' => __( 'Posts Per Page', 'md' ),
					'placeholder' => get_option( 'posts_per_page' ),
					'description' => __( 'Number of posts to display per page.', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'columns' ), array(
					'type' => 'number',
					'label' => __( 'Post Columns', 'md' ),
					'placeholder' => '1',
					'description' => __( 'Break posts into a number of columns.', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( array( $group, $field, 'featured' ), array(
					'type' => 'number',
					'label' => __( 'Featured Posts', 'md' ),
					'description' => __( 'Number of posts to feature per page.', 'md' )
				) ); ?>
			</div>

		</div>

		<hr class="md-sep-small" />

		<?php $this->fields->field( array( $group, $field, 'category_posts' ), array(
			'type' => 'checkbox',
			'wrap_classes' => 'md-sep-micro',
			'options' => array(
				'enable' => __( 'List posts by category', 'md' )
			)
		) ); ?>

		<?php $this->fields->field( array( $group, $field, 'post_type' ), array(
			'type' => 'select',
			'label' => __( 'Post Type', 'md' ),
			'empty_label' => __( 'Detect post type', 'md' ),
			'wrap_classes' => 'md-sep-small',
			'options' => $post_types
		) ); ?>

		<div class="columns-2 columns-single">

			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'tags' ), array(
					'type' => 'text',
					'label' => __( 'Include tags...', 'md' ),
					'description' => __( 'Separate tags by a comma <code>,</code>', 'md' ),
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

	<div class="md-loop-post md-tab-content">

		<?php $this->fields->field( array( $group, $field, 'featured_image' ), array(
			'type' => 'select',
			'label' => __( 'Featured Image', 'md' ),
			'empty_label' => __( 'Set image position...', 'md' ),
			'options' => $sanitize->values['featured_image'],
			'wrap_classes' => 'md-sep-small'
		) ); ?>

		<div class="columns-4 columns-half">

			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'content' ), array(
					'type' => 'select',
					'label' => __( 'Post Text', 'md' ),
					'style' => 'width: 100%',
					'empty_label' => __( 'Show default', 'md' ),
					'options' => array(
						'excerpt' => __( 'Show excerpt', 'md' ),
						'full' => __( 'Show full text', 'md' ),
						'hide' => __( 'Hide text', 'md' )
					)
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'read_more' ), array(
					'type' => 'text',
					'label' => __( 'Read More Text', 'md' ),
					'placeholder' => __( 'Continue reading &rarr;', 'md' )
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'excerpt_more' ), array(
					'type' => 'text',
					'label' => __( 'Excerpt More', 'md' ),
					'placeholder' => '[...]'
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( array( $group, $field, 'excerpt_length' ), array(
					'type' => 'number',
					'label' => __( 'Excerpt Length', 'md' ),
					'unit' => __( 'words', 'md' ),
					'placeholder' => __( '55', 'md' )
				) ); ?>
			</div>

		</div>

	</div>

	<div class="md-loop-layout md-tab-content columns-3 columns-half">

		<div class="col">

			<h4><?php echo __( 'Sidebar', 'md' ); ?></h4>

			<?php $this->fields->field( array( $group, $field, 'sidebar' ), array(
				'type' => 'checkbox',
				'wrap_classes' => 'md-sep-micro',
				'options' => array(
					'enable' => __( '<strong>Add</strong> sidebar', 'md' ),
					'sticky' => __( 'Sticky sidebar', 'md' )
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

		</div>

		<div class="col">

			<h4><?php echo __( 'Call to Action', 'md' ); ?></h4>

			<?php $this->fields->field( array( $group, $field, 'cta_x_loop' ), array(
				'type' => 'number',
				'label' => __( 'Show After X Post', 'md' ),
				'description' => __( 'Show CTA after post number in Loop.', 'md' ),
				'wrap_classes' => 'md-sep-micro'
			) ); ?>

			<?php $this->fields->field( array( $group, $field, 'x_cta' ), array(
				'type' => 'select',
				'empty_label' => __( 'Select call to action...', 'md' ),
				'options' => $cta_options
			) ); ?>

		</div>

	</div>

</div>
