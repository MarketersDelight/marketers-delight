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

}
