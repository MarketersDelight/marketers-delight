<?php
/**
 * Core files for building the MD Stream feature. Register
 * custom post type, taxonomy, load admin and frontend templates,
 * and other required actions.
 *
 * @since 4.8.4
 */

class md_stream extends md_api {

	/**
	 * Include Stream files.
	 *
	 * @since 5.0
	 */

	public function includes() {
		require_once( 'templates/templates.php' );
		require_once( 'widget.php' );
	}

	/**
	 * Run actions and filters.
	 *
	 * @since 4.8.4
	 */

	public function actions() {
		$slug = md_setting( array( 'stream', 'slug' ) );
		$this->slug = $slug ? $slug : 'stream';
		$this->taxonomy_label = 'stream_categories';
		add_action( 'init', array( $this, 'taxonomy' ), 0 );
		add_action( 'init', array( $this, 'post_type' ), 1 );
		add_filter( 'md_share_show_on', array( $this, 'share' ) );
		add_filter( 'md_filter_sidebars_post_types', array( $this, 'sidebars' ) );
		add_filter( 'md_optins_locations', array( $this, 'optins_locations' ) );
		if ( is_admin() ) {
			add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 100 );
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}
	}

	/**
	 * Run stream actions on MD post type publish.
	 *
	 * @since 5.3
	 */

	public function admin_init() {
		foreach ( md_setting( array( 'stream', 'activity_post_types' ), array() ) as $post_type => $val )
			add_action( "publish_$post_type", array( $this, 'publish_activity' ) );
	}

	/**
	 * Register custom post type in WP Admin.
	 *
	 * @since 4.8.4
	 */

	public function post_type() {
		$supports = array( 'title', 'editor', 'thumbnail' );
		$disable_comments = md_setting( array( 'stream', 'layout', 'disable_comments' ) );
		if ( ! $disable_comments )
			$supports[] = 'comments';
		register_post_type( 'stream', array(
			'hierarchial' => true,
			'public' => true,
			'menu_position' => 5,
			'has_archive' => true,
			'supports' => $supports,
			'menu_icon' => 'dashicons-format-status',
			'taxonomies' => array( $this->taxonomy_label ),
			'rewrite' => array( 'slug' => $this->slug, 'with_front' => true ),
			'labels' => array(
				'name' => __( 'Stream', 'md' ),
				'singular_name' => __( 'Stream', 'md' ),
				'menu_name' => __( 'Stream', 'md' ),
				'name_admin_bar' => __( 'Stream', 'md' ),
				'add_new_item' => __( 'Add New Stream', 'md' ),
				'edit_item' => __( 'Edit Stream', 'md' ),
				'new_item' => __( 'New Stream', 'md' ),
				'view_item' => __( 'View Stream', 'md' ),
				'view_items' => __( 'View Stream', 'md' ),
				'search_items' => __( 'Search Streams', 'md' ),
				'not_found' => __( 'No streams found', 'md' ),
				'not_found_in_trash' => __( 'No streams found in trash', 'md' ),
				'all_items' => __( 'Stream', 'md' )
			)
		) );
		if ( md_setting( array( 'stream', 'settings', 'enable_activity' ) ) )
			register_post_type( 'stream_activity', array(
				'hierarchial' => true,
				'public' => true,
				'has_archive' => false,
				'show_in_menu' => 'edit.php?post_type=stream',
				'supports' => $supports,
				'rewrite' => array( 'slug' => "{$this->slug}-activity", 'with_front' => true ),
				'labels' => array(
					'name' => __( 'Stream activity', 'md' ),
					'singular_name' => __( 'Stream activity', 'md' ),
					'menu_name' => __( 'Stream activity', 'md' ),
					'name_admin_bar' => __( 'Stream activity', 'md' ),
					'add_new_item' => __( 'Add New Stream', 'md' ),
					'edit_item' => __( 'Edit Stream', 'md' ),
					'view_items' => __( 'View Stream', 'md' ),
					'search_items' => __( 'Search activity', 'md' ),
					'not_found' => __( 'No activity found', 'md' ),
					'not_found_in_trash' => __( 'No stream activities found in trash', 'md' ),
					'all_items' => __( 'Activity', 'md' )
				)
			) );
	}

	/**
	 * Publishes updates to Stream activity from approved custom post types.
	 *
	 * @since 5.3
	 */

	public function publish_activity( $post_id ) {
		$new_post_id = wp_insert_post( array(
			'post_type' => 'stream_activity',
			'post_status' => 'publish'
		) );
		$post_meta = md_post_meta( null, $new_post_id, array() );
		$post_meta['stream']['post_id'] = esc_attr( $post_id );
		update_post_meta( $new_post_id, 'marketers_delight', $post_meta );	
	}

	/**
	 * Add taxonomies/categories meta to our custom post type.
	 *
	 * @since 1.0
	 */

	public function taxonomy() {
		register_taxonomy( $this->taxonomy_label, 'stream', array(
			'hierarchical' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array(
				'slug' => $this->slug . '/category',
				'with_front' => false,
				'hierarchical' => true
			),
			'capabilities' => array( 'manage_categories' )
		) );
	}

	/**
	 * Register Stream widgets.
	 *
	 * @since 5.2
	 */

	public function widgets() {
		register_widget( 'md_stream_widget' );
	}

	/**
	 * Create admin page and meta box.
	 *
	 * @since 4.9.2
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Settings', 'md' ),
				'parent_slug' => 'edit.php?post_type=stream',
				'fields' => array(
					'settings' => array(
						'type' => 'checkbox',
						'options' => array( 'enable_activity' )
					),
					'activity_post_types' => array(
						'type' => 'checkbox',
						'options' => md_post_type_meta()
					),
					'archives_title' => array( 'type' => 'text' ),
					'archives_text' => array( 'type' => 'textarea' ),
					'archives_photo' => array(
						'type' => 'upload',
						'upload_type' => 'media'
					),
					'slug' => array( 'type' => 'text' ),
					'posts_per_page' => array( 'type' => 'number' ),
					'layout' => array(
						'type' => 'checkbox',
						'options' => array( 'disable_comments', 'add_archives_sidebar', 'add_single_sidebar', 'add_stream_title', 'remove_breadcrumbs', 'remove_post_titles' )
					)
				)
			),
			'meta_box' => array(
				'name' => __( 'Stream', 'md' ),
				'post_type' => array( 'stream', 'stream_activity' ),
				'priority' => 'high',
				'fields' => array(
					'post_id' => array( 'type' => 'number' ),
					'thread' => array(
						'type' => 'group',
						'fields' => array(
							'name' => array( 'type' => 'text' ),
							'date' => array( 'type' => 'text' ),
							'user_id' => array( 'type' => 'number' ),
							'text' => array( 'type' => 'textarea' ),
							'image' => array(
								'type' => 'upload',
								'upload_type' => 'media'
							),
							'post_id' => array( 'type' => 'number' )
						)
					)
				)
			)
		);
	}

	/**
	 * Load CSS template to style.css.
	 *
	 * @since 4.9.4
	 */

	public function css( $templates ) {
		$templates['stream'] = md_css( 'dropins', 'stream/css', true );
		return $templates;
	}

	/**
	 * Add Stream to Share options page.
	 *
	 * @since 4.9.2
	 */

	public function share( $post_types ) {
		$post_types[] = 'stream';
		return $post_types;
	}

	/**
	 * Add Stream to custom Sidebars Manager.
	 *
	 * @since 4.9.2
	 */

	public function sidebars( $sidebars ) {
		if ( md_setting( array( 'stream', 'layout', 'add_archives_sidebar' ) ) )
			$sidebars['stream']['archive'] = true;
		if ( md_setting( array( 'stream', 'layout', 'add_single_sidebar' ) ) )
			$sidebars['stream']['single'] = true;
//		$sidebars['stream']['stream_categories'] = true;
		return $sidebars;
	}

	/**
	 * Add Stream to MD optins locations.
	 *
	 * @since 5.3
	 */

	public function optins_locations( $locations ) {
		$locations['stream'] = array(
			'archive' => __( 'Stream Page', 'md' ),
			'single' => __( 'Stream Posts', 'md' ),
			'stream_categories' => __( 'Stream Categories', 'md' )
		);
		return $locations;
	}

	/**
	 * Add Stream Archives link to Admin Bar.
	 *
	 * @since 4.9.2
	 */

	public function admin_bar( $admin_bar ) {
		$screen = get_current_screen();
		$post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		if ( $screen->base == 'stream_page_md_stream' )
			$admin_bar->add_menu( array(
				'id' => "{$this->_prefix}-archives-link",
				'title' => __( 'View Stream', 'md' ),
				'href' => esc_url( get_site_url() . '/' . $this->slug ),
				'meta' => array(
					'title' => __( 'View Stream', 'md' ),
					'target' => '_blank'
				)
			) );
	}

	/**
	 * Create admin page template.
	 *
	 * @since 4.9.2
	 */

	public function admin_page() {
		$post_types = md_post_type_meta();
		foreach ( $post_types as $post_type ) {
			if ( $post_type == 'stream' ) continue;
			$types[$post_type] = ucwords( $post_type );
		}
		include( md_template( 'dropins', 'stream/admin/stream-settings', true ) );
	}

	/**
	 * Post meta box template with fields.
	 *
	 * @since 4.8.4
	 */

	public function meta_box() {
		include( md_template( 'dropins', 'stream/admin/stream-meta', true ) );
	}

	/**
	 * Repeatable field for each thread item.
	 *
	 * @since 4.8.4
	 */

	public function thread( $group, $field ) {
		include( md_template( 'dropins', 'stream/admin/stream-thread-meta', true ) );
	}

}

new md_stream;