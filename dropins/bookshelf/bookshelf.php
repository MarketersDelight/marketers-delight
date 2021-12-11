<?php
/**
 * Drop-in Name: Books
 * Description: Share your favorite books on your site. Write book reviews and organize your collection in the nicely designed bookshelf page.
 * Author: Alex, Kolakube
 * AuthorURI: https://marketersdelight.com/
 * DropinURI: https://marketersdelight.com/dropins/books/
 * Slug: bookshelf
 * Version: 1.0.1
 */

/**
 * Create your own readings list with The Bookshelf.
 *
 * @since 4.8.5
 */

class md_bookshelf extends md_api {

	public $taxonomy_label = 'bookshelf_categories';
	public $slug;

	/**
	 * Include Bookshelf files.
	 *
	 * @since 5.0
	 */

	public function includes() {
		require_once( 'templates.php' );
	}

	/**
	 * Fire important class actions.
	 *
	 * @since 4.8.5
	 */

	public function actions() {
		$slug = md_setting( array( 'bookshelf', 'archives_slug' ) );
		$this->slug = $slug ? $slug : 'bookshelf';
		add_action( 'init', array( $this, 'taxonomy' ), 0 );
		add_action( 'init', array( $this, 'post_type' ), 1 );
		add_filter( 'md_filter_sidebars_post_types', array( $this, 'sidebars' ) );
		if ( is_admin() ) {
			add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 100 );
			if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && $_GET['post_type'] == 'bookshelf' && $_GET['page'] == 'bookshelf_settings' && isset( $_GET['settings-updated'] ) )
				flush_rewrite_rules();
		}
	}

	/**
	 * Create custom post types for admin panel.
	 *
	 * @since 4.8.5
	 */

	public function post_type() {
		register_post_type( 'bookshelf', array(
			'public' => true,
			'menu_position' => 15,
			'has_archive' => true,
			'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'menu_icon' => 'dashicons-book',
			'rewrite' => array( 'slug' => $this->slug, 'with_front' => true ),
			'taxonomies' => array( $this->taxonomy_label ),
			'labels' => array(
				'name' => __( 'Books', 'md' ),
				'singular_name' => __( 'Book', 'md' ),
				'menu_name' => __( 'Books', 'md' ),
				'name_admin_bar' => __( 'Books', 'md' ),
				'add_new_item' => __( 'Add New Book', 'md' ),
				'edit_item' => __( 'Edit Book', 'md' ),
				'new_item' => __( 'New Book', 'md' ),
				'view_item' => __( 'View Book', 'md' ),
				'view_items' => __( 'View Books', 'md' ),
				'search_items' => __( 'Search Books', 'md' ),
				'not_found' => __( 'No books found', 'md' ),
				'not_found_in_trash' => __( 'No books found in trash', 'md' ),
				'all_items' => __( 'All Books', 'md' )
			)
		) );
	}

	/**
	 * Add taxonomies/categories meta to our custom post type.
	 *
	 * @since 4.8.5
	 */

	public function taxonomy() {
		register_taxonomy( $this->taxonomy_label, 'bookshelf', array(
			'hierarchical' => true,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array(
				'slug' => esc_attr( $this->slug ) . '/category',
				'with_front' => false,
				'hierarchical' => true
			),
			'capabilities' => array( 'manage_categories' )
		) );
	}

	/**
	 * Create admin page and meta box.
	 *
	 * @since 4.8.5
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Settings', 'md' ),
				'parent_slug' => 'edit.php?post_type=bookshelf',
				'fields' => array(
					'archives_slug' => array( 'type' => 'text' ),
					'archives_title' => array( 'type' => 'text' ),
					'archives_text' => array( 'type' => 'textarea' ),
					'archives_layout' => array(
						'type' => 'checkbox',
						'options' => array( 'categories', 'sidebar' )
					),
					'archives_listing' => array(
						'type' => 'select',
						'options' => array( 'grid', 'excerpt' )
					),
					'posts_per_page' => array( 'type' => 'number' )
				)
			),
			'meta_box' => array(
				'name' => __( 'Book', 'md' ),
				'post_type' => array( 'bookshelf' ),
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					'book_title' => array( 'type' => 'text' ),
					'book_author' => array( 'type' => 'text' ),
					'book_download_text' => array( 'type' => 'text' ),
					'book_download_url' => array( 'type' => 'url' ),
					'book_rating' => array( 'type' => 'number' )
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
		$templates['bookshelf'] = md_css( 'dropins', 'bookshelf/css', true );
		return $templates;
	}

	/**
	 * Add Bookshelf to custom Sidebars Manager.
	 *
	 * @since 4.8.5
	 */

	public function sidebars( $sidebars ) {
		if ( md_setting( array( 'bookshelf', 'archives_layout', 'sidebar' ) ) )
			$sidebars['bookshelf']['archive'] = true;
		return $sidebars;
	}

	/**
	 * Add Bookshelf Archives link to Admin Bar.
	 *
	 * @since 4.8.5
	 */

	public function admin_bar( $admin_bar ) {
		$screen = get_current_screen();
		$post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		if ( $screen->base == "bookshelf_page_{$page}" )
			$admin_bar->add_menu( array(
				'id' => "{$this->_prefix}-archives-link",
				'title' => __( 'View Books', 'md' ),
				'href' => esc_url( get_site_url() . '/' . $this->slug ),
				'meta' => array(
					'title' => __( 'View Books', 'md' ),
					'target' => '_blank'
				)
			) );
	}

	/**
	 * Create admin page for Bookshelf Settings.
	 *
	 * @since 4.8.5
	 */

	public function admin_page() {
		include( md_template( 'dropins', 'bookshelf/admin/bookshelf-settings', true ) );
	}

	/**
	 * Meta box fields.
	 *
	 * @since 4.8.5
	 */

	public function meta_box() {
		include( md_template( 'dropins', 'bookshelf/admin/bookshelf-meta', true ) );
	}

}

new md_bookshelf;