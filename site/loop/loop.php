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
	 * @since 5.6
	 */

	public function includes() {
		include_once( 'loop-functions.php' );
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
	 * Fields used across all admin interface screens to be saved.
	 *
	 * @since 5.1
	 */

	public function fields() {
		$query = $this->query_fields();
		$fields = array(
			'pagination' => array(
				'type' => 'select',
				'options' => array( 'page_numbers', 'prev_next' )
			),
			'previous_label' => array( 'type' => 'text' ),
			'next_label' => array( 'type' => 'text' ),
			'query' => array(
				'type' => 'group',
				'fields' => $query
			)
		);
		$fields = array_merge( $fields, $query );

		return $fields;
	}

	public function query_fields() {
		$post_types = array_keys( get_post_types( array( 'public' => true ) ) );
		$sidebars = md_get_sidebars( true );
		$sanitize = new md_sanitize;

		$cta_ids = array();
		$cta = md_setting( array( 'cta', 'forms' ), array() );
		foreach ( $cta as $cta_id => $cta_fields )
			$cta_ids[] = $cta_id;

		$authors = get_users( array(
			'fields' => array( 'ID' ),
			'has_published_posts' => true
		) );

		return array(
			'name' => array( 'type' => 'text' ),
			'archives' => array(
				'type' => 'select',
				'options' => md_loops( 'ids' )
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
			'featured_image' => array(
				'type' => 'select',
				'options' => array_keys( $sanitize->values['featured_image'] )
			),
			'content' => array(
				'type' => 'select',
				'options' => array( 'full', 'excerpt', 'hide' )
			),
			'excerpt_length' => array( 'type' => 'number' ),
			'excerpt_more' => array( 'type' => 'text' ),
			'read_more' => array( 'type' => 'text' ),
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
		);
	}

	/**
	 * Add settings template and script to Page Settings sections.
	 *
	 * @since 5.6
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
		$cta_options = array();
		$screen = get_current_screen();
		$sanitize = new md_sanitize;
		$cta = md_setting( array( 'cta', 'forms' ), array() );
		$loops_options = md_loops( 'options' );
		unset( $loops_options['default'] );
		$category_posts = $this->fields->module( 'category_posts' );
		$order = array(
			'' => __( 'Descending', 'md' ),
			'ASC' => __( 'Ascending', 'md' )
		);
		$authors = get_users( array(
			'fields' => array( 'ID', 'display_name' ),
			'has_published_posts' => true
		) );

		foreach ( $cta as $cta_id => $cta_fields )
			$cta_options[$cta_id] = ! empty( $cta_fields['name'] ) ? $cta_fields['name'] : __( 'Untitled', 'md' );
	?>

		<div id="query" class="md-widget md-toggle md-sep-small open">
			<h3 class="md-widget-title"><?php echo __( 'Query Builder', 'md' ); ?></h3>
			<div class="md-widget-item">
				<?php $this->fields->field( 'query', array(
					'type' => 'group',
					'label' => __( 'Query Builder', 'md' ),
					'style' => 'boxes',
					'callback' => array( $this, 'query_settings' ),
					'callback_args' => array(
						'authors' => wp_list_pluck( $authors, 'display_name', 'ID' )
					)
				) ); ?>
			</div>
		</div>

		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo esc_html( $this->name ); ?></h3>
			<div class="md-widget-item">
				<?php include( 'loop-settings.php' ); ?>
			</div>
		</div>
	<?php }

	public function query_settings( $group, $field, $args ) {
		$sanitize = new md_sanitize;
		$loops_options = md_loops( 'options' );
		unset( $loops_options['default'] );
		$category_posts = true;
		$sidebars = md_get_sidebars();

		$post_types = array();
		$types = get_post_types( array( 'public' => true ), 'objects' );
		foreach ( $types as $type )
			$post_types[$type->name] = $type->labels->singular_name;

		$cta_options = array();
		$cta = md_setting( array( 'cta', 'forms' ), array() );
		foreach ( $cta as $cta_id => $cta_fields )
			$cta_options[$cta_id] = ! empty( $cta_fields['name'] ) ? $cta_fields['name'] : __( 'Untitled', 'md' );

		include( 'query-settings.php' );
	}

}

new md_loop;
