<?php
/**
 * Compile theme.json from MD design settings.
 *
 * @since 6.0
 */

class md_theme_json {

    // Create properties for re-use in class.

	private $values;
	private $colors;
	private $typography;
	private $fonts;
	private $effects;
	private $spacing;
	private $post_width;
	private $alignwide_width;

	/**
	 * Set dynamic values to properties.
	 *
	 * @since 6.0
	 */

	private function data() {
		$design = new md_design;
		$this->values = $design->values();
		$this->colors = $this->values['colors'];
		$this->typography = $this->values['typography'];
		$this->fonts = $design->fonts( $this->values );
		$this->effects = $design->effects();
		$this->spacing = $design->spacers( $this->values );
		$widths = $design->widths( $this->values );
		$this->post_width = $widths['post_width'];
		$this->alignwide_width = $widths['alignwide_width'];
	}

	/**
	 * Return line height with no unit.
	 *
	 * @since 6.0
	 */

	private function line_height( $font_size, $line_height ) {
		return (string) round( $line_height / $font_size, 2 );
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
	 * Match WordPress's kebab-case CSS slug for heading presets.
	 *
	 * @since 6.0
	 */

	private function heading_slug( $heading ) {
		return 'h-' . substr( $heading, 1 );
	}

	/**
	 * Build font size presets from the MD typography scale.
	 *
	 * @since 6.0
	 */

	private function font_sizes() {
		$body = $this->typography['body']['font_size'];
		$small_desktop = max( round( $body['desktop'] * 0.9 ), 16 );
		$small_mobile = max( round( $body['mobile'] * 0.9 ), 16 );

		$font_sizes = array(
			$this->font_size( __( 'Small', 'md' ), 'small', $small_desktop, $small_mobile ),
			$this->font_size( __( 'Normal', 'md' ), 'normal', $body['desktop'], $body['mobile'] ),
			$this->font_size( __( 'Intro', 'md' ), 'intro', round( $body['desktop'] * 1.2 ), round( $body['mobile'] * 1.2 ) )
		);

		$headings = array(
			'h6' => __( 'H6', 'md' ),
			'h5' => __( 'H5', 'md' ),
			'h4' => __( 'H4', 'md' ),
			'h3' => __( 'H3', 'md' ),
			'h2' => __( 'H2', 'md' ),
			'h1' => __( 'H1', 'md' )
		);

		foreach ( $headings as $heading => $name ) {
			$size = $this->typography[$heading]['font_size'];
			$font_sizes[] = $this->font_size( $name, $this->heading_slug( $heading ), $size['desktop'], $size['mobile'] );
		}

		$huge = $this->typography['huge']['font_size'];
		$font_sizes[] = $this->font_size( __( 'Huge', 'md' ), 'huge', $huge['desktop'], $huge['mobile'] );

		return $font_sizes;
	}

	/**
	 * Build spacing presets from the MD spacing scale.
	 *
	 * @since 6.0
	 */

	private function spacing_sizes() {
		$names = array(
			'small' => __( 'Small', 'md' ),
			'third' => __( 'Third', 'md' ),
			'half' => __( 'Half', 'md' ),
			'single' => __( 'Single', 'md' ),
			'mid' => __( 'Mid', 'md' ),
			'double' => __( 'Double', 'md' ),
			'triple' => __( 'Triple', 'md' ),
			'quad' => __( 'Quad', 'md' )
		);
		$sizes = array();

		foreach ( $names as $slug => $name )
			$sizes[] = array(
				'name' => $name,
				'slug' => $slug,
				'size' => $this->spacing[$slug]['desktop'] . 'px'
			);

		return $sizes;
	}

	/**
	 * Build the MD border radius preset.
	 *
	 * @since 6.0
	 */

	private function radius_sizes() {
		return array(
			array(
				'name' => __( 'Rounded', 'md' ),
				'slug' => 'rounded',
				'size' => $this->effects['border_radius']
			)
		);
	}

	/**
	 * Build box shadow presets from the MD effects scale.
	 *
	 * @since 6.0
	 */

	private function shadow_presets() {
		$names = array(
			'small' => __( 'Small', 'md' ),
			'medium' => __( 'Medium', 'md' ),
			'large' => __( 'Large', 'md' ),
			'huge' => __( 'Huge', 'md' )
		);
		$presets = array();

		foreach ( $names as $slug => $name )
			$presets[] = array(
				'name' => $name,
				'slug' => $slug,
				'shadow' => $this->effects['box_shadow'][$slug]
			);

		return $presets;
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

		if ( ! empty( $this->typography['h1']['font_family'] ) )
			$families[] = array(
				'fontFamily' => $this->typography['h1']['font_family'],
				'name' => __( 'Heading', 'md' ),
				'slug' => 'heading'
			);

		return $families;
	}

	/**
	 * Build heading element typography styles.
	 *
	 * @since 6.0
	 */

	private function heading_style( $type ) {
		$heading = $this->typography[$type];
		$font = isset( $this->fonts['heading_overrides'][$type] ) ? $this->fonts['heading_overrides'][$type] : array();
		$typography = array(
			'fontSize' => 'var:preset|font-size|' . $this->heading_slug( $type ),
			'fontWeight' => isset( $font['font_weight'] ) ? $font['font_weight'] : $this->fonts['heading']['font_weight'],
			'lineHeight' => $this->line_height( $heading['font_size']['desktop'], $heading['line_height']['desktop'] )
		);

		if ( isset( $font['font_family'] ) )
			$typography['fontFamily'] = $font['font_family'];

		return array(
			'typography' => $typography
		);
	}

	/**
	 * Build shared element styles from MD design values.
	 *
	 * @since 6.0
	 */

	private function element_styles() {
		$elements = array(
			'link' => array(
				'color' => array(
					'text' => $this->colors['site']['links']
				),
				'typography' => array(
					'textDecoration' => 'underline'
				),
				':hover' => array(
					'typography' => array(
						'textDecoration' => 'none'
					)
				)
			),
			'heading' => array(
				'color' => array(
					'text' => $this->colors['site']['headline']
				),
				'typography' => array(
					'fontFamily' => $this->fonts['heading']['font_family'],
					'fontWeight' => $this->fonts['heading']['font_weight']
				)
			)
		);

		foreach ( array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ) as $heading )
			$elements[$heading] = $this->heading_style( $heading );

		$elements['button'] = array(
			'border' => array(
				'radius' => 'var:preset|border-radius|rounded'
			),
			'color' => array(
				'background' => 'var:preset|color|button',
				'text' => $this->colors['site']['button-text']
			),
			'shadow' => '0 2px 4px rgba(0, 0, 0, 0.2)',
			'spacing' => array(
				'padding' => array(
					'top' => 'var:preset|spacing|half',
					'right' => 'calc(var(--wp--preset--spacing--half) + var(--wp--preset--spacing--third))',
					'bottom' => 'var:preset|spacing|half',
					'left' => 'calc(var(--wp--preset--spacing--half) + var(--wp--preset--spacing--third))'
				)
			),
			'typography' => array(
				'lineHeight' => '1'
			)
		);

		return $elements;
	}

