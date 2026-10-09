<?php

class HeaderBuilderTest extends MD_TestCase {

	private function source( $path ) {
		return file_get_contents( dirname( __DIR__ ) . "/{$path}" );
	}

	public function test_search_toggle_is_added_to_shared_builder_toggle_options() {
		$source = $this->source( 'features/header/admin.php' );

		$this->assertStringContainsString(
			"\$builder['toggle']['options'] = array_merge( array( 'search' ), \$builder['toggle']['options'] );",
			$source
		);
	}

	public function test_search_submit_uses_button_component_class() {
		$source = $this->source( 'searchform.php' );

		$this->assertStringContainsString( '<button type="submit" class="button submit">', $source );
	}

	public function test_scroll_mobile_layout_is_a_registered_option() {
		$this->assertStringContainsString(
			"array( 'standard', 'expanded', 'scroll' )",
			$this->source( 'features/header/admin.php' )
		);
	}

	public function test_scroll_mobile_layout_skips_menu_trigger() {
		$source = $this->source( 'header.php' );

		$this->assertStringContainsString( "! in_array( \$mobile, array( 'expanded', 'scroll' ), true )", $source );
		$this->assertStringContainsString( "if ( \$mobile !== 'scroll' && (", $source );
	}

	public function test_scroll_mobile_layout_wraps_menu_in_scroller_and_adds_header_class() {
		$source = $this->source( 'features/header/functions.php' );

		$this->assertStringContainsString( "\$classes[] = 'menu-scroll';", $source );
		$this->assertStringContainsString( "'classes' => 'menu-scroller'", $source );
		$this->assertStringContainsString( "'echo' => false", $source );
	}

	public function test_builder_passes_section_as_menu_area() {
		$this->assertStringContainsString( "\$field['area'] = \$section;", $this->source( 'header.php' ) );
	}

	public function test_scroll_mobile_layout_keeps_menu_visible_and_hides_submenus() {
		$header = $this->source( 'css/header.php' );
		$menus = $this->source( 'css/menus.php' );

		$this->assertStringContainsString( '.header:where(:not(.menu-scroll)) :is(.primary-menu, .aside-menu)', $header );
		$this->assertStringContainsString( '.menu-scroller .scroller-list { overflow: visible; }', $header );
		$this->assertStringContainsString( '.header .menu-scroller { margin-block-end: 0; }', $header );
		$this->assertStringContainsString( '.menu-scroller { border-block-start: 1px solid var(--md-header-border); }', $header );
		$this->assertStringContainsString( '.menu-scroll .header-controls { padding-block-end: var(--md-half); }', $header );
		$this->assertStringContainsString( '@supports not (anchor-scope: --item) {', $menus );
		$this->assertStringContainsString( '.menu-scroller .menu :is(.sub-menu, .trigger) { display: none; }', $menus );
		$this->assertStringContainsString( 'position-anchor: --item;', $menus );
		$this->assertStringContainsString( 'anchor-scope: --item;', $menus );
		$this->assertStringContainsString( '.menu-scroller .sub-menu .sub-menu {', $menus );
	}

	public function test_header_layout_previews_are_drawn_with_css_instead_of_images() {
		$template = $this->source( 'features/header/templates/admin/header.php' );
		$radio = $this->source( 'admin/templates/fields/radio.php' );

		$this->assertSame( 6, substr_count( $template, "'preview' => array(" ) );
		$this->assertStringNotContainsString( 'admin/images/header-', $template );
		$this->assertStringContainsString( "'preview' => array( 'logo', 'row', 'nav' )", $template );
		$this->assertStringContainsString( 'class="md-wire"', $radio );
	}

	public function test_menu_trigger_renders_after_links_and_before_trigger_hook() {
		$source = $this->source( 'header.php' );
		$links = strpos( $source, 'md_link( $header[' );
		$menu = strpos( $source, "md_trigger( 'menu', array( 'builder' => \$header ) );", $links );
		$hook = strpos( $source, 'md_hook_header_triggers();' );

		$this->assertNotFalse( $links );
		$this->assertNotFalse( $menu );
		$this->assertLessThan( $menu, $links );
		$this->assertLessThan( $hook, $menu );
	}

}
