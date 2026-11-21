<?php
/**
 * Regressions from the 6.2.2 release review: import scope, select and color
 * validation, opaque builder rows, ability output schemas and upgrade writes.
 *
 * @since 6.2.2
 */

require_once dirname( __DIR__ ) . '/api/upgrade.php';
require_once dirname( __DIR__ ) . '/api/save/widgets.php';
require_once dirname( __DIR__ ) . '/api/save/settings-file.php';
require_once dirname( __DIR__ ) . '/api/abilities/abilities.php';
require_once dirname( __DIR__ ) . '/api/abilities/settings.php';

class ReleaseReviewTest extends MD_TestCase {

	private function register( $pages ) {
		md_test_set_filter( 'md_register', array( 'admin_pages' => $pages ) );
	}

	private function import( $sections ) {
		$file = new md_settings_file;

		return $file->import( $file->read( json_encode( array( 'format' => 'md-export', 'version' => 1, 'sections' => $sections ) ) ) );
	}

	public function test_a_design_entry_cannot_name_a_settings_group() {
		$this->register( array(
			'colors' => array( '_source' => 'theme', 'fields' => array( 'primary' => array( 'type' => 'text' ) ) ),
			'integrations' => array( '_source' => 'theme', 'fields' => array(
				'list' => array( 'type' => 'text' ),
				'key' => array( 'type' => 'text' )
			) )
		) );
		md_test_set_option( 'marketers_delight', array( 'integrations' => array( 'list' => 'Keep', 'key' => 'Keep too' ) ) );

		$result = $this->import( array( 'design' => array(
			array( 'scope' => 'site', 'group' => 'integrations', 'values' => array( 'list' => 'Changed' ) ),
			array( 'scope' => 'site', 'group' => 'colors', 'values' => array( 'primary' => '#000' ) )
		) ) );
		$settings = get_option( 'marketers_delight' );

		$this->assertSame( array( 'list' => 'Keep', 'key' => 'Keep too' ), $settings['integrations'], 'Replace mode never reached the group' );
		$this->assertSame( array( 'primary' => '#000' ), $settings['colors'] );
		$this->assertContains( '`integrations` settings for `site` aren\'t part of Design, so they weren\'t imported.', $result['skipped'] );
	}

	public function test_a_post_type_scope_cannot_hide_under_theme_settings() {
		$this->register( array(
			'docs' => array( '_source' => 'theme', 'has_groups' => true, 'fields' => array( 'archives_title' => array( 'type' => 'text' ) ) )
		) );
		md_test_set_option( 'marketers_delight', array( 'docs' => array( 'archives_title' => 'Docs' ) ) );

		$this->import( array( 'settings' => array(
			array( 'scope' => 'post_type:docs', 'group' => 'docs', 'values' => array() )
		) ) );

		$this->assertSame( array( 'archives_title' => 'Docs' ), get_option( 'marketers_delight' )['docs'] );
	}

	public function test_a_file_from_an_older_version_merges() {
		$this->register( array(
			'colors' => array( '_source' => 'theme', 'fields' => array(
				'primary' => array( 'type' => 'text' ),
				'background' => array( 'type' => 'text' )
			) )
		) );
		$file = new md_settings_file;
		$older = array( 'format' => 'md-export', 'version' => 1, 'md_version' => '5.9', 'sections' => array( 'design' => array(
			array( 'scope' => 'site', 'group' => 'colors', 'values' => array( 'primary' => '#000', 'width' => array( 'content' => 700 ) ) )
		) ) );

		md_test_set_option( 'marketers_delight', array( 'colors' => array( 'primary' => '#111', 'background' => '#fff' ) ) );
		$result = $file->import( $file->read( json_encode( $older ) ) );

		$this->assertSame( array( 'primary' => '#000', 'background' => '#fff' ), get_option( 'marketers_delight' )['colors'], 'Fields the file lacks keep this site\'s values' );
		$this->assertContains( '`colors.width` isn\'t used on this site.', $result['skipped'] );

		$older['md_version'] = MD_VERSION;
		md_test_set_option( 'marketers_delight', array( 'colors' => array( 'primary' => '#111', 'background' => '#fff' ) ) );
		$file->import( $file->read( json_encode( $older ) ) );

		$this->assertSame( array( 'primary' => '#000' ), get_option( 'marketers_delight' )['colors'], 'A file from this version still replaces' );
	}

