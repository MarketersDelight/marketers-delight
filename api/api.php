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
	protected static $screens = array();
	public $_args = array();
	public $_source;
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

		if ( method_exists( $this, 'setting_defaults' ) ) {
			$filter = $this->_option === 'marketers_delight' ? 'md_setting_defaults' : "md_setting_defaults_{$this->_option}";

			add_filter( $filter, array( $this, 'setting_defaults' ) );
		}

		// Run instance actions and filters

		if ( method_exists( $this, 'actions' ) )
			$this->actions();

		// Methods named after a hook's purpose attach to that hook

		$hooks = array(
			'css' => 'md_dropins_css_templates',
			'css_data' => 'md_filter_css_values',
			'js' => 'md_js_templates',
			'onscroll' => 'md_hook_js_onscroll',
			'template' => 'template_redirect',
			'widgets' => 'widgets_init',
			'register_loop' => 'md_filter_loops',
			'byline' => 'md_byline',
			'breadcrumbs' => 'md_filter_breadcrumbs'
		);

		foreach ( $hooks as $method => $hook )
			if ( method_exists( $this, $method ) )
				add_filter( $hook, array( $this, $method ) );

		if ( method_exists( $this, 'pre_get_posts' ) && ! is_admin() )
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

		if ( method_exists( $this, 'enqueue' ) )
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 20 );

		// Post Type + Taxonomy, which a site can swap for a shared taxonomy or turn off

		$own_taxonomy = $this->taxonomy;

		if ( $this->post_type && $this->taxonomy )
			$this->taxonomy = apply_filters( 'md_post_type_taxonomy', $own_taxonomy, $this->post_type );

		if ( $this->post_type ) {
			if ( ! is_admin() )
				add_action( 'parse_query', array( $this, 'parse_query' ) );

			if ( method_exists( $this, 'register' ) )
				add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 100 );

			add_filter( 'md_post_type_meta', array( $this, 'post_meta' ) );
		}

		if ( $this->taxonomy && $this->taxonomy === $own_taxonomy ) {
			add_filter( 'md_edit_term_meta', array( $this, 'term_meta' ) );
			add_filter( 'md_taxonomy_meta', array( $this, 'taxonomy_meta' ) );
		}

		if ( method_exists( $this, 'collections' ) )
			add_filter( 'md_filter_collections', array( $this, '_collections' ) );

		if ( $this->post_type && method_exists( $this, 'document_title' ) )
			add_filter( 'document_title_parts', array( $this, '_document_title' ), 20 );

		if ( method_exists( $this, 'save_post_meta' ) )
			add_filter( 'md_post_meta_save', array( $this, 'save_post_meta' ), 10, 2 );

		// WP init

		add_action( 'init', array( $this, '_init' ) );

		if ( $this->taxonomy && $this->taxonomy === $own_taxonomy && method_exists( $this, 'taxonomy' ) )
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

		if ( method_exists( $this, 'register' ) )
			$this->register = $this->register();

		// Render all settings fields hierarchy

		add_filter( 'md_register', array( $this, '_register' ) );

		// Hosts rebuild after their groups, which they read their fields from

		if ( method_exists( $this, 'register' ) )
			add_action( 'md_refresh_fields', array( $this, '_register_groups' ), isset( $this->register['admin_page']['has_groups'] ) ? 20 : 10 );

		// Group admin settings together

		if ( isset( $this->register['admin_page']['has_groups'] ) )
			add_action( 'init', array( $this, '_register_groups' ), 99 );

		// Register standalone meta box fields with WordPress and the REST API

		$this->_register_standalone_meta();

		// Taxonomy settings belong to post type settings, wherever they're saved from

		if ( isset( $this->register['taxonomy'] ) && md_taxonomy_post_type( $this->taxonomy ) === $this->post_type )
			add_filter( 'md_taxonomy_groups', array( $this, '_taxonomy_groups' ) );

		if ( ! md_loads_settings() )
			return;

		add_action( 'current_screen', function() {
			$this->_get_screen = $this->_get_screen();
			$this->fields->_get_screen = $this->_get_screen;
		} );

		// Render admin interfaces

		$this->setup_admin_page();
		$this->setup_meta_box();
		$this->setup_term();

		if ( method_exists( $this, 'user_meta' ) && isset( $this->register['user_meta'] ) )
			add_action( 'md_user_meta_fields', array( $this, '_user_meta' ) );

		// Fire admin enqueue and inline scripts/styles.

		add_action( 'admin_enqueue_scripts', function() {
			$this->admin_assets( 'enqueue' );
		} );

		add_action( 'admin_print_footer_scripts', function() {
			$this->admin_assets( 'scripts' );
		}, 100 );
	}

	/**
	 * Register standalone meta box fields with WordPress metadata so they
	 * are readable and writable through the REST API.
	 *
	 * @since 6.0
	 */

	protected function _register_standalone_meta() {
		$meta_box = $this->register['meta_box'] ?? array();

		foreach ( $meta_box['fields'] ?? array() as $key => $field ) {
			if ( empty( $field['standalone'] ) )
				continue;

			$is_number = in_array( $field['type'], array( 'number', 'range' ), true );
			$is_upload = $field['type'] === 'upload';

			foreach ( (array) $meta_box['post_type'] as $post_type )
				register_post_meta( $post_type, $key, array(
					'type' => $is_number ? 'number' : ( $is_upload ? 'integer' : 'string' ),
					'single' => true,
					'show_in_rest' => $field['show_in_rest'] ?? true,
					'sanitize_callback' => $is_upload ? 'absint' : 'sanitize_text_field',
					'auth_callback' => function( $allowed, $meta_key, $post_id, $user_id ) use ( $meta_box ) {
						$capability = ! empty( $meta_box['edit_post'] ) ? 'edit_post' : 'manage_options';
						return user_can( $user_id, $capability, $post_id );
					}
				) );
		}
	}

	/**
	 * Get API sanitize class instance.
	 *
	 * @since 6.0
	 */

	protected function sanitize() {
		return self::$sanitize ??= new md_sanitize;
	}

	/**
	 * Get API design class instance.
	 *
	 * @since 6.0
	 */

	protected function design() {
		return self::$design ??= new md_design;
	}

	/**
	 * Determine the admin page context and cache it by screen, since every
	 * instance asks and WordPress can change screens during a request.
	 *
	 * @since 6.0
	 */

	protected function _get_screen() {
		$get = get_current_screen();

		if ( isset( self::$screens[$get->id] ) )
			return self::$screens[$get->id];

		$base = $get->base;
		$is_post = in_array( $base, array( 'post', 'post-new' ), true );
		$is_term = $base === 'term';
		$is_user = in_array( $base, array( 'profile', 'user-edit' ), true );
		$is_admin = ! ( $is_post || $is_term || $is_user );
		$page = sanitize_key( $_GET['page'] ?? '' );
		$md_tab = sanitize_key( $_GET['md_tab'] ?? '' );
		$groups = apply_filters( 'md_taxonomy_groups', array() );

		return self::$screens[$get->id] = array(
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
			'all_items' => sprintf( __( 'All %s', 'md' ), $plural ),
			'item_published' => sprintf( __( '%s published.', 'md' ), $singular ),
			'item_published_privately' => sprintf( __( '%s published privately.', 'md' ), $singular ),
			'item_reverted_to_draft' => sprintf( __( '%s reverted to draft.', 'md' ), $singular ),
			'item_scheduled' => sprintf( __( '%s scheduled.', 'md' ), $singular ),
			'item_updated' => sprintf( __( '%s updated.', 'md' ), $singular )
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
	 * Let the owning API personalize a generic title for an untitled singular.
	 *
	 * @since 6.0
	 */

	public function _document_title( $parts ) {
		$post = get_queried_object();

		if ( is_singular( $this->post_type ) && ! trim( (string) $post->post_title ) ) {
			$document_title = md_sanitize_document_title( $this->document_title( ! empty( $parts['title'] ) ? $parts['title'] : '', $post ) );

			if ( $document_title )
				$parts['title'] = $document_title;
		}

		return $parts;
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

		// Set final query vars

		$wp->query_vars['post_type'] = $this->post_type;
		$wp->query_vars['paged'] = get_query_var( 'paged' );

		$this->loop_query_vars( $wp, $taxonomy, $term_id );

		$this->loop_sticky( $wp, $post_type, $taxonomy, $term_id );

		return $wp;
	}

	/**
	 * Keep a post type's sticky posts (in the term, if any) out of the query, to
	 * be added back at the top of the first page by _prepend_sticky().
	 *
	 * @since 6.2.4
	 */

	protected function loop_sticky( $wp, $post_type, $taxonomy = '', $term_id = 0 ) {
		$exclude = $wp->get( 'post__not_in' ) ?: array();
		$sticky = array();

		if ( empty( $wp->query_vars['ignore_sticky_posts'] ) )
			foreach ( array_diff( md_get_sticky( $post_type ), $exclude ) as $id )
				if ( ! ( $term_id && $taxonomy ) || has_term( $term_id, $taxonomy, $id ) )
					$sticky[] = $id;

		if ( $sticky ) {
			$wp->set( 'post__not_in', array_merge( $exclude, $sticky ) );
			$wp->set( 'md_sticky', $sticky );

			if ( ! get_query_var( 'paged' ) )
				add_filter( 'the_posts', array( $this, '_prepend_sticky' ), 10, 2 );
		}
	}

	/**
	 * Registered post types modify the Loop in common ways, and this implements
	 * those values from all tiers of a CPT/taxonomy/term relationship.
	 *
	 * @since 6.0
	 */

	protected function loop_query_vars( $wp, $taxonomy = '', $term_id = 0 ) {
		$keys = array(
			'posts_per_page' => get_option( 'posts_per_page' ),
			'order' => null,
			'orderby' => null
		);

		// Set inherited query vars from proper context

		foreach ( $keys as $key => $default ) {
			$value = $this->loop_setting( $key, $default, $taxonomy, $term_id );

			if ( $value )
				$wp->query_vars[$key] = $key === 'posts_per_page' ? intval( $value ) : sanitize_key( $value );
		}

		$exclude_posts = array_values( array_filter( wp_parse_id_list( $this->loop_setting( 'exclude_posts', '', $taxonomy, $term_id ) ) ) );

		if ( $exclude_posts )
			$wp->query_vars['post__not_in'] = array_values( array_unique( array_merge( $wp->query_vars['post__not_in'] ?? array(), $exclude_posts ) ) );

		$exclude_terms = array();

		foreach ( array_filter( wp_parse_id_list( $this->loop_setting( 'exclude_terms', '', $taxonomy, $term_id ) ) ) as $id ) {
			$term = get_term( $id );

			if ( $term && ! is_wp_error( $term ) )
				$exclude_terms[$term->taxonomy][] = $id;
		}

		foreach ( $exclude_terms as $exclude_taxonomy => $ids )
			$wp->query_vars['tax_query'][] = array(
				'taxonomy' => $exclude_taxonomy,
				'terms' => $ids,
				'operator' => 'NOT IN'
			);

		$loop_type = $this->loop_setting( 'loop_type', '', $taxonomy, $term_id );

		if ( $loop_type === 'month' ) {
			$wp->query_vars['orderby'] = 'date';
			$wp->query_vars['ignore_sticky_posts'] = 1;
		}
	}

	/**
	 * Resolve one Loop setting through the post type, taxonomy and term tiers.
	 *
	 * @since 6.0
	 */

	protected function loop_setting( $key, $default, $taxonomy = '', $term_id = 0 ) {
		$exclude_setting = $key === 'exclude_posts' || $key === 'exclude_terms';

		return md_value( array(
			'key' => array( 'loop', $key ),
			'default' => $default,
			'term' => $term_id,
			'taxonomy' => $taxonomy,
			'post_type' => $this->post_type,
			'inherit_post_type' => ! ( $taxonomy && $exclude_setting )
		) );
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

		$sticky = $query->get( 'md_sticky' );

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

		if ( empty( $register['admin_page'] ) )
			return;

		if ( is_admin() ) {
			$href = ! empty( $screen['page'] ) && $screen['page'] === $id ? get_post_type_archive_link( $post_type ) : false;

			if ( $href )
				$admin_bar->add_menu( array(
					'id' => "{$this->_prefix}-archives-link",
					'title' => sprintf( __( 'View %s', 'md' ), $label ),
					'href' => esc_url( $href ),
					'meta' => array( 'title' => sprintf( __( 'View %s', 'md' ), $label ), 'target' => '_blank' )
				) );
		}
		elseif ( ( is_post_type_archive( $post_type ) || ( $post_type === 'post' && is_home() ) ) && current_user_can( $register['admin_page']['capability'] ?? 'manage_options' ) )
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
		$page = sanitize_key( $_GET['page'] ?? '' );
		$tab = sanitize_key( $_GET['md_tab'] ?? '' );
		$page_types = $page ? apply_filters( 'md_admin_groups', array() ) : array();

		if ( ! empty( $page_types[$page] ) ) {
			$group = md_clean_id( $page );
			$tax_groups = $tab ? apply_filters( 'md_taxonomy_groups', array() ) : array();

			if ( $group !== $this->_clean_id )
				$prefix = "{$this->_option}_{$group}_{$this->_clean_id}";

			if ( ! empty( $tax_groups[$page][$tab] ) )
				$prefix .= "_{$tab}";
		}

		return $prefix;
	}

	/**
	 * Show this profile group to users its capability lets in: admins, or
	 * anyone who can edit the user when the group has edit_user.
	 *
	 * @since 6.2.4
	 */

	public function _user_meta( $user ) {
		if ( current_user_can( md_meta_group_capability( 'user', $this->register['user_meta'] ), $user->ID ) )
			$this->user_meta( $user );
	}

	/**
	 * Build a group host's registration again, now that every group child
	 * has added its fields.
	 *
	 * @since 6.1
	 */

	public function _register_groups() {
		$this->register = $this->register();
	}

	/**
	 * If this instance creates new admin pages, tabs, meta, or terms
	 * add it to the full collections below.
	 *
	 * @since 5.0
	 */

	public function _register( $data ) {
		$types = array(
			'admin_page' => 'admin_pages',
			'meta_box' => 'meta_boxes',
			'term' => 'terms',
			'user_meta' => 'user_meta'
		);

		foreach ( $types as $type => $collection )
			if ( isset( $this->register[$type] ) ) {
				$data[$collection][$this->_clean_id] = $this->register[$type];
				$data[$collection][$this->_clean_id]['id'] = $this->_id;
			}

		if ( isset( $this->register['admin_page'] ) ) {
			$data['admin_pages'][$this->_clean_id]['_option'] = $this->_option;
			$data['admin_pages'][$this->_clean_id]['_source'] = $this->_source();
		}

		return $data;
	}

	/**
	 * The Drop-in that registered this instance, by its folder, or theme.
	 * Import and export name it beside each settings group.
	 *
	 * @since 6.2
	 */

	public function _source() {
		if ( isset( $this->_source ) )
			return $this->_source;

		$reflection = new ReflectionClass( $this );
		$file = wp_normalize_path( $reflection->getFileName() );
		$dropins = trailingslashit( wp_normalize_path( MD_INSTALLED_DROPINS ) );
		$folders = explode( '/', substr( $file, strlen( $dropins ) ) );
		$this->_source = strpos( $file, $dropins ) === 0 ? $folders[0] : 'theme';

		return $this->_source;
	}

	/**
	 * Add Collections declared by this API extension to the shared registry.
	 *
	 * @since 6.0
	 */

	public function _collections( $collections ) {
		return array_replace( $collections, (array) $this->collections() );
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
	 * Taxonomy settings apply to every term in a post type's taxonomy. They work
	 * like admin settings groups, but attach to a post type; a shared taxonomy's
	 * attach only to its home post type.
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
				if ( in_array( $tax, $taxonomies, true ) && ( ! md_is_shared_taxonomy( $tax ) || md_taxonomy_post_type( $tax ) === $post_type ) )
					$groups[$page_slug][$tax] = true;
		}

		return $groups;
	}

	/**
	 * Wire admin page hooks from register['admin_page']. has_groups hosts groups
	 * (see blog.php), group creates one, child_of adds fields to one and position
	 * sets the hook priority.
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

		$priority = ! empty( $register['position'] ) ? $register['position'] : 10;
		$group_callback = method_exists( $this, 'admin_group' ) ? 'admin_group' : $callback;

		if ( isset( $register['group'] ) ) {
			foreach ( (array) $register['group'] as $field )
				add_filter( "md_filter_admin_page_$field", array( $this, '_admin_fields' ) );

			add_action( "md_admin_page_{$register['group']}", array( $this, $group_callback ), $priority );
		}
		elseif ( isset( $register['child_of'] ) )
			foreach ( (array) $register['child_of'] as $group ) {
				add_filter( "md_admin_page_{$group}_fields", function( $fields ) use ( $group_callback ) {
					$fields[$this->_clean_id] = array(
						'name' => $this->name,
						'callback' => array( $this, $group_callback )
					);
					return $fields;
				} );
				add_filter( "md_filter_admin_page_$group", array( $this, '_admin_fields' ) );
			}

		add_action( "{$this->_id}_admin_page", array( $this, $callback ) );
	}

	/**
	 * Register a meta box, or add post meta fields to a group with child_of.
	 * Useful for single settings related to global admin settings.
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
				add_filter( "md_post_meta_{$group}_fields", function( $fields ) use( $group_callback, $register ) {
					$capability = ! empty( $register['edit_post'] ) ? 'edit_post' : 'manage_options';
					if ( ! current_user_can( $capability, get_the_ID() ) )
						return $fields;

					$fields[$this->_clean_id] = array(
						'name' => ! empty( $register['name'] ) ? $register['name'] : $this->name,
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
	 * Register term meta for category and tag screens. child_of adds fields to a
	 * group; position sets the hook priority (default 100).
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
