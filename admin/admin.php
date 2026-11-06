<?php
/**
 * Create admin pages, meta boxes, terms, register settings,
 * load custom fields API and run other administrative actions.
 *
 * @since 5.0
 */

class md_admin {

	public $save;
	public $settings;
	public $license;
	public $_option = 'marketers_delight';

	/**
	 * Call this method to instantiate class.
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
		include_once 'dropins/functions.php';
		include_once 'dashboard.php';
		include_once 'icons.php';
		include_once 'dropins/dropins.php';
		include_once 'dropins/actions.php';
		include_once 'page-settings.php';
		include_once 'typography.php';
		include_once 'design.php';
		include_once 'import-export.php';
	}

	/**
	 * Run admin action hooks.
	 *
	 * @since 4.7
	 */

	public function actions() {
		include_once 'save-settings.php';

		$this->save = new md_save;
		$this->settings = new md_save_settings( $this->save );
		$this->license = new md_license;

		add_action( 'wp_update_nav_menu', 'md_compile' );

		// Admin pages
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_filter( 'parent_file', array( $this, 'parent_file' ) );
		add_action( 'admin_page_access_denied', array( $this, 'redirect_moved_pages' ) );
		add_action( 'admin_init', array( $this, 'redirect_moved_pages' ) );
		add_filter( 'post_row_actions', array( $this, 'admin_row'), 10, 2 );
		add_filter( 'page_row_actions', array( $this, 'admin_row'), 10, 2 );

		// Editors
		add_action( 'edit_form_after_editor', array( $this, 'nonce' ) );
		add_action( 'block_editor_meta_box_hidden_fields', array( $this, 'nonce' ) );

		// Meta Boxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
		add_action( 'save_post', array( $this->settings, 'meta_save' ), 10, 2 );
		add_filter( 'is_protected_meta', array( $this, 'hide_meta_keys' ), 10, 2 );

		// Terms
		add_action( 'init', array( $this, 'add_terms' ) );

		// User meta
		add_action( 'show_user_profile', array( $this, 'user_meta' ) );
		add_action( 'edit_user_profile', array( $this, 'user_meta' ) );
		add_action( 'profile_update', array( $this->settings, 'user_meta_save' ), 10, 2 );

		// Enqueue
		if ( ! is_customize_preview() )
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		if ( function_exists( 'render_block' ) )
			add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_editor_layout' ) );

		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
		add_filter( 'tiny_mce_before_init', array( $this, 'classic_editor_layout' ) );

		// Upgrader hooks
		add_action( 'upgrader_process_complete', array( $this->license, 'delete_theme_update' ), 10, 2 );
		add_action( 'load-themes.php', array( $this, 'load_themes_screen' ) );

		// Actions + requests
		add_action( 'wp_ajax_md_action', array( $this->license, 'request' ) );
	}

	/**
	 * Keep the Theme menu open on MD screens that have no menu item of their
	 * own (tabs like Design or Popups), with their parent item current.
	 *
	 * @since 6.2
	 */

	public function parent_file( $parent_file ) {
		global $plugin_page, $submenu_file;

		$page = $this->menu_tab_parent( $plugin_page );

		if ( ! $page )
			return $parent_file;

		$submenu_file = $page;

		return 'md_settings';
	}

	/**
	 * The Theme menu item an MD tab screen belongs to, ex: md_optins for
	 * Popups. Empty for screens that aren't hidden MD tabs.
	 *
	 * @since 6.2
	 */

	private function menu_tab_parent( $plugin_page ) {
		if ( ! $plugin_page || strpos( $plugin_page, 'md_' ) !== 0 )
			return '';

		$admin_pages = md_register( 'admin_pages' );
		$id = substr( $plugin_page, 3 );

		if ( empty( $admin_pages[$id] ) || ! empty( $admin_pages[$id]['parent_slug'] ) || ! empty( $admin_pages[$id]['toplevel'] ) )
			return '';

		return ! empty( $admin_pages[$id]['parent'] ) ? $admin_pages[$id]['parent'] : 'md_settings';
	}

