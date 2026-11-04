<?php
/**
 * Tests md_api::schema(), the JSON Schema generated from a settings
 * group's registered fields.
 *
 * @since 6.2
 */

class SchemaTest extends MD_TestCase {

	private function register( $register ) {
		md_test_set_filter( 'md_register', $register );
	}

	public function test_field_types_become_json_schema() {
		$this->register( array( 'admin_pages' => array( 'layout' => array( 'fields' => array(
			'title' => array( 'type' => 'text', 'default' => 'Hello' ),
			'intro' => array( 'type' => 'textarea' ),
			'link' => array( 'type' => 'url' ),
			'count' => array( 'type' => 'number', 'min' => 1, 'max' => 12 ),
			'size' => array( 'type' => 'range', 'step' => 2 ),
			'sticky' => array( 'type' => 'checkbox' ),
			'color' => array( 'type' => 'color' ),
			'logo' => array( 'type' => 'upload' ),
			'script' => array( 'type' => 'code' )
		) ) ) ) );

		$schema = md_api::schema( 'layout' );
		$properties = $schema['properties'];

		$this->assertSame( 'http://json-schema.org/draft-04/schema#', $schema['$schema'] );
		$this->assertSame( 'object', $schema['type'] );
		$this->assertFalse( $schema['additionalProperties'] );
		$this->assertSame( array( 'type' => 'string', 'default' => 'Hello' ), $properties['title'] );
		$this->assertSame( 'string', $properties['intro']['type'] );
		$this->assertSame( array( 'type' => 'string', 'format' => 'uri' ), $properties['link'] );
		$this->assertSame( array( 'type' => 'number', 'minimum' => 1, 'maximum' => 12 ), $properties['count'] );
		$this->assertSame( array( 'type' => 'number', 'multipleOf' => 2 ), $properties['size'] );
		$this->assertSame( array( 'type' => 'boolean' ), $properties['sticky'] );
		$this->assertSame( 'string', $properties['color']['type'] );
		$this->assertSame( array( 'id' ), $properties['logo']['required'] );
		$this->assertSame( 'integer', $properties['logo']['properties']['id']['type'] );
		$this->assertSame( 'string', $properties['script']['type'] );
	}

	public function test_choices_become_enums_with_labels_when_registered() {
		$this->register( array( 'admin_pages' => array( 'layout' => array( 'fields' => array(
			'style' => array( 'type' => 'select', 'options' => array( 'box', 'plain' ) ),
			'position' => array( 'type' => 'radio', 'options' => array( 'left' => 'Left side', 'right' => 'Right side' ) ),
			'show' => array( 'type' => 'checkbox', 'options' => array( 'date' => 'Date', 'author' => 'Author' ) )
		) ) ) ) );

		$properties = md_api::schema( 'layout' )['properties'];

		$this->assertSame( array( 'type' => 'string', 'enum' => array( '', 'box', 'plain' ) ), $properties['style'] );
		$this->assertSame( array( '', 'left', 'right' ), $properties['position']['enum'] );
		$this->assertSame( array( 'left' => 'Left side', 'right' => 'Right side' ), $properties['position']['x-labels'] );
		$this->assertSame( array( 'date', 'author' ), array_keys( $properties['show']['properties'] ) );
		$this->assertSame( 'Date', $properties['show']['properties']['date']['title'] );
		$this->assertFalse( $properties['show']['additionalProperties'] );
	}

	public function test_nested_fields_and_builder_items_get_their_own_schema() {
		$this->register( array( 'admin_pages' => array( 'header' => array( 'fields' => array(
			'section' => array(
				'title' => array( 'type' => 'text' )
			),
			'builder' => array( 'type' => 'builder', 'fields' => array(
				'name' => array( 'type' => 'text' ),
				'menu' => array( 'type' => 'select', 'options' => array( 'main', 'footer' ) )
			) )
		) ) ) ) );

		$properties = md_api::schema( 'header' )['properties'];
		$item = $properties['builder']['patternProperties']['^[A-Za-z0-9_-]+$'];

		$this->assertSame( 'object', $properties['section']['type'] );
		$this->assertSame( 'string', $properties['section']['properties']['title']['type'] );
		$this->assertSame( 'object', $properties['builder']['type'] );
		$this->assertFalse( $properties['builder']['additionalProperties'] );
		$this->assertSame( array( 'name', 'menu' ), array_keys( $item['properties'] ) );
		$this->assertSame( array( '', 'main', 'footer' ), $item['properties']['menu']['enum'] );
	}

