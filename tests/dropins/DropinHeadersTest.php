<?php
/**
 * A Drop-in's details come from its header docblock. A config.json, when a
 * Drop-in still ships one, is read on top of the header (deprecated). The
 * Drop-ins option keeps only which Drop-ins are on.
 *
 * @since 6.2
 */

class DropinHeadersTest extends MD_Dropin_TestCase {

	public function test_header_keys_are_read() {
		$this->place( 'alpha', $this->header_dropin( 'alpha', '2.1.0', array(
			'Drop-in URI' => 'https://example.com/alpha/',
			'Author URI' => 'https://example.com/',
			'Text Domain' => 'md-alpha',
			'Requires MD' => '6.1',
			'Icon' => 'dashicons-schedule',
			'Colors' => '#1e73be, #ffffff',
			'Settings URL' => 'edit.php?post_type=alpha&page=md_alpha',
			'Load Priority' => '5',
			'Activate On Install' => 'true'
		) ) );

		$details = md_get_dropin_details( 'alpha' );

		$this->assertSame( 'Test alpha', $details['Name'] );
		$this->assertSame( '2.1.0', $details['Version'] );
		$this->assertSame( 'https://example.com/alpha/', $details['DropinURI'] );
		$this->assertSame( 'md-alpha', $details['TextDomain'] );
		$this->assertSame( '6.1', $details['RequiresMD'] );
		$this->assertSame( 'dashicons-schedule', $details['Icon'] );
		$this->assertSame( '#1e73be, #ffffff', $details['Colors'] );
		$this->assertSame( 'edit.php?post_type=alpha&page=md_alpha', $details['SettingsURL'] );
		$this->assertSame( '5', $details['LoadPriority'] );
		$this->assertTrue( $details['ActivateOnInstall'] );
		$this->assertSame( 'alpha', $details['slug'] );
		$this->assertSame( 'alpha/alpha.php', $details['file'] );
	}

	public function test_config_json_values_win_over_the_header() {
		$files = $this->header_dropin( 'beta', '1.0', array( 'Icon' => 'dashicons-star-filled', 'Colors' => '#000000' ) );
		$files['config.json'] = wp_json_encode( array(
			'name' => 'Beta From Config',
			'version' => '1.4',
			'icon' => 'dashicons-email',
			'settings_url' => 'admin.php?page=md_beta',
			'priority' => 3
		) );

		$this->place( 'beta', $files );
		$details = md_get_dropin_details( 'beta' );

		$this->assertSame( 'Beta From Config', $details['Name'] );
		$this->assertSame( '1.4', $details['Version'] );
		$this->assertSame( 'dashicons-email', $details['Icon'] );
		$this->assertSame( '#000000', $details['Colors'], 'Keys config.json leaves out keep the header value.' );
		$this->assertSame( 'admin.php?page=md_beta', $details['SettingsURL'] );
		$this->assertSame( '3', $details['LoadPriority'] );
	}

	public function test_config_only_dropin_with_old_header_keys_is_installed() {
		$this->place( 'legacy', $this->config_dropin( 'legacy', '1.2', array( 'active' => true ) ) );

		$details = md_get_dropin_details( 'legacy' );

		$this->assertSame( 'Legacy legacy', $details['Name'] );
		$this->assertSame( '1.2', $details['Version'], 'config.json, not the old header, sets the version.' );
		$this->assertSame( 'dashicons-archive', $details['Icon'] );
		$this->assertTrue( $details['ActivateOnInstall'] );
	}

	public function test_folder_without_a_name_is_not_a_dropin() {
		$this->place( 'nameless', array( 'nameless.php' => "<?php\n/**\n * Version: 1.0\n */\n" ) );
		$this->place( 'nomain', array( 'other.php' => $this->header( 'nomain', array( 'Drop-in Name' => 'X' ) ) ) );
		$this->place( 'Bad_Slug', $this->header_dropin( 'Bad_Slug' ) );

		$this->assertFalse( md_get_dropin_details( 'nameless' ) );
		$this->assertFalse( md_get_dropin_details( 'nomain' ) );
		$this->assertArrayNotHasKey( 'Bad_Slug', md_get_dropin_details() );
	}

	public function test_icon_accepts_dashicons_and_image_files_only() {
		$cases = array(
			'dashicons-book-alt' => 'dashicons-book-alt',
			'icon.svg' => 'icon.svg',
			'logo-2x.png' => 'logo-2x.png',
			'../secret.svg' => '',
			'sub/icon.svg' => '',
			'javascript:alert(1)' => '',
			'icon.php' => '',
			'fa-star' => ''
		);

		foreach ( $cases as $icon => $expected ) {
			$details = md_sanitize_dropin_details( array( 'Name' => 'X', 'Icon' => $icon ) );
			$this->assertSame( $expected, $details['Icon'], "Icon: $icon" );
		}
	}

