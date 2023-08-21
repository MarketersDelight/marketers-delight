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
		require_once( 'header/header.php' );
		require_once( 'page-cover/page-cover.php' );
		require_once( 'featured-image/featured-image.php' );
		require_once( 'featured-video/featured-video.php' );
		require_once( 'page-title.php' );
		require_once( 'loop/loop.php' );
		require_once( 'sidebars/sidebars.php' );
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
				'order' => 5,
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
				'options' => array( 'remove', 'headline', 'byline', 'add_byline', 'author_box', 'add_author_box' )
			),
			'breadcrumbs' => array(
				'type' => 'checkbox',
				'options' => array( 'add', 'remove' )
			),
			'content_box' => array(
				'type' => 'select',
				'options' => array( 'sidebar_content' )
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
		$post_base = in_array( $screen_base, array( 'post', 'post-new' ) ) ? 'post' : $screen_base;

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

		$author_box = md_setting( array( 'post', 'single', 'author_box', 'enable' ) );
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
				}
				<?php if ( in_array( $screen->post_type, array( 'post', 'page' ) ) && $screen->base !== 'term' ) : ?>
					document.getElementById( '<?php echo $prefix; ?>_content_headline' ).onchange = function( e ) {
						document.getElementById( 'headline_options' ).style.display = this.checked ? 'none' : 'block';
					}
				<?php endif; ?>
				<?php if ( md_admin_has_sidebar() ) : ?>
					document.getElementById( '<?php echo $prefix; ?>_sidebar_remove' ).onchange = function( e ) {
						document.getElementById( 'sidebar_options' ).style.display = this.checked ? 'none' : 'block';
					}
				<?php else : ?>
					document.getElementById( '<?php echo $prefix; ?>_sidebar_add' ).onchange = function( e ) {
						document.getElementById( 'sidebar_options' ).style.display = this.checked ? 'block' : 'none';
					}
				<?php endif; ?>
				document.getElementById( '<?php echo $prefix; ?>_footer_remove' ).onchange = function( e ) {
					document.getElementById( 'footer_options' ).style.display = this.checked ? 'none' : 'block';
				}
			} )();
		</script>

	<?php }

}

new md_layout;
