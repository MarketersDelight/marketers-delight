<?php
/**
 * Create admin pages, meta boxes, terms, register settings,
 * load custom fields API and run other administrative actions.
 *
 * @since 5.0
 */

class md_admin {

	/**
	 * Run class methods on instantiation.
	 *
	 * @since 5.0
	 */

	public function init() {
		$this->actions();
		$this->includes();
	}

	/**
	 * Include admin files.
	 *
	 * @since 4.7
	 */

	public function includes() {
		require_once( 'settings/dashboard/dashboard.php' );
		require_once( 'design/design.php' );
		require_once( 'settings/dropins/dropins.php' );
		require_once( 'settings/integrations/integrations.php' );
		require_once( 'updater/updater.php' );
		if ( md_setting( 'version' ) < '5.0' )
			require_once( 'updater/upgrade/upgrade.php' );
	}

	/**
	 * Run admin action hooks.
	 *
	 * @since 4.7
	 */

	public function actions() {
		$this->sanitize = new md_sanitize;
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
		// Admin pages
		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_filter( 'post_row_actions',array( $this, 'admin_row'), 10, 2 );
		add_filter( 'page_row_actions',array( $this, 'admin_row'), 10, 2 );
		// Editors
		add_action( 'edit_form_after_editor', array( $this, 'nonce' ) );
		add_action( 'block_editor_meta_box_hidden_fields', array( $this, 'nonce' ) );
		// Meta Boxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this->sanitize, 'meta_save' ), 10, 2 );
		add_filter( 'is_protected_meta', array( $this, 'hide_meta_keys' ), 10, 2 );
		// Terms
		add_action( 'init', array( $this, 'add_terms' ) );
		// Scripts
		if ( ! is_customize_preview() )
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Create sanitize method to run various options set through.
	 *
	 * @since 4.0
	 */

	public function register_setting() {
		register_setting( 'marketers_delight', 'marketers_delight', array( $this->sanitize, 'admin_save' ) );
	}

	/**
	 * Adds new admin page to admin panel.
	 *
	 * @since 4.0
	 */

	public function add_menu() {
		add_submenu_page( 'md_settings', __( 'Marketers Delight', 'md' ), __( 'Settings', 'md' ), 'edit_theme_options', 'admin.php?page=md_settings' );

		foreach ( md_register( 'admin_pages' ) as $admin_page => $fields ) {
			if ( ! isset( $fields['name'] ) )
				continue;
			if ( ! isset( $fields['parent'] ) )
				$parent_slug = isset( $fields['parent_slug'] ) ? $fields['parent_slug'] : 'md_settings';
			else
				$parent_slug = null;
			$capability = isset( $fields['capability'] ) ? $fields['capability'] : 'manage_options';
			$callback = array( $this, 'admin_page' );
			$menu_slug = 'md_' . ( isset( $fields['menu_slug'] ) ? $fields['menu_slug'] : $admin_page );
			$icon = isset( $fields['icon'] ) ? $fields['icon'] : '';
			$position = isset( $fields['position'] ) ? $fields['position'] : 30;
			$menu_title = isset( $fields['menu_title'] ) ? $fields['menu_title'] : $fields['name'];

			if ( ! empty( $fields['toplevel'] ) )
				add_menu_page( $fields['name'], $menu_title, $capability, $menu_slug, $callback, $icon, $position );
			else
				add_submenu_page( $parent_slug, $fields['name'], $fields['name'], $capability, $menu_slug, $callback );

			if ( ! empty( $fields['hide_menu'] ) )
				remove_submenu_page( $parent_slug, $menu_slug );
		}
	}

	/**
	 * Loads all scripts and styles throughout WP admin.
	 *
	 * @since 4.0
	 */

	public function enqueue() {
		$screen = get_current_screen();
		$style = 'lib/admin/css/admin.css';
		$script = 'lib/admin/js/admin.js';
		$vars['user_id'] = get_current_user_id();

		wp_enqueue_style( 'marketers-delight', MD_URL . $style, array(), md_ver( $style ) );
		wp_enqueue_script( 'marketers-delight', MD_URL . $script, array( 'jquery', 'md-sortable', 'wp-color-picker', 'md-alpha-color' ), md_ver( $script ), true );

		if ( in_array( $screen->base, array( 'edit', 'post' ) ) && ! in_array( $screen->post_type, array( 'post', 'page' ) ) ) {
			if ( $screen->base == 'post' )
				$vars = array_merge( $vars, array(
					'screen' => 'post',
					'is_sticky' => is_sticky() ? true : false,
					'checked_attribute' => checked( is_sticky(), true, false ),
					'label_text' => __( 'Stick this post to the front page','cpt_sticky' ),
					'sticky_visibility_text' => __( 'Public, Sticky','cpt_sticky' )
				) );
			else
				$vars = array_merge( $vars, array(
					'screen' => 'edit',
					'post_type' => $screen->post_type,
					'status_label_text' => __( 'Status' ),
					'label_text' => __( 'Make this post sticky','cpt_sticky' ),
					'sticky_text' => __( 'Sticky','cpt_sticky' ),
				) );
			wp_add_inline_script( 'marketers-delight', 'MD.stickyPostTypes();' );
		}

		wp_localize_script( 'marketers-delight', 'MDJS', $vars );
		wp_enqueue_script( 'md-sortable', MD_URL . 'lib/admin/js/sortable.js', array(), '', true );
		wp_register_script( 'md-alpha-color', MD_URL . 'lib/admin/js/alpha-color.js', array( 'wp-color-picker' ), '', true );
	}

	/**
	 * Main form wrapper for admin pages.
	 *
	 * @since 5.0
	 */

	public function admin_page() {
		$admin_tabs = array();
		$admin_pages = md_register( 'admin_pages' );
		$page = isset( $_GET['page'] ) ? $_GET['page'] : '';
		$page_id = md_clean_id( $page );
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';
		$hook = ! empty( $tab ) ? $tab : $page;
		include( 'settings/admin-page.php' );
	}

	/**
	 * Add Post ID to admin column rows.
	 *
	 * @since 5.0
	 */

    public function admin_row( $actions, $post ){
		$actions['md_post_id'] = '<span class="md-action-row-label">ID: ' . get_the_ID() . '</span>';
		return $actions;
    }

	/**
	 * Adds meta boxes to interface.
	 *
	 * @since 4.0
	 */

	public function add_meta_boxes() {
		foreach ( md_register( 'meta_boxes' ) as $meta_box => $fields ) {
			$post_types = isset( $fields['post_type'] ) ? $fields['post_type'] : md_post_type_meta();
			$context = isset( $fields['context'] ) ? $fields['context'] : 'normal';
			$priority = isset( $fields['priority'] ) ? $fields['priority'] : 'default';
			$callback = isset( $fields['callback'] ) ? $fields['callback'] : '';
			foreach ( $post_types as $post_type )
				add_meta_box( $fields['id'], $fields['name'], array( $this, 'meta_box' ), $post_type, $context, $priority, array(
					'function_callback' => $callback
				) );
		}
	}

	/**
	 * Default meta box callback method.
	 *
	 * @since 4.0
	 */

	public function meta_box( $post, $meta_box ) { ?>
		<div class="md-meta-box md">
			<?php if ( ! empty( $meta_box['args']['function_callback'] ) ) : ?>
				<?php call_user_func( $meta_box['args']['function_callback'] ); ?>
			<?php else : ?>
				<?php do_action( $meta_box['id'] . '_meta_box' ); ?>
			<?php endif; ?>
		</div>
	<?php }

	/**
	 * So no meta values show up in the Custom Fields meta
	 * box, loop through all fields to hide them.
	 *
	 * @since 4.0
	 */

	public function hide_meta_keys( $protected, $meta_key ) {
		if ( 'marketers_delight' == $meta_key )
			return true;
		return $protected;
	}

	/**
	 * Load taxonomy data to init.
	 *
	 * @since 4.3.5
	 */

	public function add_terms() {
		foreach ( md_taxonomy_meta() as $term ) {
			add_action( "{$term}_edit_form_fields", array( $this, 'term' ) );
			add_action( "edited_{$term}", array( $this->sanitize, 'term_save' ), 10, 2 );
		}
	}

	/**
	 * Build callback to load custom options on taxonomy screens.
	 *
	 * @since 4.3.5
	 */

	public function term( $term ) { ?>
		<?php $this->nonce(); ?>
		<tr class="form-field term-md-wrap md">
			<td colspan="2">
				<?php do_action( "md_{$term->taxonomy}_{$term->term_id}" ); ?>
			</td>
		</tr>
	<?php }

	/**
	 * Add classes to the admin <body> tag.
	 *
	 * #since 4.9
	 */

	public function admin_body_class( $classes ) {
		$screen = get_current_screen();
		if ( $screen->base == 'post' ) {
			$site_add = md_setting( array( 'content', 'sidebar', 'single' ) );
			$single_remove = md_post_meta( array( 'layout', 'sidebar', 'remove' ) );
			$single_add = md_post_meta( array( 'layout', 'sidebar', 'add' ) );
			if ( ( $site_add && $single_remove ) || ( ! $site_add && ! $single_add ) )
				$classes .= 'md-editor-full';
		}
		return $classes;
	}

	/**
	 * Load single MD nonce to post editors.
	 *
	 * @since 4.7
	 */

	public function nonce() {
		wp_nonce_field( 'marketers_delight_nonce', 'marketers_delight_nonce' );
	}

}

$md_admin = new md_admin;
$md_admin->init();