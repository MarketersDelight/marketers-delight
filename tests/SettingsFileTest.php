<?php
/**
 * Tests md_settings_file (api/save/settings-file.php): export, read, check and import of
 * MD settings files, replace and merge modes, refs, widgets, menu
 * locations, drop-in options and the Import & Export handlers.
 *
 * @since 6.2
 */

if ( ! defined( 'OBJECT' ) )
	define( 'OBJECT', 'OBJECT' );

if ( ! defined( 'HOUR_IN_SECONDS' ) )
	define( 'HOUR_IN_SECONDS', 3600 );

if ( ! function_exists( 'md_has' ) ) {
	function md_has( $dropin ) {
		return in_array( $dropin, md_get_dropins( 'active' ), true );
	}
}

if ( ! function_exists( 'md_get_dropin_details' ) ) {
	function md_get_dropin_details( $slug = null ) {
		$details = $GLOBALS['__settings_file']['details'] ?? array();

		return $slug === null ? $details : ( $details[$slug] ?? false );
	}
}

if ( ! function_exists( 'do_action' ) ) {
	function do_action( $tag, ...$args ) {
		$GLOBALS['__settings_file']['actions'][] = $tag;
	}
}

if ( ! function_exists( '_n' ) ) {
	function _n( $single, $plural, $number, $domain = 'default' ) {
		return $number === 1 ? $single : $plural;
	}
}

if ( ! function_exists( 'home_url' ) ) {
	function home_url( $path = '' ) {
		return 'https://a.example.com' . $path;
	}
}

if ( ! function_exists( 'wp_parse_url' ) ) {
	function wp_parse_url( $url, $component = -1 ) {
		return parse_url( $url, $component );
	}
}

if ( ! function_exists( 'wp_parse_id_list' ) ) {
	function wp_parse_id_list( $list ) {
		$list = is_array( $list ) ? $list : preg_split( '/[\s,]+/', (string) $list );

		return array_values( array_unique( array_filter( array_map( 'absint', $list ) ) ) );
	}
}

if ( ! function_exists( 'wp_get_attachment_url' ) ) {
	function wp_get_attachment_url( $id ) {
		return 'https://a.example.com/uploads/' . get_post_meta( $id, '_wp_attached_file', true );
	}
}

if ( ! function_exists( 'get_posts' ) ) {
	function get_posts( $args ) {
		$file = $args['meta_query'][0]['value'] ?? '';
		$found = $GLOBALS['__settings_file']['attachments'][$file] ?? 0;

		return $found ? array( $found ) : array();
	}
}

if ( ! function_exists( 'wp_get_nav_menu_object' ) ) {
	function wp_get_nav_menu_object( $menu ) {
		foreach ( $GLOBALS['__settings_file']['menus'] ?? array() as $id => $name )
			if ( $menu === $name || ( is_int( $menu ) && $menu === $id ) || ( is_numeric( $menu ) && (int) $menu === $id ) )
				return (object) array( 'term_id' => $id, 'name' => $name );

		return false;
	}
}

if ( ! function_exists( 'get_page_by_path' ) ) {
	function get_page_by_path( $path, $output = OBJECT, $post_type = 'page' ) {
		return null;
	}
}

if ( ! function_exists( 'get_page_uri' ) ) {
	function get_page_uri( $post ) {
		return '';
	}
}

if ( ! function_exists( 'get_term' ) ) {
	function get_term( $id ) {
		return null;
	}
}

if ( ! function_exists( 'get_term_by' ) ) {
	function get_term_by( $field, $value, $taxonomy ) {
		return false;
	}
}

if ( ! function_exists( 'wp_die' ) ) {
	function wp_die( $message = '' ) {
		throw new Exception( 'wp_die' );
	}
}

if ( ! function_exists( 'check_admin_referer' ) ) {
	function check_admin_referer( $action ) {
		if ( empty( $GLOBALS['__settings_file']['nonce_ok'] ) )
			throw new Exception( 'nonce' );

		return 1;
	}
}

if ( ! function_exists( 'esc_html__' ) ) {
	function esc_html__( $text, $domain = 'default' ) {
		return $text;
	}
}

if ( ! function_exists( 'wp_normalize_path' ) ) {
	function wp_normalize_path( $path ) {
		return str_replace( '\\', '/', $path );
	}
}

require_once dirname( __DIR__ ) . '/api/save/settings-file.php';
require_once dirname( __DIR__ ) . '/admin/import-export.php';

class SettingsFileTest extends MD_TestCase {

	protected $file;