	public function test_types_that_cant_be_saved_are_left_out() {
		$this->register( array( 'admin_pages' => array( 'layout' => array( 'fields' => array(
			'title' => array( 'type' => 'text' ),
			'file' => array( 'type' => 'upload', 'upload_type' => 'file' ),
			'custom' => array( 'type' => 'devices' )
		) ) ) ) );

		$this->assertSame( array( 'title' ), array_keys( md_api::schema( 'layout' )['properties'] ) );
	}

	public function test_scopes_list_every_place_a_group_saves_to() {
		$fields = array( 'title' => array( 'type' => 'text' ) );
		$this->register( array(
			'admin_pages' => array(
				'layout' => array( 'fields' => $fields ),
				'docs' => array( 'has_groups' => true, 'fields' => array( 'layout' => $fields, 'slug' => array( 'type' => 'text' ) ) )
			),
			'meta_boxes' => array( 'layout' => array( 'fields' => $fields ) ),
			'terms' => array( 'layout' => array( 'fields' => $fields ) )
		) );
		md_test_set_filter( 'md_taxonomy_groups', array( 'md_docs' => array( 'docs_category' => true ) ) );

		$this->assertSame( array( 'site', 'post_type:docs', 'taxonomy:docs/docs_category', 'post', 'term' ), md_api::schema( 'layout' )['x-scopes'] );
		$this->assertSame( array( 'post_type:docs', 'taxonomy:docs/docs_category' ), md_api::schema( 'docs' )['x-scopes'] );
	}

	public function test_a_scope_describes_its_own_fields() {
		$this->register( array(
			'admin_pages' => array( 'layout' => array( 'fields' => array( 'sidebar' => array( 'type' => 'text' ) ) ) ),
			'meta_boxes' => array( 'layout' => array( 'fields' => array( 'hide_title' => array( 'type' => 'checkbox' ) ) ) )
		) );

		$this->assertSame( array( 'sidebar' ), array_keys( md_api::schema( 'layout' )['properties'] ) );
		$this->assertSame( 'site', md_api::schema( 'layout' )['x-scope'] );
		$this->assertSame( array( 'hide_title' ), array_keys( md_api::schema( 'layout', 'post' )['properties'] ) );
		$this->assertSame( 'md_schema_scope', md_api::schema( 'layout', 'term' )->get_error_code() );
	}

	public function test_unknown_group_is_an_error() {
		$this->register( array( 'admin_pages' => array() ) );

		$this->assertSame( 'md_schema_group', md_api::schema( 'missing' )->get_error_code() );
	}

	public function test_schema_comes_from_the_same_fields_apply_validates() {
		$fields = array( 'style' => array( 'type' => 'select', 'options' => array( 'box', 'plain' ) ) );
		$this->register( array( 'admin_pages' => array( 'layout' => array( 'fields' => $fields ) ) ) );
		$site = md_scope::parse( 'site' );
		$target = $site->target( 'layout' );

		$this->assertSame( $fields, $target['fields'] );
		$this->assertTrue( is_wp_error( md_scope::parse( 'post' )->target( 'layout' ) ), 'A group without a meta box has no post fields' );
	}

	public function test_registered_labels_become_titles() {
		$this->register( array( 'admin_pages' => array( 'logo' => array( 'fields' => array(
			'width' => array( 'type' => 'range', 'max' => 500, 'label' => 'Logo <b>Width</b>' ),
			'style' => array( 'type' => 'select', 'options' => array( 'box' => 'Box', 'plain' => 'Plain' ) )
		) ) ) ) );

		$properties = md_api::schema( 'logo' )['properties'];

		$this->assertSame( array( 'title' => 'Logo Width', 'type' => 'number', 'maximum' => 500 ), $properties['width'] );
		$this->assertSame( array( '', 'box', 'plain' ), $properties['style']['enum'] );
		$this->assertSame( array( 'box' => 'Box', 'plain' => 'Plain' ), $properties['style']['x-labels'] );
	}

	public function test_labeled_select_options_validate_by_their_values() {
		$this->register( array( 'admin_pages' => array( 'layout' => array( 'fields' => array(
			'style' => array( 'type' => 'select', 'options' => array( 'box' => 'Box', 'plain' => 'Plain' ) )
		) ) ) ) );
		$validate = new md_validate;

		$this->assertSame( array( 'layout' => array( 'style' => 'plain' ) ), $validate->validate( 'admin_pages', array( 'layout' => array( 'style' => 'plain' ) ) ) );
		$this->assertSame( array( 'layout' => array( 'style' => '' ) ), $validate->validate( 'admin_pages', array( 'layout' => array( 'style' => 'Plain' ) ) ), 'A label is not a value' );
	}

}
