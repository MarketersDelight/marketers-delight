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
	public $fields;
	public $name;
	public $register;

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

		// Frontend

		if ( method_exists( $this, 'template' ) )
			add_action( 'template_redirect', array( $this, 'template' ) );

		if ( method_exists( $this, 'parse_query' ) && ! is_admin() )
			add_action( 'parse_query', array( $this, 'parse_query' ) );

		if ( method_exists( $this, 'enqueue' ) )
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 20 );

		if ( method_exists( $this, 'widgets' ) )
			add_action( 'widgets_init', array( $this, 'widgets' ) );

		// Filters

		if ( method_exists( $this, 'post_meta' ) )
			add_filter( 'md_post_type_meta', array( $this, 'post_meta' ) );

		if ( method_exists( $this, 'term_meta' ) || method_exists( $this, 'taxonomy_meta' ) )
			add_filter( 'md_taxonomy_meta', array( $this, 'term_meta' ) );

		if ( method_exists( $this, 'blocks' ) )
			add_filter( 'md_filter_blocks', array( $this, 'blocks' ) );

		if ( method_exists( $this, 'blocks_scripts' ) )
			add_filter( 'md_filter_blocks_scripts', array( $this, 'blocks_scripts' ), 10, 2 );

		if ( method_exists( $this, 'byline' ) )
			add_filter( 'md_byline', array( $this, 'byline' ) );

		add_action( 'init', array( $this, '_init' ) );
	}

	/**
	 * Setup init for admin environment.
	 *
	 * @since 6.0
	 */

	public function _init() {
		if ( method_exists( $this, 'init' ) )
			$this->init();

		if ( ! is_admin() )
			return;

		// Set admin properties

		$this->_clean_id = md_clean_id( $this->_id );
		$this->_prefix = $this->_prefix();
		$this->fields = new md_fields( array(
			'id' => $this->_id,
			'clean_id' => $this->_clean_id,
			'prefix' => $this->_prefix
		) );

		// Register components and fields

		$callback = 'admin_fields';
		$this->register = $this->register();
		add_filter( 'md_register', array( $this, '_register' ) );

		// Admin pages

		if ( isset( $this->register['admin_page'] ) ) {
			$admin_callback = $admin_group_callback = method_exists( $this, 'admin_page' ) ? 'admin_page' : $callback;

			if ( isset( $this->register['admin_page']['has_groups'] ) )
				add_filter( 'md_admin_groups', array( $this, '_admin_groups' ) );

			if ( isset( $this->register['admin_page']['parent_group'] ) || isset( $this->register['admin_page']['child_of'] ) ) {
				$admin_group_priority = ! empty( $this->register['admin_page']['position'] ) ? $this->register['admin_page']['position'] : 10;

				if ( method_exists( $this, 'admin_group' ) )
					$admin_group_callback = 'admin_group';

				if ( isset( $this->register['admin_page']['parent_group'] ) ) {
					$group = $this->register['admin_page']['parent_group'];
					$admin_hooks = array( "md_admin_page_{$group}" );
					$groups = (array) $group;
				}
				elseif ( isset( $this->register['admin_page']['child_of'] ) ) {
					$groups = $this->register['admin_page']['child_of'];
					$groups = ! is_array( $groups ) ? (array) $groups : $groups;
					foreach ( $groups as $group ) {
						$admin_hooks[] = "md_admin_page_{$group}_fields";
						add_filter( "md_admin_page_{$group}_child_fields", array( $this, '_admin_child_fields' ) );
					}
				}

				if ( method_exists( $this, 'fields' ) )
					foreach ( $groups as $group )
						add_filter( "md_filter_admin_page_$group", array( $this, '_admin_fields' ) );

				foreach ( $admin_hooks as $admin_hook )
					add_action( $admin_hook, array( $this, $admin_group_callback ), $admin_group_priority );
			}

			add_action( "{$this->_id}_admin_page", array( $this, $admin_callback ) );
		}

		if ( method_exists( $this, 'admin_page_before' ) ) #MD5.4, drop-ins page
			add_action( "{$this->_id}_admin_page_before_form", array( $this, 'admin_page_before' ) );

		// Meta boxes

		if ( isset( $this->register['meta_box'] ) ) {
			if ( isset( $this->register['meta_box']['child_of'] ) ) {
				$groups = $this->register['meta_box']['child_of'];
				$groups = ! is_array( $groups ) ? (array) $groups : $groups;
				foreach ( $groups as $group ) {
					$meta_hooks[] = "md_post_meta_{$group}_fields";
					add_filter( "md_post_meta_{$group}_child_fields", array( $this, '_admin_child_fields' ) );
				}
			} else $meta_hooks = array( "{$this->_id}_meta_box" );

			$meta_callback = method_exists( $this, 'meta_box' ) ? 'meta_box' : $callback;

			foreach ( $meta_hooks as $meta_hook )
				add_action( $meta_hook, array( $this, $meta_callback ) );
		}

		// Terms

		if ( isset( $this->register['term'] ) ) {
			$term_callback = method_exists( $this, 'term' ) ? 'term' : $callback;
			$position = ! empty( $this->register['term']['position'] ) ? $this->register['term']['position'] : 100;

			if ( method_exists( $this, $term_callback ) )
				if ( isset( $this->register['term']['child_of'] ) ) {
					$groups = $this->register['term']['child_of'];
					$groups = ! is_array( $groups ) ? (array) $groups : $groups;

					foreach ( $groups as $group ) {
						add_filter( "md_term_meta_{$group}_child_fields", array( $this, '_admin_child_fields' ) );
						add_action( "md_term_meta_{$group}_fields", array( $this, $term_callback ), $position );
					}
				}
				else {
					$taxonomy = isset( $_GET['taxonomy'] ) ? $_GET['taxonomy'] : '';
					$term = isset( $_GET['tag_ID'] ) ? $_GET['tag_ID'] : '';

					add_action( "md_{$taxonomy}_{$term}", array( $this, $term_callback ), $position );
				}
		}

		// User meta

		if ( method_exists( $this, 'user_meta' ) && isset( $this->register['user_meta'] ) )
			add_action( 'md_user_meta_fields', array( $this, 'user_meta' ) );

		// Scripts

		add_action( 'admin_enqueue_scripts', array( $this, '_admin_enqueue' ) );
		add_action( 'admin_print_footer_scripts', array( $this, '_admin_scripts' ), 100 );
	}

	/**
	 * Get a prefix for option names and values across different contexts.
	 *
	 * @since 6.0
	 */

	public function _prefix() {
		$prefix = "{$this->_option}_{$this->_clean_id}";

		if ( isset( $_GET['page'] ) ) {
			$page = esc_attr( $_GET['page'] );
			$page_types = apply_filters( 'md_admin_groups', array() );

			if ( ! empty( $page_types[$page] ) ) {
				$page = md_clean_id( $page );

				if ( $page !== $this->_clean_id )
					$prefix = "{$this->_option}_{$page}_{$this->_clean_id}";
				else
					$prefix = "{$this->_option}_{$this->_clean_id}";
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
		$order = 10;

		if ( isset( $this->register['admin_page'] ) ) {
			$data['admin_pages'][$this->_clean_id] = $this->register['admin_page'];
			$data['admin_pages'][$this->_clean_id]['id'] = $this->_id;
		}

		if ( isset( $this->register['meta_box'] ) ) {
			$data['meta_boxes'][$this->_clean_id] = $this->register['meta_box'];
			$data['meta_boxes'][$this->_clean_id]['id'] = $this->_id;
		}

		if ( isset( $this->register['term'] ) ) {
			$data['terms'][$this->_clean_id] = $this->register['term'];
			$data['terms'][$this->_clean_id]['id'] = $this->_id;
		}

		if ( isset( $this->register['user_meta'] ) ) {
			$data['user_meta'][$this->_clean_id] = $this->register['user_meta'];
			$data['user_meta'][$this->_clean_id]['id'] = $this->_id;
		}

		return $data;
	}

	/**
	 * Get API design data a little easier.
	 *
	 * @since 6.0
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

	public function register() { return array(); }

	/**
	 * Add class extensions field data to shared array.
	 *
	 * @since 6.0
	 */

	// Groups of settings pages like Post Types (Blog, Stream, Docs, etc.)
	public function _admin_groups( $settings ) {
		$settings[$this->_id] = true;
		return $settings;
	}

	// List of admin fields inside a parent group
	public function _admin_fields( $settings ) {
		$settings[$this->_clean_id] = $this->fields();
		return $settings;
	}

	// List of children admin settings pages with name
	public function _admin_child_fields( $settings ) {
		$settings[$this->_clean_id] = $this->name;
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