	protected function setUp(): void {
		parent::setUp();

		$this->file = new md_settings_file;

		global $wp_widget_factory, $wp_registered_sidebars;

		$GLOBALS['__settings_file'] = array(
			'details' => array(
				'share' => array( 'Name' => 'Share', 'Version' => '2.0' ),
				'docs' => array( 'Name' => 'Docs', 'Version' => '1.0' ),
				'stream' => array( 'Name' => 'Stream', 'Version' => '2.2.0' )
			),
			'menus' => array( 2 => 'Main', 3 => 'Top' ),
			'attachments' => array(),
			'actions' => array(),
			'nonce_ok' => true
		);
		$wp_widget_factory = (object) array( 'widgets' => array(
			(object) array( 'id_base' => 'text' ),
			(object) array( 'id_base' => 'block' )
		) );
		$wp_registered_sidebars = array( 'sidebar-main' => array(), 'panel-main' => array(), 'footer-copy' => array() );

		md_test_set_dropins( array( 'share', 'docs', 'stream' ) );
		$this->register();
	}

	private function register( $pages = array() ) {
		md_test_set_filter( 'md_register', array( 'admin_pages' => array_merge( array(
			'settings' => array( '_source' => 'theme', 'fields' => array(
				'sidebars' => array( 'type' => 'group', 'fields' => array( 'name' => array( 'type' => 'text' ) ) ),
				'css' => array( 'type' => 'code' )
			) ),
			'colors' => array( '_source' => 'theme', 'fields' => array(
				'primary' => array( 'type' => 'text' ),
				'background' => array( 'type' => 'text' )
			) ),
			'typography' => array( '_source' => 'theme', 'fields' => array(
				'body' => array( 'font_size' => array( 'type' => 'text' ), 'font_family' => array( 'type' => 'text' ) )
			) ),
			'logo' => array( '_source' => 'theme', 'fields' => array(
				'site_title' => array( 'text' => array( 'type' => 'text' ), 'color' => array( 'type' => 'text' ) ),
				'logo' => array( 'type' => 'upload' ),
				'logo_width' => array( 'desktop' => array( 'type' => 'text' ) )
			) ),
			'header' => array( '_source' => 'theme', 'fields' => array(
				'builder' => array( 'type' => 'builder', 'fields' => array(
					'builder_type' => array( 'type' => 'text' ),
					'menu' => array( 'type' => 'select', 'options' => array_keys( $GLOBALS['__settings_file']['menus'] ), 'ref' => 'nav_menu' )
				) )
			) ),
			'share' => array( '_source' => 'share', 'fields' => array(
				'label' => array( 'type' => 'text' )
			) ),
			'docs' => array( '_source' => 'docs', 'has_groups' => true, 'fields' => array(
				'archives_title' => array( 'type' => 'text' ),
				'layout' => array( 'sidebar' => array( 'type' => 'text' ) )
			) )
		), $pages ) ) );
		md_test_set_filter( 'md_taxonomy_groups', array( 'md_docs' => array( 'docs_category' => true ) ) );
	}

	private function site_a() {
		md_test_set_option( 'marketers_delight', array(
			'settings' => array( 'sidebars' => array( 'docs-side' => array( 'name' => 'Docs' ) ) ),
			'colors' => array( 'primary' => '#111', 'background' => '#fff' ),
			'typography' => array( 'body' => array( 'font_size' => '18' ) ),
			'logo' => array( 'site_title' => array( 'text' => 'Site A', 'color' => 'primary' ), 'logo_width' => array( 'desktop' => '200' ) ),
			'header' => array( 'builder' => array( 'abc' => array( 'builder_type' => 'menu', 'menu' => '2' ) ) ),
			'share' => array( 'label' => 'Share this' ),
			'docs' => array( 'archives_title' => 'Docs', 'docs_category' => array( 'archives_title' => 'Topics' ) ),
			'license' => array( 'key' => 'SECRET-LICENSE' ),
			'integrations' => array( 'api_keys' => array( 'convertkit' => 'SECRET-KEY' ) ),
			'dropins' => array( 'active' => array( 'share' ) )
		) );
		md_test_set_option( 'marketers_delight_license', array( 'key' => 'SECRET-LICENSE' ) );
		md_test_set_option( 'marketers_delight_integrations', array( 'key' => 'SECRET-KEY' ) );
		md_test_set_option( 'sidebars_widgets', array(
			'wp_inactive_widgets' => array( 'text-9' ),
			'sidebar-main' => array( 'text-2', 'block-3' ),
			'docs-side' => array( 'text-3' ),
			'array_version' => 3
		) );
		md_test_set_option( 'widget_text', array( 2 => array( 'title' => 'Hello' ), 3 => array( 'title' => 'Docs help' ), 9 => array( 'title' => 'Old' ), '_multiwidget' => 1 ) );
		md_test_set_option( 'widget_block', array( 3 => array( 'content' => '<p>Hi</p>' ), '_multiwidget' => 1 ) );
	}

