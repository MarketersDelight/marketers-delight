<?php
/**
 * Merge MD design settings into theme.json data at runtime. The theme's
 * theme.json file holds fixed settings; this class adds the values from MD settings.
 *
 * @since 6.0
 */

class md_theme_json {

	private $design;
	private $design_colors;
	private $values;
	private $fonts;

	/**
	 * Merge live MD design settings into WordPress theme.json data.
	 *
	 * @since 6.0
	 */

	public function init() {
		add_filter( 'wp_theme_json_data_theme', array( $this, 'filter' ) );
	}

	/**
	 * Merge MD settings into theme data, keeping font families from child theme.json files.
	 *
	 * @since 6.0
	 */

	public function filter( $theme_json ) {
		$data = $theme_json->get_data();
		$json = $this->build();
		$slugs = array_column( $json['settings']['typography']['fontFamilies'], 'slug' );
		$families = isset( $data['settings']['typography']['fontFamilies']['theme'] ) ? $data['settings']['typography']['fontFamilies']['theme'] : array();

		foreach ( $families as $family )
			if ( ! in_array( $family['slug'], $slugs ) )
				$json['settings']['typography']['fontFamilies'][] = $family;

		return $theme_json->update_with( $json );
	}

	/**
	 * Load MD design values used by the builders.
	 *
	 * @since 6.0
	 */

	private function data() {
		$this->design = new md_design;
		$this->design_colors = new md_design_colors;
		$this->values = $this->design->values();
		$this->fonts = $this->design->fonts( $this->values );
	}

	/**
	 * Build a list of presets from slug => name pairs and a slug-indexed source.
	 *
	 * @since 6.2
	 */

	private function presets( $names, $key, $source ) {
		$presets = array();

		foreach ( $names as $slug => $name )
			$presets[] = array(
				'name' => $name,
				'slug' => $slug,
				$key => $source[$slug]
			);

		return $presets;
	}

	/**
	 * Return a typography type's desktop line height with no unit.
	 *
	 * @since 6.0
	 */

	private function line_height( $type ) {
		$typography = $this->values['typography'][$type];

		return (string) round( $typography['line_height']['desktop'] / $typography['font_size']['desktop'], 2 );
	}

	/**
	 * Build a responsive font size preset.
	 *
	 * @since 6.0
	 */

	private function font_size( $name, $slug, $desktop, $mobile ) {
		$font_size = array(
			'name' => $name,
			'slug' => $slug,
			'size' => $desktop . 'px'
		);

		if ( $mobile < $desktop )
			$font_size['fluid'] = array(
				'min' => $mobile . 'px',
				'max' => $desktop . 'px'
			);

		return $font_size;
	}

	/**
	 * Build font size presets from the MD typography scale.
	 *
	 * @since 6.0
	 */

	private function font_sizes() {
		$typography = $this->values['typography'];
		$body = $typography['body']['font_size'];

		$font_sizes = array(
			$this->font_size( __( 'Tiny', 'md' ), 'tiny', md_text_scale( $body['desktop'], 2 ), md_text_scale( $body['mobile'], 2 ) ),
			$this->font_size( __( 'Small', 'md' ), 'small', md_text_scale( $body['desktop'] ), md_text_scale( $body['mobile'] ) ),
			$this->font_size( __( 'Normal', 'md' ), 'normal', $body['desktop'], $body['mobile'] ),
			$this->font_size( __( 'Intro', 'md' ), 'intro', round( $body['desktop'] * 1.2 ), round( $body['mobile'] * 1.2 ) )
		);

		foreach ( array( 'h6', 'h5', 'h4', 'h3', 'h2', 'h1' ) as $heading ) {
			$size = $typography[$heading]['font_size'];
			$font_sizes[] = $this->font_size( strtoupper( $heading ), 'h-' . substr( $heading, 1 ), $size['desktop'], $size['mobile'] );
		}

		$huge = $typography['huge']['font_size'];
		$font_sizes[] = $this->font_size( __( 'Huge', 'md' ), 'huge', $huge['desktop'], $huge['mobile'] );

		return $font_sizes;
	}

