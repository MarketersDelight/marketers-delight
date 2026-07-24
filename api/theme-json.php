<?php
/**
 * This class compiles theme.json and writes custom CSS to block editor.
 *
 * @since 6.0
 */

class md_theme_json {

    // Create properties for re-use in class.

	private $values;
	private $colors;
	private $typography;
	private $body_font_size;
	private $body_font_family;
	private $body_font_weight;
	private $line_height;
	private $heading_font;
	private $heading_font_weight;
	private $spacing;
	private $content_width;
	private $post_width;
	private $wide_width;

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
		$this->line_height = $this->typography['body']['line_height']['desktop'];
		$this->spacing = array(
			'quad' => round( $this->line_height * 4 ),
			'half' => round( $this->line_height / 2 ),
			'third' => round( $this->line_height / 3 )
		);
		$this->content_width = $this->colors['width']['content_width'];
		$this->post_width = $this->colors['width']['post'];
		$this->wide_width = round( $this->post_width + ( ( $this->post_width * $this->spacing['quad'] * 2 ) / $this->content_width ) );
		$this->body_font_size = $this->typography['body']['font_size']['desktop'];
		$this->body_font_family = $this->typography['body']['font_family'];
		$this->body_font_weight = ! empty( $this->typography['body']['bold'] ) ? $this->typography['body']['bold'] : '700';
		$this->heading_font = ! empty( $this->typography['h1']['font_family'] ) ? $this->typography['h1']['font_family'] : $this->body_font_family;
		$this->heading_font_weight = ! empty( $this->typography['h1']['font_weight'] ) ? $this->typography['h1']['font_weight'] : $this->body_font_weight;
	}

	/**
	 * Return line-height for em format.
	 *
	 * @since 6.0
	 */

	private function line_height( $font_size, $line_height ) {
		return round( $line_height / $font_size );
	}

	/**
	 * Build font-family presets from MD settings.
	 *
	 * @since 6.0
	 */

	private function font_families() {
		$families = array(
			array(
				'fontFamily' => $this->body_font_family,
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
		$typography = array(
			'fontSize' => $heading['font_size']['desktop'] . 'px',
			'fontWeight' => ( ! empty( $heading['font_weight'] ) ? $heading['font_weight'] : $this->heading_font_weight ),
			'lineHeight' => $this->line_height( $heading['font_size']['desktop'], $heading['line_height']['desktop'] )
		);

		if ( ! empty( $heading['font_family'] ) && $heading['font_family'] !== $this->heading_font )
			$typography['fontFamily'] = $heading['font_family'];

		return array(
			'typography' => $typography
		);
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
			'$schema' => 'https://schemas.wp.org/trunk/theme.json',
			'version' => 2,
			'settings' => array(
				'color' => array(
					'defaultPalette' => false,
					'defaultGradients' => false,
					'palette' => md_editor_colors()
				),
				'typography' => array(
					'defaultFontSizes' => false,
					'fontSizes' => array(
						array(
							'name' => __( 'Small', 'md' ),
							'slug' => 'small',
							'size' => $this->typography['h6']['font_size']['desktop'] . 'px'
						),
						array(
							'name' => __( 'Normal', 'md' ),
							'slug' => 'normal',
							'size' => $this->body_font_size . 'px'
						),
						array(
							'name' => __( 'Intro', 'md' ),
							'slug' => 'intro',
							'size' => round( $this->body_font_size * 1.2 ) . 'px'
						),
						array(
							'name' => __( 'H3', 'md' ),
							'slug' => 'h3',
							'size' => $this->typography['h3']['font_size']['desktop'] . 'px'
						),
						array(
							'name' => __( 'H2', 'md' ),
							'slug' => 'h2',
							'size' => $this->typography['h2']['font_size']['desktop'] . 'px'
						),
						array(
							'name' => __( 'H1', 'md' ),
							'slug' => 'h1',
							'size' => $this->typography['h1']['font_size']['desktop'] . 'px'
						),
						array(
							'name' => __( 'Huge', 'md' ),
							'slug' => 'huge',
							'size' => $this->typography['huge']['font_size']['desktop'] . 'px'
						)
					),
					'fontFamilies' => $this->font_families()
				),
				'layout' => array(
					'contentSize' => $this->post_width . 'px',
					'wideSize' => $this->wide_width . 'px'
				),
				'spacing' => array(
					'padding' => false
				)
			),
			'styles' => apply_filters( 'md_theme_json_styles', array(
				'blocks' => array(
					'core/button' => array(
						'border' => array(
							'radius' => '6px'
						),
						'color' => array(
							'background' => $this->colors['site']['button'],
							'text' => $this->colors['site']['button-text']
						),
						'shadow' => '0 2px 4px rgba(0, 0, 0, 0.2)',
						'spacing' => array(
							'padding' => array(
								'top' => $this->spacing['half'] . 'px',
								'right' => ( $this->spacing['half'] + $this->spacing['third'] ) . 'px',
								'bottom' => $this->spacing['half'] . 'px',
								'left' => ( $this->spacing['half'] + $this->spacing['third'] ) . 'px'
							)
						),
						'typography' => array(
							'lineHeight' => '1'
						),
						'variations' => array(
							'outline' => array(
								'border' => array(
									'width' => '3px'
								)
							)
						)
					),
					'core/post-title' => array(
						'typography' => array(
							'fontFamily' => $this->heading_font,
							'fontSize' => $this->typography['h1']['font_size']['desktop'] . 'px',
							'fontWeight' => $this->heading_font_weight,
							'lineHeight' => $this->line_height( $this->typography['h1']['font_size']['desktop'], $this->typography['h1']['line_height']['desktop'] )
						)
					)
				)
			), $this->values )
		), $this->values );
	}

}
