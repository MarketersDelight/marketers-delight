<?php
$display_mode = $row['loop_type'] ?? '';
$default_items = get_option( 'posts_per_page' ) ?: 10;

// Which display modes show which fields, matching their md-conditional-* classes

$shows_items = in_array( $display_mode, array( '', 'month' ), true );
$shows_order = $display_mode !== 'category';
$shows_category = in_array( $display_mode, array( 'category', 'category_posts' ), true );
?>
<div class="md-query-loop-fields">
	<div class="md-query-loop-section">
		<h4><?php echo esc_html__( 'Query', 'md' ); ?></h4>
		<p class="description"><?php echo esc_html__( 'Choose what to query and how it relates to the current page.', 'md' ); ?></p>
		<div class="md-query-loop-grid md-full-select">
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'source' ), array(
					'type' => 'select',
					'value' => $row['source'] ?? '',
					'label' => __( 'Content type', 'md' ),
					'description' => __( 'Choose the post type to query.', 'md' ),
					'empty_label' => __( 'Choose content type', 'md' ),
					'options' => $sources
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'context' ), array(
					'type' => 'select',
					'value' => $row['context'] ?? '',
					'label' => __( 'Query context', 'md' ),
					'description' => __( 'Match the current page, or show the latest.', 'md' ),
					'options' => array( 'current' => __( 'Current page', 'md' ), 'latest' => __( 'Latest posts', 'md' ) )
				) ); ?>
			</div>
		</div>
	</div>

	<div class="md-query-loop-section md-conditional">
		<h4><?php echo esc_html__( 'Results', 'md' ); ?></h4>
		<p class="description"><?php echo esc_html__( 'Choose how matching items are displayed and ordered.', 'md' ); ?></p>
		<div class="md-query-loop-grid md-full-select">
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'loop_type' ), array(
					'type' => 'select',
					'value' => $display_mode,
					'label' => __( 'Display mode', 'md' ),
					'description' => __( 'Choose how the results are grouped or presented.', 'md' ),
					'classes' => 'md-conditional-option',
					'empty_label' => __( 'Post listing', 'md' ),
					'options' => array(
						'month' => __( 'Posts grouped by month', 'md' ),
						'category' => __( 'Category overview', 'md' ),
						'category_posts' => __( 'List posts by category', 'md' )
					)
				) ); ?>
			</div>
			<div class="md-query-loop-field md-conditional-item md-conditional-default md-conditional-month<?php echo $shows_items ? ' is-condition' : ''; ?>">
				<?php $fields->field( array( 'builder', $group, 'items' ), array(
					'type' => 'number',
					'value' => $row['items'] ?? '',
					'placeholder' => $default_items,
					'label' => __( 'Number of items', 'md' ),
					'description' => __( 'Blank uses the site default. -1 shows all.', 'md' ),
					'min' => -1,
					'max' => 100
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'taxonomy' ), array(
					'type' => 'select',
					'value' => $row['taxonomy'] ?? '',
					'label' => __( 'Taxonomy filter', 'md' ),
					'description' => __( 'Choose a taxonomy for the selected source.', 'md' ),
					'empty_label' => __( 'All', 'md' ),
					'options' => $taxonomies
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'terms' ), array(
					'type' => 'text',
					'value' => $row['terms'] ?? '',
					'label' => __( 'Include terms', 'md' ),
					'description' => __( 'Only these terms. Slugs or IDs, comma-separated.', 'md' )
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'exclude_terms' ), array(
					'type' => 'text',
					'value' => $row['exclude_terms'] ?? '',
					'label' => __( 'Exclude terms', 'md' ),
					'description' => __( 'Leave out these terms. Slugs or IDs, comma-separated.', 'md' )
				) ); ?>
			</div>
			<div class="md-query-loop-field md-conditional-item md-conditional-default md-conditional-month md-conditional-category_posts<?php echo $shows_order ? ' is-condition' : ''; ?>">
				<?php $fields->field( array( 'builder', $group, 'orderby' ), array(
					'type' => 'select',
					'value' => $row['orderby'] ?? '',
					'label' => __( 'Order by', 'md' ),
					'description' => __( 'Field used for sorting.', 'md' ),
					'empty_label' => __( 'Date', 'md' ),
					'options' => array(
						'title' => __( 'Title', 'md' ),
						'modified' => __( 'Last Modified', 'md' ),
						'comment_count' => __( 'Comment Count', 'md' ),
						'menu_order' => __( 'Menu Order', 'md' )
					)
				) ); ?>
			</div>
			<div class="md-query-loop-field md-conditional-item md-conditional-default md-conditional-month md-conditional-category_posts<?php echo $shows_order ? ' is-condition' : ''; ?>">
				<?php $fields->field( array( 'builder', $group, 'order' ), array(
					'type' => 'select',
					'value' => $row['order'] ?? '',
					'label' => __( 'Order', 'md' ),
					'description' => __( 'Sort direction.', 'md' ),
					'empty_label' => __( 'Descending', 'md' ),
					'options' => array( 'ASC' => __( 'Ascending', 'md' ) )
				) ); ?>
			</div>
		</div>
		<div class="md-query-loop-category-settings md-conditional-item md-conditional-category md-conditional-category_posts<?php echo $shows_category ? ' is-condition' : ''; ?>">
			<hr class="md-sep-small" />
			<h4><?php echo esc_html__( 'Category', 'md' ); ?></h4>
			<p class="description"><?php echo esc_html__( 'These settings apply to this query and do not inherit the source archive’s category layout.', 'md' ); ?></p>
			<div class="md-query-loop-grid md-full-select">
				<div class="md-query-loop-field">
					<?php $fields->field( array( 'builder', $group, 'category_per_page' ), array(
						'type' => 'number',
						'value' => $row['category_per_page'] ?? '',
						'placeholder' => $default_items,
						'label' => __( 'Categories to show', 'md' ),
						'description' => __( 'Number of category sections to show. -1 shows all.', 'md' ),
						'min' => -1,
						'max' => 100
					) ); ?>
				</div>
				<div class="md-query-loop-field md-conditional-item md-conditional-category_posts<?php echo $display_mode === 'category_posts' ? ' is-condition' : ''; ?>">
					<?php $fields->field( array( 'builder', $group, 'posts_per_category' ), array(
						'type' => 'number',
						'value' => $row['posts_per_category'] ?? '',
						'placeholder' => $default_items,
						'label' => __( 'Posts per category', 'md' ),
						'description' => __( 'Number of posts in each category. -1 shows all.', 'md' ),
						'min' => -1,
						'max' => 100
					) ); ?>
				</div>
				<div class="md-query-loop-field">
					<?php $fields->field( array( 'builder', $group, 'category_columns' ), array(
						'type' => 'number',
						'value' => $row['category_columns'] ?? '',
						'placeholder' => 1,
						'label' => __( 'Category columns', 'md' ),
						'description' => __( 'Number of category sections per row.', 'md' ),
						'min' => 1,
						'max' => 6
					) ); ?>
				</div>
				<div class="md-query-loop-field">
					<?php $fields->field( array( 'builder', $group, 'category_orderby' ), array(
						'type' => 'select',
						'value' => $row['category_orderby'] ?? '',
						'label' => __( 'Order categories by', 'md' ),
						'description' => __( 'Latest post puts recent updates first.', 'md' ),
						'empty_label' => __( 'Name', 'md' ),
						'options' => array(
							'latest' => __( 'Latest post', 'md' ),
							'slug' => __( 'Slug', 'md' ),
							'term_id' => __( 'Term ID', 'md' ),
							'count' => __( 'Post count', 'md' ),
							'parent' => __( 'Parent', 'md' )
						)
					) ); ?>
				</div>
				<div class="md-query-loop-field">
					<?php $fields->field( array( 'builder', $group, 'category_order' ), array(
						'type' => 'select',
						'value' => $row['category_order'] ?? '',
						'label' => __( 'Category order', 'md' ),
						'description' => __( 'Ascending or descending.', 'md' ),
						'empty_label' => __( 'Ascending', 'md' ),
						'options' => array( 'DESC' => __( 'Descending', 'md' ) )
					) ); ?>
				</div>
				<div class="md-query-loop-field md-query-loop-field-wide">
					<?php $fields->field( array( 'builder', $group, 'category' ), array(
						'type' => 'checkbox',
						'value' => $row['category'] ?? array(),
						'label' => __( 'Category options', 'md' ),
						'inline' => true,
						'options' => array(
							'show_empty' => __( 'Show empty categories', 'md' ),
							'hide_description' => __( 'Hide descriptions', 'md' )
						)
					) ); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="md-query-loop-section">
		<h4><?php echo esc_html__( 'Display', 'md' ); ?></h4>
		<p class="description"><?php echo esc_html__( 'Blank options use Query Loop defaults, not the source archive’s Loop settings. The default view is Article view.', 'md' ); ?></p>
		<div class="md-query-loop-grid md-full-select">
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'loop' ), array(
					'type' => 'select',
					'value' => $row['loop'] ?? '',
					'label' => __( 'Template', 'md' ),
					'description' => __( 'Visual design.', 'md' ),
					'empty_label' => __( 'Use Default View', 'md' ),
					'options' => array_merge( md_loops( 'options' ), array( 'article' => __( 'Article view', 'md' ) ) )
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'columns' ), array(
					'type' => 'number',
					'value' => $row['columns'] ?? '',
					'placeholder' => 1,
					'label' => __( 'Post Columns', 'md' ),
					'description' => __( 'Desktop columns.', 'md' ),
					'min' => 1
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'columns_mobile' ), array(
					'type' => 'number',
					'value' => $row['columns_mobile'] ?? '',
					'placeholder' => 1,
					'label' => __( 'Mobile Columns', 'md' ),
					'description' => __( 'Mobile columns.', 'md' ),
					'min' => 1
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'content' ), array(
					'type' => 'select',
					'value' => $row['content'] ?? '',
					'label' => __( 'Post Content', 'md' ),
					'description' => __( 'Excerpt, full, or hidden.', 'md' ),
					'empty_label' => __( 'Show excerpt', 'md' ),
					'options' => array(
						'excerpt' => __( 'Show excerpt', 'md' ),
						'full' => __( 'Show full content', 'md' ),
						'hide' => __( 'Hide content', 'md' )
					)
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'featured_image' ), array(
					'type' => 'select',
					'value' => $row['featured_image'] ?? '',
					'label' => __( 'Featured Media', 'md' ),
					'description' => __( 'Override the post’s media setting.', 'md' ),
					'empty_label' => __( 'Use post media setting', 'md' ),
					'options' => $fields->data->values['featured_image']
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'featured_image_mobile' ), array(
					'type' => 'select',
					'value' => $row['featured_image_mobile'] ?? '',
					'label' => __( 'Mobile Image', 'md' ),
					'description' => __( 'Featured image on small screens.', 'md' ),
					'empty_label' => __( 'Stack above or below', 'md' ),
					'options' => array(
						'beside' => __( 'Keep beside', 'md' ),
						'hide' => __( 'Hide', 'md' )
					)
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'byline' ), array(
					'type' => 'select',
					'value' => $row['byline'] ?? '',
					'label' => __( 'Byline', 'md' ),
					'description' => __( 'Show the source post type’s byline or hide it.', 'md' ),
					'empty_label' => __( 'Show source byline', 'md' ),
					'options' => array( 'hide' => __( 'Hide all bylines', 'md' ) )
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'pagination' ), array(
					'type' => 'select',
					'value' => $row['pagination'] ?? '',
					'label' => __( 'Pagination', 'md' ),
					'description' => __( 'Off unless selected. Not used when showing all.', 'md' ),
					'empty_label' => __( 'No pagination', 'md' ),
					'options' => array(
						'page_numbers' => __( 'Page numbers', 'md' ),
						'prev_next' => __( 'Previous/next links', 'md' )
					)
				) ); ?>
			</div>
		</div>
	</div>
</div>