	/**
	 * Build spacing presets from the MD spacing scale.
	 *
	 * @since 6.0
	 */

	private function spacing_sizes() {
		$sizes = array();

		foreach ( $this->design->spacers( $this->values ) as $slug => $spacer )
			$sizes[$slug] = $spacer['desktop'] . 'px';

		return $this->presets( array(
			'small' => __( 'Small', 'md' ),
			'third' => __( 'Third', 'md' ),
			'half' => __( 'Half', 'md' ),
			'single' => __( 'Single', 'md' ),
			'mid' => __( 'Mid', 'md' ),
			'double' => __( 'Double', 'md' ),
			'triple' => __( 'Triple', 'md' ),
			'quad' => __( 'Quad', 'md' )
		), 'size', $sizes );
	}

	/**
	 * Build box shadow presets from the MD effects scale, plus a solid offset "card" shadow.
	 *
	 * @since 6.0
	 */

	private function shadow_presets( $shadows ) {
		$shadows['hard'] = '6px 6px 0 currentColor';

		return $this->presets( array(
			'small' => __( 'Small', 'md' ),
			'medium' => __( 'Medium', 'md' ),
			'large' => __( 'Large', 'md' ),
			'huge' => __( 'Huge', 'md' ),
			'hard' => __( 'Hard', 'md' )
		), 'shadow', $shadows );
	}

	/**
	 * Build duotone image filter presets from the MD color palette.
	 *
	 * @since 6.0
	 */

	private function duotone_presets( $palette ) {
		$c = array_column( $palette, 'color', 'slug' );

		return $this->presets( array(
			'ink' => __( 'Ink', 'md' ),
			'primary' => __( 'Primary', 'md' ),
			'highlight' => __( 'Highlight', 'md' )
		), 'colors', array(
			'ink' => array( $c['text-main'], $c['background'] ),
			'primary' => array( $c['primary'], $c['background'] ),
			'highlight' => array( $c['secondary'], $c['highlight'] )
		) );
	}

	/**
	 * Build font-family presets from MD settings.
	 *
	 * @since 6.0
	 */

	private function font_families() {
		$families = array(
			array(
				'fontFamily' => $this->fonts['body']['font_family'],
				'name' => __( 'Body', 'md' ),
				'slug' => 'body'
			)
		);

		if ( ! empty( $this->values['typography']['h1']['font_family'] ) )
			$families[] = array(
				'fontFamily' => $this->values['typography']['h1']['font_family'],
				'name' => __( 'Heading', 'md' ),
				'slug' => 'heading'
			);

		return $families;
	}

	/**
	 * Build a heading's line height, plus any font family or weight that differs from the shared heading.
	 *
	 * @since 6.0
	 */

	private function heading_style( $type ) {
		$typography = array( 'lineHeight' => $this->line_height( $type ) );

		if ( isset( $this->fonts['heading_overrides'][$type]['font_family'] ) )
			$typography['fontFamily'] = $this->fonts['heading_overrides'][$type]['font_family'];

		if ( isset( $this->fonts['heading_overrides'][$type]['font_weight'] ) )
			$typography['fontWeight'] = $this->fonts['heading_overrides'][$type]['font_weight'];

		if ( isset( $this->fonts['heading_overrides'][$type]['letter_spacing'] ) )
			$typography['letterSpacing'] = $this->fonts['heading_overrides'][$type]['letter_spacing'];

		if ( isset( $this->fonts['heading_overrides'][$type]['text_transform'] ) )
			$typography['textTransform'] = $this->fonts['heading_overrides'][$type]['text_transform'];

		return array( 'typography' => $typography );
	}

	/**
	 * Build element styles from MD design values.
	 *
	 * @since 6.0
	 */

