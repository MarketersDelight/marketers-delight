<?php
/**
 * Add Layout options around various admin screens.
 *
 * @since 4.7
 */

class md_layout extends md_api {

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
				'context' => 'side',
				'fields' => $fields
			),
			'term' => array(
				'name' => $this->name,
				'fields' => $fields,
				'position' => 5
			)
		);
	}

	/**
	 * Register fields for save.
	 *
	 * @since 4.7
	 */

	public function fields() {
		$sidebar_options = array();
		foreach ( md_get_sidebars() as $custom_sidebar => $custom_sidebar_name )
			$sidebar_options[] = $custom_sidebar;

		$fields = array(
			'header' => array(
				'type' => 'checkbox',
				'options' => array( 'remove', 'logo', 'tagline', 'menu' )
			),
			'content' => array(
				'type' => 'checkbox',
				'options' => array( 'remove', 'teasers', 'headline', 'byline', 'author_box', 'add_author_box' )
			),
			'breadcrumbs' => array(
				'type' => 'checkbox',
				'options' => array( 'add', 'remove' )
			),
			'content_box' => array(
				'type' => 'select',
				'options' => array( 'content_sidebar', 'sidebar_content' )
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

		if ( md_has( 'main_menu' ) ) {
			$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			if ( ! empty( $nav_menus ) ) {
				$menus = array();
				foreach ( $nav_menus as $menu )
					$menus[] = $menu->slug;
				$fields['main_menu'] = array(
					'type' => 'checkbox',
					'options' => array( 'remove' )
				);
				$fields['main_menu_menu'] = array(
					'type' => 'select',
					'options' => $menus
				);
			}
		}

		return $fields;
	}

	/**
	 * Meta box template and scripts callback.
	 *
	 * @since 5.0
	 */

	public function meta_box() {
		$this->admin_template();
	}

	/**
	 * Load scripts to meta box.
	 *
	 * @since 5.0
	 */

	public function meta_scripts() {
		$this->scripts();
	}

	/**
	 * Term fields template and scripts callback.
	 *
	 * @since 5.0
	 */

	public function term() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo $this->name; ?></h3>
			<div class="md-widget-item">
				<?php $this->admin_template(); ?>
			</div>
		</div>
	<?php }

	/**
	 * Load scripts to terms page.
	 *
	 * @since 5.0
	 */
	 
	public function term_scripts() {
		$this->scripts();
	}

	/**
	 * Build single admin fields with slight tweaks across screens.
	 *
	 * @since 4.7
	 */

	public function admin_template() {
		$screen = get_current_screen();
		$sidebar_display = 'none';
		$header = $this->fields->module( 'header' );
		$content = $this->fields->module( 'content' );
		$sidebar = $this->fields->module( 'sidebar' );
		$footer = $this->fields->module( 'footer' );
		$sidebars = md_get_sidebars();
		$sidebar_single = md_setting( array( 'content', 'sidebar', 'single' ) );
		$sidebar_category = md_setting( array( 'content', 'sidebar', 'category' ) );
		$author_box = md_setting( array( 'content', 'author_box', 'enable' ) );

		if ( in_array( $screen->base, array( 'post', 'post-new' ) ) && (
			( ! empty( $sidebar_single ) && empty( $sidebar['remove'] ) ) ||
			( empty( $sidebar_single ) && ! empty( $sidebar['add'] ) )
		) )
			$sidebar_display = 'block';

		if ( $screen->base == 'term' && (
			( ! empty( $sidebar_category ) && empty( $sidebar['remove'] ) ) ||
			( empty( $sidebar_category ) && ! empty( $sidebar['add'] ) )
		) )
			$sidebar_display = 'block';

		include( 'layout-settings.php' );
	}

	/**
	 * Print footer scripts to admin screens to toggle options.
	 *
	 * @since 4.7
	 */

	public function scripts() {
		$screen = get_current_screen();
		$prefix = $this->_prefix;
		$sidebar_single = md_setting( array( 'content', 'sidebar', 'single' ) );
		$sidebar_category = md_setting( array( 'content', 'sidebar', 'category' ) );
	?>

		<script>
			<?php if ( in_array( $screen->base, array( 'post', 'post-new' ) ) ) : ?>
				MD.blocksEditor();
			<?php endif; ?>
			( function() {
				document.getElementById( '<?php echo $prefix; ?>_header_remove' ).onchange = function( e ) {
					document.getElementById( 'header_options' ).style.display = this.checked ? 'none' : 'block';
				}
				<?php if ( md_has( 'main_menu' ) ) : ?>
					document.getElementById( '<?php echo $prefix; ?>_main_menu_remove' ).onchange = function( e ) {
						document.getElementById( 'main_menu_options' ).style.display = this.checked ? 'none' : 'block';
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
				<?php if ( ( $screen->base == 'term' && ! empty( $sidebar_category ) ) || ( $screen->post_type != 'page' && ! empty( $sidebar_single ) ) ) : ?>
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