<?php
/**
 * Tests md_fields::within(): a copy of the fields object whose field()
 * and module() paths start at the given keys, so a panel written for its
 * own screen saves inside another setting, like a builder row. Covers
 * nested group fields, which call back into field() from their template.
 *
 * @since 6.1
 */

class MD_Fields_Within_Capture extends md_fields {

	public $names = array();
	public $ids = array();

	protected function render_field( $name, $id, $option, $args, $wrap_classes ) {
		$this->names[] = $name;
		$this->ids[] = $id;

		if ( $args['type'] === 'group' )
			$this->field_type( $args['type'], $name, $id, $option, $args );
	}

}


class FieldsWithinTest extends MD_TestCase {

	private $row = array( 'builder', 'blog_heading', 'section_cta' );

	private function make_fields() {
		$fields = new MD_Fields_Within_Capture( array(
			'id' => 'archive_sections',
			'clean_id' => 'archive_sections',
			'prefix' => 'md',
			'option' => 'marketers_delight'
		) );

		$fields->_get_screen = array(
			'is_post' => false,
			'is_term' => false,
			'is_user' => false,
			'is_taxonomy' => false,
			'page' => 'archive_sections',
			'screen_id' => null,
			'md_tab' => ''
		);

		return $fields;
	}

	private function render_links( $fields ) {
		ob_start();
		$fields->links_group();
		$html = ob_get_clean();

		return $html;
	}

	public function test_within_prefixes_field_names() {
		$cta_fields = $this->make_fields()->within( $this->row );

		$cta_fields->field( 'custom_html', array( 'type' => 'code' ) );
		$cta_fields->field( array( 'email_input', 'name' ), array( 'type' => 'checkbox' ) );

		$this->assertSame( array(
			'marketers_delight[archive_sections][builder][blog_heading][section_cta][custom_html]',
			'marketers_delight[archive_sections][builder][blog_heading][section_cta][email_input][name]'
		), $cta_fields->names );
	}

	public function test_within_leaves_the_original_unchanged() {
		$fields = $this->make_fields();
		$cta_fields = $fields->within( $this->row );

		$fields->field( 'custom_html', array( 'type' => 'code' ) );

		$this->assertSame( array( 'marketers_delight[archive_sections][custom_html]' ), $fields->names );
		$this->assertSame( array(), $cta_fields->names );
	}

	public function test_within_stacks_keys() {
		$fields = $this->make_fields()->within( array( 'builder', 'blog_heading' ) )->within( 'section_cta' );

		$fields->field( 'page_cta', array( 'type' => 'select', 'options' => array() ) );

		$this->assertSame( array( 'marketers_delight[archive_sections][builder][blog_heading][section_cta][page_cta]' ), $fields->names );
	}

	public function test_within_reads_module_values_from_the_row() {
		md_test_set_settings( array( 'archive_sections' => array( 'builder' => array( 'blog_heading' => array(
			'section_cta' => array( 'page_cta' => 'links' )
		) ) ) ) );

		$cta_fields = $this->make_fields()->within( $this->row );

		$this->assertSame( 'links', $cta_fields->module( 'page_cta' ) );
		$this->assertNull( $this->make_fields()->module( 'page_cta' ) );
	}

	public function test_nested_links_group_names_include_the_row_once() {
		md_test_set_settings( array( 'archive_sections' => array( 'builder' => array( 'blog_heading' => array(
			'section_cta' => array( 'links' => array( 'join' => array( 'name' => 'Join', 'type' => 'url' ) ) )
		) ) ) ) );

		$cta_fields = $this->make_fields()->within( $this->row );
		$html = $this->render_links( $cta_fields );
		$base = 'marketers_delight[archive_sections][builder][blog_heading][section_cta][links]';

		$this->assertContains( $base, $cta_fields->names );
		$this->assertContains( "{$base}[join][name]", $cta_fields->names );
		$this->assertContains( "{$base}[join][subtitle]", $cta_fields->names );
		$this->assertContains( "{$base}[join][type]", $cta_fields->names );

		foreach ( $cta_fields->names as $name )
			$this->assertSame( 1, substr_count( $name, '[builder][blog_heading][section_cta]' ), "Row path repeated in $name" );

		$this->assertStringContainsString( 'id="md_group_archive_sections_builder_blog_heading_section_cta_links"', $html );
	}

	public function test_links_group_without_within_is_unchanged() {
		$fields = $this->make_fields();
		$this->render_links( $fields );

		$this->assertContains( 'marketers_delight[archive_sections][links]', $fields->names );
		$this->assertContains( 'marketers_delight[archive_sections][links][{clone:archive_sections_links}][name]', $fields->names );
	}

}
