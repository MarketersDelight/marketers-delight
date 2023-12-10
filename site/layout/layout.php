<?php
/**
 * Add Layout options around various admin screens.
 *
 * @since 4.7
 */

class md_layout extends md_api {

	/**
	 * Include additional files for Layout rendering.
	 *
	 * @since 5.6
	 */

	public function includes() {
		require_once( 'layout-functions.php' );
	}

	/**
	 * Create meta box and terms.
	 *
	 * @since 5.0
	 */

	public function register() {
		$this->_id = 'md_layout';
		$this->name = __( 'Layout', 'md' );

		$fields = $this->fields();

		return array(
			'meta_box' => array(
				'name' => $this->name,
				'page_settings' => true,
				'order' => 5,
				'fields' => $fields
			),
			'term' => array(
				'name' => $this->name,
				'position' => 30,
				'fields' => $fields,
				'callback' => array( $this, 'admin_fields' )
			)
		);
	}

	/**
	 * Register Layout settings for save.
	 *
	 * @since 4.7
	 * @moved 5.6
	 */

	public function fields() {
		$menus = $sidebar_options = array();

		$sidebars = md_get_sidebars();

		foreach ( $sidebars as $custom_sidebar => $custom_sidebar_name )
			$sidebar_options[] = esc_attr( $custom_sidebar );

		$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		foreach ( $nav_menus as $menu )
			$menus[] = esc_attr( $menu->slug );

		$fields = array(
			'header' => array(
				'type' => 'checkbox',
				'options' => array( 'remove', 'logo', 'tagline', 'menu' )
			),
			'header_menu' => array(
				'type' => 'select',
				'options' => $menus
			),
			'content' => array(
				'type' => 'checkbox',
				'options' => array( 'remove', 'headline', 'byline', 'add_byline', 'author_box', 'add_author_box', 'post_nav', 'add_post_nav', 'full', 'page_title' )
			),
			'breadcrumbs' => array(
				'type' => 'checkbox',
				'options' => array( 'add', 'remove' )
			),
			'content_box' => array(
				'type' => 'select',
				'options' => array( 'sidebar_content' )
			),
			'content_box_style' => array(
				'type' => 'select',
				'options' => array( 'box_style', 'minimal' )
			),
			'sidebar' => array(
				'type' => 'checkbox',
				'options' => array( 'add', 'remove' )
			),
			'custom_sidebar' => array(
				'type' => 'select',
				'options' => $sidebar_options
			),
			'entries_sidebar' => array(
				'type' => 'select',
				'options' => $sidebar_options
			),
			'footer' => array(
				'type' => 'checkbox',
				'options' => array( 'remove', 'columns' )
			)
		);

		return $fields;
	}

	/**
	 * Add settings template and script to Page Settings sections.
	 *
	 * @since 5.6
	 */

