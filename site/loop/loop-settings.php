<div class="columns-25-75">

	<div class="col col1 md-sep-small">
		<?php $this->fields->field( 'archives', array(
			'type' => 'select',
			'label' => __( 'Select Loop', 'md' ),
			'empty_label' => __( 'Use default loop', 'md' ),
			'options' => $loops_options
		) ); ?>
	</div>

	<?php if ( $screen->base !== 'term' ) : ?>

	<div class="col col2 md-sep-small field-no-label">
		<?php $this->fields->field( 'category_posts', array(
			'type' => 'checkbox',
			'options' => array(
				'enable' => __( 'Show posts by category', 'md' )
			)
		) ); ?>
	</div>

	<?php endif; ?>

</div>

<hr class="md-sep-small" />

<div class="columns-3 columns-single mb-sep-small">

	<?php if ( $screen->base !== 'term' ) : ?>

	<div id="loop_category_posts" class="col md-sep-micro" style="display: <?php echo $category_posts ? 'inline-block' : 'none'; ?>">
		<?php $this->fields->field( 'category_per_page', array(
			'type' => 'number',
			'label' => __( 'Categories Per Page', 'md' ),
			'placeholder' => 5,
			'description' => __( 'Number of categories to show.', 'md' )
		) ); ?>
	</div>

	<?php endif; ?>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'posts_per_page', array(
			'type' => 'number',
			'label' => __( 'Posts Per Page', 'md' ),
			'placeholder' => get_option( 'posts_per_page' ),
			'description' => __( 'Number of posts to display per page.', 'md' )
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'columns', array(
			'type' => 'number',
			'label' => __( 'Post Columns', 'md' ),
			'placeholder' => '1',
			'description' => __( 'Break posts into a number of columns.', 'md' )
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'featured', array(
			'type' => 'number',
			'label' => __( 'Featured Posts', 'md' ),
			'description' => __( 'Number of posts to feature per page.', 'md' )
		) ); ?>
	</div>

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
			'description' => __( 'Order by lowest/highest value.', 'md' ),
			'empty_label' => __( 'Descending', 'md' ),
			'options' => array(
				'ASC' => __( 'Ascending', 'md' )
			)
		) ); ?>
	</div>

</div>

<hr class="md-sep-small" />

<?php $this->fields->field( 'featured_image', array(
	'type' => 'select',
	'label' => __( 'Featured Image', 'md' ),
	'empty_label' => __( 'Set image position...', 'md' ),
	'options' => $sanitize->values['featured_image'],
	'wrap_classes' => 'md-sep-small'
) ); ?>

<div class="columns-4 columns-half">

	<div class="col md-sep-small">
		<?php $this->fields->field( 'content', array(
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

<hr class="md-sep-small" />

<h4><?php echo __( 'Call to Action', 'md' ); ?></h4>

<div class="columns-2 columns-single">

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'cta_x_loop', array(
			'type' => 'number',
			'label' => __( 'Show After X Post', 'md' ),
			'description' => __( 'Show CTA after post number in Loop.', 'md' )
		) ); ?>
	</div>

	<div class="col md-sep-micro">
		<?php $this->fields->field( 'x_cta', array(
			'type' => 'select',
			'label' => __( 'Call to Action', 'md' ),
			'description' => sprintf( __( 'Choose a pre-made <a href="%s">call to action</a> to show within this loop.', 'md' ), admin_url( 'admin.php?page=md_optins&tab=md_cta' ) ),
			'empty_label' => __( 'Select call to action...', 'md' ),
			'options' => $cta_options
		) ); ?>
	</div>

</div>

<?php endif; ?>
