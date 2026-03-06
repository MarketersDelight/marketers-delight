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
				'parent_group' => 'page_settings',
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
		$block_ids = $cta_ids = array();
		$sidebars = md_get_sidebars( true );
		$cta = md_setting( array( 'cta', 'forms' ), array() );
		$post_types = array_keys( get_post_types( array( 'public' => true ) ) );
		$sanitize = new md_sanitize;

		foreach ( $cta as $cta_id => $cta_fields )
			$cta_ids[] = $cta_id;

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
			'category_posts' => array(
				'type' => 'checkbox',
				'options' => array( 'enable' )
			),
			'featured' => array( 'type' => 'number' ),
			'columns' => array( 'type' => 'number' ),
			'posts_per_page' => array( 'type' => 'number' ),
			'category_per_page' => array( 'type' => 'number' ),
			'category_columns' => array( 'type' => 'number' ),
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
				'options' => $cta_ids
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
				'options' => $post_types
			),
			'sidebar' => array(
				'type' => 'checkbox',
				'options' => array( 'enable', 'sticky' )
			),
			'custom_sidebar' => array(
				'type' => 'select',
				'options' => $sidebars
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
		$screen = get_current_screen();
		$category_posts = $this->fields->module( 'category_posts' );
		$featured = $this->fields->module( 'featured' );
		$cta = $this->fields->module( array( 'cta', 'forms' ), array() );
		$image_sizes = get_intermediate_image_sizes();
	?>

	<div class="md-widget md-loop md-toggle md-sep-small<?php echo $featured >= 1 ? ' has-featured' : ''; ?><?php echo $category_posts ? ' has-category-posts' : ''; ?>">
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

	public function scripts() {
		$screen = get_current_screen();
		$prefix = $this->_prefix;
	?>
		<script>
			jQuery( document ).ready( function( $ ) {
				$( '.md-check-val' ).on( 'change', function( e ) {
					$( this ).parents( '.md-loop' ).toggleClass( 'has-category-posts' );
				});
				$( '.md-content-val' ).on( 'change', function() {
					$( this ).parents( '.md-loop-post-group' ).find( '.md-loop-content-options' ).toggle( this.value !== 'hide' );
				});
				$( '.md-num-val' ).on( 'change', function( e ) {
					var loop = $( this ).parents( '.md-loop' );
					if ( this.value >= 1 )
						loop.addClass( 'has-featured' );
					else
						loop.removeClass( 'has-featured' );
				});
			} );
		</script>
	<?php }

}

new md_loop;