	private function empty_site() {
		$GLOBALS['__test_options'] = array();
	}

	private function entry( $file, $section, $scope, $group ) {
		foreach ( $file['sections'][$section] ?? array() as $entry )
			if ( $entry['scope'] === $scope && $entry['group'] === $group )
				return $entry;

		return null;
	}

	private function settings() {
		return get_option( 'marketers_delight', array() );
	}

	// Export

	public function test_export_holds_every_section_with_scope_and_source() {
		$this->site_a();
		$file = $this->file->export();

		$this->assertSame( 'md-export', $file['format'] );
		$this->assertSame( 1, $file['version'] );
		$this->assertSame( array( 'settings', 'design', 'dropins', 'post_types', 'widgets' ), array_keys( $file['sections'] ) );
		$this->assertSame( 'theme', $this->entry( $file, 'settings', 'site', 'settings' )['source'] );
		$this->assertSame( 'share', $this->entry( $file, 'dropins', 'site', 'share' )['source'] );
		$this->assertSame( array( 'archives_title' => 'Docs' ), $this->entry( $file, 'post_types', 'post_type:docs', 'docs' )['values'], 'Taxonomy branches stay out of the post type entry' );
		$this->assertSame( array( 'archives_title' => 'Topics' ), $this->entry( $file, 'post_types', 'taxonomy:docs/docs_category', 'docs' )['values'] );
		$this->assertSame( array( 'name' => 'Main' ), $file['refs']['nav_menu:2'] );
		$this->assertSame( array( 'Share', 'Docs', 'Stream' ), array_column( $file['dropins'], 'name' ) );
	}

	public function test_design_only_export_holds_only_design_keys() {
		$this->site_a();
		$file = $this->file->export( array( 'design' ) );
		$logo = $this->entry( $file, 'design', 'site', 'logo' );

		$this->assertSame( array( 'design' ), array_keys( $file['sections'] ) );
		$this->assertSame( array( 'site_title' => array( 'color' => 'primary' ), 'logo_width' => array( 'desktop' => '200' ) ), $logo['values'], 'No site title text or logo image' );
		$this->assertSame( 'md-aexamplecom-' . gmdate( 'Y-m-d' ) . '.json', $this->file->file_name() );
	}

	public function test_export_never_holds_license_integrations_or_active_dropins() {
		$this->site_a();
		$json = json_encode( $this->file->export() );

		$this->assertStringNotContainsString( 'SECRET', $json );
		$this->assertStringNotContainsString( 'api_keys', $json );
		$this->assertStringNotContainsString( '"active"', $json );
		$this->assertStringNotContainsString( 'text-9', $json, 'Inactive widgets stay' );
	}

	// Round trip, replace, untouched sections

	public function test_round_trip_to_an_empty_site_gives_the_same_values() {
		$this->site_a();
		$before = $this->settings();
		$file = $this->file->read( json_encode( $this->file->export() ) );

		$this->empty_site();
		$result = $this->file->import( $file );
		$after = $this->settings();

		foreach ( array( 'settings', 'colors', 'typography', 'logo', 'header', 'share', 'docs' ) as $group )
			$this->assertSame( $before[$group], $after[$group], $group );

		$this->assertArrayNotHasKey( 'license', $after );
		$this->assertSame( array(), $result['skipped'] );
	}

	public function test_replace_removes_keys_the_file_does_not_have() {
		$this->site_a();
		$file = $this->file->export( array( 'design' ) );

		md_test_set_option( 'marketers_delight', array(
			'colors' => array( 'primary' => '#222', 'background' => '#000' ),
			'typography' => array( 'body' => array( 'font_family' => 'Georgia' ) ),
			'logo' => array( 'site_title' => array( 'text' => 'Site B', 'color' => '#333' ) )
		) );
		$file['sections']['design'][0]['values'] = array( 'primary' => '#111' );

		$this->file->import( $file );
		$after = $this->settings();

		$this->assertSame( array( 'primary' => '#111' ), $after['colors'] );
		$this->assertSame( array( 'body' => array( 'font_size' => '18' ) ), $after['typography'], 'Nested keys the file lacks are removed' );
		$this->assertSame( 'Site B', $after['logo']['site_title']['text'], 'A design file leaves the site title text' );
		$this->assertSame( 'primary', $after['logo']['site_title']['color'] );
	}

