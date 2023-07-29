<?php
/**
 * Create Site Design admin page.
 *
 * @since 5.0
 * TODO 5.6: Needs rework to be less Colors oriented.
 */

class md_colors extends md_api {

	/**
	 * Include admin files.
	 *
	 * @since 4.7
	 */

	public function includes() {
		require_once( 'icons/icons.php' );
		require_once( 'design-functions.php' );
	}

	/**
	 * Actions, filters, and properties.
	 *
	 * @since 5.0
	 */

	public function actions() {
		$this->data = $this->_data();
		$this->sanitize = new md_sanitize;
		$this->colors = array(
			'site' => array(
				'bg_color' => array( 'type' => 'color' ),
				'links' => array( 'type' => 'color' ),
				'links_sec' => array( 'type' => 'color' ),
				'primary' => array( 'type' => 'color' ),
				'secondary' => array( 'type' => 'color' ),
				'tertiary' => array( 'type' => 'color' ),
				'action' => array( 'type' => 'color' ),
				'accent' => array( 'type' => 'color' ),
				'text' => array( 'type' => 'color' ),
				'text-sec' => array( 'type' => 'color' ),
				'headline' => array( 'type' => 'color' ),
				'headline-links' => array( 'type' => 'color' ),
				'button' => array( 'type' => 'color' ),
				'button-text' => array( 'type' => 'color' ),
				'button-sec' => array( 'type' => 'color' ),
				'button-sec-text' => array( 'type' => 'color' )
			),
			'header' => array(
				'bg_color' => array( 'type' => 'color' ),
				'border_color' => array( 'type' => 'color' ),
				'color' => array( 'type' => 'color' ),
				'site_title' => array( 'type' => 'color' ),
				'site_tagline' => array( 'type' => 'color' ),
				'menu' => array(
					'links' => array( 'type' => 'color' ),
					'hover' => array( 'type' => 'color' ),
					'active' => array( 'type' => 'color' )
				),
				'submenu' => array(
					'bg_color' => array( 'type' => 'color' ),
					'links' => array( 'type' => 'color' ),
					'hover' => array( 'type' => 'color' )
				),
				'cover_color' => array( 'type' => 'color' )
			),
			'main_menu' => array(
				'bg_color' => array( 'type' => 'color' ),
				'links' => array( 'type' => 'color' ),
				'active' => array( 'type' => 'color' ),
				'subtext' => array( 'type' => 'color' ),
				'sub_menu' => array( 'type' => 'color' ),
				'submenu_links' => array( 'type' => 'color' ),
				'submenu_links_hover' => array( 'type' => 'color' ),
				'icons' => array( 'type' => 'color' ),
				'social' => array( 'type' => 'color' )
			),
			'content' => array(
				'bg_color' => array( 'type' => 'color' ),
				'border_color' => array( 'type' => 'color' )
			),
			'sidebar' => array(
				'bg_color' => array( 'type' => 'color' ),
				'text' => array( 'type' => 'color' ),
				'title' => array( 'type' => 'color' ),
				'links' => array( 'type' => 'color' )
			),
			'footer' => array(
				'bg_color' => array( 'type' => 'color' ),
				'border_color' => array( 'type' => 'color' ),
				'text' => array( 'type' => 'color' ),
				'title' => array( 'type' => 'color' ),
				'links' => array( 'type' => 'color' )
			)
		);

		// Add default colors
		foreach ( $this->colors as $group => $options ) {
			foreach ( $options as $field => $fields ) {
				if ( ! empty( $this->data['defaults']['colors'][$group][$field] ) ) {
					$default_value = $this->data['defaults']['colors'][$group][$field];
					if ( is_array( $default_value ) )
						foreach ( $default_value as $sub => $sub_value )
							$this->colors[$group][$field][$sub]['default'] = esc_attr( $sub_value );
					else
						$this->colors[$group][$field]['default'] = esc_attr( $default_value );
				}
			}
		}
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		$fields = $this->colors;
		$logo = array(
			'logo' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'logo_alt' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'logo_width' => array(
				'desktop' => array( 'type' => 'range' ),
				'tablet' => array( 'type' => 'range' ),
				'mobile' => array( 'type' => 'range' )
			),
			'logo_html' => array( 'type' => 'code' ),
			'logo_html_display' => array(
				'type' => 'checkbox',
				'options' => array( 'enable' )
			)
		);

		$fields = array_merge( $fields, $logo );

		$fields['featured_image']['position'] = array(
			'type' => 'select',
			'options' => array_keys( $this->sanitize->values['featured_image'] )
		);

		$fields['featured_image']['cover_position'] = array(
			'type' => 'select',
			'options' => array_keys( $this->sanitize->values['covers'] )
		);

		$fields['featured_image']['cover_image'] = array(
			'type' => 'upload',
			'upload_type' => 'media'
		);

		$fields['featured_image']['cover_color'] = array(
			'type' => 'color'
		);

		$fields['featured_image']['cover_styles'] = array(
			'type' => 'checkbox',
			'options' => array( 'text_color', 'disable_cover' )
		);

		$fields['layout'] = array(
			'type' => 'select',
			'options' => array_keys( $this->sanitize->values['content_box'] )
		);

		$fields['style'] = array(
			'type' => 'select',
			'options' => array( 'minimal' )
		);

		$fields['width']['site'] = array( 'type' => 'range' );
		$fields['width']['content'] = array( 'type' => 'range' );
		$fields['width']['sidebar'] = array( 'type' => 'range' );

		return array(
			'admin_page' => array(
				'name' => __( 'Design', 'md' ),
				'parent' => 'md_settings',
				'fields' => $fields
			)
		);
	}

