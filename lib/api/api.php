<?php
/**
 * Welcome to the custom world of the MD design system: the backbone of all scripts, styles,
 * helper functions, and other tools used to power MD's custom features. This system powers
 * all of the reusable components within the Marketers Delight theme and associated child theme.
 *
 * @since 4.0
 * @refactored 5.0
 */

class md_api {

	public $_id;
	public $_clean_id;
	public $_option = 'marketers_delight';
	public $_prefix;

	/**
	 * Fire off class extension actions, filters, and set core properties.
	 *
	 * @since 4.0
	 */

	public function __construct( $id = null ) {

		$this->_id = isset( $id ) ? $id : get_class( $this );

		/**
		 * Load subclass' psuedo-contructor, if it exists.
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

		if ( method_exists( $this, 'css' ) )
			add_filter( 'md_dropins_css_templates', array( $this, 'css' ) );

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

		// Admin

		if ( is_admin() ) {

			// Set core properties

			$this->_clean_id = md_clean_id( $this->_id );
			$this->_prefix = "{$this->_option}_{$this->_clean_id}";
			$this->fields = new md_fields( $this->_id );

			// Register components and fields

			$register = $this->register();

			add_filter( 'md_register', array( $this, '_register' ) );

			// Admin pages

			if ( method_exists( $this, 'admin_page' ) && ( isset( $register['admin_page'] ) || $this->admin_page ) )
				add_action( "{$this->_id}_admin_page", array( $this, 'admin_page' ) );

			// Meta boxes

			if ( method_exists( $this, 'meta_box' ) && ( isset( $register['meta_box'] ) || $this->meta_box ) )
				add_action( "{$this->_id}_meta_box", array( $this, 'meta_box' ) );

			// Terms

			if ( ( method_exists( $this, 'term' ) || isset( $register['term']['callback'] ) && ( isset( $register['term'] ) || $this->taxonomy ) ) ) {
				$taxonomy = isset( $_GET['taxonomy'] ) ? $_GET['taxonomy'] : '';
				$term = isset( $_GET['tag_ID'] ) ? $_GET['tag_ID'] : '';
				$callback = ! empty( $register['term']['callback'] ) ? $register['term']['callback'] : array( $this, 'term' );
				$position = ! empty( $register['term']['position'] ) ? $register['term']['position'] : 10;
				add_action( "md_{$taxonomy}_{$term}", $callback, $position );
			}

			// Scripts

			add_action( 'admin_enqueue_scripts', array( $this, '_admin_enqueue' ) );
			add_action( 'admin_print_footer_scripts', array( $this, '_admin_scripts' ) );
		}

	}

	/**
	 * If this instance creates new admin pages, tabs, meta, or terms
	 * add it to the full collections below. Supports deprecated data formats.
	 *
	 * @since 5.0
	 */

	public function _register( $data ) {
		$register = $this->register();

		if ( isset( $register['admin_page'] ) || $this->admin_page ) {
			$admin_page = $this->admin_page ? $this->admin_page : $register['admin_page'];
			$data['admin_pages'][$this->_clean_id] = $admin_page;
			if ( ! isset( $data['admin_pages'][$this->_id]['id'] ) )
				$data['admin_pages'][$this->_clean_id]['id'] = $this->_id;
		}

		if ( isset( $register['meta_box'] ) || $this->meta_box ) {
			$meta_box = $this->meta_box ? $this->meta_box : $register['meta_box'];
			$data['meta_boxes'][$this->_clean_id] = $meta_box;
			$data['meta_boxes'][$this->_clean_id]['id'] = $this->_id;
		}

		if ( isset( $register['term'] ) || $this->taxonomy ) {
			$term = $this->taxonomy ? $this->taxonomy : $register['term'];
			$data['terms'][$this->_clean_id] = $term;
			$data['terms'][$this->_clean_id]['id'] = $this->_id;
		}

		return $data;
	}

	/**
	 * Register custom components to be loaded throughout
	 * the WordPres interface.
	 *
	 * @since 5.0
	 */

	public function register() {
		return array();
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
	}

	// @DEPRECATED 5.0
	public $admin_page;
	public $admin_tab;
	public $meta_box;
	public $taxonomy;
	/**
	 * Use this method in class extensions to send options data
	 * through MD sanitize.
	 *
	 * @since 4.0
	 * @DEPRECATED 5.0
	 */
	public function register_fields() {
		return array();
	}
	/**
	 * Create admin field with Fields API.
	 *
	 * @since 4.0
	 * @DEPRECATED 5.0 use $this->fields->field()
	 */
	public function field( $type, $field, $values = null, $args = null ) {
		$args = array();
		$args['type'] = $type;
		if ( isset( $values ) )
			$args['options'] = $values;
		$this->fields->field( $field, $args );
	}
	/**
	 * Load admin field based on type of screen.
	 *
	 * @since 4.1
	 * @DEPRECATED 5.0 use $this->fields->module()
	 */
	public function module_field( $field ) {
		$this->fields->module( $field );
	}
	/**
	 * Easily create a label for your fields.
	 *
	 * @since 4.0
	 * @DEPRECATED 5.0 (use $this->fields->label())
	 */
	public function label( $field, $label ) {
		$args['label'] = $label;
		$this->fields->label( $field, $args );
	}
	/**
	 * Easily create a description for your fields.
	 *
	 * @since 4.0
	 * @DEPRECATED 5.0
	 */
	public function desc( $desc ) {
		$this->fields->description( $desc );
	}

}