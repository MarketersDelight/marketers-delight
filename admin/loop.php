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
		$sanitize = $this->sanitize();
		$post_content = array(
			'featured_image' => array(
				'type' => 'select',
				'options' => array_keys( $this->fields->data->values['featured_image'] )
			),
			'featured_image_size' => array(
				'type' => 'select',
				'options' => get_intermediate_image_sizes()
			),
			'content' => array(
				'type' => 'select',
				'options' => array( 'full', 'excerpt', 'hide' )
			),
			'remove_byline' => array(
				'type' => 'checkbox',
				'options' => array( 'before_post', 'after_post', 'before_title', 'after_title', 'remove' )
			),
			'inherit' => array(
				'type' => 'checkbox',
				'options' => array( 'position', 'page_cover' )
			),
			'excerpt_length' => array( 'type' => 'number' ),
			'excerpt_more' => array( 'type' => 'text' ),
			'read_more' => array( 'type' => 'text' ),
			'excerpt_settings' => array(
				'type' => 'checkbox',
				'options' => array( 'remove_text', 'remove_more' )
			)
		);

		foreach ( $post_content as $content_id => $content_fields )
			$post_content["featured_{$content_id}"] = $content_fields;

		return array_merge( array(
			'name' => array( 'type' => 'text' ),
			'title' => array( 'type' => 'text' ),
			'description' => array( 'type' => 'text' ),
			'loop' => array(
				'type' => 'radio',
				'options' => array_keys( md_loops() )
			),
			'loop_type' => array(
				'type' => 'select',
				'options' => array( 'post_listing', 'category_posts', 'category' )
			),
			'category' => array(
				'type' => 'checkbox',
				'options' => array( 'hide_subcategory', 'show_subcategory', 'show_empty' )
			),
			'featured' => array( 'type' => 'number' ),
			'columns' => array( 'type' => 'number' ),
			'posts_per_page' => array( 'type' => 'number' ),
			'posts_per_category' => array( 'type' => 'number' ),
			'category_per_page' => array( 'type' => 'number' ),
			'category_columns' => array( 'type' => 'number' ),
			'category_orderby' => array(
				'type' => 'select',
				'options' => array( 'slug', 'term_id', 'count', 'parent' )
			),
			'category_order' => array(
				'type' => 'select',
				'options' => array( 'DESC' )
			),
			'category_include' => array( 'type' => 'text' ),
			'category_exclude' => array( 'type' => 'text' ),
			'orderby' => array(
				'type' => 'select',
				'options' => array( 'title', 'modified', 'comment_count', 'rand' )
			),
			'order' => array(
				'type' => 'select',
				'options' => array( 'ASC' )
			),
			'pagination' => array(
				'type' => 'select',
				'options' => array( 'page_numbers', 'prev_next' )
			),
			'previous_label' => array( 'type' => 'text' ),
			'next_label' => array( 'type' => 'text' ),
			'cta_x_loop' => array( 'type' => 'number' ),
			'x_cta' => array(
				'type' => 'select',
				'options' => array_keys( md_setting( array( 'cta', 'forms' ), array() ) )
			),
			'position' => array(
				'type' => 'select',
				'options' => array( 'before_content_box', 'before_content', 'content', 'before_footer' )
			),
			'offset' => array( 'type' => 'number' ),
			'show_query' => array(
				'type' => 'select',
				'options' => array( 'before_loop', 'after_loop' )
			),
			'post_type' => array(
				'type' => 'select',
				'options' => array_keys( get_post_types( array( 'public' => true ) ) )
			),
			'sidebar' => array(
				'type' => 'checkbox',
				'options' => array( 'enable', 'sticky' )
			),
			'custom_sidebar' => array(
				'type' => 'select',
				'options' => md_layout_areas( 'sidebar', true )
			),
			'content_layout' => array(
				'type' => 'select',
				'options' => array( 'sidebar_content' )
			),
			'size' => array(
				'type' => 'select',
				'options' => array( 'large', 'medium', 'small', 'normal' )
			),
			'tags' => array( 'type' => 'text' ),
			'include_cats' => array(
				'type' => 'checkbox',
				'options' => $sanitize->terms()
			),
			'exclude_cats' => array(
				'type' => 'checkbox',
				'options' => $sanitize->terms()
			),
			'classes' => array( 'type' => 'text' )
		), $post_content );
	}

	/**
	 * Add settings template and script to Page Settings sections.
	 *
	 * @since 5.1
	 */

	public function admin_fields() {
		$screen = $this->_get_screen;
		$loop_type = $this->fields->module( 'loop_type' );
		$featured = $this->fields->module( 'featured' );
		$cta = $this->fields->module( array( 'cta', 'forms' ), array() );
		$image_sizes = get_intermediate_image_sizes();
		$subcat_key = ( $screen['is_admin'] && ! $screen['is_taxonomy'] ) ? 'show_subcategory' : 'hide_subcategory';
		$subcat_label = $subcat_key === 'show_subcategory' ? __( 'Show subcategories', 'md' ) : __( 'Hide subcategories', 'md' );
	?>

	<div class="md-widget md-loop md-toggle md-sep-small<?php echo $featured >= 1 ? ' has-featured' : ''; ?><?php echo in_array( $loop_type, array( 'category', 'category_posts' ) ) ? ' has-category-posts' : ''; ?>">
		<h3 class="md-widget-title"><?php echo esc_html( $this->name ); ?></h3>
		<div class="md-widget-item">
			<?php include md_template( 'admin/loop', true ); ?>
		</div>
	</div>

	<?php $this->scripts(); }

	/**
	 * Print footer scripts to admin screens to toggle options.
	 *
	 * @since 6.0
	 */

	public function scripts() { ?>
		<script>
			jQuery( document ).ready( function( $ ) {
				function resetTabs( $tabs ) {
					$tabs.find( '.md-tab' ).removeClass( 'nav-tab-active' ).first().addClass( 'nav-tab-active' );
					$tabs.find( '.md-tab-content' ).removeClass( 'active' ).first().addClass( 'active' );
				}

				$( '.md-check-val' ).on( 'change', function( e ) {
					var loop = $( this ).parents( '.md-loop' );
					var active = this.value === 'category' || this.value === 'category_posts';
					loop.toggleClass( 'has-category-posts', active );

					if ( ! active )
						resetTabs( loop.find( '.md-loop-options' ) );
				});

				$( '.md-content-val' ).on( 'change', function() {
					$( this ).parents( '.md-loop-post-group' ).find( '.md-loop-content-options' ).toggle( this.value !== 'hide' );
				});

				$( '.md-num-val' ).on( 'change', function( e ) {
					var loop = $( this ).parents( '.md-loop' );

					if ( this.value >= 1 )
						loop.addClass( 'has-featured' );
					else {
						loop.removeClass( 'has-featured' );
						resetTabs( loop.find( '.md-loop-post' ) );
					}
				});
			} );
		</script>
	<?php }

}

new md_loop;