	/**
	 * Organize options with labels.
	 *
	 * @since 5.0
	 */

	public function options() {
		return array(
			'site' => array(
				'bg_color' => __( 'Background', 'md' ),
				'primary' => __( 'Primary', 'md' ),
				'secondary' => __( 'Secondary', 'md' ),
				'tertiary' => __( 'Tertiary', 'md' ),
				'action' => __( 'Action', 'md' ),
				'accent' => __( 'Accent', 'md' )
			),
			'text' => array(
				'text' => __( 'Text', 'md' ),
				'text-sec' => __( 'Text Secondary', 'md' ),
				'links' => __( 'Links', 'md' ),
				'links_sec' => __( 'Links Secondary', 'md' ),
				'headline' => __( 'Headline', 'md' ),
				'headline-links' => __( 'Headline Links', 'md' )
			),
			'button' => array(
				'button' => __( 'Background color', 'md' ),
				'button-text' => __( 'Text color', 'md' ),
				'button-sec' => __( 'Background color', 'md' ),
				'button-sec-text' => __( 'Text color', 'md' )
			),
			'header' => array(
				'bg_color' => __( 'Background', 'md' ),
				'border_color' => __( 'Border', 'md' ),
				'color' => __( 'Text', 'md' ),
				'site_title' => __( 'Site Title', 'md' ),
				'site_tagline' => __( 'Tagline', 'md' ),
				'menu' => array(
					'links' => __( 'Links', 'md' ),
					'hover' => __( 'Links Hover', 'md' ),
					'active' => __( 'Links Active', 'md' )
				),
				'submenu' => array(
					'bg_color' => __( 'Background', 'md' ),
					'links' => __( 'Links', 'md' ),
					'hover' => __( 'Links Hover', 'md' )
				)
			),
			'main_menu' => array(
				'bg_color' => __( 'Background', 'md' ),
				'subtext' => __( 'Text', 'md' ),
				'links' => __( 'Links', 'md' ),
				'active' => __( 'Links Active', 'md' ),
				'sub_menu' => __( 'Background', 'md' ),
				'submenu_links' => __( 'Color', 'md' ),
				'icons' => __( 'Icons', 'md' ),
				'social' => __( 'Social Icons', 'md' )
			),
			'content' => array(
				'bg_color' => __( 'Background', 'md' ),
				'border_color' => __( 'Border', 'md' )
			),
			'sidebar' => array(
				'bg_color' => __( 'Background', 'md' ),
				'text' => __( 'Text', 'md' ),
				'title' => __( 'Title', 'md' ),
				'links' => __( 'Links', 'md' )
			),
			'footer' => array(
				'bg_color' => __( 'Background', 'md' ),
				'border_color' => __( 'Border', 'md' ),
				'text' => __( 'Text', 'md' ),
				'title' => __( 'Title', 'md' ),
				'links' => __( 'Links', 'md' )
			)
		);
	}

	/**
	 * Create admin settings fields.
	 *
	 * @since 5.0
	 */

	public function admin_page() {
		$options = $this->options();
		$defaults = $this->data['defaults'];
		$values = $this->data['values'];
		$sanitize = $this->sanitize;
		$line_height = $values['typography']['body']['line_height']['desktop'];
		$layout_spacing = $line_height + round( $line_height / 2 );
		$cover = $this->fields->module( array( 'featured_image', 'cover_position' ) );
		$disable_overlay = $this->fields->module( array( 'featured_image', 'cover_styles', 'disable_cover' ) );
		include( 'templates/admin-page.php' );
	}

	/**
	 * Popups admin scripts.
	 *
	 * @since 5.0
	 */

	public function admin_scripts() { ?>
		<script>
			document.getElementById( 'marketers_delight_colors_logo_html_display_enable' ).onchange = function( e ) {
				jQuery( '.md-header-logo' ).toggleClass( 'md-has-logo-html' );
			};
			( function() {
				document.getElementById( '<?php echo $this->_prefix; ?>_featured_image_cover_position' ).onchange = function() {
					document.getElementById( 'md_cover_settings' ).style.display = this.value !== '' ? 'block' : 'none';
				}
				document.getElementById( '<?php echo $this->_prefix; ?>_featured_image_cover_styles_disable_cover' ).onchange = function() {
					document.getElementById( 'md_cover_overlay' ).style.display = this.checked ? 'none' : 'block';
				}
			})();
		</script>
	<?php }

}

new md_colors;