	public function test_icon_tile_shows_an_image_file_or_falls_back_to_a_dashicon() {
		$files = $this->header_dropin( 'pic', '1.0', array( 'Icon' => 'icon.svg', 'Colors' => '#6b21a8, #ffffff' ) );
		$files['icon.svg'] = '<svg xmlns="http://www.w3.org/2000/svg"/>';
		$this->place( 'pic', $files );
		$this->place( 'nopic', $this->header_dropin( 'nopic', '1.0', array( 'Icon' => 'missing.png' ) ) );
		$this->place( 'dash', $this->header_dropin( 'dash', '1.0', array( 'Icon' => 'dashicons-email' ) ) );

		$pic = md_get_dropin_icon( 'pic' );
		$this->assertStringContainsString( '<img src="https://example.com/md-dropins/pic/icon.svg"', $pic );
		$this->assertStringContainsString( 'background-color', $pic );
		$this->assertStringContainsString( 'dashicons-admin-plugins', md_get_dropin_icon( 'nopic' ) );
		$this->assertStringContainsString( 'dashicons-email', md_get_dropin_icon( 'dash' ) );
		$this->assertStringContainsString( 'md-dropin-icon-small', md_get_dropin_icon( 'dash', 'small' ) );
		$this->assertStringContainsString( 'dashicons-admin-plugins', md_get_dropin_icon( 'not-installed' ) );
	}

	public function test_colors_priority_and_activate_flags_are_cleaned() {
		$details = md_sanitize_dropin_details( array(
			'Name' => 'X',
			'Colors' => '#fff, red, #123456, #abcdef',
			'LoadPriority' => 'soon',
			'ActivateOnInstall' => 'no',
			'DropinURI' => 'javascript:alert(1)'
		) );

		$this->assertSame( '#fff, #123456', $details['Colors'] );
		$this->assertSame( '', $details['LoadPriority'] );
		$this->assertFalse( $details['ActivateOnInstall'] );
		$this->assertSame( '', $details['DropinURI'] );
	}

	public function test_active_list_loads_by_priority_then_name() {
		$this->place( 'zulu', $this->header_dropin( 'zulu' ) );
		$this->place( 'alpha', $this->header_dropin( 'alpha' ) );
		$this->place( 'late', $this->header_dropin( 'late', '1.0', array( 'Load Priority' => '20' ) ) );
		$this->place( 'early', $this->header_dropin( 'early', '1.0', array( 'Load Priority' => '1' ) ) );
		$this->place( 'off', $this->header_dropin( 'off' ) );

		md_set_dropins_status( array( 'zulu', 'alpha', 'late', 'early' ), true );

		$this->assertSame( array( 'early', 'late', 'alpha', 'zulu' ), $this->active() );
		$this->assertSame( array( 'off' ), md_get_dropins( 'inactive' ) );
	}

	public function test_option_keeps_only_which_dropins_are_on() {
		$this->place( 'stream', $this->header_dropin( 'stream' ) );

		md_update_dropins( array(
			'installed' => array(
				'stream' => array( 'name' => 'Stream', 'version' => '2.0', 'icon' => 'x', 'status' => array( 'enable' => true ) ),
				'old' => array( 'name' => 'Old', 'version' => '1.0' )
			),
			'priority' => array( 'stream' => true )
		) );

		$option = get_option( 'marketers_delight_dropins' );

		$this->assertSame( array( 'dropins' => array( 'installed' => array( 'stream' => array( 'status' => array( 'enable' => true ) ) ) ) ), $option );
		$this->assertSame( array( 'stream' ), $this->active() );
	}

	public function test_active_dropin_missing_from_disk_is_not_loaded() {
		md_update_dropins( array( 'installed' => array( 'gone' => array( 'status' => array( 'enable' => true ) ) ) ) );

		$this->assertSame( array(), $this->active() );
	}

	public function test_package_validation_accepts_config_only_and_checks_identity() {
		$root = MD_DROPIN_TESTS_ROOT . '/staged';
		$legacy = $this->write( $root, 'legacy', $this->config_dropin( 'legacy' ) );
		$mismatch = $this->write( $root, 'mine', $this->header_dropin( 'mine', '1.0', array( 'Drop-in Slug' => 'theirs' ) ) );
		$future = $this->write( $root, 'future', $this->header_dropin( 'future', '1.0', array( 'Requires MD' => '99.0' ) ) );

		$data = md_validate_dropin_package( $legacy, 'legacy' );
		$this->assertIsArray( $data );
		$this->assertSame( 'Legacy legacy', $data['Name'] );
		$this->assertSame( 'invalid_dropin_identity', md_validate_dropin_package( $mismatch, 'mine' )->get_error_code() );
		$this->assertSame( 'incompatible_md_required_version', md_validate_dropin_package( $future, 'future' )->get_error_code() );
		$this->assertSame( 'invalid_dropin_slug', md_validate_dropin_package( $legacy, '../legacy' )->get_error_code() );
	}

	public function test_every_dropin_in_the_site_parses_with_a_name_and_version() {
		$directory = WP_CONTENT_DIR . '/md-dropins';

		if ( ! is_dir( $directory ) )
			$this->markTestSkipped( 'The site has no md-dropins folder.' );

		$checked = 0;

		foreach ( scandir( $directory ) as $slug ) {
			if ( $slug[0] === '.' || ! is_dir( "$directory/$slug" ) )
				continue;

			$details = md_read_dropin_details( "$directory/$slug", $slug );

			$this->assertIsArray( $details, "$slug has no Drop-in Name (header or config.json)." );
			$this->assertNotSame( '', $details['Version'], "$slug has no Version." );

			if ( ! is_file( "$directory/$slug/config.json" ) )
				$this->assertNotSame( '', $details['Icon'], "$slug moved to headers but has no Icon." );

			$checked++;
		}

		$this->assertGreaterThan( 0, $checked );
	}

}
