<div class="md-radio-fields md-clear md-sep-micro">
	<?php $this->fields->field( 'loop', array(
		'type' => 'radio',
		'label' => __( 'Select Loop', 'md' ),
		'svg' => '<svg viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg" width="25" height="25" aria-hidden="true" focusable="false"><path d="M18.1823 11.6392C18.1823 13.0804 17.0139 14.2487 15.5727 14.2487C14.3579 14.2487 13.335 13.4179 13.0453 12.2922L13.0377 12.2625L13.0278 12.2335L12.3985 10.377L12.3942 10.3785C11.8571 8.64997 10.246 7.39405 8.33961 7.39405C5.99509 7.39405 4.09448 9.29465 4.09448 11.6392C4.09448 13.9837 5.99509 15.8843 8.33961 15.8843C8.88499 15.8843 9.40822 15.781 9.88943 15.5923L9.29212 14.0697C8.99812 14.185 8.67729 14.2487 8.33961 14.2487C6.89838 14.2487 5.73003 13.0804 5.73003 11.6392C5.73003 10.1979 6.89838 9.02959 8.33961 9.02959C9.55444 9.02959 10.5773 9.86046 10.867 10.9862L10.8772 10.9836L11.4695 12.7311C11.9515 14.546 13.6048 15.8843 15.5727 15.8843C17.9172 15.8843 19.8178 13.9837 19.8178 11.6392C19.8178 9.29465 17.9172 7.39404 15.5727 7.39404C15.0287 7.39404 14.5066 7.4968 14.0264 7.6847L14.6223 9.20781C14.9158 9.093 15.2358 9.02959 15.5727 9.02959C17.0139 9.02959 18.1823 10.1979 18.1823 11.6392Z"></path></svg>',
		'layout' => 'banner',
		'columns' => 4,
		'options' => array(
			'fluid' => array(
				'name' => __( 'Fluid (default)', 'md' ),
				'description' => __( 'A traditional blog with a flexible layout.', 'md' ),
				'image' => MD_URL . 'lib/admin/images/loop-fluid.png'
			),
			'list' => array(
				'name' => __( 'Simple List', 'md' ),
				'description' => __( 'A simplified list with compact images.', 'md' ),
				'image' => MD_URL . 'lib/admin/images/loop-list.png'
			),
			'icons' => array(
				'name' => __( 'Icon Cards', 'md' ),
				'description' => __( 'Small cards with a focus on the image thumbnail.', 'md' ),
				'image' => MD_URL . 'lib/admin/images/loop-icons.png'
			)
		)
	) ); ?>
</div>

<?php if ( $screen->base !== 'term' ) : ?>

<?php $this->fields->field( 'category_posts', array(
	'type' => 'checkbox',
	'wrap_classes' => 'md-sep-micro',
	'options' => array(
		'enable' => __( 'Show posts by category', 'md' )
	)
) ); ?>

<?php endif; ?>

<div class="columns-4 columns-half mb-sep-small">

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
			'description' => __( 'Order lowest/highest value.', 'md' ),
			'empty_label' => __( 'Descending', 'md' ),
			'options' => array(
				'ASC' => __( 'Ascending', 'md' )
			)
		) ); ?>
	</div>

	<?php if ( $screen->base !== 'term' ) : ?>

	<div id="loop_category_posts" class="col md-sep-micro" style="display: <?php echo $category_posts ? 'inline-block' : 'none'; ?>">
		<?php $this->fields->field( 'category_per_page', array(
			'type' => 'number',
			'label' => __( 'Categories Per Page', 'md' ),
			'placeholder' => 5,
			'description' => __( 'Category sections to show.', 'md' )
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
			'description' => __( 'Feature the first X posts.', 'md' )
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

<hr class="md-sep-small" />

<?php $this->fields->field( 'featured_image', array(
	'type' => 'select',
	'label' => __( 'Featured Image', 'md' ),
	'empty_label' => __( 'Use default position', 'md' ),
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
