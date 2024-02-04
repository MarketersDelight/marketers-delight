<?php
/**
 * Create and store loops settings data.
 *
 * @since 5.1
 */

class md_loop extends md_api {

	/**
	 * Include related files.
	 *
	 * @since 6.0
	 */

	public function includes() {
		include_once( 'loop-functions.php' );
		include_once( 'post-functions.php' );
	}

	/**
 	* Load Queries to hook areas when auto-inserted.
 	*
 	* @since 6.0
 	*/

	public function template() {
		if ( ! is_post_type_archive() && ! is_home() )
			return;

		$queries = md_post_type_field( array( 'loop', 'query' ), array() );
		$hooks = array(
			'before_content_box' => 'md_hook_before_content_box',
			'before_content' => 'md_hook_before_content',
			'content' => 'md_hook_after_content',
			'before_footer' => 'md_hook_before_footer'
		);

		foreach ( $queries as $query_id => $loop ) {
			if ( ! isset( $loop['position'] ) )
				continue;

			$position = $loop['position'];

			if ( empty( $hooks[$position] ) )
				continue;

			add_action( $hooks[$position], 'md_query' );
		}
	}

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
				'parent' => 'design',
				'fields' => $fields
			),
			'term' => array(
				'name' => $this->name,
				'fields' => $fields,
				'position' => 40,
				'callback' => array( $this, 'admin_template' )
			)
		);
	}

	/**
	 * Send admin fields to sanitize and save.
	 *
	 * @since 5.1
	 */

	public function fields() {
		$fields = $this->loop_fields();
		$fields['query'] = array(
			'type' => 'group',
			'fields' => $fields
		);

		return $fields;
	}

	/**
	 * Register list of Loop admin fields to save.
	 *
	 * @since 6.0
	 */

	public function loop_fields() {
		$block_ids = $cta_ids = array();
		$sidebars = md_get_sidebars( true );
		$cta = md_setting( array( 'cta', 'forms' ), array() );
		$post_types = array_keys( get_post_types( array( 'public' => true ) ) );
		$sanitize = new md_sanitize;

		foreach ( $cta as $cta_id => $cta_fields )
			$cta_ids[] = $cta_id;

		$authors = get_users( array(
			'fields' => array( 'ID' ),
			'has_published_posts' => true
		) );

		$post_content = array(
			'featured_image' => array(
				'type' => 'select',
				'options' => array_keys( $sanitize->values['featured_image'] )
			),
			'featured_image_size' => array(
				'type' => 'select',
				'options' => array( 'thumbnail')
			),
			'content' => array(
				'type' => 'select',
				'options' => array( 'full', 'excerpt', 'hide' )
			),
			'remove_byline' => array(
				'type' => 'select',
				'options' => array( 'before_headline', 'after_headline', 'remove' )
			),
			'post_footer' => array(
				'type' => 'checkbox',
				'options' => array( 'remove' )
			),
			'excerpt_length' => array( 'type' => 'number' ),
			'excerpt_more' => array( 'type' => 'text' ),
			'read_more' => array( 'type' => 'text' ),
			'read_more_style' => array(
				'type' => 'select',
				'options' => array( 'button' )
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
			'style' => array(
				'type' => 'select',
				'options' => array( 'box_style', 'simple' )
			),
			'list' => array(
				'type' => 'select',
				'options' => array( 'timeline', 'timeline-left', 'numbers', 'list' )
			),
			'tags' => array( 'type' => 'text' ),
			'author' => array(
				'type' => 'select',
				'multiple' => true,
				'options' => wp_list_pluck( $authors, 'ID' )
			),
			'include_cats' => array(
				'type' => 'checkbox',
				'options' => $sanitize->terms()
			),
			'exclude_cats' => array(
				'type' => 'checkbox',
				'options' => $sanitize->terms()
			)
		), $post_content );
	}

	/**
	 * Add settings template and script to Page Settings sections.
	 *
	 * @since 6.0
	 */

	public function admin_fields() {
		$screen = get_current_screen();
		$page = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : '';
		$screen_base = ! empty( $page ) ? $page : $screen->base;

		do_action( "md_layout_{$screen_base}_before_settings" );

		$this->admin_template();

		do_action( "md_layout_{$screen_base}_after_settings" );
	}

	/**
	 * Call template with required data passed down.
	 *
	 * @since 5.1
	 */

	public function admin_template() {
		$screen = get_current_screen();
		$sanitize = new md_sanitize;
		$category_posts = $this->fields->module( 'category_posts' );
		$featured = $this->fields->module( 'featured' );
		$cta = md_setting( array( 'cta', 'forms' ), array() );
		$cta_options = wp_list_pluck( $cta, 'name' );
		$authors = get_users( array(
			'fields' => array( 'ID', 'display_name' ),
			'has_published_posts' => true
		) );
	?>

		<div class="md-widget md-loop md-toggle md-sep-small<?php echo $featured >= 1 ? ' has-featured' : ''; ?><?php echo $category_posts ? ' has-category-posts' : ''; ?>">
			<h3 class="md-widget-title"><?php echo esc_html( $this->name ); ?></h3>
			<div class="md-widget-item">
				<?php include( 'loop-settings.php' ); ?>
			</div>
		</div>

		<div id="query" class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo __( 'Query Loops', 'md' ); ?></h3>
			<div class="md-widget-item">
				<?php $this->fields->field( 'query', array(
					'type' => 'group',
					'label' => __( 'Add Queries', 'md' ),
					'style' => 'boxes',
					'callback' => array( $this, 'query_settings' ),
					'callback_args' => array(
						'authors' => wp_list_pluck( $authors, 'display_name', 'ID' )
					)
				) ); ?>
			</div>
		</div>

	<?php $this->scripts(); }

	/**
	 * Call admin template for repeatable fields.
	 *
	 * @since 6.0
	 */

	public function query_settings( $group, $field, $args ) {
		$post_types = $cta_options = array();
		$sanitize = new md_sanitize;
		$category_posts = $this->fields->module( array( $group, $field, 'category_posts' ) );
		$featured = $this->fields->module( array( $group, $field, 'featured' ) );
		$sidebars = md_get_sidebars();
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		$post_types = wp_list_pluck( $post_types, 'label' );
		$cta = md_setting( array( 'cta', 'forms' ), array() );
		$cta_options = wp_list_pluck( $cta, 'name' );

		echo '<div class="md-loop' . ( $featured >= 1 ? ' has-featured' : '' ) . ( $category_posts ? ' has-category-posts' : '' ) . '">';
		include( 'query-settings.php' );
		echo '</div>';
	}

	/**
	 * Print footer scripts to admin screens to toggle options.
	 *
	 * @since 6.0
	 */

	public function scripts() {
		$screen = get_current_screen();
		$prefix = $this->_prefix();
	?>
		<script>
			<?php if ( $screen->base !== 'term' ) : ?>
			( function() {
				document.getElementById( '<?php echo $prefix; ?>_category_posts_enable' ).onchange = function( e ) {
					document.getElementById( 'loop_category_posts' ).style.display = this.checked ? 'block' : 'none';
				}
			} )();
			<?php endif; ?>
			jQuery( document ).ready( function( $ ) {
				$( '.md-check-val' ).on( 'change', function( e ) {
					$( this ).parents( '.md-loop' ).toggleClass( 'has-category-posts' );
				} );
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
