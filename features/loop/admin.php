<?php
/**
 * Create and store loops settings data.
 *
 * @since 5.1
 */

class md_loop extends md_api {

	/**
	 * Create meta box and terms.
	 *
	 * @since 5.0
	 */

	public function register() {
		$this->name = __( 'Loop', 'md' );
		$fields = $this->fields();

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'group' => 'page_settings',
				'fields' => $fields
			),
			'term' => array(
				'name' => $this->name,
				'fields' => $fields
			)
		);
	}

	/**
	 * Register list of Loop admin fields to save.
	 *
	 * @since 5.0
	 */

	public function fields() {
		$post_content = array(
			'featured_image' => array(
				'type' => 'select',
				'label' => __( 'Featured Media', 'md' ),
				'options' => $this->fields->data->values['featured_image']
			),
			'featured_image_size' => array(
				'type' => 'select',
				'label' => __( 'Image Size', 'md' ),
				'options' => array_merge( array( 'full' ), get_intermediate_image_sizes() )
			),
			'featured_image_mobile' => array(
				'type' => 'select',
				'label' => __( 'Mobile Image', 'md' ),
				'options' => array(
					'beside' => __( 'Keep beside', 'md' ),
					'hide' => __( 'Hide', 'md' )
				)
			),
			'content' => array(
				'type' => 'select',
				'label' => __( 'Post Content', 'md' ),
				'options' => array(
					'excerpt' => __( 'Show excerpt', 'md' ),
					'full' => __( 'Show full content', 'md' ),
					'hide' => __( 'Hide content', 'md' )
				)
			),
			'remove_byline' => array(
				'type' => 'checkbox',
				'options' => array(
					'entry_top' => __( 'Entry Top', 'md' ),
					'before_title' => __( 'Before Title', 'md' ),
					'inside_title' => __( 'Inside Title', 'md' ),
					'after_title' => __( 'After Title', 'md' ),
					'before_content' => __( 'Before Content', 'md' ),
					'after_content' => __( 'After Content', 'md' ),
					'entry_footer' => __( 'Entry Footer', 'md' ),
					'remove' => __( 'All', 'md' )
				)
			),
			'inherit' => array(
				'type' => 'checkbox',
				'options' => array( 'position', 'page_cover' )
			),
			'excerpt_length' => array(
				'type' => 'number',
				'label' => __( 'Excerpt Length', 'md' )
			),
			'excerpt_more' => array(
				'type' => 'text',
				'label' => __( 'Excerpt More', 'md' )
			),
			'read_more' => array(
				'type' => 'text',
				'label' => __( 'Read More Text', 'md' )
			),
			'excerpt_settings' => array(
				'type' => 'checkbox',
				'options' => array( 'remove_text', 'remove_more' )
			)
		);

		foreach ( $post_content as $content_id => $content_fields )
			$post_content["featured_{$content_id}"] = $content_fields;

		return array_merge( array(
			'loop' => array(
				'type' => 'radio',
				'label' => __( 'Template', 'md' ),
				'options' => array_keys( md_loops() )
			),
			'loop_type' => array(
				'type' => 'select',
				'label' => __( 'Loop type', 'md' ),
				'options' => array(
					'post_listing' => __( 'Post listing (default)', 'md' ),
					'month' => __( 'Posts grouped by month', 'md' ),
					'category' => __( 'Category overview', 'md' ),
					'category_posts' => __( 'List posts by category', 'md' )
				)
			),
			'category' => array(
				'type' => 'checkbox',
				'options' => array( 'hide_subcategory', 'show_subcategory', 'show_empty', 'hide_description' )
			),
			'featured' => array(
				'type' => 'number',
				'label' => __( 'Featured Posts', 'md' )
			),
			'columns' => array(
				'type' => 'number',
				'label' => __( 'Post Columns', 'md' )
			),
			'columns_mobile' => array(
				'type' => 'number',
				'label' => __( 'Mobile Columns', 'md' ),
				'min' => 1,
				'step' => 1
			),
			'posts_per_page' => array(
				'type' => 'number',
				'label' => __( 'Posts Per Page', 'md' )
			),
			'posts_per_category' => array(
				'type' => 'number',
				'label' => __( 'Posts Per Category', 'md' )
			),
			'category_per_page' => array(
				'type' => 'number',
				'label' => __( 'Categories Per Page', 'md' )
			),
			'category_columns' => array(
				'type' => 'number',
				'label' => __( 'Category Columns', 'md' )
			),
			'category_orderby' => array(
				'type' => 'select',
				'label' => __( 'Orderby', 'md' ),
				'options' => array(
					'slug' => __( 'Slug', 'md' ),
					'term_id' => __( 'Term ID', 'md' ),
					'count' => __( 'Post Count', 'md' ),
					'parent' => __( 'Parent', 'md' )
				)
			),
			'category_order' => array(
				'type' => 'select',
				'label' => __( 'Order', 'md' ),
				'options' => array(
					'DESC' => __( 'Descending', 'md' )
				)
			),
			'category_include' => array(
				'type' => 'text',
				'label' => __( 'Include Categories', 'md' )
			),
			'category_exclude' => array(
				'type' => 'text',
				'label' => __( 'Exclude Categories', 'md' )
			),
			'orderby' => array(
				'type' => 'select',
				'label' => __( 'Order by', 'md' ),
				'options' => array( 'title', 'modified', 'comment_count', 'rand' )
			),
			'order' => array(
				'type' => 'select',
				'label' => __( 'Order', 'md' ),
				'options' => array(
					'ASC' => __( 'Ascending', 'md' )
				)
			),
			'pagination' => array(
				'type' => 'select',
				'label' => __( 'Type', 'md' ),
				'options' => array( 'page_numbers', 'prev_next' )
			),
			'previous_label' => array(
				'type' => 'text',
				'label' => __( 'Previous', 'md' )
			),
			'next_label' => array(
				'type' => 'text',
				'label' => __( 'Next', 'md' )
			),
			'cta_x_loop' => array(
				'type' => 'number',
				'label' => __( 'Show CTA after', 'md' )
			),
			'x_cta' => array(
				'type' => 'select',
				'label' => __( 'Call to Action', 'md' ),
				'options' => array_keys( md_setting( array( 'cta', 'forms' ), array() ) )
			),
			'exclude_posts' => array(
				'type' => 'text',
				'label' => __( 'Exclude Posts', 'md' )
			),
			'exclude_terms' => array(
				'type' => 'text',
				'label' => __( 'Exclude Terms', 'md' )
			),
			'classes' => array(
				'type' => 'text',
				'label' => __( 'CSS Classes', 'md' )
			)
		), $post_content );
	}

	/**
	 * Add settings template to Page Settings sections.
	 *
	 * @since 5.1
	 */

	public function admin_fields() {
		$screen = $this->_get_screen;
		$loop_type = $this->fields->module( 'loop_type' );
		$featured = $this->fields->module( 'featured' );
		$cta = $this->fields->module( array( 'cta', 'forms' ), array() );
		$image_sizes = array_merge( array( 'full' ), get_intermediate_image_sizes() );
		$subcat_key = ( $screen['is_admin'] && ! $screen['is_taxonomy'] ) ? 'show_subcategory' : 'hide_subcategory';
		$subcat_label = $subcat_key === 'show_subcategory' ? __( 'Show child categories', 'md' ) : __( 'Hide child categories', 'md' );
	?>

	<div class="md-widget md-loop md-toggle mb-single<?php echo $featured >= 1 ? ' has-featured' : ''; ?><?php echo in_array( $loop_type, array( 'category', 'category_posts' ) ) ? ' has-category-posts is-category-inherited' : ''; ?>">
		<h3 class="md-widget-title"><span><?php echo esc_html( $this->name ); ?><span class="md-widget-description"><?php echo __( 'Which posts show and how they look', 'md' ); ?></span></span></h3>
		<div class="md-widget-item">
			<?php include md_template( 'features', 'loop/admin/loop', true ); ?>
		</div>
	</div>

	<?php }

}

new md_loop;
