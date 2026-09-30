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
					'description' => __( 'Match the current author, search, date, or term page when applicable; otherwise show latest.', 'md' ),
					'options' => array( 'current' => __( 'Current page', 'md' ), 'latest' => __( 'Latest posts', 'md' ) )
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php
				$source = sanitize_key( $row['source'] ?? '' );
				$source_loop = $source ? md_post_type_field( 'loop', array(), $source ) : array();
				$source_items = is_array( $source_loop ) && ! empty( $source_loop['posts_per_page'] )
					? $source_loop['posts_per_page']
					: get_option( 'posts_per_page' );
				?>
				<?php $fields->field( array( 'builder', $group, 'items' ), array(
					'type' => 'number',
					'value' => $row['items'] ?? '',
					'placeholder' => $source_items,
					'label' => __( 'Number of items', 'md' ),
					'description' => __( 'Blank uses the content type’s posts-per-page setting.', 'md' ),
					'min' => 1,
					'max' => 100
				) ); ?>
			</div>
		</div>
	</div>

	<div class="md-query-loop-section">
		<h4><?php echo esc_html__( 'Results', 'md' ); ?></h4>
		<p class="description"><?php echo esc_html__( 'Optionally narrow results by taxonomy, then choose their order.', 'md' ); ?></p>
		<div class="md-query-loop-grid md-full-select">
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
					'label' => __( 'Term slugs', 'md' ),
					'description' => __( 'Comma-separated slugs.', 'md' )
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'orderby' ), array(
					'type' => 'select',
					'value' => $row['orderby'] ?? '',
					'label' => __( 'Order by', 'md' ),
					'description' => __( 'Field used for sorting.', 'md' ),
					'empty_label' => __( 'Use default', 'md' ),
					'options' => array(
						'date' => __( 'Date', 'md' ),
						'title' => __( 'Title', 'md' ),
						'modified' => __( 'Last Modified', 'md' ),
						'comment_count' => __( 'Comment Count', 'md' ),
						'menu_order' => __( 'Menu Order', 'md' )
					)
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'order' ), array(
					'type' => 'select',
					'value' => $row['order'] ?? '',
					'label' => __( 'Order', 'md' ),
					'description' => __( 'Sort direction.', 'md' ),
					'empty_label' => __( 'Use default', 'md' ),
					'options' => array(
						'DESC' => __( 'Descending', 'md' ),
						'ASC' => __( 'Ascending', 'md' )
					) ) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'group_by' ), array(
					'type' => 'select',
					'value' => $row['group_by'] ?? '',
					'label' => __( 'Group results', 'md' ),
					'description' => __( 'Optionally organize results by month.', 'md' ),
					'empty_label' => __( 'No grouping', 'md' ),
					'options' => array( 'month' => __( 'By month', 'md' ) )
				) ); ?>
			</div>
		</div>
	</div>

	<div class="md-query-loop-section">
		<h4><?php echo esc_html__( 'Display', 'md' ); ?></h4>
		<p class="description"><?php echo esc_html__( 'Blank options inherit the selected content type’s Loop settings. Pagination is off unless enabled below.', 'md' ); ?></p>
		<div class="md-query-loop-grid md-full-select">
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'loop' ), array(
					'type' => 'select',
					'value' => $row['loop'] ?? '',
					'label' => __( 'Template', 'md' ),
					'description' => __( 'Visual design.', 'md' ),
					'empty_label' => __( 'Use default', 'md' ),
					'options' => md_loops( 'options' )
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'columns' ), array(
					'type' => 'number',
					'value' => $row['columns'] ?? '',
					'label' => __( 'Post Columns', 'md' ),
					'description' => __( 'Desktop columns.', 'md' ),
					'min' => 1
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'columns_mobile' ), array(
					'type' => 'number',
					'value' => $row['columns_mobile'] ?? '',
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
					'empty_label' => __( 'Use default', 'md' ),
					'options' => array(
						'excerpt' => __( 'Show excerpt', 'md' ),
						'full' => __( 'Show full content', 'md' ),
						'hide' => __( 'Hide content', 'md' )
					) ) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'featured_image' ), array(
					'type' => 'select',
					'value' => $row['featured_image'] ?? '',
					'label' => __( 'Featured Media', 'md' ),
					'description' => __( 'Featured image placement.', 'md' ),
					'empty_label' => __( 'Use default', 'md' ),
					'options' => $fields->data->values['featured_image']
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'byline' ), array(
					'type' => 'select',
					'value' => $row['byline'] ?? '',
					'label' => __( 'Byline', 'md' ),
					'description' => __( 'Use source or hide all.', 'md' ),
					'empty_label' => __( 'Use default', 'md' ),
					'options' => array( 'hide' => __( 'Hide all bylines', 'md' ) )
				) ); ?>
			</div>
			<div class="md-query-loop-field">
				<?php $fields->field( array( 'builder', $group, 'pagination' ), array(
					'type' => 'select',
					'value' => $row['pagination'] ?? '',
					'label' => __( 'Pagination', 'md' ),
					'description' => __( 'Off unless selected.', 'md' ),
					'empty_label' => __( 'No pagination', 'md' ),
					'options' => array(
						'page_numbers' => __( 'Page numbers', 'md' ),
						'prev_next' => __( 'Previous/next links', 'md' )
					) ) ); ?>
			</div>
		</div>
	</div>
</div>