	public function test_a_design_file_without_a_split_groups_branch_keeps_its_other_keys() {
		$file = $this->file->read( json_encode( array( 'format' => 'md-export', 'version' => 1, 'sections' => array(
			'design' => array( array( 'scope' => 'site', 'group' => 'logo', 'values' => array( 'logo_width' => array( 'desktop' => '52' ) ) ) )
		) ) ) );
		md_test_set_option( 'marketers_delight', array( 'logo' => array(
			'site_title' => array( 'text' => 'Site B', 'color' => '#333' ),
			'logo' => array( 'id' => 5 )
		) ) );

		$this->file->import( $file );

		$this->assertSame( array(
			'site_title' => array( 'text' => 'Site B' ),
			'logo' => array( 'id' => 5 ),
			'logo_width' => array( 'desktop' => '52' )
		), $this->settings()['logo'] );
	}

	public function test_replace_keeps_taxonomy_branches_inside_a_post_type() {
		$file = array( 'format' => 'md-export', 'version' => 1, 'refs' => array(), 'sections' => array(
			'post_types' => array( array( 'scope' => 'post_type:docs', 'group' => 'docs', 'source' => 'docs', 'values' => array( 'archives_title' => 'Handbook' ) ) )
		) );
		md_test_set_option( 'marketers_delight', array( 'docs' => array(
			'archives_title' => 'Docs',
			'layout' => array( 'sidebar' => 'docs-side' ),
			'docs_category' => array( 'archives_title' => 'Topics' )
		) ) );

		$this->file->import( $file );
		$docs = $this->settings()['docs'];

		$this->assertSame( 'Handbook', $docs['archives_title'] );
		$this->assertArrayNotHasKey( 'layout', $docs );
		$this->assertSame( array( 'archives_title' => 'Topics' ), $docs['docs_category'] );
	}

	public function test_a_full_export_clears_taxonomy_settings_the_source_does_not_set() {
		md_test_set_option( 'marketers_delight', array( 'docs' => array( 'archives_title' => 'Docs' ) ) );
		$file = $this->file->export( array( 'post_types' ) );

		md_test_set_option( 'marketers_delight', array( 'docs' => array( 'docs_category' => array( 'archives_title' => 'Target only' ) ) ) );
		$this->file->import( $file );

		$this->assertSame( array( 'archives_title' => 'Docs' ), $this->settings()['docs'] );
	}

	public function test_unticked_sections_are_untouched() {
		$this->site_a();
		$file = $this->file->export();

		$this->empty_site();
		md_test_set_option( 'marketers_delight', array( 'colors' => array( 'primary' => '#999' ), 'share' => array( 'label' => 'Mine' ) ) );
		md_test_set_option( 'sidebars_widgets', array( 'sidebar-main' => array() ) );

		$this->file->import( $file, array( 'settings' ) );

		$this->assertSame( array( 'primary' => '#999' ), $this->settings()['colors'] );
		$this->assertSame( array( 'label' => 'Mine' ), $this->settings()['share'] );
		$this->assertSame( array( 'sidebar-main' => array() ), get_option( 'sidebars_widgets' ) );
	}

	public function test_replace_input_resets_nested_and_item_keys() {
		$fields = array(
			'title' => array( 'type' => 'text' ),
			'body' => array( 'size' => array( 'type' => 'text' ), 'font' => array( 'type' => 'text' ) ),
			'rows' => array( 'type' => 'group', 'fields' => array( 'name' => array( 'type' => 'text' ), 'icon' => array( 'type' => 'text' ) ) )
		);
		$stored = array( 'title' => 'Old', 'body' => array( 'size' => 1, 'font' => 'x' ), 'rows' => array( 'a' => array( 'name' => 'A', 'icon' => 'i' ) ), 'category' => array( 'kept' => 1 ) );
		$input = $this->file->replace_input( $stored, array( 'body' => array( 'size' => 2 ), 'rows' => array( 'a' => array( 'name' => 'A2' ) ) ), $fields );

		$this->assertSame( array(
			'body' => array( 'size' => 2, 'font' => null ),
			'rows' => array( 'a' => array( 'name' => 'A2', 'icon' => null ) ),
			'title' => null
		), $input );
	}

	// IDs

	public function test_attachment_is_swapped_by_file_path() {
		$GLOBALS['__test_object_meta'][41]['_wp_attached_file'] = '2026/10/logo.png';
		md_test_set_option( 'marketers_delight', array( 'logo' => array( 'logo' => array( 'id' => 41 ) ) ) );
		$file = $this->file->export( array( 'settings' ) );

		$this->assertSame( array( 'file' => '2026/10/logo.png' ), $file['refs']['attachment:41'] );

		$this->empty_site();
		$GLOBALS['__settings_file']['attachments']['2026/10/logo.png'] = 77;
		$this->file->import( $file );

		$this->assertSame( array( 'id' => 77 ), $this->settings()['logo']['logo'] );
	}