	private function element_styles() {
		$colors = $this->values['colors'];
		$elements = array(
			'link' => array(
				'color' => array( 'text' => $colors['site']['link_color'] )
			),
			'heading' => array(
				'color' => array( 'text' => $colors['site']['headline_color'] ),
				'typography' => array(
					'fontFamily' => $this->fonts['heading']['font_family'],
					'fontWeight' => $this->fonts['heading']['font_weight']
				)
			),
			'button' => array(
				'color' => array(
					'background' => $colors['actions']['primary']['bg_color'],
					'text' => $colors['actions']['primary']['text_color']
				)
			)
		);

		if ( $this->fonts['heading']['letter_spacing'] !== '' )
			$elements['heading']['typography']['letterSpacing'] = $this->fonts['heading']['letter_spacing'];

		if ( $this->fonts['heading']['text_transform'] !== '' )
			$elements['heading']['typography']['textTransform'] = $this->fonts['heading']['text_transform'];

		foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) as $heading )
			$elements[$heading] = $this->heading_style( $heading );

		return $elements;
	}

	/**
	 * Build the theme.json values that come from MD settings.
	 *
	 * @since 6.0
	 */

	public function build() {
		$this->data();

		$effects = $this->design->effects();
		$widths = $this->design->widths( $this->values );
		$palette = $this->design_colors->editor_colors();
		$colors = $this->values['colors'];
		$button = $colors['actions']['primary']['bg_color'];
		$post_title = $this->heading_style( 'h1' );
		$post_title['typography']['fontFamily'] = $this->fonts['heading']['font_family'];
		$post_title['typography']['fontWeight'] = $this->fonts['heading']['font_weight'];

		if ( $this->fonts['heading']['letter_spacing'] !== '' )
			$post_title['typography']['letterSpacing'] = $this->fonts['heading']['letter_spacing'];

		if ( $this->fonts['heading']['text_transform'] !== '' )
			$post_title['typography']['textTransform'] = $this->fonts['heading']['text_transform'];

		$body = array(
			'fontWeight' => $this->fonts['body']['font_weight'],
			'lineHeight' => $this->line_height( 'body' )
		);

		if ( $this->fonts['body']['letter_spacing'] !== '' )
			$body['letterSpacing'] = $this->fonts['body']['letter_spacing'];

		if ( $this->fonts['body']['text_transform'] !== '' )
			$body['textTransform'] = $this->fonts['body']['text_transform'];

		$styles = array(
			'color' => array(
				'background' => $colors['site']['bg_color'],
				'text' => $colors['site']['text_color']
			),
			'typography' => $body,
			'elements' => $this->element_styles(),
			'blocks' => array(
				'core/button' => array(
					'variations' => array(
						'outline' => array(
							'border' => array( 'color' => $button ),
							'color' => array( 'text' => $button )
						)
					)
				),
				'core/post-title' => $post_title
			)
		);

		return apply_filters( 'md_theme_json', array(
			'version' => 3,
			'settings' => array(
				'border' => array(
					'radiusSizes' => array(
						array(
							'name' => __( 'Rounded', 'md' ),
							'slug' => 'rounded',
							'size' => $effects['border_radius']
						)
					)
				),
				'color' => array(
					'duotone' => $this->duotone_presets( $palette ),
					'gradients' => $this->design_colors->editor_gradients(),
					'palette' => $palette
				),
				'typography' => array(
					'fontSizes' => $this->font_sizes(),
					'fontFamilies' => $this->font_families()
				),
				'layout' => array(
					'contentSize' => $widths['post_width'] . 'px',
					'wideSize' => $widths['alignwide_width'] . 'px'
				),
				'shadow' => array(
					'presets' => $this->shadow_presets( $effects['box_shadow'] )
				),
				'spacing' => array(
					'blockGap' => true,
					'spacingSizes' => $this->spacing_sizes()
				)
			),
			'styles' => apply_filters_deprecated( 'md_theme_json_styles', array( $styles, $this->values ), '6.2.1', 'md_theme_json' )
		), $this->values );
	}

}
