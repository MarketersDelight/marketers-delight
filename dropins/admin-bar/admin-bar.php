<?php
/**
 * Dropin Name: MD Admin Bar
 * Dropin Author: Alex Mangini
 * Dropin Demo: https://marketersdelight.com/dropins/admin-bar/
 * Dropin Description: A far more lightweight admin bar with customizeable links.
 * @since MD5.2.1
 */

 class md_admin_bar extends md_api {

 	/**
	 * Register plugin data.
	 *
	 * @since 1.1
	 */
	
	public function links() {
	 	return array(
		 	'custom' => array(
		 		'label' => __( 'Enter custom link...', 'md-admin-bar' ),
		 	),
		 	'customize' => array(
		 		'label' => __( 'Customize', 'md-admin-bar' ),
		 		'url' => admin_url( 'customize.php' ),
		 		'icon' => 'md-icon-eye'
		 	),
		 	'menus' => array(
		 		'label' => __( 'Menus', 'md-admin-bar' ),
		 		'url' => admin_url( 'nav-menus.php' ),
		 		'icon' => 'md-icon-menu'
		 	),
		 	'plugins' => array(
		 		'label' => __( 'Plugins', 'md-admin-bar' ),
		 		'url' => admin_url( 'plugins.php' ),
		 		'icon' => 'md-icon-download'
		 	),
		 	'widgets' => array(
		 		'label' => __( 'Widgets', 'md-admin-bar' ),
		 		'url' => admin_url( 'widgets.php' ),
		 		'icon' => 'md-icon-menu'
		 	),
		 	'settings' => array(
		 		'label' => __( 'MD Settings', 'md-admin-bar' ),
		 		'url' => admin_url( 'admin.php?page=md_settings' ),
		 		'icon' => 'md-icon-loading'
		 	),
		 	'site_design' => array(
		 		'label' => __( 'MD Site Design', 'md-admin-bar' ),
		 		'url' => admin_url( 'admin.php?page=md_site_design' ),
		 		'icon' => 'md-icon-eye'
		 	),
		 	'dropins' => array(
		 		'label' => __( 'MD Drop-ins', 'md-admin-bar' ),
		 		'url' => admin_url( 'admin.php?page=md_dropins' ),
		 		'icon' => 'md-icon-download'
		 	),
		 	'integrations' => array(
		 		'label' => __( 'MD Integrations', 'md-admin-bar' ),
		 		'url' => admin_url( 'admin.php?page=md_integrations' ),
		 		'icon' => 'md-icon-bolt'
		 	),
		 	'optins' => array(
		 		'label' => __( 'MD Optins', 'md-admin-bar' ),
		 		'url' => admin_url( 'admin.php?page=md_optins' ),
		 		'icon' => 'md-icon-mail-alt'
		 	),
		 	'compile_css' => array(
		 		'label' => __( 'MD Compile CSS', 'md-admin-bar' ),
		 		'url' => '?md=compile_css',
		 		'icon' => 'md-icon-code'
		 	)
	 	);
	}

	/**
	 * Fire actions and filters.
	 *
	 * @since 1.0
	 */

	public function actions() {
		$this->links = $this->links();
		if ( current_user_can( 'administrator' ) ) {
			$show_admin_bar = get_user_meta( get_current_user_id(), 'show_admin_bar_front', true );
			if ( $show_admin_bar == 'true' ) {
				add_filter( 'body_class', array( $this, 'body_class' ) );
				add_filter( 'show_admin_bar', '__return_false' );
				add_action( 'md_hook_before_html', array( $this, 'admin_bar' ) );
			}
		}
	}

	/**
	 * Register admin bar settings.
	 *
	 * @since 1.0
	 */

	public function register() {
		$link_types = array();
		foreach ( $this->links as $link_id => $link_fields )
			$link_types[] = $link_id;
		return array(
			'admin_page' => array(
				'name' => __( 'Admin Bar', 'md-admin-bar' ),
				'parent' => 'md_settings',
				'fields' => array(
					'links' => array(
						'type' => 'group',
						'fields' => array(
							'name' => array( 'type' => 'text' ),
							'icon' => array(
								'type' => 'select',
								'options' => md_get_icons( 'ids', null, 'md-icon-' )
							),
							'url' => array( 'type' => 'url' ),
							'link_type' => array(
								'type' => 'select',
								'options' => $link_types
							)
						)
					)
				)
			)
		);
	}

	/**
	 * Add body class.
	 *
	 * @since 1.0
	 */

	public function body_class( $classes ) {
		$classes[] = 'has-md-admin-bar';
		return $classes;
	}

	/**
	 * Admin Bar template.
	 *
	 * @since 1.0
	 */

	public function admin_bar() {
		$user = wp_get_current_user();
		$display_name = $user->display_name;
		$add_new_links = $this->add_new_links();
		$edit_url = $this->edit_url();
		$links = md_setting( array( 'admin_bar', 'links' ), array() );
		$link_types = $this->links;
		include( md_template( 'dropins', 'admin-bar/admin-bar', true ) );
	}

	/**
	 * Load CSS template to style.css.
	 *
	 * @since 1.0
	 */

	public function css( $templates ) {
		$templates['admin-bar'] = md_css( 'dropins', 'admin-bar/css', true );
		return $templates;
	}

	/**
	 * Admin page template.
	 *
	 * @since 1.0
	 */

	public function admin_page() { ?>
		<div class="md-content-wrap">
			<div class="md-sep-small">
				<?php $this->fields->field( 'links', array(
					'type' => 'group',
					'label' => __( 'Links', 'md-admin-bar' ),
					'new_label' => __( 'Link name...', 'md-admin-bar' ),
					'style' => 'boxes',
					'callback' => array( $this, 'admin_links' )
				) ); ?>
			</div>
			<hr class="md-sep-small" />
			<?php $this->fields->save(); ?>
		</div>
	<?php }

	/**
	 * Link settings fields.
	 *
	 * @since 1.0
	 */

	public function admin_links( $group, $field ) {
		$link_types = array();
		$link_type = md_setting( array( 'admin_bar', 'links', $field, 'link_type' ) );
		$link_url = md_setting( array( 'admin_bar', 'links', $field, 'url' ) );
		foreach ( $this->links as $link_id => $link_fields )
			$link_types[$link_id] = $link_fields['label'];
	?>
		<div class="md-conditional">
			<div class="md-sep-small">
				<?php $this->fields->field( array( $group, $field, 'link_type' ), array(
					'type' => 'select',
					'label' => __( 'Link to...', 'md-admin-bar' ),
					'empty_label' => __( 'Choose admin link...', 'md-admin-bar' ),
					'classes' => 'md-conditional-option',
					'options' => $link_types
				) ); ?>
			</div>
			<div class="md-conditional-item md-conditional-custom columns-25-75 columns-half" style="display: <?php echo $link_type == 'custom' || ( empty( $link_type ) && ! empty( $link_url ) ) ? 'block' : 'none'; ?>;">
				<div class="col col1 md-sep-small">
					<?php $this->fields->field( array( $group, $field, 'icon' ), array(
						'type' => 'select',
						'label' => __( 'Icon', 'md-admin-bar' ),
						'empty_label' => __( 'Select icon...', 'md-admin-bar' ),
						'options' => md_get_icons( 'options', null, 'md-icon-' )
					) ); ?>
				</div>
				<div class="col col2 md-sep-small">
					<?php $this->fields->field( array( $group, $field, 'url' ), array(
						'type' => 'url',
						'label' => __( 'Link URL', 'md-admin-bar' )
					) ); ?>
				</div>
			</div>
		</div>
	<?php }

	/**
	 * Get Edit URL.
	 *
	 * @since 1.0
	 */

	public function edit_url() {
		$slug = '';
		$id = get_queried_object_id();
		$post_type = get_post_type();
		if ( is_category() || is_tax() ) {
			$taxonomies = get_taxonomies( array( 'public' => true ) );
			$terms = wp_get_post_terms( get_the_ID(), $taxonomies );
			$taxonomy = $terms[0]->taxonomy;
			$slug = "term.php?taxonomy={$taxonomy}&tag_ID={$id}&post_type=$post_type";
		}
		elseif ( is_singular() || ( is_home() && get_option( 'page_for_posts' ) ) )
			$slug = "post.php?post={$id}&action=edit";
		if ( empty( $slug ) )
			return;
		return admin_url( $slug );
	}

	/**
	 * Get custom post type Add New links.
	 *
	 * @since 1.0
	 */

	public function add_new_links() {
		$actions = array();
		$cpts = (array) get_post_types( array( 'show_in_admin_bar' => true ), 'objects' );

		if ( isset( $cpts['post'] ) && current_user_can( $cpts['post']->cap->create_posts ) )
			$actions['post-new.php'] = array( $cpts['post']->labels->name_admin_bar, 'new-post' );

		if ( isset( $cpts['attachment'] ) && current_user_can( 'upload_files' ) )
			$actions['media-new.php'] = array( $cpts['attachment']->labels->name_admin_bar, 'new-media' );

		if ( isset( $cpts['page'] ) && current_user_can( $cpts['page']->cap->create_posts ) )
			$actions['post-new.php?post_type=page'] = array( $cpts['page']->labels->name_admin_bar, 'new-page' );

		unset( $cpts['post'], $cpts['page'], $cpts['attachment'] );

		foreach ( $cpts as $cpt ) {
			if ( ! current_user_can( $cpt->cap->create_posts ) )
				continue;
			$key = 'post-new.php?post_type=' . $cpt->name;
			$actions[$key] = array( $cpt->labels->name_admin_bar, 'new-' . $cpt->name );
		}

		if ( isset( $actions['post-new.php?post_type=content'] ) )
			$actions['post-new.php?post_type=content'][1] = 'add-new-content';

		if ( current_user_can( 'create_users' ) || ( is_multisite() && current_user_can( 'promote_users' ) ) )
			$actions['user-new.php'] = array( _x( 'User', 'add new from admin bar' ), 'new-user' );

		return $actions;
	}

 }

 new md_admin_bar;