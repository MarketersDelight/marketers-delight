<div class="columns-3 columns-single md-full-select">

	<?php $loop_options = md_loops( 'options' ); ?>

	<div class="col">
		<?php $this->fields->field( 'loop', array(
			'type' => 'select',
			'label' => __( 'Template', 'md' ),
			'empty_label' => __( 'Use default', 'md' ),
			'inherit' => true,
			'options' => $loop_options
		) ); ?>
	</div>

	<?php echo '<div class="col">';

	$loop_type_options = array(
		'post_listing' => __( 'Post listing (default)', 'md' ),
		'category' => __( 'Category overview', 'md' ),
		'category_posts' => __( 'List posts by category', 'md' )
	);

	$this->fields->field( 'loop_type', array(
		'type' => 'select',
		'label' => __( 'Loop type', 'md' ),
		'wrap_classes' => 'md-sep-micro',
		'classes' => 'md-check-val',
		'empty_label' => __( 'Use default', 'md' ),
		'inherit' => true,
		'options' => $loop_type_options
	) );

	echo '</div>'.
		 '<div class="col">';

	$this->fields->field( 'category', array(
		'type' => 'checkbox',
		'wrap_classes' => 'md-sep-micro md-sep-top-med',
		'options' => array( $subcat_key => $subcat_label )
	) );

	$this->fields->field( 'date', array(
		'type' => 'checkbox',
		'wrap_classes' => 'md-sep-micro',
		'options' => array(
			'group' => __( 'Group posts by month', 'md' )
		),
		'inherit' => array(
			'group' => array(
				'on' => __( 'Group posts by month', 'md' ),
				'off' => __( 'Do not group posts by month', 'md' )
			)
		)
	) );

	echo '</div>';

?>

</div>