	public function test_menu_is_swapped_by_name() {
		$this->site_a();
		$file = $this->file->export( array( 'settings' ) );

		$this->empty_site();
		$GLOBALS['__settings_file']['menus'] = array( 5 => 'Top', 9 => 'Main' );
		$this->register();
		$this->file->import( $file );

		$this->assertSame( '9', $this->settings()['header']['builder']['abc']['menu'] );
	}

	public function test_ids_not_found_are_left_as_they_are_and_listed() {
		$GLOBALS['__test_object_meta'][41]['_wp_attached_file'] = '2026/10/logo.png';
		md_test_set_option( 'marketers_delight', array(
			'logo' => array( 'logo' => array( 'id' => 41 ) ),
			'header' => array( 'builder' => array( 'abc' => array( 'builder_type' => 'menu', 'menu' => '2' ) ) )
		) );
		$file = $this->file->export( array( 'settings' ) );

		$this->empty_site();
		$GLOBALS['__settings_file']['menus'] = array( 3 => 'Top' );
		$this->register();
		md_test_set_option( 'marketers_delight', array( 'logo' => array( 'logo' => array( 'id' => 5 ) ) ) );
		$result = $this->file->import( $file );

		$this->assertSame( array( 'id' => 5 ), $this->settings()['logo']['logo'], 'The site\'s own logo stays' );
		$this->assertSame( array( 'abc' => array( 'builder_type' => 'menu' ) ), $this->settings()['header']['builder'] );
		$this->assertCount( 2, $result['skipped'] );
		$this->assertStringContainsString( 'logo', $result['skipped'][0] );
		$this->assertStringContainsString( 'builder.abc.menu', $result['skipped'][1] );
	}

	// Widgets

	public function test_widgets_are_renumbered_with_their_settings_and_placed() {
		$this->site_a();
		$file = $this->file->export( array( 'widgets' ) );

		$this->empty_site();
		md_test_set_option( 'widget_text', array( 2 => array( 'title' => 'Existing' ), 4 => array( 'title' => 'In footer' ), '_multiwidget' => 1 ) );
		md_test_set_option( 'sidebars_widgets', array( 'sidebar-main' => array( 'text-2' ), 'footer-copy' => array( 'text-4' ) ) );
		$result = $this->file->import( $file );
		$sidebars = get_option( 'sidebars_widgets' );

		$this->assertSame( array( 'text-5', 'block-2' ), $sidebars['sidebar-main'] );
		$this->assertSame( array( 'text-4' ), $sidebars['footer-copy'], 'Sidebars the file doesn\'t name are untouched' );
		$this->assertSame( array( 'title' => 'Hello' ), get_option( 'widget_text' )[5] );
		$this->assertArrayNotHasKey( 2, get_option( 'widget_text' ), 'The replaced sidebar\'s old widget is deleted' );
		$this->assertSame( array( 'content' => '<p>Hi</p>' ), get_option( 'widget_block' )[2] );
		$this->assertSame( array( 'The `docs-side` sidebar doesn\'t exist here, so its widgets weren\'t placed. Select Theme settings to create it.' ), $result['skipped'] );
	}

	public function test_an_empty_sidebar_that_does_not_exist_here_is_not_listed() {
		$file = $this->file->read( json_encode( array( 'format' => 'md-export', 'version' => 1, 'sections' => array( 'widgets' => array(
			'sidebars' => array( 'old-area' => array(), 'sidebar-main' => array() ),
			'instances' => array()
		) ) ) ) );

		$this->assertSame( array(), $this->file->import( $file )['skipped'] );
	}

	public function test_unknown_widget_type_is_skipped_and_listed() {
		$file = array( 'format' => 'md-export', 'version' => 1, 'sections' => array( 'widgets' => array(
			'sidebars' => array( 'sidebar-main' => array( 'stream_widget-2', 'text-2' ) ),
			'instances' => array( 'stream_widget-2' => array( 'count' => 3 ), 'text-2' => array( 'title' => 'Hi' ) )
		) ) );

		$result = $this->file->import( $this->file->read( json_encode( $file ) ) );

		$this->assertSame( array( 'text-2' ), get_option( 'sidebars_widgets' )['sidebar-main'] );
		$this->assertSame( array( 'The `stream_widget` widget isn\'t available on this site.' ), $result['skipped'] );
	}

	public function test_custom_sidebar_from_theme_settings_gets_its_widgets() {
		$this->site_a();
		$file = $this->file->export( array( 'settings', 'widgets' ) );

		$this->empty_site();
		$result = $this->file->import( $file );

		$this->assertSame( array( 'name' => 'Docs' ), $this->settings()['settings']['sidebars']['docs-side'] );
		$this->assertSame( array( 'text-3' ), get_option( 'sidebars_widgets' )['docs-side'] );
		$this->assertSame( array(), $result['skipped'] );
	}