	/**
	 * Write the compiled theme.json file.
	 *
	 * @since 6.0
	 */

	public function generate() {
		$json = wp_json_encode( $this->build(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );

		if ( ! empty( $json ) )
			file_put_contents( get_template_directory() . '/theme.json', $json );
	}

	/**
	 * Build theme.json data from MD settings.
	 *
	 * @since 6.0
	 */

	public function build() {
		$this->data();

		return apply_filters( 'md_theme_json', array(
			'$schema' => 'https://schemas.wp.org/wp/7.0/theme.json',
			'version' => 3,
			'settings' => array(
				'border' => array(
					'radius' => true,
					'radiusSizes' => $this->radius_sizes()
				),
				'color' => array(
					'defaultPalette' => false,
					'defaultGradients' => false,
					'palette' => md_editor_colors()
				),
				'dimensions' => array(
					'aspectRatio' => true
				),
				'typography' => array(
					'fluid' => true,
					'fontSizes' => $this->font_sizes(),
					'fontFamilies' => $this->font_families()
				),
				'layout' => array(
					'contentSize' => $this->post_width . 'px',
					'wideSize' => $this->alignwide_width . 'px'
				),
				'shadow' => array(
					'defaultPresets' => false,
					'presets' => $this->shadow_presets()
				),
				'spacing' => array(
					'blockGap' => true,
					'customSpacingSize' => false,
					'defaultSpacingSizes' => false,
					'margin' => true,
					'padding' => true,
					'spacingScale' => array(
						'steps' => 0
					),
					'spacingSizes' => $this->spacing_sizes()
				)
			),
			'styles' => apply_filters( 'md_theme_json_styles', array(
				'color' => array(
					'background' => $this->colors['palette']['background'],
					'text' => $this->colors['palette']['text-main']
				),
				'typography' => array(
					'fontFamily' => 'var:preset|font-family|body',
					'fontSize' => 'var:preset|font-size|normal',
					'fontWeight' => $this->fonts['body']['font_weight'],
					'lineHeight' => $this->line_height( $this->typography['body']['font_size']['desktop'], $this->typography['body']['line_height']['desktop'] )
				),
				'elements' => $this->element_styles(),
				'blocks' => array(
					'core/button' => array(
						'variations' => array(
							'outline' => array(
								'border' => array(
									'color' => $this->colors['site']['button'],
									'width' => '3px'
								),
								'color' => array(
									'background' => 'transparent',
									'text' => $this->colors['site']['button']
								)
							)
						)
					),
					'core/post-title' => array(
						'typography' => array(
							'fontFamily' => $this->fonts['heading']['font_family'],
							'fontSize' => 'var:preset|font-size|h1',
							'fontWeight' => $this->fonts['heading']['font_weight'],
							'lineHeight' => $this->line_height( $this->typography['h1']['font_size']['desktop'], $this->typography['h1']['line_height']['desktop'] )
						)
					)
				)
			), $this->values )
		), $this->values );
	}

}
