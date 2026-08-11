<?php
/**
 * Tests the inherited typography scale and its body/h1 safety constraints.
 *
 * @since 6.0
 */

class DesignTest extends MD_TestCase {

	private $design;
	private $colors;

	protected function setUp(): void {
		parent::setUp();
		$this->design = new md_design;
		$this->colors = new md_design_colors;
	}

	private function sizes( $typography, $device ) {
		$sizes = array( $typography['body']['font_size'][$device] );

		foreach ( array( 'h6', 'h5', 'h4', 'h3', 'h2', 'h1' ) as $heading )
			$sizes[] = $typography[$heading]['font_size'][$device];

		return $sizes;
	}

	public function test_default_scale_uses_body_and_h1_as_geometric_anchors() {
		$typography = $this->design->defaults()['typography'];

		$this->assertEquals( array( 20, 23, 26, 30, 34, 39, 45 ), $this->sizes( $typography, 'desktop' ) );
		$this->assertEquals( array( 18, 20, 22, 25, 28, 31, 34 ), $this->sizes( $typography, 'mobile' ) );
	}

	public function test_alignwide_width_preserves_the_frontend_breakout_ratio() {
		$values = $this->design->values();
		$widths = $this->design->widths( $values );

		$this->assertSame( 896.0, $widths['alignwide_width'] );
	}

	public function test_color_defaults_use_the_normalized_semantic_groups() {
		$colors = $this->colors->defaults();
		$roles = $this->colors->roles;
		$assert_normalized = function( $entries ) use ( &$assert_normalized ) {
			if ( array_key_exists( 'default', $entries ) ) {
				$this->assertArrayHasKey( 'label', $entries );
				$this->assertArrayNotHasKey( 'palette', $entries );
				$this->assertArrayNotHasKey( 'inherit_label', $entries );

				if ( isset( $entries['inherit'] ) ) {
					$this->assertIsArray( $entries['inherit'] );
					$this->assertSame( '', $entries['default'] );
					$this->assertNotSame( '', $this->colors->role_label( $entries['inherit'] ) );
				}

				return;
			}

			foreach ( $entries as $children )
				$assert_normalized( $children );
		};

		$assert_normalized( $roles );

		$this->assertSame( 'background', $colors['site']['bg_color'] );
		$this->assertSame( '#FFFFFF', $colors['site']['contrast_text_color'] );
		$this->assertSame( 'button', $colors['actions']['primary']['bg_color'] );
		$this->assertSame( '#AE2525', $colors['actions']['status']['danger_color'] );
		$this->assertSame( '#F58F2A', $colors['actions']['status']['warning_color'] );
		$this->assertSame( '#FFFFFF', $colors['header']['bg_color'] );
		$this->assertSame( '', $colors['header']['menu']['link_color'] );
		$this->assertSame( 'surface', $colors['content']['main_bg_color'] );
		$this->assertSame( '#FFFFFF', $colors['content']['box_bg_color'] );
		$this->assertSame( '', $colors['content']['box_border_color'] );
		$this->assertSame( '#1E1E1E', $colors['content']['box_text_color'] );
		$this->assertSame( '#777777', $colors['content']['box_muted_text_color'] );
		$this->assertSame( 'primary', $colors['content']['box_link_color'] );
		$this->assertArrayNotHasKey( 'box_headline_color', $roles['content'] );
		$this->assertArrayNotHasKey( 'box_headline_link_color', $roles['content'] );
		$this->assertSame( '', $colors['sidebar']['bg_color'] );
		$this->assertSame( 'surface', $colors['panel']['bg_color'] );
		$this->assertArrayHasKey( 'border_color', $colors['header']['submenu'] );
		$this->assertSame( array( 'site', 'muted_text_color' ), $roles['site']['muted_link_color']['inherit'] );
		$this->assertSame( 'Muted Text', $this->colors->role_label( $roles['site']['muted_link_color']['inherit'] ) );
		$this->assertSame( array( 'content', 'border_color' ), $roles['content']['box_border_color']['inherit'] );
		$this->assertSame( 'Content Border', $this->colors->role_label( $roles['content']['box_border_color']['inherit'] ) );
	}

	public function test_new_inherited_role_resolves_from_its_role_definition() {
		$this->colors->roles['content']['meta_color'] = array(
			'label' => 'Meta Text',
			'default' => '',
			'inherit' => array( 'site', 'muted_text_color' )
		);

		$colors = $this->colors->defaults();
		$colors = $this->colors->resolve( $colors, $this->colors->active_palette() );

		$this->assertSame( '#777777', $colors['content']['meta_color'] );
		$this->assertSame( 'Muted Text', $this->colors->role_label( array( 'site', 'muted_text_color' ) ) );
	}