<div class="md-loop-options md-tabs md-sep-micro">

	<?php if ( $screen['is_admin'] || $screen['is_term'] ) : ?>
	<div class="nav-tab-wrapper mt-half">
		<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="md-loop-post-options"><?php echo __( 'Post Options', 'md' ); ?></a>
		<a href="#" class="md-tab nav-tab" data-md-tab="md-loop-category-options"><?php echo __( 'Category Options', 'md' ); ?></a>
	</div>
	<?php endif; ?>

	<div class="md-loop-post-options md-tab-content active">
		<div class="columns-4 columns-single md-full-select">

			<div class="col md-sep-micro">
				<?php $orderby_options = array(
					'title' => __( 'Title', 'md' ),
//					'author' => __( 'Author', 'md' ),
					'modified' => __( 'Last Modified', 'md' ),
					'comment_count' => __( 'Comment Count', 'md' ),
					'menu_order' => __( 'Menu Order', 'md' ),
					'rand' => __( 'Random', 'md' )
				);

				$this->fields->field( 'orderby', array(
					'type' => 'select',
					'label' => __( 'Orderby', 'md' ),
					'description' => __( 'Sort order of posts', 'md' ),
					'empty_label' => __( 'Date', 'md' ),
					'inherit' => array( 'default' => 'date' ),
					'options' => $orderby_options
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $order_options = array(
					'ASC' => __( 'Ascending', 'md' )
				);

				$this->fields->field( 'order', array(
					'type' => 'select',
					'label' => __( 'Order', 'md' ),
					'description' => __( 'Lowest/highest value', 'md' ),
					'empty_label' => __( 'Descending', 'md' ),
					'inherit' => array( 'default' => 'DESC' ),
					'options' => $order_options
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'posts_per_page', array(
					'type' => 'number',
					'label' => __( 'Posts Per Page', 'md' ),
					'inherit' => array( 'default' => get_option( 'posts_per_page' ) ),
					'description' => __( 'Show number of posts', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'featured', array(
					'type' => 'number',
					'label' => __( 'Featured Posts', 'md' ),
					'description' => __( 'Feature the first X posts', 'md' ),
					'inherit' => true,
					'classes' => 'md-num-val'
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'columns', array(
					'type' => 'number',
					'label' => __( 'Post Columns', 'md' ),
					'inherit' => array( 'default' => 1 ),
					'description' => __( 'Sort posts in columns', 'md' )
				) ); ?>
			</div>

		</div>
	</div>

	<?php if ( $screen['is_admin'] || $screen['is_term'] ) : ?>
	<div class="md-loop-category-options md-tab-content">
		<div class="columns-4 columns-single md-full-select">

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'category_per_page', array(
					'type' => 'number',
					'label' => __( 'Categories Per Page', 'md' ),
					'inherit' => true,
					'description' => __( 'Show category sections', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'posts_per_category', array(
					'type' => 'number',
					'label' => __( 'Posts Per Category', 'md' ),
					'inherit' => array( 'default' => get_option( 'posts_per_page' ) ),
					'description' => __( 'Posts per category section', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'category_columns', array(
					'type' => 'number',
					'label' => __( 'Category Columns', 'md' ),
					'inherit' => array( 'default' => 1 ),
					'description' => __( 'Show category columns', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $category_orderby_options = array(
					'slug' => __( 'Slug', 'md' ),
					'term_id' => __( 'Term ID', 'md' ),
					'count' => __( 'Post Count', 'md' ),
					'parent' => __( 'Parent', 'md' )
				);

				$this->fields->field( 'category_orderby', array(
					'type' => 'select',
					'label' => __( 'Orderby', 'md' ),
					'description' => __( 'Sort order of categories', 'md' ),
					'empty_label' => __( 'Name', 'md' ),
					'inherit' => array( 'default' => 'name' ),
					'options' => $category_orderby_options
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $category_order_options = array(
					'DESC' => __( 'Descending', 'md' )
				);

				$this->fields->field( 'category_order', array(
					'type' => 'select',
					'label' => __( 'Order', 'md' ),
					'description' => __( 'Lowest/highest value', 'md' ),
					'empty_label' => __( 'Ascending', 'md' ),
					'inherit' => array( 'default' => 'ASC' ),
					'options' => $category_order_options
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'category_include', array(
					'type' => 'text',
					'label' => __( 'Include Categories', 'md' ),
					'placeholder' => '1, 4, 7',
					'description' => __( 'Comma-separated term IDs', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'category_exclude', array(
					'type' => 'text',
					'label' => __( 'Exclude Categories', 'md' ),
					'placeholder' => '2, 5',
					'description' => __( 'Comma-separated term IDs', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'category', array(
					'type' => 'checkbox',
					'label' => __( 'Settings', 'md' ),
					'options' => array(
						'hide_description' => __( 'Hide description', 'md' ),
						'show_empty' => __( 'Show empty categories', 'md' )
					),
					'inherit' => array(
						'hide_description' => array(
							'on' => __( 'Hide description', 'md' ),
							'off' => __( 'Show description', 'md' )
						),
						'show_empty' => array(
							'on' => __( 'Show empty categories', 'md' ),
							'off' => __( 'Hide empty categories', 'md' )
						)
					)
				) ); ?>
			</div>

		</div>
	</div>
	<?php endif; ?>

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
		$post_content = $this->fields->module( "{$p}content" );
	?>

	<div class="md-loop-post-group md-loop-post-<?php echo esc_attr( $post ); ?> md-tab-content<?php echo $active; ?><?php echo $post_content === 'hide' ? ' is-content-hidden-inherited' : ''; ?>">

		<div class="columns-3 columns-half md-sep-micro">

			<div class="col">
				<?php $content_options = array(
					'full' => __( 'Show full content', 'md' ),
					'hide' => __( 'Hide content', 'md' )
				);

				$this->fields->field( "{$p}content", array(
					'type' => 'select',
					'label' => __( 'Post Content', 'md' ),
					'classes' => 'md-content-val',
					'style' => 'width: 75%;',
					'wrap_classes' => 'md-sep-micro',
					'empty_label' => __( 'Show excerpt', 'md' ),
					'inherit' => array( 'default' => 'excerpt' ),
					'options' => $content_options
				) );

				$this->fields->field( "{$p}inherit", array(
					'type' => 'checkbox',
					'options' => array(
						'position' => __( 'Inherit Media Position', 'md' ),
						'page_cover' => __( 'Inherit Page Cover', 'md' )
					)
				) ); ?>
			</div>

			<div class="col">
				<?php $featured_image_options = $this->fields->data->values['featured_image'];

				$this->fields->field( "{$p}featured_image", array(
					'type' => 'select',
					'label' => __( 'Featured Media', 'md' ),
					'empty_label' => __( 'Use default position', 'md' ),
					'inherit' => true,
					'options' => $featured_image_options,
					'wrap_classes' => 'md-sep-micro',
				) );
				$this->fields->field( "{$p}featured_image_size", array(
					'type' => 'select',
					'empty_label' => __( 'Show full size image', 'md' ),
					'inherit' => true,
					'options' => array_combine( $image_sizes, $image_sizes )
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( "{$p}remove_byline", array(
					'type' => 'checkbox',
					'label' => __( 'Remove Byline(s)', 'md' ),
					'inline' => true,
					'options' => array(
						'entry_top' => __( 'Entry Top', 'md' ),
						'before_title' => __( 'Before Title', 'md' ),
						'after_title' => __( 'After Title', 'md' ),
						'before_content' => __( 'Before Content', 'md' ),
						'entry_footer' => __( 'Entry Footer', 'md' ),
						'remove' => __( 'All', 'md' )
					),
					'inherit' => array(
						'entry_top' => array(
							'on' => __( 'Remove Entry Top byline', 'md' ),
							'off' => __( 'Show Entry Top byline', 'md' )
						),
						'before_title' => array(
							'on' => __( 'Remove Before Title byline', 'md' ),
							'off' => __( 'Show Before Title byline', 'md' )
						),
						'after_title' => array(
							'on' => __( 'Remove After Title byline', 'md' ),
							'off' => __( 'Show After Title byline', 'md' )
						),
						'before_content' => array(
							'on' => __( 'Remove Before Content byline', 'md' ),
							'off' => __( 'Show Before Content byline', 'md' )
						),
						'entry_footer' => array(
							'on' => __( 'Remove Entry Footer byline', 'md' ),
							'off' => __( 'Show Entry Footer byline', 'md' )
						),
						'remove' => array(
							'on' => __( 'Remove all bylines', 'md' ),
							'off' => __( 'Show bylines', 'md' )
						)
					)
				) ); ?>
			</div>

		</div>

		<div class="md-loop-content-options columns-3 columns-half md-sep-small" style="display: <?php echo $post_content !== 'hide' ? 'block' : 'none'; ?>">

			<div class="col">
				<?php $this->fields->field( "{$p}read_more", array(
					'type' => 'text',
					'label' => __( 'Read More Text', 'md' ),
					'inherit' => array( 'default' => __( 'Continue reading &rarr;', 'md' ) )
				) );
				$this->fields->field( "{$p}excerpt_settings", array(
					'type' => 'checkbox',
					'options' => array(
						'remove_text' => __( 'Do not show', 'md' )
					),
					'inherit' => array(
						'remove_text' => array(
							'on' => __( 'Hide excerpt text', 'md' ),
							'off' => __( 'Show excerpt text', 'md' )
						)
					)
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( "{$p}excerpt_more", array(
					'type' => 'text',
					'label' => __( 'Excerpt More', 'md' ),
					'inherit' => array( 'default' => '[...]' )
				) );
				$this->fields->field( "{$p}excerpt_settings", array(
					'type' => 'checkbox',
					'options' => array(
						'remove_more' => __( 'Do not show', 'md' )
					),
					'inherit' => array(
						'remove_more' => array(
							'on' => __( 'Hide excerpt more', 'md' ),
							'off' => __( 'Show excerpt more', 'md' )
						)
					)
				) ); ?>
			</div>

			<div class="col md-sep-small" style="width: 19%;">
				<?php $this->fields->field( "{$p}excerpt_length", array(
					'type' => 'number',
					'label' => __( 'Excerpt Length', 'md' ),
					'unit' => __( 'words', 'md' ),
					'style' => 'width: 70px',
					'inherit' => array( 'default' => 55 )
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
		<?php $pagination_options = array(
			'prev_next' => __( 'Previous/Next Links', 'md' )
		);

		$this->fields->field( 'pagination', array(
			'type' => 'select',
			'empty_label' => __( 'Page Numbers', 'md' ),
			'inherit' => array( 'default' => 'page_numbers' ),
			'style' => 'width: 100%',
			'options' => $pagination_options
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'previous_label', array(
			'type' => 'text',
			'inherit' => array( 'default' => __( 'Previous', 'md' ) )
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'next_label', array(
			'type' => 'text',
			'inherit' => array( 'default' => __( 'Next', 'md' ) )
		) ); ?>
	</div>

</div>

<?php if ( md_has( 'optins' ) ) : ?>

<h4><?php echo __( 'Call to Action', 'md' ); ?></h4>

<div class="columns-3 columns-half">

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'x_cta', array(
			'type' => 'select',
			'label' => __( 'Call to Action', 'md' ),
			'description' => sprintf( __( 'Choose a pre-made <a href="%s">call to action</a> to show within this loop.', 'md' ), admin_url( 'admin.php?page=md_optins&tab=md_cta' ) ),
			'empty_label' => __( 'Select call to action...', 'md' ),
			'inherit' => true,
			'options' => wp_list_pluck( $cta, 'name' )
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'cta_x_loop', array(
			'type' => 'number',
			'label' => __( 'Show CTA', 'md' ),
			'inherit' => true,
			'description' => __( 'Show after the Xth post.', 'md' )
		) ); ?>
	</div>

</div>

<?php endif; ?>