	public function test_a_design_reset_of_a_mixed_group_keeps_theme_settings_text() {
		$this->register( array(
			'logo' => array( '_source' => 'theme', 'fields' => array(
				'site_title' => array( 'text' => array( 'type' => 'text' ), 'color' => array( 'type' => 'text' ) )
			) )
		) );
		md_test_set_option( 'marketers_delight', array( 'logo' => array( 'site_title' => array( 'text' => 'Site A', 'color' => 'primary' ) ) ) );

		$result = $this->import( array( 'design' => array(
			array( 'scope' => 'site', 'group' => 'logo', 'values' => array( 'site_title' => null ) )
		) ) );

		$this->assertSame( array( 'site_title' => array( 'text' => 'Site A' ) ), get_option( 'marketers_delight' )['logo'] );
		$this->assertIsArray( $result['imported'] );
	}

	public function test_check_flags_a_file_from_an_older_version() {
		$file = new md_settings_file;
		$older = array( 'format' => 'md-export', 'version' => 1, 'md_version' => '5.9', 'sections' => array() );
		$same = array_merge( $older, array( 'md_version' => MD_VERSION ) );

		$this->assertTrue( $file->check( $file->read( json_encode( $older ) ) )['older'] );
		$this->assertFalse( $file->check( $file->read( json_encode( $same ) ) )['older'] );
	}

	public function test_a_single_select_refuses_a_list() {
		$validate = new md_validate;
		$fields = array(
			'position' => array( 'type' => 'select', 'options' => array( 'left', 'right' ) ),
			'many' => array( 'type' => 'select', 'multiple' => true, 'options' => array( 'a', 'b' ) )
		);

		$save = $validate->fields( array( 'position' => array( 'left' ), 'many' => array( 'a', 'z' ) ), $fields );

		$this->assertSame( '', $save['position'], 'Cleared like any value the field can\'t use, never stored as a list' );
		$this->assertSame( array( 'a' ), $save['many'] );
		$this->assertContains( '`position` has a value this site can\'t use.', $validate->warnings() );
		$this->assertSame( array( 'position' => 'right' ), $validate->fields( array( 'position' => 'right' ), $fields ) );
	}

	public function test_a_color_matching_its_default_saves_nothing_and_says_so() {
		$palette = array_keys( md_color_palette() );
		$sanitize = new md_sanitize;
		$validate = new md_validate;
		$fields = array(
			'link' => array( 'type' => 'color', 'palette' => $palette[0] ),
			'text' => array( 'type' => 'color', 'default' => '#1a1a1a' ),
			'bare' => array( 'type' => 'color', 'default' => 'ffffff' )
		);

		$this->assertSame( '', $sanitize->color( $palette[0], $fields['link'] ) );
		$this->assertSame( $palette[1], $sanitize->color( $palette[1], $fields['link'] ) );
		$this->assertSame( '', $sanitize->color( '#1A1A1A', $fields['text'] ) );
		$this->assertSame( '', $sanitize->color( '#FFFFFF', $fields['bare'] ) );
		$this->assertSame( '#2b2b2b', $sanitize->color( '#2B2B2B', $fields['text'] ) );
		$this->assertSame( '', $sanitize->color( "ab\n", array() ), 'A trailing newline isn\'t a hex color' );
		$this->assertSame( '', $sanitize->color( "#abc\n", array() ) );
		$this->assertSame( '#abcd', $sanitize->color( '#ABCD', array() ) );
		$this->assertSame( '#11223344', $sanitize->color( '#11223344', array() ) );

		$save = $validate->fields( array( 'link' => $palette[0], 'text' => '#1A1A1A', 'bare' => '#fff' . 'fff' ), $fields );

		$this->assertSame( array( 'link' => '', 'text' => '', 'bare' => '' ), $save, 'No override is stored' );
		$this->assertCount( 3, $validate->warnings() );

		foreach ( $validate->warnings() as $warning )
			$this->assertStringContainsString( 'is already its default', $warning );
	}

