<?php
/**
 * Welcome to the custom world of the MD design system: the backbone of all scripts, styles,
 * helper functions, and other tools used to power MD's custom features. This system powers
 * all of the reusable components within the Marketers Delight theme and associated child theme.
 *
 * @since 4.0
 */

class md_api {

	// Set important properties available in class extensions.

	public $_option = 'marketers_delight';
	public $register = array();
	public $_id;
	public $_clean_id;
	public $_prefix;
	public $fields;
	public $name;
	protected static $sanitize;
	protected static $design;

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
			add_filter( 'md_hook_js_onscroll', array( $this, 'onscroll' ) );

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

		if ( method_exists( $this, 'term_meta' ) )
			add_filter( 'md_edit_term_meta', array( $this, 'term_meta' ) );

		if ( method_exists( $this, 'taxonomy_meta' ) )
			add_filter( 'md_taxonomy_meta', array( $this, 'taxonomy_meta' ) );

		if ( method_exists( $this, 'blocks' ) )
			add_filter( 'md_filter_blocks', array( $this, 'blocks' ) );

		if ( method_exists( $this, 'blocks_scripts' ) )
			add_filter( 'md_filter_blocks_scripts', array( $this, 'blocks_scripts' ), 10, 2 );

		if ( method_exists( $this, 'byline' ) )
			add_filter( 'md_byline', array( $this, 'byline' ) );

		// WP init