	// Drop-ins

	public function test_missing_and_inactive_dropins_are_named_and_skipped() {
		$this->site_a();
		md_test_set_option( 'marketers_delight', array_merge( $this->settings(), array( 'stream' => array( 'title' => 'Activity' ) ) ) );
		$this->register( array( 'stream' => array( '_source' => 'stream', 'has_groups' => true, 'fields' => array( 'title' => array( 'type' => 'text' ) ) ) ) );
		$file = $this->file->export();

		$this->empty_site();
		unset( $GLOBALS['__settings_file']['details']['docs'] );
		md_test_set_dropins( array( 'share' ) );
		$this->register( array( 'docs' => array(), 'stream' => array() ) );
		$check = $this->file->check( $file );
		$result = $this->file->import( $file );

		$this->assertSame( array( 'docs' => 'Docs' ), $check['missing'] );
		$this->assertSame( array( 'stream' => 'Stream' ), $check['inactive'] );
		$this->assertContains( 'No settings are registered for `stream`.', $result['skipped'] );
		$this->assertContains( 'No settings are registered for `docs`.', $result['skipped'] );
		$this->assertSame( array( 'label' => 'Share this' ), $this->settings()['share'], 'Active drop-ins import' );
	}

	public function test_option_without_sanitize_is_exported_but_not_imported() {
		md_test_set_filter( 'md_export_options', array( 'md_popups' => array( 'source' => 'optins' ) ) );
		md_test_set_option( 'md_popups', array( 'p1' => array( 'content' => array( 'headline' => 'Join' ) ) ) );
		$file = $this->file->export( array( 'dropins' ) );

		$this->assertSame( array( 'source' => 'optins', 'values' => array( 'p1' => array( 'content' => array( 'headline' => 'Join' ) ) ) ), $file['sections']['options']['md_popups'] );

		$this->empty_site();
		$result = $this->file->import( $file );

		$this->assertFalse( get_option( 'md_popups' ) );
		$this->assertContains( 'The `md_popups` settings can\'t be imported on this site.', $result['skipped'] );
	}

	public function test_option_with_sanitize_is_imported_through_it() {
		md_test_set_filter( 'md_export_options', array( 'md_popups' => array( 'source' => 'optins', 'sanitize' => 'array_reverse' ) ) );
		md_test_set_option( 'md_popups', array( 'a' => 1, 'b' => 2 ) );
		$file = $this->file->export( array( 'dropins' ) );

		$this->empty_site();
		$this->file->import( $file );

		$this->assertSame( array( 'b' => 2, 'a' => 1 ), get_option( 'md_popups' ) );
	}

	// Code fields

	public function test_code_field_imports_for_an_admin_and_is_skipped_with_a_warning_without() {
		md_test_set_option( 'marketers_delight', array( 'settings' => array( 'css' => 'body{color:red}' ) ) );
		$file = $this->file->export( array( 'settings' ) );

		$this->empty_site();
		$this->file->import( $file );
		$this->assertSame( 'body{color:red}', $this->settings()['settings']['css'] );

		$this->empty_site();
		md_test_set_capability( 'unfiltered_html', false );
		$result = $this->file->import( $file );

		$this->assertArrayNotHasKey( 'settings', $this->settings() );
		$this->assertContains( '`css` is code, which needs an administrator who can add unfiltered HTML. It was left as it is.', $result['skipped'] );
	}

	public function test_a_rejected_value_is_listed_in_the_result() {
		$file = $this->file->read( json_encode( array( 'format' => 'md-export', 'version' => 1, 'sections' => array(
			'settings' => array( array( 'scope' => 'site', 'group' => 'header', 'values' => array(
				'builder' => array( 'abc' => array( 'builder_type' => 'menu', 'menu' => '2' ) )
			) ) ),
			'dropins' => array( array( 'scope' => 'site', 'group' => 'pick', 'source' => 'theme', 'values' => array( 'list' => 'gone', 'empty' => '' ) ) )
		) ) ) );
		$this->register( array( 'pick' => array( '_source' => 'theme', 'fields' => array(
			'list' => array( 'type' => 'select', 'options' => array( 'a', 'b' ) ),
			'empty' => array( 'type' => 'select', 'options' => array( 'a', 'b' ) )
		) ) ) );

		$result = $this->file->import( $file, array( 'dropins' ) );

		$this->assertSame( array( '`list` has a value this site can\'t use.' ), $result['skipped'] );
		$this->assertArrayNotHasKey( 'pick', $this->settings() );
	}