	public function test_content_box_foreground_stays_independent_from_a_light_site_text_palette() {
		md_test_set_settings( array(
			'colors' => array(
				'palette' => array(
					'text-main' => array( 'hex' => '#FFFFFF' )
				)
			)
		) );

		$colors = $this->design->values()['colors'];

		$this->assertSame( '#FFFFFF', $colors['site']['text_color'] );
		$this->assertSame( '#FFFFFF', $colors['content']['box_bg_color'] );
		$this->assertSame( '#CCCCCC', $colors['content']['box_border_color'] );
		$this->assertSame( '#1E1E1E', $colors['content']['box_text_color'] );
	}

	public function test_circular_color_inheritance_returns_an_empty_fallback() {
		$this->colors->roles['site']['headline_color']['inherit'] = array( 'site', 'headline_link_color' );

		$colors = $this->colors->defaults();
		$colors = $this->colors->resolve( $colors, $this->colors->active_palette() );

		$this->assertSame( '', $colors['site']['headline_color'] );
		$this->assertSame( '', $colors['site']['headline_link_color'] );
	}

	public function test_base_palette_only_accepts_hex_overrides() {
		md_test_set_settings( array(
			'colors' => array(
				'palette' => array(
					'primary' => array(
						'hex' => '#123456',
						'name' => 'Renamed Color',
						'key' => 'renamed-color'
					)
				)
			)
		) );

		$palette = $this->colors->base_palette();

		$this->assertSame( '#123456', $palette['primary']['hex'] );
		$this->assertSame( 'Primary', $palette['primary']['name'] );
		$this->assertArrayNotHasKey( 'renamed-color', $palette );
	}

	public function test_values_only_resolve_known_color_fields() {
		md_test_set_settings( array(
			'colors' => array(
				'custom' => array(
					array(
						'hex' => '#123456',
						'name' => 'primary',
						'key' => 'primary'
					)
				)
			),
			'logo' => array(
				'site_title' => array( 'color' => 'secondary' )
			)
		) );

		$values = $this->design->values();

		$this->assertSame( 'primary', $values['colors']['custom'][0]['name'] );
		$this->assertSame( 'primary', $values['colors']['custom'][0]['key'] );
		$this->assertSame( '#2E2E2E', $values['logo']['site_title']['color'] );
	}

	public function test_color_fallbacks_follow_resolved_parent_roles() {
		md_test_set_settings( array(
			'colors' => array(
				'site' => array(
					'text_color' => '#101010',
					'muted_text_color' => '#202020'
				),
				'header' => array(
					'text_color' => '#123456',
					'menu' => array(
						'link_hover_color' => '#654321'
					)
				),
				'footer' => array(
					'text_color' => '#112233'
				),
				'sidebar' => array(
					'text_color' => '#303030',
					'title_color' => '#404040'
				),
				'panel' => array(
					'text_color' => '#505050'
				),
				'content' => array(
					'border_color' => '#909090',
					'box_text_color' => '#606060',
					'box_muted_text_color' => '#707070',
					'box_link_color' => '#808080'
				)
			)
		) );

		$colors = $this->design->values()['colors'];

		$this->assertSame( '#202020', $colors['site']['muted_link_color'] );
		$this->assertSame( '#101010', $colors['site']['headline_color'] );
		$this->assertSame( '#101010', $colors['site']['headline_link_color'] );
		$this->assertSame( '#123456', $colors['header']['menu']['link_color'] );
		$this->assertSame( '#654321', $colors['header']['menu']['link_active_color'] );
		$this->assertArrayHasKey( 'bg_color', $colors['sidebar'] );
		$this->assertSame( '', $colors['sidebar']['bg_color'] );
		$this->assertSame( '#404040', $colors['sidebar']['title_link_color'] );
		$this->assertSame( '#303030', $colors['sidebar']['link_color'] );
		$this->assertSame( '#505050', $colors['panel']['link_color'] );
		$this->assertSame( '#909090', $colors['content']['box_border_color'] );
		$this->assertSame( '#606060', $colors['content']['box_text_color'] );
		$this->assertSame( '#707070', $colors['content']['box_muted_text_color'] );
		$this->assertSame( '#808080', $colors['content']['box_link_color'] );
		$this->assertSame( '#112233', $colors['footer']['title_color'] );
		$this->assertSame( '#112233', $colors['footer']['title_link_color'] );
	}

