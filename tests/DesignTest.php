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

	public function test_saved_widths_feed_the_width_maths() {
		md_test_set_settings( array( 'settings' => array( 'width' => array( 'content' => 700, 'sidebar' => 300, 'panel' => 250, 'site' => 1300 ) ) ) );
		$widths = $this->design->widths( $this->design->values() );

		$this->assertEquals( 700, $widths['post_width'] );
		$this->assertEquals( 300, $widths['sidebar_width'] );
		$this->assertEquals( 250, $widths['panel_width'] );
		$this->assertEquals( 1300, $widths['site_width'] );
	}

	public function test_old_colors_widths_are_ignored() {
		$default = $this->design->widths( $this->design->values() );
		md_test_set_settings( array( 'colors' => array( 'width' => array( 'content' => 700, 'sidebar' => 300, 'site' => 1300 ) ) ) );

		$this->assertEquals( $default, $this->design->widths( $this->design->values() ) );
	}

	public function test_site_width_adds_one_single_spacer_between_content_and_sidebar() {
		$values = $this->design->values();
		$widths = $this->design->widths( $values );
		$single = $values['typography']['body']['line_height']['desktop'];

		$this->assertEquals( $widths['content_width'] + $widths['sidebar_width'] + $single, $widths['site_width'] );
		$this->assertEquals( $widths['content_width'] + $widths['sidebar_width'] + $widths['panel_width'] + $single * 2, $widths['site_width_wide'] );
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
		$this->assertSame( '#C75000', $colors['actions']['status']['warning_color'] );
		$this->assertSame( 'card', $colors['header']['bg_color'] );
		$this->assertSame( '', $colors['header']['menu']['link_color'] );
		$this->assertSame( 'surface', $colors['content']['main_bg_color'] );
		$this->assertSame( 'card', $colors['content']['box_bg_color'] );
		$this->assertSame( '', $colors['content']['box_border_color'] );
		$this->assertSame( 'text-main', $colors['content']['box_text_color'] );
		$this->assertSame( 'text-secondary', $colors['content']['box_muted_text_color'] );
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

		$this->assertSame( '#6B6B6B', $colors['content']['meta_color'] );
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

		$this->assertSame( '#1E1E1E', $colors['site']['text_color'] );
		$this->assertSame( '#FFFFFF', $colors['content']['box_bg_color'] );
		$this->assertSame( '#CCCCCC', $colors['content']['box_border_color'] );
		$this->assertSame( '#1E1E1E', $colors['content']['box_text_color'] );
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
		$this->assertSame( '#202020', $colors['panel']['link_color'] );
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

		$this->assertSame( '#F5F5F5', $this->colors->base_palette()['background']['hex'] );
		$this->assertSame( 'card', $this->colors->roles['header']['bg_color']['default'] );
		$this->assertSame( '#345678', $this->colors->path_value( $colors, $this->colors->roles['header']['menu']['link_color']['inherit'] ) );
		$this->assertSame( '#456789', $colors['header']['menu']['link_color'] );
	}

	private function palette_colors( $palette ) {
		$saved = array();

		foreach ( $palette as $key => $hex )
			$saved[$key] = array( 'hex' => $hex );

		md_test_set_settings( array( 'colors' => array( 'palette' => $saved ) ) );

		return $this->design->values()['colors'];
	}

	public function test_changing_background_only_changes_the_page() {
		$colors = $this->palette_colors( array( 'background' => '#FAF6EE' ) );

		$this->assertSame( '#FAF6EE', $colors['site']['bg_color'] );
		$this->assertSame( '#FFFFFF', $colors['header']['bg_color'] );
		$this->assertSame( '#FFFFFF', $colors['content']['box_bg_color'] );
		$this->assertSame( '#FFFFFF', $colors['footer']['bg_color'] );
	}

	public function test_card_moves_the_boxes_and_bars_together() {
		$colors = $this->palette_colors( array( 'card' => '#FAF6EE' ) );

		$this->assertSame( '#FAF6EE', $colors['header']['bg_color'] );
		$this->assertSame( '#FAF6EE', $colors['header']['submenu']['bg_color'] );
		$this->assertSame( '#FAF6EE', $colors['content']['box_bg_color'] );
		$this->assertSame( '#FAF6EE', $colors['footer']['bg_color'] );
		$this->assertSame( '#FFFFFF', $colors['site']['bg_color'] );
	}

	public function test_text_main_reaches_text_but_not_secondary() {
		$colors = $this->palette_colors( array( 'secondary' => '#1E1E1E', 'text-main' => '#777777' ) );

		$this->assertSame( '#777777', $colors['header']['text_color'] );
		$this->assertSame( '#777777', $colors['content']['box_text_color'] );
		$this->assertSame( '#777777', $colors['footer']['text_color'] );
		$this->assertSame( '#1E1E1E', $colors['actions']['secondary']['bg_color'] );
	}

	public function test_text_secondary_leaves_sidebar_text_and_footer_links_alone() {
		$colors = $this->palette_colors( array( 'text-secondary' => '#0F766E' ) );

		$this->assertSame( '#0F766E', $colors['content']['box_muted_text_color'] );
		$this->assertSame( '#6b6b6b', $colors['sidebar']['text_color'] );
		$this->assertSame( '#6b6b6b', $colors['footer']['link_color'] );
	}

	public function test_auto_keeps_readable_colors_like_white_on_green() {
		$colors = $this->palette_colors( array( 'button' => '#22A340' ) );

		$this->assertSame( '#FFFFFF', $colors['actions']['primary']['text_color'] );
	}

	public function test_light_text_on_a_white_card_never_stays_unreadable() {
		$colors = $this->palette_colors( array( 'background' => '#1C1C1C', 'text-main' => '#FFFFFF' ) );

		$this->assertSame( '#FFFFFF', $colors['header']['bg_color'] );
		$this->assertSame( '#1E1E1E', $colors['header']['text_color'] );
		$this->assertSame( '#1E1E1E', $colors['content']['box_text_color'] );
	}

	public function test_a_dark_palette_makes_a_dark_readable_site() {
		$colors = $this->palette_colors( array(
			'background' => '#1C1C1C',
			'surface' => '#2A2A2A',
			'card' => '#2A2A2A',
			'text-main' => '#FFFFFF',
			'text-secondary' => '#BBBBBB',
			'divider' => '#343434'
		) );

		$this->assertSame( '#2A2A2A', $colors['header']['bg_color'] );
		$this->assertSame( '#FFFFFF', $colors['header']['text_color'] );
		$this->assertSame( '#2A2A2A', $colors['footer']['bg_color'] );
		$this->assertSame( '#FFFFFF', $colors['footer']['text_color'] );
		$this->assertSame( '#343434', $colors['header']['border_color'] );
	}

	public function test_saved_colors_win_even_when_unreadable() {
		md_test_set_settings( array( 'colors' => array(
			'palette' => array( 'text-main' => array( 'hex' => '#FFFFFF' ) ),
			'header' => array( 'text_color' => '#FFFFFF' )
		) ) );

		$this->assertSame( '#FFFFFF', $this->design->values()['colors']['header']['text_color'] );
	}

	public function test_page_text_stays_readable_on_the_main_area() {
		$colors = $this->palette_colors( array( 'surface' => '#1A1A1A' ) );

		$this->assertSame( '#FFFFFF', $colors['site']['text_color'] );
		$this->assertSame( '#FFFFFF', $colors['site']['headline_color'] );
	}

	public function test_page_text_ignores_a_dark_page_behind_a_light_main_area() {
		$colors = $this->palette_colors( array( 'background' => '#111111' ) );

		$this->assertSame( '#1E1E1E', $colors['site']['text_color'] );
	}

	public function test_muted_text_falls_back_to_page_text() {
		$colors = $this->palette_colors( array( 'text-secondary' => '#EEEEEE' ) );

		$this->assertSame( '#1E1E1E', $colors['site']['muted_text_color'] );
	}

	public function test_saved_page_text_wins_even_when_unreadable() {
		md_test_set_settings( array( 'colors' => array(
			'palette' => array( 'surface' => array( 'hex' => '#1A1A1A' ) ),
			'site' => array( 'text_color' => '#1E1E1E' )
		) ) );

		$this->assertSame( '#1E1E1E', $this->design->values()['colors']['site']['text_color'] );
	}

	public function test_font_inheritance_is_resolved_for_compilers() {
		$fonts = $this->design->fonts( $this->design->values() );

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

		$fonts = $this->design->fonts( $this->design->values() );

		$this->assertSame( 'Body Font', $fonts['body']['font_family'] );
		$this->assertSame( '400', $fonts['body']['font_weight'] );
		$this->assertSame( '800', $fonts['body']['bold'] );
		$this->assertSame( 'Heading Font', $fonts['heading']['font_family'] );
		$this->assertSame( '600', $fonts['heading']['font_weight'] );
		$this->assertSame( array( 'font_family' => 'Secondary Heading Font' ), $fonts['heading_overrides']['h2'] );
		$this->assertSame( array( 'font_weight' => '300' ), $fonts['heading_overrides']['h3'] );
		$this->assertArrayNotHasKey( 'h4', $fonts['heading_overrides'] );
	}

	public function test_letter_values_are_empty_until_saved() {
		$fonts = $this->design->fonts( $this->design->values() );

		$this->assertSame( '', $fonts['heading']['letter_spacing'] );
		$this->assertSame( '', $fonts['heading']['text_transform'] );
		$this->assertSame( '', $fonts['body']['letter_spacing'] );
		$this->assertSame( '', $this->design->letters( array() ) );
	}

	public function test_heading_letters_come_from_h1_and_levels_only_override_differences() {
		md_test_set_settings( array(
			'typography' => array(
				'h1' => array( 'letter_spacing' => '-0.5', 'text_transform' => 'uppercase' ),
				'h2' => array( 'letter_spacing' => '-0.5', 'text_transform' => 'uppercase' ),
				'h3' => array( 'letter_spacing' => '0' ),
				'h4' => array( 'text_transform' => 'lowercase' ),
				'header' => array( 'letter_spacing' => '1.5', 'text_transform' => 'capitalize' )
			)
		) );

		$fonts = $this->design->fonts( $this->design->values() );

		$this->assertSame( '-0.5px', $fonts['heading']['letter_spacing'] );
		$this->assertSame( 'uppercase', $fonts['heading']['text_transform'] );
		$this->assertArrayNotHasKey( 'h2', $fonts['heading_overrides'] );
		$this->assertSame( array( 'letter_spacing' => '0px' ), $fonts['heading_overrides']['h3'] );
		$this->assertSame( array( 'text_transform' => 'lowercase' ), $fonts['heading_overrides']['h4'] );
		$this->assertArrayNotHasKey( 'h5', $fonts['heading_overrides'] );
		$this->assertSame( "\tletter-spacing: 1.5px;\n\ttext-transform: capitalize;\n", $this->design->letters( array( 'letter_spacing' => '1.5', 'text_transform' => 'capitalize' ) ) );
		$this->assertSame( "\tletter-spacing: 0px;\n", $this->design->letters( array( 'letter_spacing' => 0 ) ) );
		$this->assertSame( '', $this->design->letters( array( 'letter_spacing' => '', 'text_transform' => 'bogus' ) ) );
	}

	public function test_empty_h2_letters_take_h1() {
		md_test_set_settings( array(
			'typography' => array(
				'h1' => array( 'letter_spacing' => '2', 'text_transform' => 'uppercase' ),
				'h2' => array( 'letter_spacing' => '', 'text_transform' => '' )
			)
		) );

		$fonts = $this->design->fonts( $this->design->values() );

		$this->assertSame( '2px', $fonts['heading']['letter_spacing'] );
		$this->assertFalse( isset( $fonts['heading_overrides']['h2'] ) );
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

	public function test_saved_heading_sizes_set_their_line_heights() {
		md_test_set_settings( array(
			'typography' => array(
				'h2' => array(
					'font_size' => array( 'desktop' => 33 )
				)
			)
		) );

		$typography = $this->design->values()['typography'];

		$this->assertEquals( 33, $typography['h2']['font_size']['desktop'] );
		$this->assertEquals( 45, $typography['h2']['line_height']['desktop'] );
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


	public function test_effects_defaults_match_the_old_fixed_values() {
		$effects = $this->design->effects();

		$this->assertSame( '8px', $effects['border_radius'] );
		$this->assertSame( '1px', $effects['border_width'] );
		$this->assertSame( '8px', $effects['field_radius'] );
		$this->assertSame( '1px', $effects['field_border_width'] );
		$this->assertSame( 'var(--md-form-background)', $effects['field_bg'] );
		$this->assertSame( '#000', $effects['shadow_color'] );
		$this->assertSame( array(
			'default' => '0 2px 8px rgba(0, 0, 0, 0.15)',
			'small' => '0 1px 3px rgba(0, 0, 0, 0.15)',
			'medium' => '0 4px 16px rgba(0, 0, 0, 0.12)',
			'large' => '0 8px 32px rgba(0, 0, 0, 0.15)',
			'huge' => '0 16px 48px rgba(0, 0, 0, 0.20)'
		), $effects['box_shadow'] );
	}

	public function test_effects_settings_change_only_their_values() {
		md_test_set_settings( array( 'effects' => array( 'border_radius' => 4, 'border_width' => 2, 'field_radius' => 12 ) ) );
		$effects = $this->design->effects();

		$this->assertSame( '4px', $effects['border_radius'] );
		$this->assertSame( '2px', $effects['border_width'] );
		$this->assertSame( '12px', $effects['field_radius'] );
		$this->assertSame( '2px', $effects['field_border_width'] );
		$this->assertSame( '0 2px 8px rgba(0, 0, 0, 0.15)', $effects['box_shadow']['default'] );
	}

	public function test_underline_fields_have_no_radius_or_background_and_a_bottom_border() {
		md_test_set_settings( array( 'effects' => array( 'field_style' => 'underline', 'border_width' => 2, 'field_radius' => 12 ) ) );
		$effects = $this->design->effects();

		$this->assertSame( '0', $effects['field_radius'] );
		$this->assertSame( '0 0 2px', $effects['field_border_width'] );
		$this->assertSame( 'var(--md-tint)', $effects['field_bg'] );
		$this->assertSame( '0', $effects['field_focus_outline'] );
		$this->assertSame( 'none', $effects['field_focus_shadow'] );
	}

	public function test_hard_and_none_shadow_styles() {
		md_test_set_settings( array( 'effects' => array( 'shadow_style' => 'hard', 'shadow_color' => '#112233' ) ) );
		$hard = $this->design->effects();

		$this->assertSame( '4px 4px 0 #112233', $hard['box_shadow']['default'] );
		$this->assertSame( '2px 2px 0 #112233', $hard['box_shadow']['small'] );
		$this->assertSame( '#112233', $hard['shadow_color'] );

		md_test_set_settings( array( 'effects' => array( 'shadow_style' => 'none' ) ) );

		$this->assertSame( array_fill_keys( array( 'default', 'small', 'medium', 'large', 'huge' ), 'none' ), $this->design->effects()['box_shadow'] );
	}

	public function test_a_soft_shadow_color_mixes_at_the_same_strengths() {
		md_test_set_settings( array( 'effects' => array( 'shadow_color' => '#112233' ) ) );

		$this->assertSame( '0 2px 8px color-mix(in srgb, #112233 15%, transparent)', $this->design->effects()['box_shadow']['default'] );
	}

	public function test_join_squares_inner_corners_and_overlaps_borders() {
		$apart = $this->design->effects();

		$this->assertSame( 'var(--md-half)', $apart['field_gap'] );
		$this->assertSame( 'var(--md-field-radius)', $apart['field_inner_radius'] );
		$this->assertSame( 'var(--md-border-radius)', $apart['field_button_radius'] );
		$this->assertSame( '0px', $apart['field_overlap'] );

		md_test_set_settings( array( 'effects' => array( 'field_join' => array( 'join' => true ), 'border_width' => 2 ) ) );
		$joined = $this->design->effects();

		$this->assertSame( '0px', $joined['field_gap'] );
		$this->assertSame( 'stretch', $joined['field_align'] );
		$this->assertSame( '0', $joined['field_inner_radius'] );
		$this->assertSame( '0', $joined['field_button_radius'] );
		$this->assertSame( 'calc(-1 * 2px)', $joined['field_overlap'] );

		md_test_set_settings( array( 'effects' => array( 'field_join' => array( 'join' => true ), 'field_style' => 'underline' ) ) );

		$this->assertSame( '0px', $this->design->effects()['field_overlap'] );
	}
}
