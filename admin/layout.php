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
		$this->name = __( 'Layout', 'md' );

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'parent_group' => 'page_settings',
				'fields' => $this->fields()
			),
			'meta_box' => array(
				'name' => $this->name,
				'child_of' => 'page_settings',
				'fields' => $this->fields()
			),
			'term' => array(
				'name' => $this->name,
				'parent_group' => 'page_settings',
				'fields' => $this->fields()
			)
		);
	}

	/**
	 * Register Layout settings for save.
	 *
	 * @since 4.7
	 * @moved 6.0
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
				'options' => array( 'remove', 'logo', 'tagline', 'menu', 'elements' )
			),
			'header_menu' => array(
				'type' => 'select',
				'options' => $menus
			),
			'content' => array(
				'type' => 'checkbox',
				'options' => array( 'remove', 'builder', 'the_content', 'headline', 'author_box', 'add_author_box', 'post_nav', 'add_post_nav', 'full', 'wpautop' )
			),
			'content_style' => array(
				'type' => 'select',
				'options' => array_keys( md_filter_loop_styles() )
			),
			'breadcrumbs' => array(
				'type' => 'checkbox',
				'options' => array( 'add', 'remove' )
			),
			'content_box' => array(
				'type' => 'select',
				'options' => array( 'sidebar_content' )
			),
			'featured_image' => array(
				'type' => 'select',
				'options' => array_keys( $this->fields->data->values['featured_image'] )
			),
			'featured_image_width' => array( 'type' => 'number' ),
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
	 * Meta box template and scripts callback.
	 *
	 * @since 5.0
	 */

	public function meta_box() {
		$this->admin_template();
	}

	/**
	 * Add settings template and script to Page Settings sections.
	 *
	 * @since 6.0
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
	 * Build single admin fields with slight tweaks across screens.
	 *
	 * @since 4.7
	 */

	public function admin_template() {
		$screen_id = '';
		$screen = get_current_screen();
		$sanitize = new md_sanitize;
		$post_type = $screen->post_type;
		$is_post = in_array( $screen->base, array( 'post', 'post-new' ) ) ? true : false;
		$is_term = $screen->base == 'term' ? true : false;
		$is_admin = ! in_array( $screen->base, array( 'post', 'post-new', 'term' ) ) ? true : false;

		if ( $is_post )
			$screen_id = isset( $_GET['post'] ) ? sanitize_key( $_GET['post'] ) : '';
		elseif ( $is_term )
			$screen_id = isset( $_GET['tag_ID'] ) ? sanitize_key( $_GET['tag_ID'] ) : '';

		$page_types = array(
			'archive' => __( 'Archive', 'md' ),
			'term' => __( 'Categories', 'md' ),
			'single' => __( 'Single', 'md' )
		);

		$header = $this->fields->module( 'header' );
		$content = $this->fields->module( 'content' );
		$footer = $this->fields->module( 'footer' );

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
		$featured_image_position = $this->fields->module( 'featured_image' );

		if ( ( $has_sidebar || $single_add ) && ! $single_remove )
			$sidebar_display = 'block';

		$sidebar_classes = array( 'md-sidebars', 'md-sep-small' );

		if ( $is_admin ) {
			$global = $this->fields->module( array( 'sidebar', 'global' ) );

			if ( $global )
				$sidebar_classes[] = 'is-global';
		}

		$sidebar_classes = join( ' ', $sidebar_classes );

		$breadcrumbs_options = array( 'add' => __( 'Add <b>Breadcrumbs</b>', 'md' ) );

		if ( ! $is_admin && md_post_type_field( array( 'layout', 'breadcrumbs', 'add' ), null, $post_type ) )
			$breadcrumbs_options = array( 'remove' => __( 'Remove <b>Breadcrumbs</b>', 'md' ) );

		$author_box = md_post_type_field( array( 'layout', 'content', 'add_author_box' ), null, $post_type );
		$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		foreach ( $nav_menus as $menu )
			$menus[$menu->slug] = $menu->name;

		if ( $is_post )
			echo "<div class=\"md-$this->_clean_id md-tab-content active\">";

		include md_template( 'admin/layout', true );

		if ( $is_post )
			echo '</div>';

		do_action( 'md_layout_edit_screen_fields' );

		$this->scripts( $has_sidebar );
	}

	/**
	 * Print footer scripts to admin screens to toggle options.
	 *
	 * @since 4.7
	 */

	public function scripts( $has_sidebar ) {
		$screen = get_current_screen();
		$prefix = $this->_prefix();
		$sidebars = md_get_sidebars();
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
					document.getElementById( 'md_layout' ).classList.toggle( 'remove-content-box' );
					document.getElementById( 'content_options' ).style.display = this.checked ? 'none' : 'block';
					document.getElementById( 'sidebar_fields' ).style.display = this.checked ? 'none' : 'block';
				}
				<?php if ( in_array( $screen->post_type, array( 'post', 'page' ) ) && $screen->base !== 'term' ) : ?>
				document.getElementById( '<?php echo $prefix; ?>_content_headline' ).onchange = function( e ) {
					document.getElementById( 'headline_options' ).style.display = this.checked ? 'none' : 'block';
				}
				<?php endif; ?>

				<?php if ( in_array( $screen->base, array( 'post', 'post-new' ) ) ) : ?>
				document.getElementById( '<?php echo $prefix; ?>_featured_image' ).onchange = function( e ) {
					document.getElementById( 'featured_image_fields' ).style.display = ['above_headline', 'below_headline', 'remove'].includes( this.value ) ? 'none' : 'block';
				}
				<?php endif; ?>

				<?php if ( ! empty( $sidebars ) ) : ?>
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
				<?php endif; ?>

				document.getElementById( '<?php echo $prefix; ?>_footer_remove' ).onchange = function( e ) {
					document.getElementById( 'footer_options' ).style.display = this.checked ? 'none' : 'block';
				}
			} )();
		</script>

	<?php }

}

new md_layout;