	public function test_color_admin_defaults_ignore_the_fields_saved_custom_value() {
		md_test_set_settings( array(
			'colors' => array(
				'palette' => array(
					'background' => array( 'hex' => '#F5F5F5' )
				),
				'site' => array(
					'bg_color' => '#123456'
				),
				'header' => array(
					'bg_color' => '#234567',
					'text_color' => '#345678',
					'menu' => array(
						'link_color' => '#456789'
					)
				)
			)
		) );

		$colors = $this->design->values()['colors'];
		$inherit = $this->colors->inheritance( $colors );

		$this->assertSame( '#F5F5F5', $this->colors->base_palette()['background']['hex'] );
		$this->assertSame( '#FFFFFF', $this->colors->roles['header']['bg_color']['default'] );
		$this->assertSame( '#345678', $inherit['header']['menu']['link_color'] );
		$this->assertSame( '#456789', $colors['header']['menu']['link_color'] );
	}

	public function test_font_inheritance_is_resolved_for_compilers() {
		$fonts = $this->design->fonts();

		$this->assertSame( 'normal', $fonts['body']['font_weight'] );
		$this->assertSame( 'bold', $fonts['body']['bold'] );
		$this->assertSame( $fonts['body']['font_family'], $fonts['heading']['font_family'] );
		$this->assertSame( 'bold', $fonts['heading']['font_weight'] );
		$this->assertArrayNotHasKey( 'heading_overrides', $fonts );
	}

	public function test_saved_heading_fonts_override_body_inheritance() {
		md_test_set_settings( array(
			'typography' => array(
				'body' => array(
					'font_family' => 'Body Font',
					'font_weight' => '400',
					'bold' => '800'
				),
				'h1' => array(
					'font_family' => 'Heading Font',
					'font_weight' => '600'
				),
				'h2' => array(
					'font_family' => 'Secondary Heading Font'
				),
				'h3' => array(
					'font_weight' => '300'
				)
			)
		) );

		$fonts = $this->design->fonts();

		$this->assertSame( 'Body Font', $fonts['body']['font_family'] );
		$this->assertSame( '400', $fonts['body']['font_weight'] );
		$this->assertSame( '800', $fonts['body']['bold'] );
		$this->assertSame( 'Heading Font', $fonts['heading']['font_family'] );
		$this->assertSame( '600', $fonts['heading']['font_weight'] );
		$this->assertSame( array( 'font_family' => 'Secondary Heading Font' ), $fonts['heading_overrides']['h2'] );
		$this->assertSame( array( 'font_weight' => '300' ), $fonts['heading_overrides']['h3'] );
		$this->assertArrayNotHasKey( 'h4', $fonts['heading_overrides'] );
	}

	public function test_close_h1_values_are_given_room_for_six_ordered_steps() {
		md_test_set_settings( array(
			'typography' => array(
				'body' => array(
					'font_size' => array(
						'desktop' => 20,
						'mobile' => 18
					)
				),
				'h1' => array(
					'font_size' => array(
						'desktop' => 22,
						'mobile' => 19
					)
				)
			)
		) );

		$typography = $this->design->values()['typography'];

		$this->assertEquals( array( 20, 21, 22, 23, 24, 25, 26 ), $this->sizes( $typography, 'desktop' ) );
		$this->assertEquals( array( 18, 19, 20, 21, 22, 23, 24 ), $this->sizes( $typography, 'mobile' ) );
	}

	public function test_zero_anchors_fall_back_without_division_by_zero() {
		md_test_set_settings( array(
			'typography' => array(
				'body' => array(
					'font_size' => array(
						'desktop' => 0,
						'mobile' => 0
					)
				),
				'h1' => array(
					'font_size' => array(
						'desktop' => 0,
						'mobile' => 0
					)
				)
			)
		) );

		$typography = $this->design->values()['typography'];

		$this->assertEquals( array( 20, 21, 22, 23, 24, 25, 26 ), $this->sizes( $typography, 'desktop' ) );
		$this->assertEquals( array( 18, 19, 20, 21, 22, 23, 24 ), $this->sizes( $typography, 'mobile' ) );
	}

	public function test_positive_body_sizes_below_the_supported_minimum_are_clamped() {
		md_test_set_settings( array(
			'typography' => array(
				'body' => array(
					'font_size' => array(
						'desktop' => 5,
						'mobile' => 8
					)
				),
				'h1' => array(
					'font_size' => array(
						'desktop' => 10,
						'mobile' => 10
					)
				)
			)
		) );

		$typography = $this->design->values()['typography'];

		$this->assertEquals( array( 16, 17, 18, 19, 20, 21, 22 ), $this->sizes( $typography, 'desktop' ) );
		$this->assertEquals( array( 16, 17, 18, 19, 20, 21, 22 ), $this->sizes( $typography, 'mobile' ) );
	}

}