	/**
	 * Old addresses of the screens that moved to the Theme menu in 6.2
	 * (Appearance and Tools) go to their new ones, ex: bookmarks and docs
	 * links to themes.php?page=md_settings.
	 *
	 * @since 6.2
	 */

	public function redirect_moved_pages() {
		global $pagenow;

		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';

		if ( ! in_array( $pagenow, array( 'themes.php', 'tools.php' ), true ) || ! in_array( $page, array( 'md_settings', 'md_dropins', 'md_integrations' ), true ) )
			return;

		wp_safe_redirect( add_query_arg( wp_unslash( $_GET ), self_admin_url( 'admin.php' ) ) );

		exit;
	}

	/**
	 * Load single MD nonce to post editors.
	 *
	 * @since 4.7
	 */

	public function nonce() {
		wp_nonce_field( "{$this->_option}_nonce", "{$this->_option}_nonce" );
	}

	/**
	 * Adds new admin page to admin panel.
	 *
	 * @since 4.0
	 */

	public function add_menu() {
		foreach ( md_register( 'admin_pages' ) as $admin_page => $fields ) {
			if ( ! isset( $fields['name'] ) )
				continue;

			$parent_slug = isset( $fields['parent_slug'] ) ? $fields['parent_slug'] : '';
			$capability = isset( $fields['capability'] ) ? $fields['capability'] : 'manage_options';
			$callback = array( $this, 'admin_page' );
			$menu_slug = 'md_' . ( isset( $fields['menu_slug'] ) ? $fields['menu_slug'] : $admin_page );
			$icon = isset( $fields['icon'] ) ? $fields['icon'] : '';
			$position = isset( $fields['menu_position'] ) ? $fields['menu_position'] : ( isset( $fields['position'] ) ? $fields['position'] : null );
			$menu_title = isset( $fields['menu_title'] ) ? $fields['menu_title'] : $fields['name'];

			if ( ! empty( $fields['toplevel'] ) ) {
				$hook = add_menu_page( $fields['name'], $menu_title, $capability, $menu_slug, $callback, $icon, $position );

				// The top-level page is also its menu's first item, named by its tab

				if ( ! empty( $fields['tab_name'] ) )
					add_submenu_page( $menu_slug, $fields['tab_name'], $fields['tab_name'], $capability, $menu_slug, $callback, 0 );
			}
			else {
				$sub_page_title = ! empty( $fields['tab_name'] ) ? $fields['tab_name'] : $fields['name'];
				$hook = add_submenu_page( $parent_slug, $sub_page_title, $menu_title, $capability, $menu_slug, $callback, $position );
			}

			if ( $hook )
				add_action( "load-$hook", array( $this->settings, 'load' ) );

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
		$style = 'admin/admin.css';
		$script = 'admin/js/admin.js';

		wp_enqueue_style( 'marketers-delight', MD_URL . $style, array(), md_ver( $style ) );
		wp_enqueue_script( 'marketers-delight', MD_URL . $script, array( 'jquery', 'md-sortable', 'md-color' ), md_ver( $script ), true );

		$is_block_editor = $screen->base === 'post' && (bool) $screen->is_block_editor();
		$sticky = $is_block_editor && in_array( $screen->post_type, md_sticky_post_types() ) && current_user_can( get_post_type_object( $screen->post_type )->cap->edit_others_posts );

		if ( $screen->base === 'post' && ( $sticky || in_array( $screen->post_type, md_post_type_meta() ) ) ) {
			$post_id = get_the_ID();
			$editor = 'admin/js/editors.js';
			$editor_deps = $is_block_editor ? array( 'jquery', 'wp-plugins', 'wp-element', 'wp-components', 'wp-data', 'wp-editor' ) : array( 'jquery' );

			wp_enqueue_script( 'md-editors', MD_URL . $editor, $editor_deps, md_ver( $editor ), true );
			wp_localize_script( 'md-editors', 'mdEditorContext', array(
				'isBlockEditor' => $is_block_editor,
				'contentStyles' => array_keys( md_filter_loop_styles() ),
				'inheritedContentStyle' => empty( $post_id ) ? 'box' : $this->editor_content_style( $post_id, true ),
				'sticky' => $sticky,
				'stickyLabel' => __( 'Sticky', 'md' ),
				'stickyHelp' => sprintf( __( 'Keep this post at the top of %s.', 'md' ), get_post_type_object( $screen->post_type )->labels->name )
			) );
		}

		$vars = array(
			'nonce' => wp_create_nonce( "{$this->_option}_nonce" ),
			'license_request_error' => __( 'Could not connect. Please try again.', 'md' ),
			'copied' => __( 'Copied!', 'md' ),
			'preview_image' => __( 'Preview image', 'md' ),
			'save' => array(
				'clean' => __( 'All changes saved', 'md' ),
				'dirty' => __( 'Unsaved changes', 'md' ),
				'saving' => __( 'Saving…', 'md' ),
				'saved' => __( 'Saved', 'md' ),
				'error' => __( 'Couldn\'t save: %s', 'md' ),
				'failed' => __( 'check your connection and try again', 'md' ),
				'button' => __( 'Save', 'md' ),
				'saving_button' => __( 'Saving…', 'md' )
			),
			'colors' => md_localize_scripts( array( 'colors' ) )
		);

		if ( $screen->base == 'edit' && in_array( $screen->post_type, md_sticky_post_types() ) ) {
			$vars['label_text'] = sprintf( __( 'Keep at the top of %s', 'md' ), get_post_type_object( $screen->post_type )->labels->name );

			wp_add_inline_script( 'marketers-delight', 'MD.stickyPostTypes();' );
		}

		wp_localize_script( 'marketers-delight', 'MDJS', $vars );
		wp_register_script( 'md-color', MD_URL . 'admin/js/jscolor.js', array(), md_ver( 'admin/js/jscolor.js' ), true );
		wp_register_script( 'md-sortable', MD_URL . 'admin/js/sortable.js', array(), md_ver( 'admin/js/sortable.js' ), true );
	}

	/**
	 * Add the initial layout class before the Block Editor iframe renders.
	 *
	 * @since 6.0
	 */

	public function enqueue_block_editor_layout() {
		if ( ! is_admin() || wp_should_load_block_editor_scripts_and_styles() )
			return;

		$post_id = get_the_ID();

		if ( empty( $post_id ) )
			return;

		$layout = md_has_sidebar( array(
			'post_id' => $post_id,
			'post_type' => get_post_type( $post_id ),
			'page' => 'single'
		) ) ? 'compact' : 'expanded';
		$style = 'is-' . $this->editor_content_style( $post_id ) . '-style';
		$script = 'document.documentElement.classList.add(' . wp_json_encode( $layout ) . ', ' . wp_json_encode( $style );

		if ( md_post_meta( array( 'layout', 'content', 'builder' ), $post_id ) )
			$script .= ', "md-builder"';

		$script .= ');';

		wp_register_script( 'md-block-editor-layout', false, array(), false );
		wp_enqueue_script( 'md-block-editor-layout' );
		wp_add_inline_script(
			'md-block-editor-layout',
			$script,
			'before'
		);
	}

	/**
	 * Resolve the active content style for an editor post.
	 *
	 * @since 6.0
	 */

	private function editor_content_style( $post_id, $exclude_single = false ) {
		if ( ! $exclude_single && md_post_meta( array( 'layout', 'content', 'builder' ), $post_id ) )
			return 'plain';

		return md_loop_style( array(
			'post_id' => $post_id,
			'post_type' => get_post_type( $post_id ),
			'exclude_single' => $exclude_single
		) );
	}

	/**
	 * Add the active content style class to the Classic Editor body.
	 *
	 * @since 6.0
	 */

	public function classic_editor_layout( $settings ) {
		$post_id = get_the_ID();

		if ( empty( $post_id ) )
			return $settings;

		$style = 'is-' . $this->editor_content_style( $post_id ) . '-style';
		$settings['body_class'] = trim( ( $settings['body_class'] ?? '' ) . " $style" );

		return $settings;
	}

	/**
	 * Add body class to the admin panel.
	 *
	 * @since 6.0
	 */

	public function admin_body_class( $classes ) {
		$post_id = get_the_ID();
		$builder = md_post_meta( array( 'layout', 'content', 'builder' ), $post_id );

		if ( ! empty( $post_id ) )
			$classes .= ' is-' . $this->editor_content_style( $post_id ) . '-style';

		if ( ! empty( $builder ) )
			$classes .= ' md-builder';

		if ( md_has_sidebar( array( 'post_id' => $post_id ) ) )
			$classes .= ' compact';
		else
			$classes .= ' expanded';

		return $classes;
	}

	/**
	 * Main form wrapper for admin pages.
	 *
	 * @since 5.0
	 */

	public function admin_page() {
		$admin_tabs = $admin_order = array();
		$admin_pages = md_register( 'admin_pages' );
		$page = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
		$page_id = md_clean_id( $page );
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';
		$hook = ! empty( $tab ) ? $tab : $page;

		ob_start();
		include md_template( 'admin/admin', true );

		echo md_save_settings::close_form( ob_get_clean() );
	}

	/**
	 * Add Post ID to admin column rows.
	 *
	 * @since 5.0
	 */

    public function admin_row( $actions, $post ) {
	    $user = wp_get_current_user();

	    if ( in_array( $user->roles[0], array( 'administrator', 'editor' ) ) )
			$actions['md_post_id'] = '<span class="md-action-row-label">ID: ' . get_the_ID() . '</span>';

		return $actions;
    }

	/**
	 * Adds meta boxes to interface.
	 *
	 * @since 4.0
	 */

	public function add_meta_boxes( $post_type = null, $post = null ) {
		$screen = get_current_screen();
		$blog_id = get_option( 'page_for_posts' );
		$post_id = isset( $_GET['post'] ) ? esc_attr( $_GET['post'] ) : '';
		$edit_id = is_object( $post ) ? $post->ID : get_the_ID();

		foreach ( md_register( 'meta_boxes' ) as $meta_box => $fields ) {
			$post_types = isset( $fields['post_type'] ) ? $fields['post_type'] : md_post_type_meta();
			$context = isset( $fields['context'] ) ? $fields['context'] : 'normal';
			$priority = isset( $fields['priority'] ) ? $fields['priority'] : 'default';
			$callback = isset( $fields['callback'] ) ? $fields['callback'] : '';

			foreach ( $post_types as $post_type ) {
				$capability = ! empty( $fields['edit_post'] ) ? 'edit_post' : 'manage_options';
				if (
					! isset( $fields['name'] ) || isset( $fields['hide'] ) || isset( $fields['child_of'] ) ||
					! current_user_can( $capability, $edit_id ) ||
					( ! empty( $fields['is_group'] ) && ! apply_filters( "md_post_meta_{$meta_box}_fields", array() ) ) ||
					( isset( $fields['show_on_block_editor'] ) && ! ( method_exists( $screen, 'is_block_editor' ) && $screen->is_block_editor() ) ) ||
					( isset( $fields['post_id'] ) && $fields['post_id'] != $post_id ) ||
					( $blog_id == $post_id )
				)
					continue;

				add_meta_box( $fields['id'], $fields['name'], array( $this, 'meta_box' ), $post_type, $context, $priority, array(
					'function_callback' => $callback
				) );
			}
		}
	}

	/**
	 * Default meta box callback method.
	 *
	 * @since 4.0
	 */

	public function meta_box( $post, $meta_box ) {
		echo '<div class="md-meta-box md">';

		if ( ! empty( $meta_box['args']['function_callback'] ) )
			call_user_func( $meta_box['args']['function_callback'] );
		else
			do_action( $meta_box['id'] . '_meta_box' );

		echo '</div>';
	}

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
			add_action( "edited_{$term}", array( $this->settings, 'term_save' ), 10, 2 );
		}
	}

	/**
	 * Build callback to load custom options on taxonomy screens.
	 *
	 * @since 4.3.5
	 */

	public function term( $term ) {
		if ( ! current_user_can( 'manage_options' ) )
			return;

		$this->nonce();

		echo '<tr class="form-field term-md-wrap md">'.
			 '<td colspan="2">';

		do_action( "md_{$term->taxonomy}_{$term->term_id}" );

		echo '</td>'.
			 '</tr>';
	}

	/**
	 * Build callback to load custom options on taxonomy screens.
	 *
	 * @since 4.3.5
	 */

	public function user_meta( $user_meta ) {
		if ( ! current_user_can( 'manage_options' ) )
			return;

		$this->nonce();
		echo '<div class="md md-user-meta">';
		do_action( 'md_user_meta_fields', $user_meta );
		echo '</div>';
	}

	/**
	 * Show MD update notification when necessary.
	 *
	 * @since 4.7
	 */

	public function load_themes_screen() {
		add_action( 'admin_notices', array( $this, 'update_nag' ) );
	}

	/**
	 * Display the update notifications
	 *
	 * @since 5.4
	 */

	public function update_nag() {
		$license_status = md_license_setting( 'status' );
		$theme = md_license_setting( array( 'updates', 'theme' ) );
		$new_version = md_license_setting( array( 'updates', 'theme', 'new_version' ) );

		if ( $license_status !== 'valid' || empty( $theme ) || version_compare( MD_VERSION, $new_version, '>=' ) )
			return;

		$strings = array(
			'update-notice' => esc_js( __( "Updating MD will lose any customizations you\'ve made to the core files. Be sure to backup any changes to a Child Theme before updating. 'Cancel' to stop, 'OK' to update.", 'md' ) ),
			'update-available' => '<strong>%1$s %2$s</strong> is available. <a href="%3$s" onclick="document.querySelector(this.hash).showModal();return false;" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>'
		);

		$theme_slug = $this->license->license( 'theme_slug' );
		$theme_name = str_replace( ' 4', '', $theme['name'] );

		$update_url = wp_nonce_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( $theme_slug ), 'upgrade-theme_' . $theme_slug );
		$update_onclick = ' onclick="if ( confirm(\'' . esc_js( $strings['update-notice'] ) . '\') ) {return true;}return false;"';

	?>
		<div id="update-nag" class="update-message notice inline notice-warning">
			<?php printf(
				$strings['update-available'],
				$theme_name,
				$new_version,
				'#' . esc_attr( $theme_slug . '_changelog' ),
				$theme_name,
				$update_url,
				$update_onclick
			); ?>
		</div>
		<dialog id="<?php echo esc_attr( $theme_slug . '_changelog' ); ?>" style="width:90%;max-width:640px;">
			<h1><?php echo sprintf( __( 'Ready to update %s %2s?', 'md' ), $theme_name, $new_version ); ?></h1>
			<p><?php echo __( 'Here are some resources to help you:', 'md' ); ?></p>
			<h3>- <a href="https://marketersdelight.com/changelog/" target="_blank"><?php echo __( 'Read the changelog', 'md' ); ?></a></h3>
			<h3>- <a href="https://marketersdelight.com/stream/" target="_blank"><?php echo __( 'See what\'s new in MD', 'md' ); ?></a></h3>
			<h3>- <a href="https://kolakube.com/community/" target="_blank"><?php echo __( 'Get help at support', 'md' ); ?></a></h3>
			<p><?php echo __( 'When in doubt, make a backup your website before proceeding.', 'md' ); ?></p>
			<form method="dialog"><button class="button"><?php echo __( 'Close', 'md' ); ?></button></form>
		</dialog>
	<?php }

}

$md_admin = new md_admin;
$md_admin->init();
