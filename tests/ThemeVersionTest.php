<?php

class ThemeVersionTest extends MD_TestCase {

	private function source( $path ) {
		return file_get_contents( dirname( __DIR__ ) . "/{$path}" );
	}

	public function test_installed_parent_stylesheet_is_the_primary_version_source() {
		$source = $this->source( 'marketers-delight.php' );
		$file_data = strpos( $source, "get_file_data( get_template_directory() . '/style.css'" );
		$wp_theme = strpos( $source, 'wp_get_theme( get_template() )' );

		$this->assertNotFalse( $file_data );
		$this->assertNotFalse( $wp_theme );
		$this->assertLessThan( $wp_theme, $file_data );
		$this->assertStringContainsString( "if ( ! empty( \$theme['version'] ) )", $source );
		$this->assertStringContainsString( "define( 'MD_VERSION', \$this->theme_version() );", $source );
	}

	public function test_admin_version_badge_is_not_rendered_without_a_version() {
		$source = $this->source( 'admin/templates/admin.php' );

		$this->assertStringContainsString( "if ( ! empty( \$theme_version ) )", $source );
		$this->assertStringContainsString( 'esc_html( $theme_version )', $source );
	}

}
