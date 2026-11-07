<?php
/**
 * Tests the parts of MD's abilities that don't need WordPress's Abilities
 * API. Calls through it are covered by running them over MCP.
 */

require_once dirname( __DIR__ ) . '/api/save/widgets.php';
require_once dirname( __DIR__ ) . '/api/save/settings-file.php';
require_once dirname( __DIR__ ) . '/api/abilities/abilities.php';
require_once dirname( __DIR__ ) . '/api/abilities/settings.php';

class AbilitiesTest extends MD_TestCase {

	protected $abilities;

	protected function setUp(): void {
		parent::setUp();

		$this->abilities = new md_settings_abilities;
	}

	public function test_messages_come_back_as_plain_text() {
		$result = $this->abilities->result( array( 'warnings' => array( '`a.b` was skipped.' ), 'skipped' => array( '`c`' ), 'name' => '`kept`' ) );

		$this->assertSame( array( 'a.b was skipped.' ), $result['warnings'] );
		$this->assertSame( array( 'c' ), $result['skipped'] );
		$this->assertSame( '`kept`', $result['name'] );
		$this->assertSame( 'Unknown x', $this->abilities->result( new WP_Error( 'code', 'Unknown `x`' ) )->get_error_message() );
	}

	public function test_edits_change_text_that_appears_once() {
		$this->assertSame( 'Hello there', $this->abilities->edited_text( 'Hello world', array( array( 'find' => 'world', 'replace' => 'there' ) ) ) );
	}

	public function test_edits_refuse_text_missing_or_repeated() {
		$this->assertSame( 'md_abilities_edit', $this->abilities->edited_text( 'a a', array( array( 'find' => 'a', 'replace' => 'b' ) ) )->get_error_code() );
		$this->assertSame( 'md_abilities_edit', $this->abilities->edited_text( 'a', array( array( 'find' => 'z', 'replace' => 'b' ) ) )->get_error_code() );
	}

	public function test_edits_need_text_to_find() {
		$this->assertSame( 1, $this->abilities->edits_input()['items']['properties']['find']['minLength'] );
	}

	public function test_apps_cannot_write_internal_fields_at_any_depth() {
		$fields = array(
			'title' => array( 'type' => 'text' ),
			'logged' => array( 'type' => 'text', 'internal' => true ),
			'nested' => array( 'seen' => array( 'type' => 'text', 'internal' => true ), 'kept' => array( 'type' => 'text' ) )
		);

		$this->assertSame(
			array( 'title' => 'T', 'nested' => array( 'kept' => 'K' ) ),
			$this->abilities->without_internal( array( 'title' => 'T', 'logged' => 'x', 'nested' => array( 'seen' => 'y', 'kept' => 'K' ) ), $fields )
		);
	}

	public function test_fields_describe_what_an_app_needs() {
		$described = $this->abilities->fields( array(
			'size' => array( 'type' => 'number', 'label' => '<b>Size</b>', 'min' => 1, 'code_options' => array( 'x' ) ),
			'style' => array( 'type' => 'select', 'options' => array( 'a' => '<b>A</b>', 'b' => 'B' ) ),
			'list' => array( 'type' => 'checkbox', 'options' => array( 'one', 'two' ) ),
			'logged' => array( 'type' => 'text', 'internal' => true ),
			'items' => array( 'type' => 'group', 'fields' => array( 'name' => array( 'type' => 'text' ) ) ),
			'nested' => array( 'color' => array( 'type' => 'color' ) )
		) );

		$this->assertSame( array( 'type' => 'number', 'min' => 1, 'label' => 'Size' ), $described['size'] );
		$this->assertSame( array( 'a' => 'A', 'b' => 'B' ), $described['style']['options'] );
		$this->assertSame( array( 'one', 'two' ), $described['list']['options'] );
		$this->assertArrayNotHasKey( 'logged', $described );
		$this->assertSame( array( 'name' => array( 'type' => 'text' ) ), $described['items']['item_fields'] );
		$this->assertSame( 'color', $described['nested']['fields']['color']['type'] );
	}

	public function test_a_host_names_the_groups_it_hosts() {
		$fields = array( 'slug' => array( 'type' => 'text' ), 'layout' => array( 'sidebar' => array( 'type' => 'text' ) ) );

		$this->assertSame( array( 'slug' ), array_keys( $this->abilities->fields( $fields, true ) ) );
		$this->assertSame( array( 'layout' ), $this->abilities->hosted_groups( $fields ) );
	}

}
