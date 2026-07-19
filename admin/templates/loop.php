<div class="columns-3 columns-single md-full-select">

	<?php $loop_options = md_loops( 'options' ); ?>

	<div class="col">
		<?php $this->fields->field( 'loop', array(
			'type' => 'select',
			'label' => __( 'Template', 'md' ),
			'empty_label' => $this->fields->inherit_label( 'loop', __( 'Use default', 'md' ), $loop_options ),
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
		'empty_label' => $this->fields->inherit_label( 'loop_type', __( 'Use default', 'md' ), $loop_type_options ),
		'options' => $loop_type_options
	) );

	echo '</div>'.
		 '<div class="col">';

	$this->fields->field( 'category', array(
		'type' => 'checkbox',
		'wrap_classes' => 'md-sep-micro md-sep-top-med',
		'options' => array( $subcat_key => $subcat_label )
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
					'empty_label' => $this->fields->inherit_label( 'orderby', __( 'Date', 'md' ), $orderby_options ),
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
					'empty_label' => $this->fields->inherit_label( 'order', __( 'Descending', 'md' ), $order_options ),
					'options' => $order_options
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'posts_per_page', array(
					'type' => 'number',
					'label' => __( 'Posts Per Page', 'md' ),
					'placeholder' => $this->fields->module( 'posts_per_page' ) ?: get_option( 'posts_per_page' ),
					'description' => __( 'Show number of posts', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'featured', array(
					'type' => 'number',
					'label' => __( 'Featured Posts', 'md' ),
					'description' => __( 'Feature the first X posts', 'md' ),
					'classes' => 'md-num-val'
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'columns', array(
					'type' => 'number',
					'label' => __( 'Post Columns', 'md' ),
					'placeholder' => $this->fields->module( 'columns' ) ?: 1,
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
					'description' => __( 'Show category sections', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'posts_per_category', array(
					'type' => 'number',
					'label' => __( 'Posts Per Category', 'md' ),
					'placeholder' => $this->fields->module( 'posts_per_category' ) ?: get_option( 'posts_per_page' ),
					'description' => __( 'Posts per category section', 'md' )
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( 'category_columns', array(
					'type' => 'number',
					'label' => __( 'Category Columns', 'md' ),
					'placeholder' => $this->fields->module( 'category_columns' ) ?: 1,
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
					'empty_label' => $this->fields->inherit_label( 'category_orderby', __( 'Name', 'md' ), $category_orderby_options ),
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
					'empty_label' => $this->fields->inherit_label( 'category_order', __( 'Ascending', 'md' ), $category_order_options ),
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
					'empty_label' => $this->fields->inherit_label( "{$p}content", __( 'Show excerpt', 'md' ), $content_options ),
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
					'empty_label' => $this->fields->inherit_label( "{$p}featured_image", __( 'Use default position', 'md' ), $featured_image_options ),
					'options' => $featured_image_options,
					'wrap_classes' => 'md-sep-micro',
				) );
				$this->fields->field( "{$p}featured_image_size", array(
					'type' => 'select',
					'empty_label' => __( 'Show full size image', 'md' ),
					'options' => array_combine( $image_sizes, $image_sizes )
				) ); ?>
			</div>

			<div class="col">
				<?php $this->fields->field( "{$p}remove_byline", array(
					'type' => 'checkbox',
					'label' => __( 'Remove Byline(s)', 'md' ),
					'inline' => true,
					'options' => array(
						'before_post' => __( 'Before Post', 'md' ),
						'before_title' => __( 'Before Title', 'md' ),
						'after_title' => __( 'After Title', 'md' ),
						'after_post' => __( 'After Post', 'md' ),
						'remove' => __( 'All', 'md' )
					)
				) ); ?>
			</div>

		</div>

		<div class="md-loop-content-options columns-3 columns-half md-sep-small" style="display: <?php echo $post_content !== 'hide' ? 'block' : 'none'; ?>">

			<div class="col">
				<?php $this->fields->field( "{$p}read_more", array(
					'type' => 'text',
					'label' => __( 'Read More Text', 'md' ),
					'placeholder' => $this->fields->module( "{$p}read_more" ) ?: __( 'Continue reading &rarr;', 'md' )
				) );
				$this->fields->field( "{$p}excerpt_settings", array(
					'type' => 'checkbox',
					'options' => array(
						'remove_text' => __( 'Do not show', 'md' )
					)
				) ); ?>
			</div>

			<div class="col md-sep-micro">
				<?php $this->fields->field( "{$p}excerpt_more", array(
					'type' => 'text',
					'label' => __( 'Excerpt More', 'md' ),
					'placeholder' => $this->fields->module( "{$p}excerpt_more" ) ?: '[...]'
				) );
				$this->fields->field( "{$p}excerpt_settings", array(
					'type' => 'checkbox',
					'options' => array(
						'remove_more' => __( 'Do not show', 'md' )
					)
				) ); ?>
			</div>

			<div class="col md-sep-small" style="width: 19%;">
				<?php $this->fields->field( "{$p}excerpt_length", array(
					'type' => 'number',
					'label' => __( 'Excerpt Length', 'md' ),
					'unit' => __( 'words', 'md' ),
					'style' => 'width: 70px',
					'placeholder' => $this->fields->module( "{$p}excerpt_length" ) ?: 55
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
			'empty_label' => $this->fields->inherit_label( 'pagination', __( 'Page Numbers', 'md' ), $pagination_options ),
			'style' => 'width: 100%',
			'options' => $pagination_options
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'previous_label', array(
			'type' => 'text',
			'placeholder' => $this->fields->module( 'previous_label' ) ?: __( 'Previous', 'md' )
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'next_label', array(
			'type' => 'text',
			'placeholder' => $this->fields->module( 'next_label' ) ?: __( 'Next', 'md' )
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
			'options' => wp_list_pluck( $cta, 'name' )
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'cta_x_loop', array(
			'type' => 'number',
			'label' => __( 'Show CTA', 'md' ),
			'description' => __( 'Show after the Xth post.', 'md' )
		) ); ?>
	</div>

</div>

<?php endif; ?>