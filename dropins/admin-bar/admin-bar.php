<?php
/**
 * Dropin Name: MD Admin Bar
 * Dropin Author: Alex Mangini
 * Dropin Demo: https://marketersdelight.com/dropins/admin-bar/
 * Dropin Description: A far more lightweight admin bar with customizeable links.
 * Dropin Version: 1.0
 * @since MD5.2.1
 */

 class md_admin_bar extends md_api {

	/**
	 * Fire actions and filters.
	 *
	 * @since 1.0
	 */

	public function actions() {
		if ( current_user_can( 'administrator' ) ) {
			add_filter( 'body_class', array( $this, 'body_class' ) );
			add_filter( 'show_admin_bar', '__return_false' );
			add_action( 'md_hook_before_html', array( $this, 'admin_bar' ) );
		}
	}

	/**
	 * Register admin bar settings.
	 *
	 * @since 1.0
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Admin Bar', 'md' ),
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
							'url' => array( 'type' => 'url' )
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
					'label' => __( 'Links', 'md' ),
					'new_label' => __( 'Link name...', 'md' ),
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

	public function admin_links( $group, $field ) { ?>
		<div class="columns-25-75 columns-half">
			<div class="col col1 md-sep-small">
				<?php $this->fields->field( array( $group, $field, 'icon' ), array(
					'type' => 'select',
					'label' => __( 'Icon', 'md' ),
					'empty_label' => __( 'Select icon...', 'md' ),
					'options' => md_get_icons( 'options', null, 'md-icon-' )
				) ); ?>
			</div>
			<div class="col col2 md-sep-small">
				<?php $this->fields->field( array( $group, $field, 'url' ), array(
					'type' => 'url',
					'label' => __( 'Link', 'md' )
				) ); ?>
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