<?php
/**
 * Holds frequently used data of more complex fields.
 *
 * @since 6.0
 */

class md_fields_data {

	public $values;

	/**
     * Assign shared class data and other setup actions.
 	 *
 	 * @since 6.0
 	 */

	public function __construct() {
		$this->values = $this->values();
	}

	/**
	 * A generic list of commonly referenced option values.
	 *
	 * @since 5.0
	 */

	public function values() {
		return array(
			'featured_image' => array(
				'title_right' => __( 'Right, title wrap', 'md' ),
				'title_left' => __( 'Left, title wrap', 'md' ),
				'title_center' => __( 'Center, title wrap', 'md' ),
				'right' => __( 'Right, content wrap', 'md' ),
				'left' => __( 'Left, content wrap', 'md' ),
				'center' => __( 'Center, no content wrap', 'md' ),
				'above_headline' => __( 'Before headline', 'md' ),
				'below_headline' => __( 'After headline', 'md' ),
				'remove' => __( 'Don\'t show', 'md' )
			)
		);
	}

	/**
     * A common fields structure for deploying Fonts & Typography options.
 	 *
 	 * @since 6.0
 	 */

	public function typography() {
		$fields = array();

		foreach ( array( 'desktop', 'mobile' ) as $device ) {
			$fields['font_size'][$device]['type'] = 'range';
			$fields['line_height'][$device]['type'] = 'range';
		}

		$fields['font_family']['type'] = 'font';

		$fields['font_type'] = array(
			'type' => 'select',
			'options' => array( 'default', 'google', 'typekit' )
		);

		$fields['font_weight'] = array(
			'type' => 'select',
			'options' => array_keys( $this->font_weights() )
		);

		return $fields;
	}

	/**
	 * Font weight options used by weight-select fields (label keyed by value).
	 *
	 * @since 4.8
	 */

	public function font_weights() {
		return array(
			'normal' => 'Regular',
			'bold' => 'Bold',
			'100' => '100',
			'200' => '200',
			'300' => '300',
			'400' => '400',
			'500' => '500',
			'600' => '600',
			'700' => '700',
			'800' => '800',
			'900' => '900'
		);
	}

	/**
	 * Font stacks the font picker offers, which need no font files. From
	 * Modern Font Stacks (modernfontstacks.com, CC0). Add your own with
	 * the md_font_stacks filter: id => array( name, stack ).
	 *
	 * @since 6.2
	 */

	public function font_stacks() {
		return apply_filters( 'md_font_stacks', array(
			'system' => array(
				'name' => __( 'System UI', 'md' ),
				'stack' => "system-ui, sans-serif"
			),
			'neo' => array(
				'name' => __( 'Neo-Grotesque', 'md' ),
				'stack' => "Inter, Roboto, 'Helvetica Neue', 'Arial Nova', 'Nimbus Sans', Arial, sans-serif"
			),
			'humanist' => array(
				'name' => __( 'Humanist', 'md' ),
				'stack' => "Seravek, 'Gill Sans Nova', Ubuntu, Calibri, 'DejaVu Sans', source-sans-pro, sans-serif"
			),
			'geometric' => array(
				'name' => __( 'Geometric Humanist', 'md' ),
				'stack' => "Avenir, Montserrat, Corbel, 'URW Gothic', source-sans-pro, sans-serif"
			),
			'classical' => array(
				'name' => __( 'Classical Humanist', 'md' ),
				'stack' => "Optima, Candara, 'Noto Sans', source-sans-pro, sans-serif"
			),
			'rounded' => array(
				'name' => __( 'Rounded Sans', 'md' ),
				'stack' => "ui-rounded, 'Hiragino Maru Gothic ProN', Quicksand, Comfortaa, Manjari, 'Arial Rounded MT', 'Arial Rounded MT Bold', Calibri, source-sans-pro, sans-serif"
			),
			'industrial' => array(
				'name' => __( 'Industrial', 'md' ),
				'stack' => "Bahnschrift, 'DIN Alternate', 'Franklin Gothic Medium', 'Nimbus Sans Narrow', sans-serif-condensed, sans-serif"
			),
			'transitional' => array(
				'name' => __( 'Transitional', 'md' ),
				'stack' => "Charter, 'Bitstream Charter', 'Sitka Text', Cambria, serif"
			),
			'oldstyle' => array(
				'name' => __( 'Old Style', 'md' ),
				'stack' => "'Iowan Old Style', 'Palatino Linotype', 'URW Palladio L', P052, serif"
			),
			'antique' => array(
				'name' => __( 'Antique', 'md' ),
				'stack' => "Superclarendon, 'Bookman Old Style', 'URW Bookman', 'URW Bookman L', 'Georgia Pro', Georgia, serif"
			),
			'slab' => array(
				'name' => __( 'Slab Serif', 'md' ),
				'stack' => "Rockwell, 'Rockwell Nova', 'Roboto Slab', 'DejaVu Serif', 'Sitka Small', serif"
			),
			'didone' => array(
				'name' => __( 'Didone', 'md' ),
				'stack' => "Didot, 'Bodoni MT', 'Noto Serif Display', 'URW Palladio L', P052, Sylfaen, serif"
			)
		) );
	}

	/**
	 * Fonts with files that WordPress loads on the front end itself: fonts
	 * activated in the Font Library, and fonts a child theme bundles in its
	 * theme.json. Same shape as font_stacks(), plus where each comes from.
	 *
	 * @since 6.2
	 */

