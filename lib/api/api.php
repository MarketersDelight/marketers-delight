<?php
/**
 * Welcome to the custom world of the MD design system: the backbone of all scripts, styles,
 * helper functions, and other tools used to power MD's custom features. This system powers
 * all of the reusable components within the Marketers Delight theme and associated child theme.
 *
 * @since 4.0
 * @refactored 5.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class md_api {

	public $_id;
	public $_clean_id;
	public $_option = 'marketers_delight';
	public $_prefix;

	/**
	 * Fires class extension actions, filters, and set core properties.
	 *
	 * @since 4.0
	 */

	public function __construct( $id = null ) {

		$this->_id = isset( $id ) ? $id : get_class( $this );

		/**
		 * Load subclass' pseudo-constructor, if it exists.
		 * @DEPRECATED 5.0, now use $this->actions() and $this->includes() respectively
		 */

		if ( method_exists( $this, 'construct' ) )
			$this->construct();

		// Load included files

		if ( method_exists( $this, 'includes' ) )
			$this->includes();

		// Run instance actions and filters

		if ( method_exists( $this, 'actions' ) )
			$this->actions();

		// Print dynamic CSS to master stylesheet

		if ( method_exists( $this, 'css' ) ) #since 4.9
			add_filter( 'md_dropins_css_templates', array( $this, 'css' ) );

		if ( method_exists( $this, 'css_data' ) )
			add_filter( 'md_filter_css_values', array( $this, 'css_data' ) );

		if ( method_exists( $this, 'js' ) ) #since 5.4.2
			add_filter( 'md_js_templates', array( $this, 'js' ) );

		if ( method_exists( $this, 'onscroll' ) ) #since 5.4.2
			add_filter( 'md_js_onscroll', array( $this, 'onscroll' ) );

		// Utilities

		if ( method_exists( $this, 'after_setup_theme' ) )
			add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );

		// Frontend

		if ( method_exists( $this, 'template' ) )
			add_action( 'template_redirect', array( $this, 'template' ) );

		if ( method_exists( $this, 'parse_query' ) && ! is_admin() )
			add_action( 'parse_query', array( $this, 'parse_query' ) );

		if ( method_exists( $this, 'enqueue' ) )
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

		if ( method_exists( $this, 'widgets' ) )
			add_action( 'widgets_init', array( $this, 'widgets' ) );

		// Filters

		if ( method_exists( $this, 'post_type_meta' ) )
			add_filter( 'md_post_type_meta', array( $this, 'post_type_meta' ) );

		if ( method_exists( $this, 'taxonomy_meta' ) )
			add_filter( 'md_taxonomy_meta', array( $this, 'taxonomy_meta' ) );

		// Admin

		if ( is_admin() ) {

			// Set core properties

			$this->_clean_id = md_clean_id( $this->_id );
			$this->_prefix = $this->_prefix();
			$this->fields = new md_fields( array(
				'id' => $this->_id,
				'clean_id' => $this->_clean_id,
				'prefix' => $this->_prefix
			) );

			// Register components and fields

			$register = $this->register();

			add_filter( 'md_register', array( $this, '_register' ) );

			// Admin pages

			if ( method_exists( $this, 'admin_page' ) && isset( $register['admin_page'] ) )
				add_action( "{$this->_id}_admin_page", array( $this, 'admin_page' ) );

			if ( method_exists( $this, 'admin_page_before' ) ) #MD5.4, drop-ins page
				add_action( "{$this->_id}_admin_page_before_form", array( $this, 'admin_page_before' ) );

			// Admin Settings #5.6

			if ( method_exists( $this, 'admin_settings' ) )
				add_filter( 'md_admin_settings', array( $this, 'admin_settings' ) );

			if ( method_exists( $this, 'admin_fields' ) ) {
				add_action( 'admin_init', array( $this, '_admin_init' ) );
				if ( method_exists( $this, 'fields' ) )
					add_filter( 'md_page_settings_fields', array( $this, '_admin_fields' ) );
			}

			// Meta boxes

			if ( isset( $register['meta_box'] ) && method_exists( $this, 'meta_box' ) )
				if ( isset( $register['meta_box']['page_settings'] ) )
					add_action( 'md_post_meta_page_settings', array( $this, 'meta_box' ) );
				else
					add_action( "{$this->_id}_meta_box", array( $this, 'meta_box' ) );

			// Terms

			if ( isset( $register['term'] ) && ( method_exists( $this, 'term' ) || isset( $register['term']['callback'] ) ) ) {
				if ( isset( $register['term']['page_settings'] ) )
					add_action( 'md_term_meta_page_settings', array( $this, 'term' ) );
				else {
					$taxonomy = isset( $_GET['taxonomy'] ) ? $_GET['taxonomy'] : '';
					$term = isset( $_GET['tag_ID'] ) ? $_GET['tag_ID'] : '';
					$callback = ! empty( $register['term']['callback'] ) ? $register['term']['callback'] : array( $this, 'term' );
					$position = ! empty( $register['term']['position'] ) ? $register['term']['position'] : 100;
					add_action( "md_{$taxonomy}_{$term}", $callback, $position );
				}
			}

			// User meta

			if ( method_exists( $this, 'user_meta' ) && isset( $register['user_meta'] ) )
				add_action( 'md_user_meta_fields', array( $this, 'user_meta' ) );

			// Scripts

			add_action( 'admin_enqueue_scripts', array( $this, '_admin_enqueue' ) );
			add_action( 'admin_print_footer_scripts', array( $this, '_admin_scripts' ), 100 );
		}

	}

	/**
	 * Get a prefix for option names and values across different contexts.
	 *
	 * @since 5.6
	 */

	public function _prefix() {
		$prefix = "{$this->_option}_{$this->_clean_id}";

		if ( isset( $_GET['page'] ) ) {
			$page = esc_attr( $_GET['page'] );
			$page_types = md_admin_settings();

			if ( ! empty( $page_types[$page] ) ) {
				$page = md_clean_id( $page );
				$prefix = "{$this->_option}_{$page}_{$this->_clean_id}";
			}
		}

		return $prefix;
	}

	/**
	 * If this instance creates new admin pages, tabs, meta, or terms
	 * add it to the full collections below.
	 *
	 * @since 5.0
	 */

	public function _register( $data ) {
		$register = $this->register();
		$order = 10;

		if ( isset( $register['admin_page'] ) ) {
			$data['admin_pages'][$this->_clean_id] = $register['admin_page'];

			if ( ! isset( $data['admin_pages'][$this->_id]['id'] ) )
				$data['admin_pages'][$this->_clean_id]['id'] = $this->_id;
		}

		if ( isset( $register['meta_box'] ) ) {
			$data['meta_boxes'][$this->_clean_id] = $register['meta_box'];
			$data['meta_boxes'][$this->_clean_id]['id'] = $this->_id;

			if ( isset( $data['meta_boxes'][$this->_clean_id]['page_settings'] ) ) {
				$name = $data['meta_boxes'][$this->_clean_id]['name'];

				if ( isset( $data['meta_boxes'][$this->_clean_id]['tab_name'] ) )
					$name = $data['meta_boxes'][$this->_clean_id]['tab_name'];

				if ( isset( $data['meta_boxes'][$this->_clean_id]['order'] ) )
					$order = $data['meta_boxes'][$this->_clean_id]['order'];

				$data['post_meta_page_settings'][$this->_clean_id]['name'] = $name;
				$data['post_meta_page_settings'][$this->_clean_id]['order'] = $order;
			}
		}

		if ( isset( $register['term'] ) ) {
			$data['terms'][$this->_clean_id] = $register['term'];
			$data['terms'][$this->_clean_id]['id'] = $this->_id;

			if ( isset( $data['terms'][$this->_clean_id]['page_settings'] ) ) {
				$name = $data['terms'][$this->_clean_id]['name'];

				if ( isset( $data['terms'][$this->_clean_id]['tab_name'] ) )
					$name = $data['terms'][$this->_clean_id]['tab_name'];

				if ( isset( $data['terms'][$this->_clean_id]['order'] ) )
					$order = $data['terms'][$this->_clean_id]['order'];

				$data['term_meta_page_settings'][$this->_clean_id]['name'] = $name;
				$data['term_meta_page_settings'][$this->_clean_id]['order'] = $order;
			}
		}

		if ( isset( $register['user_meta'] ) ) {
			$data['user_meta'][$this->_clean_id] = $register['user_meta'];
			$data['user_meta'][$this->_clean_id]['id'] = $this->_id;
		}

		return $data;
	}

	/**
	 * Get API design data a little easier.
	 *
	 * @since 5.6
	 */

	protected function _data( $key = null ) {
		$design = new md_design;
		$sanitize = new md_sanitize;
		$data = array(
			'values' => $design->values(),
			'defaults' => $design->defaults(),
			'sanitize' => $sanitize,
			'menus' => $sanitize->menus()
		);

		if ( isset( $key ) )
			$data = $data[$key];

		return $data;
	}

	/**
	 * Register custom components to be loaded throughout
	 * the WordPress interface.
	 *
	 * @since 5.0
	 */

	public function register() {
		return array();
	}

	/**
	 * Fire all-purpose admin actions. Currently restricted to adding
	 * page settings to Admin Pages when called from Drop-ins.
	 *
	 * @since 5.6
	 */

	public function _admin_init() {
		if ( wp_doing_ajax() )
			return;

		$order = 100;
		$admin_fields = md_admin_fields();

		if ( ! empty( $admin_fields[$this->_clean_id] ) )
			foreach ( $admin_fields[$this->_clean_id] as $admin_field ) {
				if ( $this->_clean_id == 'featured_image' )
					$order = 10;
				elseif ( $this->_clean_id == 'page_cover' )
					$order = 20;
				elseif ( $this->_clean_id == 'layout' )
					$order = 30;
				elseif ( $this->_clean_id == 'loop' )
					$order = 40;
				elseif ( $this->_clean_id == 'single' )
					$order = 50;

				add_action( "{$admin_field}_admin_fields", array( $this, 'admin_fields' ), $order );
			}
	}

	/**
	 * Add class extensions field data to shared array.
	 *
	 * @since 5.6
	 */

	public function _admin_fields( $settings ) {
		$settings[$this->_clean_id] = $this->fields();
		return $settings;
	}

	/**
	 * Load class instance enqueue scripts across different admin screens.
	 *
	 * @since 5.0
	 */

	public function _admin_enqueue() {
		$screen = get_current_screen();
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';

		if ( in_array( $screen->base, array( 'post', 'post-new' ) ) && in_array( get_post_type(), md_post_type_meta() ) && method_exists( $this, 'meta_enqueue' ) )
			$this->meta_enqueue();

		if ( $screen->base == 'term' && in_array( $_GET['taxonomy'], md_taxonomy_meta() ) && method_exists( $this, 'term_enqueue' ) )
			$this->term_enqueue();

		if ( in_array( $this->_id, array( $page, $tab ) ) && method_exists( $this, 'admin_enqueue' ) )
			$this->admin_enqueue();

		if ( in_array( $screen->base, array( 'profile' ) ) && method_exists( $this, 'user_meta_enqueue' ) )
			$this->user_meta_enqueue();
	}

	/**
	 * Print class instance inline scripts across different admin screens.
	 *
	 * @since 5.0
	 */

	public function _admin_scripts() {
		$screen = get_current_screen();
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';

		if ( in_array( $screen->base, array( 'post', 'post-new' ) ) && in_array( get_post_type(), md_post_type_meta() ) && method_exists( $this, 'meta_scripts' ) )
			$this->meta_scripts();

		if ( $screen->base == 'term' && in_array( $_GET['taxonomy'], md_taxonomy_meta() ) && method_exists( $this, 'term_scripts' ) )
			$this->term_scripts();

		if ( in_array( $this->_id, array( $page, $tab ) ) && method_exists( $this, 'admin_scripts' ) )
			$this->admin_scripts();

		if ( in_array( $screen->base, array( 'profile' ) ) && method_exists( $this, 'user_meta_scripts' ) )
			$this->user_meta_scripts();
	}

}