	public function admin_fields() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo esc_html( $this->name ); ?></h3>
			<div class="md-widget-item">
				<?php $this->admin_template(); ?>
			</div>
		</div>
	<?php }

	/**
	 * Meta box template and scripts callback.
	 *
	 * @since 5.0
	 */

	public function meta_box() {
		$this->admin_template();
	}

	/**
	 * Build single admin fields with slight tweaks across screens.
	 *
	 * @since 4.7
	 */

	public function admin_template() {
		$screen = get_current_screen();
		$post_type = esc_attr( $screen->post_type );
		$screen_base = esc_attr( $screen->base );
		$is_edit = in_array( $screen_base, array( 'post', 'post-new' ) ) ? true : false;
		$is_admin = ! in_array( $screen_base, array( 'post', 'post-new', 'term' ) ) ? true : false;
		$hook = $is_edit ? 'post' : $screen_base;

		$header = $this->fields->module( 'header' );
		$content = $this->fields->module( 'content' );
		$footer = $this->fields->module( 'footer' );

		$sidebar_display = 'none';
		$sidebars = md_get_sidebars();
		$has_sidebar = md_admin_has_sidebar();
		$single_add = $this->fields->module( array( 'sidebar', 'add' ) );
		$single_remove = $this->fields->module( array( 'sidebar', 'remove' ) );

		if ( ( $has_sidebar || $single_add ) && ! $single_remove )
			$sidebar_display = 'block';

		$breadcrumbs_options = array( 'add' => __( 'Add <b>Breadcrumbs</b>', 'md' ) );
		if ( in_array( $screen->id, array( 'post', 'post-new', 'term' ) ) && md_post_type_field( array( 'layout', 'breadcrumbs', 'add' ) ) )
			$breadcrumbs_options = array( 'remove' => __( 'Remove <b>Breadcrumbs</b>', 'md' ) );

		$author_box = md_post_type_field( array( 'single', 'author_box', 'enable' ), null, $post_type );
		$disable_post_nav = md_post_type_field( array( 'single', 'post_nav', 'disable' ), null, $post_type );
		$post_nav_options = array( 'post_nav' => __( 'Remove <b>Post Nav</b>', 'md' ) );

		if ( $disable_post_nav )
			$post_nav_options = array( 'add_post_nav' => __( 'Add <b>Post Nav</b>', 'md' ) );

		$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		foreach ( $nav_menus as $menu )
			$menus[$menu->slug] = $menu->name;

		echo "<div class=\"md-$this->_clean_id md-tab-content active\">";
		include( 'layout-settings.php' );
		echo '</div>';

		$this->scripts();
	}

	/**
	 * Print footer scripts to admin screens to toggle options.
	 *
	 * @since 4.7
	 */

	public function scripts() {
		$screen = get_current_screen();
		$post_type = esc_attr( $screen->post_type );
		$prefix = $this->_prefix();
	?>

		<script>
			( function() {
				document.getElementById( '<?php echo $prefix; ?>_header_remove' ).onchange = function( e ) {
					document.getElementById( 'header_options' ).style.display = this.checked ? 'none' : 'block';
				}
				<?php if ( md_has_menu() ) : ?>
					document.getElementById( '<?php echo $prefix; ?>_header_menu' ).onchange = function( e ) {
						document.getElementById( 'header_menu_options' ).style.display = this.checked ? 'none' : 'block';
					}
				<?php endif; ?>
				document.getElementById( '<?php echo $prefix; ?>_content_remove' ).onchange = function( e ) {
					document.getElementById( 'content_options' ).style.display = this.checked ? 'none' : 'block';
					document.getElementById( 'sidebar_fields' ).style.display = this.checked ? 'none' : 'block';
				}
				<?php if ( in_array( $screen->post_type, array( 'post', 'page' ) ) && $screen->base !== 'term' ) : ?>
					document.getElementById( '<?php echo $prefix; ?>_content_headline' ).onchange = function( e ) {
						document.getElementById( 'headline_options' ).style.display = this.checked ? 'none' : 'block';
					}
				<?php endif; ?>
				<?php if ( in_array( $screen->base, array( 'post', 'post-new', 'term' ) ) ) : ?>
					<?php if ( md_admin_has_sidebar() ) : ?>
						document.getElementById( '<?php echo $prefix; ?>_sidebar_remove' ).onchange = function( e ) {
							document.getElementById( 'sidebar_options' ).style.display = this.checked ? 'none' : 'block';
						}
					<?php else : ?>
						document.getElementById( '<?php echo $prefix; ?>_sidebar_add' ).onchange = function( e ) {
							document.getElementById( 'sidebar_options' ).style.display = this.checked ? 'block' : 'none';
						}
					<?php endif; ?>
				<?php endif; ?>
				document.getElementById( '<?php echo $prefix; ?>_footer_remove' ).onchange = function( e ) {
					document.getElementById( 'footer_options' ).style.display = this.checked ? 'none' : 'block';
				}
			} )();
		</script>

	<?php }

}

new md_layout;
