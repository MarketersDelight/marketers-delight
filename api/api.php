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

	public $_id;
	public $_clean_id;
	public $_prefix;
	public $_get_screen;
	public $fields;
	public $name;
	public $post_type;
	public $taxonomy;
	public $slug;
	public $plural;
	public $singular;
	protected static $sanitize;
	protected static $design;
	public $_args = array();
	public $register = array();
	public $_option = 'marketers_delight';

	/**
	 * Fires class extension actions, filters, and set core properties.
	 *
	 * @since 4.0
	 */

	public function __construct( $id = null, $args = array() ) {

		$this->_id = isset( $id ) ? $id : get_class( $this );
		$this->_args = $args;

		/**
		 * Load subclass' pseudo-constructor, if it exists.
		 * @DEPRECATED 5.0, now use $this->actions() and $this->includes() respectively
		 */

		if ( method_exists( $this, 'construct' ) )
			$this->construct();

		// Load included files

		if ( method_exists( $this, 'includes' ) )
			$this->includes();

		// Register setting defaults before subclass setup reads any settings.

		if ( method_exists( $this, 'setting_defaults' ) )
			add_filter( 'md_setting_defaults', array( $this, 'setting_defaults' ) );

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

		if ( method_exists( $this, 'enqueue' ) )
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 20 );

		if ( method_exists( $this, 'widgets' ) )
			add_action( 'widgets_init', array( $this, 'widgets' ) );

		// Post Type + Taxonomy

		if ( $this->post_type ) {
			if ( ! is_admin() )
				add_action( 'parse_query', array( $this, 'parse_query' ) );

			if ( method_exists( $this, 'register' ) )
				add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 100 );

			add_filter( 'md_post_type_meta', array( $this, 'post_meta' ) );
		}

		if ( $this->taxonomy ) {
			add_filter( 'md_edit_term_meta', array( $this, 'term_meta' ) );
			add_filter( 'md_taxonomy_meta', array( $this, 'taxonomy_meta' ) );
		}

		// Filters

		if ( method_exists( $this, 'register_loop' ) )
			add_filter( 'md_filter_loops', array( $this, 'register_loop' ) );

		if ( method_exists( $this, 'blocks' ) )
			add_filter( 'md_filter_blocks', array( $this, 'blocks' ) );

		if ( method_exists( $this, 'blocks_scripts' ) )
			add_filter( 'md_filter_blocks_scripts', array( $this, 'blocks_scripts' ), 10, 2 );

		if ( method_exists( $this, 'byline' ) )
			add_filter( 'md_byline', array( $this, 'byline' ) );

		if ( method_exists( $this, 'save_post_meta' ) )
			add_filter( 'md_post_meta_save', array( $this, 'save_post_meta' ), 10, 2 );

		// WP init

		add_action( 'init', array( $this, '_init' ) );

		if ( $this->taxonomy && method_exists( $this, 'taxonomy' ) )
			add_action( 'init', array( $this, 'taxonomy' ), 0 );

		if ( $this->post_type && method_exists( $this, 'post_type' ) )
			add_action( 'init', array( $this, 'post_type' ), 1 );
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
			'prefix' => $this->_prefix,
			'option' => $this->_option,
			'post_type' => array(
				'name' => $this->post_type,
				'taxonomy' => $this->taxonomy,
				'slug' => $this->slug,
				'plural' => $this->plural,
				'singular' => $this->singular
			)
		) );

		add_action( 'current_screen', function() {
			$this->_get_screen = $this->_get_screen();
			$this->fields->_get_screen = $this->_get_screen;
		} );

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
			$this->admin_assets( 'enqueue' );
		} );

		add_action( 'admin_print_footer_scripts', function() {
			$this->admin_assets( 'scripts' );
		}, 100 );
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
	 * Determine the admin page context and cache it so instances
	 * can know where they are loading settings.
	 *
	 * @since 6.0
	 */

	protected function _get_screen() {
		static $screen = null;

		if ( $screen !== null )
			return $screen;

		$get = get_current_screen();
		$base = $get->base;
		$is_post = in_array( $base, array( 'post', 'post-new' ), true );
		$is_term = $base === 'term';
		$is_user = in_array( $base, array( 'profile', 'user-edit' ), true );
		$is_admin = ! ( $is_post || $is_term || $is_user );
		$page = sanitize_key( $_GET['page']   ?? '' );
		$md_tab = sanitize_key( $_GET['md_tab'] ?? '' );
		$groups = apply_filters( 'md_taxonomy_groups', array() );

		return array(
			'base' => $base,
			'is_post' => $is_post,
			'is_term' => $is_term,
			'is_user' => $is_user,
			'is_admin' => $is_admin,
			'is_taxonomy' => $is_admin && $md_tab && isset( $groups[$page][$md_tab] ),
			'is_block_editor' => method_exists( $get, 'is_block_editor' ) && $get->is_block_editor(),
			'screen_id' => $is_post ? sanitize_key( $_GET['post'] ?? '' ) : ( $is_term ? sanitize_key( $_GET['tag_ID'] ?? '' ) : '' ),
			'post_type' => $get->post_type,
			'taxonomy' => $get->taxonomy,
			'page' => $page,
			'md_tab' => $md_tab,
			'taxonomy_groups' => $groups
		);
	}

	/**
	 * Default post type labels with singular/plural context passed in.
	 *
	 * @since 6.0
	 */

	protected function post_type_labels( $singular = null, $plural = null ) {
		$singular = $singular ?: ( $this->singular ?: ucfirst( $this->post_type ) );
		$plural = $plural ?: ( $this->plural ?: $singular );

		return array(
			'name' => $plural,
			'singular_name' => $singular,
			'menu_name' => $plural,
			'name_admin_bar' => $plural,
			'add_new_item' => sprintf( __( 'Add New %s', 'md' ), $singular ),
			'edit_item' => sprintf( __( 'Edit %s', 'md' ), $singular ),
			'new_item' => sprintf( __( 'New %s', 'md' ), $singular ),
			'view_item' => sprintf( __( 'View %s', 'md' ), $singular ),
			'view_items' => sprintf( __( 'View %s', 'md' ), $plural ),
			'search_items' => sprintf( __( 'Search %s', 'md' ), $plural ),
			'not_found' => sprintf( __( 'No %s found', 'md' ), strtolower( $plural ) ),
			'not_found_in_trash' => sprintf( __( 'No %s found in trash', 'md' ), strtolower( $plural ) ),
			'all_items' => sprintf( __( 'All %s', 'md' ), $plural )
		);
	}

	/**
	 * Add registered MD meta boxes to this post type.
	 *
	 * @since 6.0
	 */

	public function post_meta( $post_type ) {
		$post_type[] = $this->post_type;

		return $post_type;
	}

	/**
	 * Default taxonomy labels with singular/plural context passed in.
	 *
	 * @since 6.0
	 */

	protected function taxonomy_labels( $singular = null, $plural = null ) {
		$singular = $singular ?: ( $this->singular ?: ucfirst( $this->post_type ) );
		$plural = $plural ?: ( $this->plural ?: $singular );

		return array(
			'name' => sprintf( __( '%s Categories', 'md' ), $plural ),
			'singular_name' => sprintf( __( '%s Category', 'md' ), $singular ),
			'search_items' => sprintf( __( 'Search %s Categories', 'md' ), $plural ),
			'all_items' => sprintf( __( 'All %s Categories', 'md' ), $plural ),
			'parent_item' => __( 'Parent Category', 'md' ),
			'parent_item_colon' => __( 'Parent Category:', 'md' ),
			'edit_item' => sprintf( __( 'Edit %s Category', 'md' ), $singular ),
			'update_item' => sprintf( __( 'Update %s Category', 'md' ), $singular ),
			'add_new_item' => sprintf( __( 'Add New %s Category', 'md' ), $singular ),
			'new_item_name' => sprintf( __( 'New %s Category', 'md' ), $singular ),
			'menu_name' => sprintf( __( '%s Categories', 'md' ), $plural )
		);
	}

	/**
	 * Add registered MD term meta boxes to this post type.
	 *
	 * @since 6.0
	 */

	public function term_meta( $taxonomies ) {
		$taxonomies[] = $this->taxonomy;

		return $taxonomies;
	}

	/**
	 * Give this taxonomy a edit settings screen.
	 *
	 * @since 6.0
	 */

	public function taxonomy_meta( $taxonomies ) {
		$taxonomies[] = $this->taxonomy;

		return $taxonomies;
	}

	/**
	 * Change any user-set Loop settings on post type archive and terms.
	 *
	 * @since 6.0
	 */

	public function parse_query( $wp ) {
		if ( is_admin() || ! $wp->is_main_query() || ! $this->post_type )
			return $wp;

		// Only manipulate post type archives

		$post_type = $this->post_type;
		$taxonomy = $this->taxonomy;

		$context = ( $wp->is_post_type_archive || $wp->is_tax ) && (
			( isset( $wp->query['post_type'] ) && $wp->query['post_type'] == $post_type ) ||
			( $taxonomy && isset( $wp->query[$taxonomy] ) )
		);

		if ( ! $context )
			return $wp;

		// If taxonomy, determine which term we are on

		$term_id = 0;

		if ( $wp->is_tax && $taxonomy && isset( $wp->query[$taxonomy] ) ) {
			$term = get_term_by( 'slug', $wp->query[$taxonomy], $taxonomy );
			$term_id = $term ? $term->term_id : 0;
		}

		if ( ! $wp->is_tax )
			$taxonomy = '';

		// Consider sticky posts since CPTs dont natively support

		$sticky = md_get_sticky( $post_type );

		if ( $sticky ) {
			if ( $term_id && $taxonomy ) {
				$term_sticky = array();

				foreach ( $sticky as $id )
					if ( has_term( $term_id, $taxonomy, $id ) )
						$term_sticky[] = $id;

				$sticky = $term_sticky;
			}

			if ( $sticky ) {
				$wp->set( 'post__not_in', $sticky );

				if ( ! get_query_var( 'paged' ) )
					add_filter( 'the_posts', array( $this, '_prepend_sticky' ), 10, 2 );
			}
		}

		// Set final query vars

		$wp->query_vars['post_type'] = $this->post_type;
		$wp->query_vars['paged'] = get_query_var( 'paged' );

		$this->loop_query_vars( $wp, $taxonomy, $term_id );

		return $wp;
	}

	/**
	 * Registered post types modify the Loop in common ways, and this implements
	 * those values from all tiers of a CPT/taxonomy/term relationship.
	 *
	 * @since 6.0
	 */

	protected function loop_query_vars( $wp, $taxonomy = '', $term_id = 0 ) {
		$post_type = $this->post_type;
		$keys = array(
			'posts_per_page' => get_option( 'posts_per_page' ),
			'order' => null,
			'orderby' => null
		);

		// Set inherited query vars from proper context

		foreach ( $keys as $key => $default ) {
			$value = md_post_type_field( array( 'loop', $key ), $default, $post_type );

			if ( $taxonomy )
				$value = md_taxonomy_field( array( 'loop', $key ), $value, $post_type, $taxonomy );

			if ( $term_id )
				$value = md_term_meta( array( 'loop', $key ), $term_id, $value );

			if ( $value )
				$wp->query_vars[$key] = $key === 'posts_per_page' ? absint( $value ) : sanitize_key( $value );
		}
	}

	/**
	 * Prepend sticky CPT posts to the front of the main query results on page 1.
	 * Mirrors how WP core handles sticky posts for the native post type.
	 *
	 * @since 6.0
	 */

	public function _prepend_sticky( $posts, $query ) {
		if ( ! $query->is_main_query() )
			return $posts;

		remove_filter( 'the_posts', array( $this, '_prepend_sticky' ), 10 );

		$sticky = md_get_sticky( $this->post_type );

		if ( empty( $sticky ) )
			return $posts;

		$taxonomy = $this->taxonomy;

		if ( $query->is_tax && $taxonomy ) {
			$queried = $query->get_queried_object();

			if ( $queried instanceof WP_Term ) {
				$term_sticky = array();

				foreach ( $sticky as $id )
					if ( has_term( $queried->term_id, $taxonomy, $id ) )
						$term_sticky[] = $id;

				$sticky = $term_sticky;
			}
		}

		if ( empty( $sticky ) )
			return $posts;

		$sticky_posts = get_posts( array(
			'post_type' => $this->post_type,
			'post__in' => $sticky,
			'posts_per_page' => count( $sticky ),
			'ignore_sticky_posts' => true,
			'orderby' => 'post__in'
		) );

		return array_merge( $sticky_posts, $posts );
	}

	/**
	 * Add matching admin links in admin bar to CPT archive pages front/backend.
	 *
	 * @since 6.0
	 */

	public function admin_bar( $admin_bar ) {
		$id = $this->_id;
		$screen = $this->_get_screen;
		$register = $this->register;
		$post_type = $this->post_type;
		$label = $this->plural ?: ucfirst( $post_type );

		if ( is_admin() && ! empty( $register['admin_page'] ) && ! empty( $screen['page'] ) && $screen['page'] === $id ) {
			$href = get_site_url() . '/' . ( $this->slug ?: $post_type );

			if ( $post_type === 'post' ) {
				$page_for_posts = get_option( 'page_for_posts' );
				$href = $page_for_posts ? get_permalink( $page_for_posts ) : home_url( '/' );
			}

			$admin_bar->add_menu( array(
				'id' => "{$this->_prefix}-archives-link",
				'title' => sprintf( __( 'View %s', 'md' ), $label ),
				'href' => esc_url( $href ),
				'meta' => array( 'title' => sprintf( __( 'View %s', 'md' ), $label ), 'target' => '_blank' )
			) );
		}
		elseif ( ! is_admin() && ( is_post_type_archive( $post_type ) || ( $post_type === 'post' && is_home() ) ) )
			$admin_bar->add_menu( array(
				'id' => "{$id}-settings-link",
				'title' => '<span class="ab-icon dashicons dashicons-edit"></span>' . sprintf( __( 'Edit %s Settings', 'md' ), $label ),
				'href' => esc_url( admin_url( "admin.php?page=$id" ) )
			) );
	}

	/**
	 * Get a prefix for option names and values across different page contexts.
	 *
	 * @since 6.0
	 */

	protected function _prefix() {
		$prefix = "{$this->_option}_{$this->_clean_id}";

		if ( ! isset( $_GET['page'] ) )
			return $prefix;

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
			$data['admin_pages'][$this->_clean_id]['_option'] = $this->_option;
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

		$callback = method_exists( $this, 'admin_page' ) ? 'admin_page' : ( method_exists( $this, 'admin_fields' ) ? 'admin_fields' : null );

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

		$callback = method_exists( $this, 'meta_box' ) ? 'meta_box' : ( method_exists( $this, 'admin_fields' ) ? 'admin_fields' : null );

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

		$callback = method_exists( $this, 'term' ) ? 'term' : ( method_exists( $this, 'admin_fields' ) ? 'admin_fields' : null );

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
	 * Loads admin scripts and styles based on wp_enqueue or print_inline, on specified admin screen.
	 * In md_api class extension, create methods named ${context}_scripts or ${context}_enqueue.
	 * Examples: admin_enqueue, meta_enqueue, term_scripts, meta_scripts
	 *
	 * @since 5.0
	 */

	private function admin_assets( $suffix ) {
		$screen = get_current_screen();
		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
		$tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : '';
		$taxonomy = isset( $_GET['taxonomy'] ) ? sanitize_key( $_GET['taxonomy'] ) : '';

		// Load meta box

		if ( in_array( $screen->base, array( 'post', 'post-new' ), true ) && in_array( get_post_type(), md_post_type_meta(), true ) && method_exists( $this, "meta_$suffix" ) )
			call_user_func( array( $this, "meta_$suffix" ) );

		// Load terms

		if ( $screen->base === 'term' && in_array( $taxonomy, md_edit_term_meta(), true ) && method_exists( $this, "term_$suffix" ) )
			call_user_func( array( $this, "term_$suffix" ) );

		// Load admin pages

		$is_own_page = in_array( $this->_id, array( $page, $tab ) );
		$is_group_child = isset( $this->register['admin_page']['group'] ) && ! empty( apply_filters( 'md_admin_groups', array() )[$page] );

		if ( ( $is_own_page || $is_group_child ) && method_exists( $this, "admin_$suffix" ) )
			call_user_func( array( $this, "admin_$suffix" ) );

		// Load user meta

		if ( $screen->base == 'profile' && method_exists( $this, "user_meta_$suffix" ) )
			call_user_func( array( $this, "user_meta_$suffix" ) );
	}

}