	public function test_saving_keeps_builder_rows_the_screen_could_not_show() {
		md_test_set_filter( 'md_register', array( 'admin_pages' => array( 'header' => array( 'fields' => array(
			'builder' => array( 'type' => 'builder', 'fields' => array(
				'builder_type' => array( 'type' => 'text' ),
				'builder_area' => array( 'type' => 'text' ),
				'name' => array( 'type' => 'text' )
			) )
		) ) ) ) );
		md_test_set_option( 'marketers_delight', array( 'header' => array( 'builder' => array(
			'aaa' => array( 'builder_type' => 'link', 'builder_area' => 'primary', 'name' => 'Old' ),
			'bbb' => array( 'builder_type' => 'beacon_menu', 'builder_area' => 'primary', 'menu' => '5', 'beacon' => array( 'open' => true ) ),
			'ccc' => array( 'builder_type' => 'search', 'builder_area' => 'secondary' ),
			'ddd' => array( 'builder_type' => 'gone', 'builder_area' => 'drop_area', 'note' => 'Opaque' )
		) ) ) );

		// What the form posts: visible rows in full, hidden rows as type and area, ccc deleted

		$posted = array( 'builder' => array(
			'aaa' => array( 'builder_type' => 'link', 'builder_area' => 'primary', 'name' => 'New' ),
			'bbb' => array( 'builder_type' => 'beacon_menu', 'builder_area' => 'primary' ),
			'ddd' => array( 'builder_type' => 'gone', 'builder_area' => 'drop_area' )
		) );

		( new md_save )->apply( 'site', array( 'header' ), array( 'header' => $posted ), array( 'compile' => false ) );

		$this->assertSame( array(
			'aaa' => array( 'builder_type' => 'link', 'builder_area' => 'primary', 'name' => 'New' ),
			'bbb' => array( 'builder_type' => 'beacon_menu', 'builder_area' => 'primary', 'menu' => '5', 'beacon' => array( 'open' => true ) ),
			'ddd' => array( 'builder_type' => 'gone', 'builder_area' => 'drop_area', 'note' => 'Opaque' )
		), get_option( 'marketers_delight' )['header']['builder'] );
	}

	public function test_builder_template_posts_back_rows_it_does_not_render() {
		$source = file_get_contents( dirname( __DIR__ ) . '/admin/templates/fields/builder.php' );

		$this->assertStringContainsString( "elseif ( \$area == \$area_id && \$type )\n\t\t\t\t\t\$this->builder_kept( \$key, \$group, \$fields );", $source );
		$this->assertStringContainsString( "! isset( \$areas[\$fields['builder_area'] ?? ''] )", $source );
	}

	public function test_ability_output_keeps_a_full_schema_and_expands_shorthand() {
		$abilities = new md_settings_abilities;
		$schema = array(
			'type' => 'object',
			'properties' => array( 'books' => array( 'type' => 'array', 'items' => array( 'type' => 'object' ) ) ),
			'required' => array( 'books' )
		);

		$this->assertSame( $schema, $abilities->output( $schema ) );
		$this->assertSame( array( 'type' => 'object', 'properties' => array( 'items' => array( 'type' => 'array' ), 'total' => array( 'type' => 'integer' ) ) ), $abilities->output( array( 'items' => 'array', 'total' => 'integer' ) ) );
		$this->assertSame( array( 'type' => 'object', 'properties' => array( 'type' => array( 'type' => 'string' ), 'id' => array( 'type' => 'integer' ) ) ), $abilities->output( array( 'type' => 'string', 'id' => 'integer' ) ), 'A result key named type is still shorthand' );
	}

	public function test_upgrade_keeps_a_category_box_under_a_plain_post_type() {
		$moved = ( new md_upgrade )->move_box_style( array( 'post' => array(
			'layout' => array( 'content_style' => 'plain' ),
			'category' => array( 'layout' => array( 'content_style' => 'box' ) )
		) ) );

		$this->assertSame( array( 'content' => array( 'box_style' => true ) ), $moved['post']['layout'] );
		$this->assertSame( array( 'content' => array( 'box_style' => 0 ) ), $moved['post']['category']['layout'] );
	}

	public function test_a_failed_upgrade_write_is_not_stamped() {
		md_test_set_option( 'marketers_delight', array( 'colors' => array( 'design' => 'plain', 'width' => array( 'content' => 700 ) ) ) );
		$GLOBALS['__test_update_fails']['marketers_delight'] = true;

		$upgrade = new md_upgrade;

		$this->assertInstanceOf( WP_Error::class, $upgrade->upgrade_622() );
		$this->assertArrayNotHasKey( 'version', get_option( 'marketers_delight' ) );

		$GLOBALS['__test_update_fails'] = array();

		$this->assertTrue( $upgrade->upgrade_622() );
		$this->assertSame( array( 'content' => 700 ), get_option( 'marketers_delight' )['settings']['width'] );
	}

}