	public function test_checkboxes_saved_as_off_are_not_listed_as_rejected() {
		$this->register( array( 'pick' => array( '_source' => 'theme', 'fields' => array(
			'show' => array( 'type' => 'checkbox', 'options' => array( 'date', 'author' ) )
		) ) ) );
		$file = $this->file->read( json_encode( array( 'format' => 'md-export', 'version' => 1, 'sections' => array(
			'settings' => array( array( 'scope' => 'site', 'group' => 'pick', 'values' => array( 'show' => array( 'date' => false ) ) ) )
		) ) ) );

		$this->assertSame( array(), $this->file->import( $file )['skipped'] );
	}

	// Bad files

	public function test_bad_files_are_refused() {
		$this->assertSame( 'md_import_json', $this->file->read( '{not json' )->get_error_code() );
		$this->assertSame( 'md_import_format', $this->file->read( json_encode( array( 'format' => 'other', 'version' => 1 ) ) )->get_error_code() );
		$this->assertSame( 'md_import_version', $this->file->read( json_encode( array( 'format' => 'md-export', 'version' => 2 ) ) )->get_error_code() );
		$this->assertSame( 'md_import_size', $this->file->read( str_repeat( ' ', $this->file->max_size + 1 ) )->get_error_code() );
		$this->assertSame( 'md_import_json', $this->file->read( str_repeat( '[', 70 ) . str_repeat( ']', 70 ) )->get_error_code(), 'Too deep' );
	}

	public function test_bad_entries_are_skipped_and_the_rest_imports() {
		$file = $this->file->read( json_encode( array( 'format' => 'md-export', 'version' => 1, 'sections' => array(
			'settings' => array(
				array( 'scope' => 'style:dark', 'group' => 'colors', 'values' => array( 'primary' => '#000' ) ),
				array( 'scope' => 'post:4', 'group' => 'layout', 'values' => array() ),
				'not an entry',
				array( 'scope' => 'site', 'group' => 'share', 'source' => 'share', 'values' => array( 'label' => 'Ok', 'unknown' => 1 ) )
			)
		) ) ) );

		$result = $this->file->import( $file );

		$this->assertContains( 'Unknown settings scope: `style:dark`', $result['skipped'] );
		$this->assertContains( 'Settings for `post:4` can\'t be imported.', $result['skipped'] );
		$this->assertContains( 'An entry in `settings` can\'t be read.', $result['skipped'] );
		$this->assertSame( array( 'label' => 'Ok' ), $this->settings()['share'] );
		$this->assertContains( '`share.unknown` isn\'t used on this site.', $result['skipped'] );
	}

	public function test_unknown_sections_are_listed_and_a_style_file_applies_its_design() {
		$file = $this->file->read( json_encode( array(
			'format' => 'md-style',
			'version' => 1,
			'style' => array( 'name' => 'Midnight', 'kind' => 'visitor' ),
			'sections' => array(
				'design' => array( array( 'scope' => 'site', 'group' => 'colors', 'values' => array( 'primary' => '#000' ) ) ),
				'patterns' => array( 'x' => 1 )
			)
		) ) );

		$check = $this->file->check( $file );
		$this->file->import( $file );

		$this->assertSame( array( 'The `patterns` section isn\'t used by this version of MD.' ), $check['skipped'] );
		$this->assertSame( array( 'design' ), array_keys( $check['parts'] ) );
		$this->assertSame( array( 'primary' => '#000' ), $this->settings()['colors'] );
	}

	public function test_an_import_marks_one_compile_and_rebuilds_fields_once() {
		$this->site_a();
		$file = $this->file->export();

		$this->empty_site();
		$this->file->import( $file );

		$this->assertTrue( get_option( 'md_compile_after_upgrade' ) );
		$this->assertSame( array( 'md_refresh_fields' ), $GLOBALS['__settings_file']['actions'] );
	}

	// Merge mode and labels

	private function setup_file( $mode = 'merge' ) {
		return $this->file->read( json_encode( array( 'format' => 'md-export', 'version' => 1, 'sections' => array(
			'settings' => array( array(
				'scope' => 'site',
				'group' => 'settings',
				'mode' => $mode,
				'label' => 'Docs sidebars',
				'values' => array( 'sidebars' => array(
					'docs-single' => array( 'name' => 'Docs single' ),
					'docs-archive' => array( 'name' => 'Docs archive' ),
					'docs-panel' => array( 'name' => 'Docs panel' )
				) )
			) ),
			'post_types' => array( array(
				'scope' => 'post_type:docs',
				'group' => 'docs',
				'mode' => $mode,
				'label' => 'Docs archive title',
				'values' => array( 'archives_title' => 'Knowledge base' )
			) ),
			'widgets' => array(
				'sidebars' => array( 'docs-single' => array( 'text-1' ) ),
				'instances' => array( 'text-1' => array( 'title' => 'Need help?' ) )
			)
		) ) ) );
	}