		add_action( 'init', array( $this, '_init' ) );
	}

	/**
	 * Get API sanitize class instance.
	 *
	 * @since 6.0
	 */

	protected function sanitize() {
		if ( ! isset( self::$sanitize ) )
			self::$sanitize = new md_sanitize;

		return self::$sanitize;
	}

	/**
	 * Get API design class instance.
	 *
	 * @since 6.0
	 */

	protected function design() {
		if ( ! isset( self::$design ) )
			self::$design = new md_design;

		return self::$design;
	}

	/**
	 * Run core API actions and filters on WP init.
	 *
	 * @since 6.0
	 */

	public function _init() {
		if ( method_exists( $this, 'init' ) )
			$this->init();

		if ( ! is_admin() )
			return;

		// Set important properties

		$this->_clean_id = md_clean_id( $this->_id );
		$this->_prefix = $this->_prefix();
		$this->fields = new md_fields( array(
			'id' => $this->_id,
			'clean_id' => $this->_clean_id,
			'prefix' => $this->_prefix
		) );

		if ( method_exists( $this, 'register' ) )
			$this->register = $this->register();

		// Render all settings fields hierarchy

		add_filter( 'md_register', array( $this, '_register' ) );

		// Render admin interfaces

		$this->setup_admin_page();
		$this->setup_meta_box();
		$this->setup_term();

		if ( isset( $this->register['taxonomy'] ) )
			add_filter( 'md_taxonomy_groups', array( $this, '_taxonomy_groups' ) );

		if ( method_exists( $this, 'user_meta' ) && isset( $this->register['user_meta'] ) )
			add_action( 'md_user_meta_fields', array( $this, 'user_meta' ) );

		// Fire admin enqueue and inline scripts/styles.

		add_action( 'admin_enqueue_scripts', function() {
			$this->_admin_assets( 'enqueue' );
		} );

		add_action( 'admin_print_footer_scripts', function() {
			$this->_admin_assets( 'scripts' );
		}, 100 );
	}

	/**
	 * Get a prefix for option names and values across different page contexts.
	 *
	 * @since 6.0
	 */

	public function _prefix() {
		$prefix = "{$this->_option}_{$this->_clean_id}";

		if ( isset( $_GET['page'] ) ) {
			$page = sanitize_key( $_GET['page'] );
			$page_types = apply_filters( 'md_admin_groups', array() );

			if ( ! empty( $page_types[$page] ) ) {
				$page = md_clean_id( $page );

				if ( $page !== $this->_clean_id )
					$prefix = "{$this->_option}_{$page}_{$this->_clean_id}";
				else
					$prefix = "{$this->_option}_{$this->_clean_id}";

				if ( isset( $_GET['md_tab'] ) ) {
					$taxonomy = sanitize_key( $_GET['md_tab'] );
					$tax_groups = apply_filters( 'md_taxonomy_groups', array() );
					$page_slug = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';

					if ( ! empty( $tax_groups[$page_slug][$taxonomy] ) )
						$prefix .= "_{$taxonomy}";
				}
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
	 * List of admin fields inside a parent group.
	 *
	 * @since 6.0
	 */

	public function _admin_fields( $fields ) {
		if ( method_exists( $this, 'fields' ) )
			$fields[$this->_clean_id] = $this->fields();

		return $fields;
	}

	/**
	 * Loads admin scripts and styles based on wp_enqueue or print_inline, on specified admin screen.
	 * In md_api class extension, create methods named ${context}_scripts or ${context}_enqueue.
	 * Examples: admin_enqueue, meta_enqueue, term_scripts, meta_scripts
	 *
	 * @since 5.0
	 */

	private function _admin_assets( $suffix ) {
		$screen = get_current_screen();
		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
		$tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : '';
		$taxonomy = isset( $_GET['taxonomy'] ) ? sanitize_key( $_GET['taxonomy'] ) : '';

		// Load meta box

		if ( in_array( $screen->base, array( 'post', 'post-new' ) ) && in_array( get_post_type(), md_post_type_meta() ) && method_exists( $this, "meta_$suffix" ) )
			call_user_func( array( $this, "meta_$suffix" ) );

		// Load terms

		if ( $screen->base == 'term' && in_array( $taxonomy, md_edit_term_meta() ) && method_exists( $this, "term_$suffix" ) )
			call_user_func( array( $this, "term_$suffix" ) );

		// Load admin pages

		if ( in_array( $this->_id, array( $page, $tab ) ) && method_exists( $this, "admin_$suffix" ) )
			call_user_func( array( $this, "admin_$suffix" ) );

		// Load user meta

		if ( $screen->base == 'profile' && method_exists( $this, "user_meta_$suffix" ) )
			call_user_func( array( $this, "user_meta_$suffix" ) );
	}

	/**
	 * Looks for admin interface callback between context-specific
	 * template, or univeral method in class extension.
	 *
	 * Admin settings = admin_page()
	 * Term settings  = term()
	 * Post meta      = meta_box()
	 * General        = admin_fields()
	 *
	 * @since 6.0
	 */

	private function _template_callback( $context ) {
		if ( method_exists( $this, $context ) )
			return $context;

		if ( method_exists( $this, 'admin_fields' ) )
			return 'admin_fields';

		return;
	}

	/**
	 * Wire admin page hooks for this instance based on register['admin_page'] parameters.
	 * Render admin page settings fields in various nested formats. Based on the extensions
	 * register() method, admin page fields can render inline, as children, or created as parents.
	 *
	 * has_groups : Marks as fields group host and allows children to add themselves.
	 *              See blog.php for Core example, common pattern in Drop-ins.
	 *
	 * group      : Creates the group that can be added to group hosts, will inherit children.
	 *              See page_settings for example of a Core group.
	 *
	 * child_of   : Add fields into a group.
	 *
	 * position   : add_action priority, changes the load order (default 10). ignored when child_of set.
	 *
	 * @since 6.0
	 */

	private function setup_admin_page() {
		if ( ! isset( $this->register['admin_page'] ) )
			return;

		$callback = $this->_template_callback( 'admin_page' );

		if ( ! $callback )
			return;

		$register = $this->register['admin_page'];

		// Create a list of group hosts

		if ( isset( $register['has_groups'] ) )
			add_filter( 'md_admin_groups', function( $settings ) {
				$settings[$this->_id] = true;
				return $settings;
			} );

		// If admin groups or children are set, formulate fields data and template

		if ( isset( $register['group'] ) || isset( $register['child_of'] ) ) {
			$priority = ! empty( $register['position'] ) ? $register['position'] : 10;
			$group_callback = method_exists( $this, 'admin_group' ) ? 'admin_group' : $callback;

			// Assigned as group

			if ( isset( $register['group'] ) ) {
				$group = $register['group'];

				if ( method_exists( $this, 'fields' ) )
					foreach ( (array) $group as $field )
						add_filter( "md_filter_admin_page_$field", array( $this, '_admin_fields' ) );

				add_action( "md_admin_page_{$group}", array( $this, $group_callback ), $priority );
			}

			// Assigned as children to a group

			elseif ( isset( $register['child_of'] ) ) {
				$groups = (array) $register['child_of'];

				foreach ( $groups as $group ) {
					add_filter( "md_admin_page_{$group}_fields", function( $fields ) use( $group_callback ) {
						$fields[$this->_clean_id] = array(
							'name' => $this->name,
							'callback' => array( $this, $group_callback )
						);
						return $fields;
					} );
				}

				if ( method_exists( $this, 'fields' ) )
					foreach ( $groups as $group )
						add_filter( "md_filter_admin_page_$group", array( $this, '_admin_fields' ) );
			}
		}

		if ( ! isset( $register['child_of'] ) )
			add_action( "{$this->_id}_admin_page", array( $this, $callback ) );
	}

	/**
	 * Meta boxes can be registered outright, or the contents of the post meta
	 * can be applied to a metagroup with the child_of parameter. Since meta boxes are
	 * inherently groups, the register() method doesn't need the same parameters as general
	 * admin settings. Useful for when creating Single settings related to global admin settings.
	 *
	 * child_of : Add fields into a group.
	 *
	 * @since 6.0
	 */

	private function setup_meta_box() {
		if ( ! isset( $this->register['meta_box'] ) )
			return;

		$callback = $this->_template_callback( 'meta_box' );

		if ( ! $callback )
			return;

		$register = $this->register['meta_box'];

		// Assigned as children to a group

		if ( isset( $register['child_of'] ) ) {
			$groups = (array) $register['child_of'];
			$group_callback = $callback;

			foreach ( $groups as $group ) {
				add_filter( "md_post_meta_{$group}_fields", function( $fields ) use( $group_callback ) {
					$fields[$this->_clean_id] = array(
						'name' => $this->name,
						'callback' => array( $this, $group_callback )
					);
					return $fields;
				} );
			}
		}

		// Just create a meta box

		else add_action( "{$this->_id}_meta_box", array( $this, $callback ) );
	}

	/**
	 * Term meta applies to edit category/tags type admin pages, and the WP Term API
	 * is open like the Settings API but meant to be used contextually like the
	 * Post Meta API, so registering fields has similar outcomes to both.
	 *
	 * child_of : Add fields into a group.
	 * position : add_action priority, changes the load order (default 100).
	 *
	 * @since 6.0
	 */

	private function setup_term() {
		if ( ! isset( $this->register['term'] ) )
			return;

		$callback = $this->_template_callback( 'term' );

		if ( ! $callback )
			return;

		$register = $this->register['term'];
		$position = ! empty( $register['position'] ) ? $register['position'] : 100;

		// Assigned as children to a group

		if ( isset( $register['child_of'] ) ) {
			$group_callback = $callback;

			foreach ( (array) $register['child_of'] as $group )
				add_filter( "md_term_meta_{$group}_fields", function( $fields ) use ( $group_callback ) {
					$fields[$this->_clean_id] = array(
						'name' => $this->name,
						'callback' => array( $this, $group_callback )
					);
					return $fields;
				} );
		}

		// Just add fields to term pages

		elseif ( isset( $_GET['taxonomy'] ) && isset( $_GET['tag_ID'] ) ) {
			$taxonomy = sanitize_key( $_GET['taxonomy'] );
			$term = intval( $_GET['tag_ID'] );

			add_action( "md_{$taxonomy}_{$term}", array( $this, $callback ), $position );
		}
	}

	/**
	 * Taxonomy settings pages apply to every term in a post type's taxonomy.
	 * They inherit admin settings since they live in the Settings API, and thus
	 * inherit the same group/child structure, with the biggest difference being that
	 * they must be applied to an accompanying post_types from a register_post_type() call.
	 *
	 * @since 6.0
	 */

	public function _taxonomy_groups( $groups ) {
		$register = $this->register['taxonomy'];
		$post_types = $register['post_types'] ?? (array) $this->_clean_id;
		$meta = $register['taxonomies'] ?? md_taxonomy_meta();

		foreach ( $post_types as $post_type ) {
			$page_slug = "md_{$post_type}";
			$taxonomies = get_object_taxonomies( $post_type );

			foreach ( $meta as $tax )
				if ( in_array( $tax, $taxonomies, true ) )
					$groups[$page_slug][$tax] = true;
		}

		return $groups;
	}

}