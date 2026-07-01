<?php
/**
 * Create admin pages, meta boxes, terms, register settings,
 * load custom fields API and run other administrative actions.
 *
 * @since 5.0
 */

class md_admin {

	public $sanitize;
	public $requests;
	public $files;
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
		include_once 'admin_functions.php';
		include_once 'dashboard.php';
		include_once 'icons.php';
		include_once 'integrations.php';
		include_once 'dropins.php';
		include_once 'page-settings.php';
		include_once 'page-title.php';
		include_once 'typography.php';
		include_once 'design.php';
		include_once 'layout.php';
		include_once 'loop.php';
		include_once 'byline.php';
		include_once 'logo.php';
		include_once 'header.php';
		include_once 'upgrade/dropin-upgrader.php';
	}

	/**
	 * Run admin action hooks.
	 *
	 * @since 4.7
	 */

	public function actions() {
		$this->sanitize = new md_sanitize;
		$this->requests = new md_requests;
		$this->files = new md_files;

		add_action( 'wp_update_nav_menu', 'md_compile' );

		// Admin pages
		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_filter( 'post_row_actions', array( $this, 'admin_row'), 10, 2 );
		add_filter( 'page_row_actions', array( $this, 'admin_row'), 10, 2 );

		// Editors
		add_action( 'edit_form_after_editor', array( $this, 'nonce' ) );
		add_action( 'block_editor_meta_box_hidden_fields', array( $this, 'nonce' ) );

		// Meta Boxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this->sanitize, 'meta_save' ), 10, 2 );
		add_filter( 'is_protected_meta', array( $this, 'hide_meta_keys' ), 10, 2 );

		// Terms
		add_action( 'init', array( $this, 'add_terms' ) );

		// User meta
		add_action( 'show_user_profile', array( $this, 'user_meta' ) );
		add_action( 'edit_user_profile', array( $this, 'user_meta' ) );
		add_action( 'profile_update', array( $this->sanitize, 'user_meta_save' ), 10, 2 );

		// Enqueue
		if ( ! is_customize_preview() )
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

		// Upgrader hooks
		add_filter( 'pre_set_site_transient_update_themes', array( $this->requests, 'set_theme_update' ) );
		add_filter( 'delete_site_transient_update_themes', array( $this->requests, 'delete_theme_update' ) );
		add_action( 'load-themes.php', array( $this, 'load_themes_screen' ) );
		add_action( 'update-custom_update-md-dropins', array( $this->requests, 'update_dropin' ) );
		add_action( 'update-custom_upload-md-dropin', array( $this->requests, 'upload_dropin' ) );
		add_action( 'update-custom_upload-dropin-cancel-overwrite', array( $this->requests, 'cancel_dropin_overwrite' ) );

		// Actions + requests
		add_action( 'wp_ajax_md_action', array( $this->requests, 'request' ) );
		add_action( 'wp_ajax_md_file', array( $this->files, 'file_action' ) );
	}

	/**
	 * Create sanitize method to run various options set through.
	 *
	 * @since 4.0
	 */

	public function register_setting() {
		register_setting( $this->_option, $this->_option, array( $this->sanitize, 'admin_save' ) );

		// Register settings for custom option keys

		$custom = array();

		foreach ( md_register( 'admin_pages' ) as $fields ) {
			$option = isset( $fields['_option'] ) ? $fields['_option'] : $this->_option;

			if ( $option === $this->_option || isset( $custom[$option] ) )
				continue;

			$custom[$option] = true;

			register_setting( $option, $option, array( $this->sanitize, 'admin_save_custom' ) );
		}
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

			$parent_slug = isset( $fields['parent_slug'] ) ? $fields['parent_slug'] : 'md_settings';
			$capability = isset( $fields['capability'] ) ? $fields['capability'] : 'manage_options';
			$callback = array( $this, 'admin_page' );
			$menu_slug = 'md_' . ( isset( $fields['menu_slug'] ) ? $fields['menu_slug'] : $admin_page );
			$icon = isset( $fields['icon'] ) ? $fields['icon'] : '';
			$position = isset( $fields['position'] ) ? $fields['position'] : null;
			$menu_title = isset( $fields['menu_title'] ) ? $fields['menu_title'] : $fields['name'];

			if ( ! empty( $fields['toplevel'] ) )
				add_menu_page( $fields['name'], $menu_title, $capability, $menu_slug, $callback, $icon, $position );
			else {
				$sub_page_title = ! empty( $fields['tab_name'] ) ? $fields['tab_name'] : $fields['name'];
				add_submenu_page( $parent_slug, $sub_page_title, $fields['name'], $capability, $menu_slug, $callback, $position );
			}

			if ( ! empty( $fields['hide_menu'] ) )
				remove_submenu_page( $parent_slug, $menu_slug );
		}
	}

	/**
	 * Enqueue scripts and styles to the Block Editor.
	 *
	 * @since 6.0
	 */

	public function enqueue_block_editor() {
		wp_enqueue_script( 'md-block-editor', MD_URL . 'admin/js/block-editor.js', array( 'wp-dom-ready', 'wp-plugins', 'wp-editor', 'wp-element', 'wp-components', 'wp-data' ), MD_VERSION, true );
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

		$vars = array(
			'user_id' => get_current_user_id(),
			'nonce' => wp_create_nonce( "{$this->_option}_nonce" ),
			'colors' => md_localize_scripts( array( 'colors' ) )
		);

		if ( in_array( $screen->base, array( 'edit', 'post' ) ) && ! in_array( $screen->post_type, array( 'post', 'page' ) ) ) {
			if ( $screen->base == 'post' )
				$vars = array_merge( $vars, array(
					'screen' => 'post',
					'is_sticky' => is_sticky() ? true : false,
					'checked_attribute' => checked( is_sticky(), true, false ),
					'label_text' => __( 'Stick this post to the front page', 'md' ),
					'sticky_visibility_text' => __( 'Public, Sticky', 'md' )
				) );
			else $vars = array_merge( $vars, array(
				'screen' => 'edit',
				'post_type' => $screen->post_type,
				'status_label_text' => __( 'Status', 'md' ),
				'label_text' => __( 'Make this post sticky', 'md' ),
				'sticky_text' => __( 'Sticky', 'md' )
			) );

			wp_add_inline_script( 'marketers-delight', 'MD.stickyPostTypes();' );
		}

		wp_localize_script( 'marketers-delight', 'MDJS', $vars );
		wp_register_script( 'md-color', MD_URL . 'admin/js/jscolor.js', array(), '', true );
		wp_register_script( 'md-sortable', MD_URL . 'admin/js/sortable.js', array(), '', true );
		wp_register_style( 'md-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css' );
		wp_register_script( 'md-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array( 'marketers-delight' ) );
	}

	/**
	 * Add body class to the admin panel.
	 *
	 * @since 6.0
	 */

	public function admin_body_class( $classes ) {
		$builder = md_post_meta( array( 'layout', 'content', 'builder' ) );

		if ( ! empty( $builder ) )
			$classes .= ' md-builder';

		if ( md_has_sidebar( array( 'post_id' => get_the_ID() ) ) )
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
		$hook_id = md_clean_id( $hook );
		$option = isset( $admin_pages[$hook_id]['_option'] ) ? $admin_pages[$hook_id]['_option'] : $this->_option;

		$taxonomies = apply_filters( 'md_taxonomy_groups', array() );
		$taxonomy_tabs = ! empty( $taxonomies[$page] ) ? array_keys( $taxonomies[$page] ) : array();
		$active_taxonomy_tab = isset( $_GET['md_tab'] ) ? sanitize_key( $_GET['md_tab'] ) : '';

		include md_template( 'admin/admin', true );
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

	public function add_meta_boxes() {
		$screen = get_current_screen();
		$blog_id = get_option( 'page_for_posts' );
		$post_id = isset( $_GET['post'] ) ? esc_attr( $_GET['post'] ) : '';

		foreach ( md_register( 'meta_boxes' ) as $meta_box => $fields ) {
			$post_types = isset( $fields['post_type'] ) ? $fields['post_type'] : md_post_type_meta();
			$context = isset( $fields['context'] ) ? $fields['context'] : 'normal';
			$priority = isset( $fields['priority'] ) ? $fields['priority'] : 'default';
			$callback = isset( $fields['callback'] ) ? $fields['callback'] : '';

			foreach ( $post_types as $post_type ) {
				if (
					! isset( $fields['name'] ) || isset( $fields['hide'] ) || isset( $fields['child_of'] ) ||
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
			add_action( "edited_{$term}", array( $this->sanitize, 'term_save' ), 10, 2 );
		}
	}

	/**
	 * Build callback to load custom options on taxonomy screens.
	 *
	 * @since 4.3.5
	 */

	public function term( $term ) {
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
		add_thickbox();
		add_action( 'admin_notices', array( $this, 'update_nag' ) );
	}

	/**
	 * Display the update notifications
	 *
	 * @since 5.4
	 */

	public function update_nag() {
		$license_status = md_setting( array( 'license', 'status' ) );
		$theme = md_setting( array( 'license', 'updates', 'theme' ) );
		$new_version = md_setting( array( 'license', 'updates', 'theme', 'new_version' ) );

		if ( $license_status !== 'valid' || empty( $theme ) || version_compare( MD_VERSION, $new_version, '>=' ) )
			return;

		$strings = array(
			'update-notice' => esc_js( __( "Updating MD will lose any customizations you\'ve made to the core files. Be sure to backup any changes to a Child Theme before updating. 'Cancel' to stop, 'OK' to update.", 'md' ) ),
			'update-available' => '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>'
		);

		$theme_slug = $this->requests->license( 'theme_slug' );
		$theme_name = str_replace( ' 4', '', $theme['name'] );

		$update_url = wp_nonce_url( 'update.php?action=upgrade-theme&amp;theme=' . urlencode( $theme_slug ), 'upgrade-theme_' . $theme_slug );
		$update_onclick = ' onclick="if ( confirm(\'' . esc_js( $strings['update-notice'] ) . '\') ) {return true;}return false;"';

	?>
		<div id="update-nag" class="update-message notice inline notice-warning">
			<?php printf(
				$strings['update-available'],
				$theme_name,
				$new_version,
				'#TB_inline?width=640&amp;inlineId=' . $theme_slug . '_changelog',
				$theme_name,
				$update_url,
				$update_onclick
			); ?>
		</div>
		<div id="<?php echo esc_attr( $theme_slug . '_changelog' ); ?>" style="display:none;">
			<h1><?php echo sprintf( __( 'Ready to update %s %2s?', 'md' ), $theme_name, $new_version ); ?></h2>
			<p><?php echo __( 'Here are some resources to help you:', 'md' ); ?></p>
			<h3>- <a href="https://marketersdelight.com/changelog/" target="_blank"><?php echo __( 'Read the changelog', 'md' ); ?></a></h3>
			<h3>- <a href="https://marketersdelight.com/stream/" target="_blank"><?php echo __( 'See what\'s new in MD', 'md' ); ?></a></h3>
			<h3>- <a href="https://kolakube.com/community/" target="_blank"><?php echo __( 'Get help at support', 'md' ); ?></a></h3>
			<p><?php echo __( 'When in doubt, make a backup your website before proceeding.', 'md' ); ?></p>
		</div>
	<?php }

}

$md_admin = new md_admin;
$md_admin->init();