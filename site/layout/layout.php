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
		$menus = array();
		$sanitize = new md_sanitize;
		$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		foreach ( $nav_menus as $menu )
			$menus[] = esc_attr( $menu->slug );

		$custom_sidebars = md_get_sidebars( true );

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
				'options' => array( 'remove', 'headline', 'author_box', 'add_author_box', 'post_nav', 'add_post_nav', 'full', 'page_title' )
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
			'featured_image' => array(
				'type' => 'select',
				'options' => array_keys( $sanitize->values['featured_image'] )
			),
			'sidebar' => array(
				'type' => 'checkbox',
				'options' => array( 'add', 'remove', 'global' )
			),
			'custom_sidebar' => array(
				'type' => 'select',
				'options' => $custom_sidebars
			),
			'entries_sidebar' => array(
				'type' => 'select',
				'options' => $custom_sidebars
			),
			'footer' => array(
				'type' => 'checkbox',
				'options' => array( 'remove', 'columns' )
			)
		);

		foreach ( array( 'archive', 'term', 'single' ) as $type ) {
			$fields["sidebar_$type"] = array(
				'type' => 'select',
				'options' => $custom_sidebars
			);
			$fields["sidebar_{$type}_show"] = array(
				'type' => 'checkbox',
				'options' => array( 'enable', 'disable' )
			);
		}

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
		$screen_id = '';
		$screen = get_current_screen();
		$sanitize = new md_sanitize;
		$post_type = esc_attr( $screen->post_type );
		$screen_base = esc_attr( $screen->base );
		$is_post = in_array( $screen_base, array( 'post', 'post-new' ) ) ? true : false;
		$is_term = $screen_base == 'term' ? true : false;
		$is_admin = ! in_array( $screen_base, array( 'post', 'post-new', 'term' ) ) ? true : false;

		if ( $is_post )
			$screen_id = isset( $_GET['post'] ) ? esc_attr( $_GET['post'] ) : '';
		elseif ( $is_term )
			$screen_id = isset( $_GET['tag_ID'] ) ? esc_attr( $_GET['tag_ID'] ) : '';

		$page_types = array(
			'archive' => __( 'Archive', 'md' ),
			'term' => __( 'Categories', 'md' ),
			'single' => __( 'Single', 'md' )
		);

		$header = $this->fields->module( 'header' );
		$content = $this->fields->module( 'content' );

		$sidebar_display = 'none';
		$sidebars = md_get_sidebars();
		$has_sidebar = md_has_sidebar( array(
			'page' => ( $is_post ? 'single' : 'term' ),
			'post_type' => $screen->post_type,
			'post_id' => $screen_id,
			'exclude_single' => true
		) );
		$single_add = $this->fields->module( array( 'sidebar', 'add' ) );
		$single_remove = $this->fields->module( array( 'sidebar', 'remove' ) );

		if ( ( $has_sidebar || $single_add ) && ! $single_remove )
			$sidebar_display = 'block';

		$sidebar_classes = array( 'md-sidebars', 'col', 'md-sep-small' );

		if ( $is_admin ) {
			$global = $this->fields->module( array( 'sidebar', 'global' ) );

			if ( $global )
				$sidebar_classes[] = 'is-global';

			$sidebar_classes[] = 'col-50';
		}

		$sidebar_classes = join( ' ', $sidebar_classes );

		$breadcrumbs_options = array( 'add' => __( 'Add <b>Breadcrumbs</b>', 'md' ) );

		if ( ! $is_admin && md_post_type_field( array( 'layout', 'breadcrumbs', 'add' ), null, $post_type ) )
			$breadcrumbs_options = array( 'remove' => __( 'Remove <b>Breadcrumbs</b>', 'md' ) );

		$author_box = md_post_type_field( array( 'layout', 'content', 'add_author_box' ), null, $post_type );
		$disable_post_nav = md_post_type_field( array( 'layout', 'content', 'post_nav' ), null, $post_type );
		$post_nav_options = array( 'post_nav' => __( 'Remove <b>Post Nav</b>', 'md' ) );

		if ( $disable_post_nav )
			$post_nav_options = array( 'add_post_nav' => __( 'Add <b>Post Nav</b>', 'md' ) );

		$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		foreach ( $nav_menus as $menu )
			$menus[$menu->slug] = $menu->name;

		echo "<div class=\"md-$this->_clean_id md-tab-content active\">";

		include( 'layout-settings.php' );

		echo '</div>';

		$this->scripts( $has_sidebar );
	}

	public function footer_fields() {
		$footer = $this->fields->module( 'footer' );
	?>
		<?php $this->fields->field( 'footer', array(
			'type' => 'checkbox',
			'label' => __( 'Footer', 'md' ),
			'options' => array(
				'remove' => __( 'Remove <b>Footer</b>', 'md' )
			)
		) ); ?>
		<div id="footer_options" style="display: <?php echo ! empty( $footer['remove'] ) ? 'none' : 'block'; ?>;">
			<?php $this->fields->field( 'footer', array(
				'type' => 'checkbox',
				'options' => array(
					'columns' => __( 'Remove <b>Columns</b>', 'md' )
				)
			) ); ?>
		</div>
	<?php }

	/**
	 * Print footer scripts to admin screens to toggle options.
	 *
	 * @since 4.7
	 */

	public function scripts( $has_sidebar ) {
		$screen = get_current_screen();
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
					<?php if ( $has_sidebar ) : ?>
						document.getElementById( '<?php echo $prefix; ?>_sidebar_remove' ).onchange = function( e ) {
							document.getElementById( 'sidebar_options' ).style.display = this.checked ? 'none' : 'block';
						}
					<?php else : ?>
						document.getElementById( '<?php echo $prefix; ?>_sidebar_add' ).onchange = function( e ) {
							document.getElementById( 'sidebar_options' ).style.display = this.checked ? 'block' : 'none';
						}
					<?php endif; ?>
				<?php else : ?>
					document.getElementById( '<?php echo $prefix; ?>_sidebar_global' ).onchange = function() {
						jQuery( '#sidebar_fields' ).toggleClass( 'is-global' );
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
