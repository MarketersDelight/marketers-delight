<?php
/**
 * Tests Advanced block styling's CSS and theme.json additions.
 *
 * @since 6.2.3
 */

class BlocksTest extends MD_TestCase {

	private function advanced( $on ) {
		md_test_set_settings( array( 'settings' => array( 'head' => array( 'blocks' => $on ) ) ) );
	}

	public function test_off_adds_nothing() {
		$this->advanced( false );
		$blocks = new md_blocks;

		$this->assertSame( array( 'style' => 'style.php' ), $blocks->css_templates( array( 'style' => 'style.php' ) ) );
		$this->assertSame( array( 'settings' => array() ), $blocks->theme_json( array( 'settings' => array() ) ) );
	}

	public function test_on_adds_block_css() {
		$this->advanced( true );

		$templates = ( new md_blocks )->css_templates( array( 'style' => 'style.php' ) );

		$this->assertArrayHasKey( 'blocks', $templates );
		$this->assertStringEndsWith( 'css/blocks.php', $templates['blocks'] );
	}

	public function test_on_hides_duotone_and_lightbox() {
		$this->advanced( true );

		$json = ( new md_blocks )->theme_json( array( 'settings' => array( 'color' => array( 'duotone' => array( 'preset' ) ) ) ) );

		$this->assertFalse( $json['settings']['color']['customDuotone'] );
		$this->assertSame( array(), $json['settings']['color']['duotone'] );
		$this->assertFalse( $json['settings']['blocks']['core/image']['lightbox']['allowEditing'] );
	}

}