	public function site_fonts() {
		$fonts = array();
		$families = function_exists( 'wp_get_global_settings' ) ? wp_get_global_settings( array( 'typography', 'fontFamilies' ) ) : array();
		$sources = array(
			'custom' => __( 'Font Library', 'md' ),
			'theme' => __( 'Theme', 'md' )
		);

		foreach ( $sources as $origin => $source ) {
			$list = isset( $families[$origin] ) ? (array) $families[$origin] : array();

			foreach ( $list as $family )
				$fonts = array_merge( $fonts, $this->site_font( $family, $source ) );
		}

		return $fonts;
	}

	/**
	 * One font family from theme.json settings, if it has font files.
	 *
	 * @since 6.2
	 */

	private function site_font( $family, $source ) {
		if ( empty( $family['fontFamily'] ) || empty( $family['name'] ) || empty( $family['fontFace'] ) )
			return array();

		$stack = $family['fontFamily'];

		if ( ! preg_match( '/(serif|monospace|cursive|fantasy|system-ui)\s*$/i', $stack ) )
			$stack .= ', sans-serif';

		return array(
			( ! empty( $family['slug'] ) ? $family['slug'] : sanitize_title( $family['name'] ) ) => array(
				'name' => $family['name'],
				'stack' => $stack,
				'source' => $source
			)
		);
	}

	/**
	 * Return a list of term IDs for a given taxonomy.
	 *
	 * @since 5.3.1
	 */

	public function terms( $taxonomy = 'category' ) {
		$cats = array();
		$terms = get_terms( $taxonomy );

		foreach ( $terms as $term )
			if ( isset( $term->term_id ) )
				$cats[] = intval( $term->term_id );

		return $cats;
	}

	/**
	 * Return a save ready list of WP menus.
	 *
	 * @since 6.0
	 */

	public function menus() {
		$menus = array( 'ids' => array(), 'options' => array() );
		$nav_menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );

		if ( ! empty( $nav_menus ) )
			foreach ( $nav_menus as $menu ) {
				$menu_id = intval( $menu->term_id );
				$menus['ids'][] = $menu_id;
				$menus['options'][$menu_id] = sanitize_text_field( $menu->name );
			}

		return $menus;
	}

	/**
     * A collection of save fields to be pre-grouped for Page Settings.
 	 *
 	 * @since 6.0
 	 */

	public function page_fields() {
		return apply_filters( 'md_page_settings_fields', array(
			'archives_title' => array( 'type' => 'text', 'tokens' => true ),
			'archives_text' => array( 'type' => 'textarea' )
		) );
	}

	/**
	 * Returns save field definitions for visibility conditions.
	 *
	 * @since 6.0
	 */

	public function visibility_condition() {
		return array(
			'type' => 'checkbox',
			'options' => array_keys( md_visibility_conditions() )
		);
	}

	/**
	 * Collect a list of fields in a Links Group.
	 *
	 * @since 6.0
	 */

	public function links( $args = array() ) {
		$group = isset( $args['group'] ) ? $args['group'] : array();
		$fields = array(
			'visibility' => array(
				'field' => 'visibility',
				'save'  => $this->visibility_condition()
			),
			'name' => array(
				'field' => 'name',
				'save' => array( 'type' => 'text' )
			),
			'text' => array(
				'field' => 'text',
				'save' => array( 'type' => 'text' )
			),
			'subtitle' => array(
				'field' => 'subtitle',
				'save' => array( 'type' => 'text' )
			),
			'type' => array(
				'field' => 'type',
				'save' => array(
					'type' => 'select',
					'options' => array( 'url', 'popup', 'phone' )
				)
			),
			'style' => array(
				'field' => 'style',
				'save' => array(
					'type' => 'select',
					'options' => array( 'button' )
				)
			),
			'color' => array(
				'field' => 'color',
				'save' => array( 'type' => 'color' )
			),
			'icon' => array(
				'field' => 'icon',
				'save' => array(
					'type' => 'select',
					'options' => md_get_icons( 'ids' )
				)
			),
			'url' => array(
				'field' => 'url',
				'save' => array( 'type' => 'url' )
			),
			'settings' => array(
				'field' => 'settings',
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'new', 'icon_end' )
				)
			),
			'toggle' => array(
				'field' => 'toggle',
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'hide_label', 'hide_label_mobile' )
				)
			),
			'phone' => array(
				'field' => 'phone',
				'save' => array( 'type' => 'text' )
			),
			'popup' => array(
				'field' => 'popup',
				'save' => array(
					'type' => 'select',
					'options' => function_exists( 'md_get_popups' ) ? md_get_popups( 'ids' ) : array()
				)
			),
			'size' => array(
				'field' => 'size',
				'save' => array(
					'type' => 'select',
					'options' => array( 'small', 'large' )
				)
			),
			'button_style' => array(
				'field' => 'button_style',
				'save' => array(
					'type' => 'checkbox',
					'options' => array( 'outline', 'frame' )
				)
			),
		);

		if ( $group )
			foreach ( $fields as $key => $options ) {
				$fields[$key]['field'] = (array) $key;
				$fields[$key]['field'] = array_merge( $group, $fields[$key]['field'] );
			}

		if ( isset( $args['sort'] ) ) {
			$sort = array();

			if ( $args['sort'] =='save' ) {
				foreach ( $fields as $key => $options ) {
					$field_key = $options['field'];
					$sort[$field_key] = $options['save'];
				}
			}

			$fields = $sort;
		}
		return $fields;
	}

}
