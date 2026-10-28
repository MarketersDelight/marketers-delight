<?php
/**
 * Tests that a group host, like the Blog settings page, takes in fields
 * that group children (like Drop-in tabs) add after the host first
 * registered, so they validate and save.
 *
 * @since 6.1
 */

class md_test_group_host extends md_api {

	public function register() {
		return array(
			'admin_page' => array(
				'name' => 'Settings',
				'has_groups' => true,
				'fields' => array_merge(
					array( 'archives_title' => array( 'type' => 'text' ) ),
					apply_filters( 'md_filter_admin_page_page_settings', array() )
				)
			)
		);
	}
}

class GroupHostTest extends MD_InheritanceTestCase {

	private function host() {
		$host = ( new ReflectionClass( 'md_test_group_host' ) )->newInstanceWithoutConstructor();
		$host->_id = 'md_test_group_host';
		$host->_clean_id = 'test_group_host';
		$host->register = $host->register();

		return $host;
	}

	public function test_registering_again_takes_in_fields_added_later() {
		$host = $this->host();

		$this->assertSame( array( 'archives_title' ), array_keys( $host->register['admin_page']['fields'] ), 'A tab added after the host registered is missing at first' );

		md_test_set_filter( 'md_filter_admin_page_page_settings', array(
			'archive_sections' => array( 'builder' => array( 'type' => 'builder', 'fields' => array() ) )
		) );

		$host->_register_groups();

		$this->assertSame( array( 'archives_title', 'archive_sections' ), array_keys( $host->register['admin_page']['fields'] ) );
	}

	public function test_md_register_reports_the_rebuilt_fields() {
		$host = $this->host();

		md_test_set_filter( 'md_filter_admin_page_page_settings', array(
			'scripts' => array( 'body_class' => array( 'type' => 'text' ) )
		) );

		$host->_register_groups();

		$this->assertArrayHasKey( 'scripts', $host->_register( array() )['admin_pages']['test_group_host']['fields'] );
	}
}
