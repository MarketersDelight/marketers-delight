<?php

class HeaderTest extends MD_InheritanceTestCase {

	public function test_header_renderer_preserves_mixed_case_builder_ids() {
		md_test_set_option( 'marketers_delight', array(
			'header' => array(
				'builder' => array(
					'V3Xco55' => array(
						'builder_type' => 'menu',
						'builder_area' => 'primary'
					)
				)
			)
		) );

		md_test_set_filter( 'md_header_builder_elements', array(
			'menu' => array( 'callback' => function() { return true; } )
		) );

		$builder = md_get_builder( 'header' );
		$item = $builder['data']['primary'][0];
		$source = file_get_contents( dirname( __DIR__, 2 ) . '/header.php' );

		$this->assertSame( 'V3Xco55', $item['id'] );
		$this->assertArrayHasKey( $item['id'], $builder['fields'] );
		$this->assertStringContainsString( '$id = $items[\'id\'];', $source );
		$this->assertStringNotContainsString( "sanitize_key( \$items['id'] )", $source );
	}

}