	public function test_merge_adds_sidebars_and_keeps_the_sites_own() {
		md_test_set_option( 'marketers_delight', array(
			'settings' => array( 'css' => 'a{}', 'sidebars' => array( 'shop' => array( 'name' => 'Shop' ), 'blog' => array( 'name' => 'Blog' ) ) ),
			'docs' => array( 'layout' => array( 'sidebar' => 'shop' ) )
		) );

		$this->file->import( $this->setup_file() );
		$settings = $this->settings();

		$this->assertSame( array( 'shop', 'blog', 'docs-single', 'docs-archive', 'docs-panel' ), array_keys( $settings['settings']['sidebars'] ) );
		$this->assertSame( 'a{}', $settings['settings']['css'] );
		$this->assertSame( array( 'layout' => array( 'sidebar' => 'shop' ), 'archives_title' => 'Knowledge base' ), $settings['docs'] );
	}

	public function test_merge_reimport_updates_the_same_ids_without_duplicates() {
		$this->file->import( $this->setup_file() );
		$file = $this->setup_file();
		$file['sections']['settings'][0]['values']['sidebars']['docs-panel']['name'] = 'Docs help panel';

		$this->file->import( $file );
		$sidebars = $this->settings()['settings']['sidebars'];

		$this->assertCount( 3, $sidebars );
		$this->assertSame( array( 'name' => 'Docs help panel' ), $sidebars['docs-panel'] );
		$this->assertSame( array( 'docs-single' => array( 'text-2' ) ), array_intersect_key( get_option( 'sidebars_widgets' ), array( 'docs-single' => 1 ) ) );
		$this->assertCount( 1, array_diff_key( get_option( 'widget_text' ), array( '_multiwidget' => 1 ) ), 'The first import\'s widget was replaced, not doubled' );
	}

	public function test_merge_widgets_only_touch_the_files_sidebars() {
		md_test_set_option( 'sidebars_widgets', array( 'sidebar-main' => array( 'block-2' ) ) );
		md_test_set_option( 'widget_block', array( 2 => array( 'content' => 'Mine' ), '_multiwidget' => 1 ) );

		$this->file->import( $this->setup_file() );

		$this->assertSame( array( 'block-2' ), get_option( 'sidebars_widgets' )['sidebar-main'] );
		$this->assertSame( array( 'text-2' ), get_option( 'sidebars_widgets' )['docs-single'] );
	}

	public function test_labelled_parts_can_be_unticked_on_their_own() {
		$file = $this->setup_file();
		$check = $this->file->check( $file );

		$this->assertSame( array( 'settings:docs-sidebars', 'post_types:docs-archive-title', 'widgets' ), array_keys( $check['parts'] ) );
		$this->assertSame( 'Docs sidebars', $check['parts']['settings:docs-sidebars'] );

		$result = $this->file->import( $file, array( 'settings:docs-sidebars', 'widgets' ) );

		$this->assertArrayNotHasKey( 'docs', $this->settings() );
		$this->assertCount( 3, $this->settings()['settings']['sidebars'] );
		$this->assertSame( array( 'Docs sidebars', 'Widgets' ), $result['imported'] );
	}

	public function test_registered_groups_name_the_dropin_folder_they_come_from() {
		require_once __DIR__ . '/fixtures/dropins/example/source.php';

		$dropin = new md_example_source;
		$theme = new md_import_export;

		$this->assertSame( 'example', $dropin->_source() );
		$this->assertSame( 'theme', $theme->_source() );
	}

	// Handlers

	public function test_messages_show_marked_names_as_code_and_stay_escaped() {
		$page = new md_import_export;

		$this->assertSame( '<code>a_b</code> &lt;b&gt;', $page->message( '`a_b` <b>' ) );
	}

	public function test_every_handler_needs_manage_options_and_its_nonce() {
		$page = new md_import_export;

		foreach ( array( 'export', 'upload', 'import', 'cancel' ) as $handler ) {
			md_test_set_capability( 'manage_options', false );
			$GLOBALS['__settings_file']['nonce_ok'] = true;

			try {
				$page->$handler();
				$this->fail( "$handler ran without manage_options" );
			}
			catch ( Exception $e ) {
				$this->assertSame( 'wp_die', $e->getMessage(), $handler );
			}

			md_test_set_capability( 'manage_options', true );
			$GLOBALS['__settings_file']['nonce_ok'] = false;

			try {
				$page->$handler();
				$this->fail( "$handler ran without its nonce" );
			}
			catch ( Exception $e ) {
				$this->assertSame( 'nonce', $e->getMessage(), $handler );
			}
		}
	}

}
