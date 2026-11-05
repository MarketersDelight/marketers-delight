<?php
/**
 * Drop-in operations (admin/dropins/functions.php): install from a ZIP or
 * a licensed package, replace, activate, deactivate, delete, update and the
 * update check, as the screens, AJAX and cron call them.
 *
 * @since 6.2
 */

class DropinManagerTest extends MD_Dropin_TestCase {

	public function test_install_header_only_dropin_from_zip() {
		$zip = $this->zip( 'alpha', array( 'alpha' => $this->header_dropin( 'alpha', '1.0', array( 'Icon' => 'dashicons-email' ) ) ) );

		$result = md_install_dropin( $zip );

		$this->assertIsArray( $result, is_wp_error( $result ) ? $result->get_error_message() : '' );
		$this->assertSame( 'alpha', $result['slug'] );
		$this->assertSame( '1.0', $result['version'] );
		$this->assertFalse( $result['active'] );
		$this->assertFalse( $result['replaced'] );
		$this->assertFileExists( MD_INSTALLED_DROPINS . '/alpha/alpha.php' );
		$this->assertFileExists( MD_INSTALLED_DROPINS . '/alpha/index.php', 'The folder gets a silent index.' );
		$this->assertSame( 'dashicons-email', md_get_dropin_details( 'alpha' )['Icon'] );
		$this->assertNotContains( 'alpha', $this->active() );
	}

	public function test_install_config_only_dropin_activates_when_it_asks_to() {
		$zip = $this->zip( 'legacy', array( 'legacy' => $this->config_dropin( 'legacy', '1.0', array( 'active' => true ) ) ) );

		$result = md_install_dropin( $zip );

		$this->assertIsArray( $result );
		$this->assertSame( 'Legacy legacy', $result['name'] );
		$this->assertTrue( $result['active'] );
		$this->assertContains( 'legacy', $this->active() );
		$this->assertTrue( (bool) get_option( 'md_compile_after_upgrade' ) );
	}

	public function test_installed_dropin_is_only_replaced_when_asked() {
		md_install_dropin( $this->zip( 'one', array( 'alpha' => $this->header_dropin( 'alpha', '1.0', array( 'Requires MD' => '6.0' ) ) ) ) );
		$newer = $this->zip( 'two', array( 'alpha' => $this->header_dropin( 'alpha', '1.1', array( 'Requires MD' => '6.1' ) ) ) );

		$exists = md_install_dropin( $newer );

		$this->assertWPError( $exists, 'md_dropin_exists' );
		$data = $exists->get_error_data();
		$this->assertSame( '1.0', $data['current']['Version'] );
		$this->assertSame( '1.1', $data['uploaded']['Version'] );
		$this->assertSame( '6.1', $data['uploaded']['RequiresMD'] );
		$this->assertSame( '1.0', md_get_dropin_details( 'alpha' )['Version'], 'Nothing changes until Replace.' );

		$replaced = md_install_dropin( $newer, array( 'overwrite' => true ) );

		$this->assertIsArray( $replaced );
		$this->assertTrue( $replaced['replaced'] );
		$this->assertSame( '1.1', md_get_dropin_details( 'alpha' )['Version'] );
	}

	public function test_replacing_keeps_the_dropin_on_and_clears_a_met_update() {
		md_install_dropin( $this->zip( 'one', array( 'alpha' => $this->header_dropin( 'alpha', '1.0' ) ) ) );
		md_activate_dropins( array( 'alpha' ) );
		$this->offer_update( 'alpha', '1.1', 'https://example.com/alpha.zip' );

		md_install_dropin( $this->zip( 'two', array( 'alpha' => $this->header_dropin( 'alpha', '1.1' ) ) ), array( 'overwrite' => true ) );

		$this->assertContains( 'alpha', $this->active() );
		$this->assertArrayNotHasKey( 'alpha/alpha.php', md_dropin_updates() );
	}

	public function test_install_rejects_bad_packages() {
		$future = $this->zip( 'future', array( 'future' => $this->header_dropin( 'future', '1.0', array( 'Requires MD' => '99.0' ) ) ) );
		$two = $this->zip( 'two', array( 'a' => $this->header_dropin( 'a' ), 'b' => $this->header_dropin( 'b' ) ) );
		$nameless = $this->zip( 'nameless', array( 'nameless' => array( 'nameless.php' => "<?php\n" ) ) );

		$this->assertWPError( md_install_dropin( $future ), 'incompatible_md_required_version' );
		$this->assertWPError( md_install_dropin( $two ), 'invalid_dropin_structure' );
		$this->assertWPError( md_install_dropin( $nameless ), 'invalid_dropin_identity' );
		$this->assertSame( array(), md_get_dropin_details() );
	}

	public function test_install_from_a_licensed_package_url() {
		$zip = $this->zip( 'remote', array( 'remote' => $this->header_dropin( 'remote', '2.0' ) ) );
		$license = md_license_setting();
		$license['available']['remote'] = array( 'name' => 'Remote', 'slug' => 'remote', 'version' => '2.0', 'package' => 'https://example.com/remote.zip' );
		md_update_license( $license );

		$this->mock_download( 'https://example.com/remote.zip', $zip );

		$this->assertArrayHasKey( 'remote', md_available_dropins() );

		$result = md_install_dropin( 'https://example.com/remote.zip' );

		$this->assertIsArray( $result, is_wp_error( $result ) ? $result->get_error_message() : '' );
		$this->assertSame( '2.0', $result['version'] );
		$this->assertArrayNotHasKey( 'remote', md_available_dropins() );
		$this->assertArrayNotHasKey( 'remote', (array) md_license_setting( 'available', array() ) );
	}

	public function test_activate_and_deactivate_several_at_once() {
		$this->place( 'alpha', $this->header_dropin( 'alpha' ) );
		$this->place( 'beta', $this->header_dropin( 'beta' ) );

		$results = md_activate_dropins( array( 'alpha', 'beta', 'missing' ) );

		$this->assertTrue( $results['alpha'] );
		$this->assertTrue( $results['beta'] );
		$this->assertWPError( $results['missing'], 'md_dropin_missing' );
		$this->assertSame( array( 'alpha', 'beta' ), $this->active() );
		$this->assertTrue( (bool) get_option( 'md_compile_after_upgrade' ), 'One compile on the next load.' );

		md_deactivate_dropins( array( 'alpha' ) );

		$this->assertSame( array( 'beta' ), $this->active() );
		$this->assertSame( array( 'beta' => true ), md_deactivate_dropins( array( 'beta' ) ) );
		$this->assertSame( array(), $this->active() );
	}

	public function test_incompatible_dropin_cannot_be_activated() {
		$this->place( 'future', $this->header_dropin( 'future', '1.0', array( 'Requires MD' => '99.0' ) ) );

		$results = md_activate_dropins( array( 'future' ) );

		$this->assertWPError( $results['future'], 'incompatible_md_required_version' );
		$this->assertSame( array(), $this->active() );
	}

	public function test_delete_removes_inactive_dropins_and_refuses_active_ones() {
		$this->place( 'alpha', $this->header_dropin( 'alpha' ) );
		$this->place( 'beta', $this->header_dropin( 'beta' ) );
		md_activate_dropins( array( 'beta' ) );
		$this->offer_update( 'alpha', '2.0', 'https://example.com/alpha.zip' );

		$results = md_delete_dropins( array( 'alpha', 'beta', '../themes' ) );

		$this->assertTrue( $results['alpha'] );
		$this->assertWPError( $results['beta'], 'md_dropin_active' );
		$this->assertWPError( $results['themes'], 'md_dropin_missing' );
		$this->assertDirectoryDoesNotExist( MD_INSTALLED_DROPINS . '/alpha' );
		$this->assertDirectoryExists( MD_INSTALLED_DROPINS . '/beta' );
		$this->assertFalse( md_get_dropin_details( 'alpha' ) );
		$this->assertArrayNotHasKey( 'alpha/alpha.php', (array) md_license_setting( array( 'updates', 'dropins' ), array() ) );
		$this->assertSame( array( 'beta' ), $this->active() );
	}

	public function test_update_several_dropins_from_the_last_check() {
		$this->place( 'alpha', $this->header_dropin( 'alpha', '1.0' ) );
		$this->place( 'legacy', $this->config_dropin( 'legacy', '1.0' ) );
		md_activate_dropins( array( 'alpha' ) );

		$this->offer_update( 'alpha', '1.1', $this->zip( 'alpha-1.1', array( 'alpha' => $this->header_dropin( 'alpha', '1.1' ) ) ) );
		$this->offer_update( 'legacy', '1.1', $this->zip( 'legacy-1.1', array( 'legacy' => $this->config_dropin( 'legacy', '1.1' ) ) ) );

		$this->assertCount( 2, md_dropin_updates() );

		$results = md_upgrade_dropins( array( 'alpha', 'legacy' ) );

		$this->assertSame( array( 'alpha' => true, 'legacy' => true ), $results );
		$this->assertSame( '1.1', md_get_dropin_details( 'alpha' )['Version'] );
		$this->assertSame( '1.1', md_get_dropin_details( 'legacy' )['Version'] );
		$this->assertSame( array(), md_dropin_updates() );
		$this->assertSame( array( 'alpha' ), $this->active(), 'An update keeps a Drop-in on.' );
		$this->assertFileDoesNotExist( ABSPATH . '.maintenance' );
	}

	public function test_failed_update_leaves_the_dropin_and_its_update_in_place() {
		$this->place( 'alpha', $this->header_dropin( 'alpha', '1.0' ) );
		$this->offer_update( 'alpha', '1.1', MD_DROPIN_TESTS_ROOT . '/missing.zip' );

		$results = md_upgrade_dropins( array( 'alpha' ) );

		$this->assertNotTrue( $results['alpha'] );
		$this->assertSame( '1.0', md_get_dropin_details( 'alpha' )['Version'] );
		$this->assertCount( 1, md_dropin_updates() );
	}

	public function test_updates_only_count_versions_newer_than_the_files() {
		$this->place( 'alpha', $this->header_dropin( 'alpha', '2.0' ) );
		$this->offer_update( 'alpha', '1.9', 'https://example.com/old.zip' );
		$this->offer_update( 'gone', '3.0', 'https://example.com/gone.zip' );

		$this->assertSame( array(), md_dropin_updates() );
	}

	public function test_update_check_saves_updates_and_available_dropins() {
		$this->place( 'alpha', $this->header_dropin( 'alpha', '1.0' ) );
		$this->place( 'beta', $this->header_dropin( 'beta', '2.0' ) );

		$this->mock_mdcom( array(
			'1' => array( 'slug' => 'alpha', 'name' => 'Alpha', 'version' => '1.2', 'package' => 'https://example.com/alpha.zip' ),
			'2' => array( 'slug' => 'beta', 'name' => 'Beta', 'version' => '2.0', 'package' => 'https://example.com/beta.zip' ),
			'3' => array( 'slug' => 'gamma', 'name' => 'Gamma', 'version' => '1.0', 'package' => 'https://example.com/gamma.zip' ),
			'4' => array( 'slug' => 'nopackage', 'name' => 'No package', 'version' => '1.0' )
		) );

		$this->assertSame( 'success', md_check_dropin_updates() );

		$updates = md_dropin_updates();
		$available = md_available_dropins();

		$this->assertSame( array( 'alpha/alpha.php' ), array_keys( $updates ) );
		$this->assertSame( '1.2', $updates['alpha/alpha.php']['new_version'] );
		$this->assertSame( array( 'gamma' ), array_keys( $available ) );
		$this->assertSame( 'https://example.com/gamma.zip', $available['gamma']['package'] );
		$this->assertGreaterThan( time() - 60, md_license_setting( 'last_check' ) );
	}

	public function test_update_check_sorts_dropins_by_plan_status() {
		$this->mock_mdcom( array(
			'1' => array( 'slug' => 'claimed', 'name' => 'Claimed', 'version' => '1.0', 'status' => 'claimed', 'package' => 'https://example.com/claimed.zip', 'description' => 'Mine.', 'icon' => 'dashicons-email', 'colors' => '#123456' ),
			'2' => array( 'slug' => 'included', 'name' => 'Included', 'version' => '1.0', 'status' => 'included' ),
			'3' => array( 'slug' => 'locked', 'name' => 'Locked', 'version' => '1.0', 'status' => 'upgrade', 'plan' => 'Pro', 'upgrade_url' => 'https://example.com/upgrade/' ),
			'4' => array( 'slug' => 'old', 'name' => 'Old response', 'version' => '1.0', 'package' => 'https://example.com/old.zip' ),
			'5' => array( 'slug' => 'broken', 'name' => 'Claimed, no package', 'status' => 'claimed' ),
			'6' => array( 'slug' => 'odd', 'name' => 'Unknown status', 'status' => 'beta', 'package' => 'https://example.com/odd.zip' ),
			'7' => array( 'slug' => 'unready', 'name' => 'Claimed, not 6.0 ready', 'status' => 'claimed', 'ready' => '0', 'package' => 'https://example.com/unready.zip' )
		) );

		md_check_dropin_updates();

		$available = md_available_dropins();
		$more = md_available_dropins( 'upgrade' );

		$this->assertSame( array( 'claimed', 'included', 'old' ), array_keys( $available ) );
		$this->assertSame( 'claimed', $available['old']['status'], 'A package without a status is a claimed Drop-in (MD.com before 6.2).' );
		$this->assertSame( 'Mine.', $available['claimed']['description'] );
		$this->assertSame( 'dashicons-email', $available['claimed']['icon'] );
		$this->assertArrayNotHasKey( 'package', $available['included'] );
		$this->assertSame( array( 'locked' ), array_keys( $more ) );
		$this->assertSame( 'Pro', $more['locked']['plan'] );
		$this->assertSame( 'https://example.com/upgrade/', $more['locked']['upgrade_url'] );
	}

	public function test_update_check_keeps_installed_pro_dropins_for_the_banner() {
		$this->place( 'protool', $this->header_dropin( 'protool', '1.0' ) );
		$this->mock_mdcom( array(
			'1' => array( 'slug' => 'protool', 'name' => 'Pro Tool', 'version' => '2.0', 'status' => 'upgrade', 'plan' => 'Pro' ),
			'2' => array( 'slug' => 'prolater', 'name' => 'Pro Later', 'version' => '1.0', 'status' => 'upgrade', 'plan' => 'Pro' ),
			'3' => array( 'slug' => 'starterone', 'name' => 'Starter One', 'version' => '1.0', 'status' => 'included' )
		) );

		md_check_dropin_updates();
		$upgrades = md_plan_upgrade_dropins();

		$this->assertTrue( $upgrades['protool']['installed'] );
		$this->assertFalse( $upgrades['prolater']['installed'] );
		$this->assertArrayNotHasKey( 'starterone', $upgrades );
		$this->assertSame( array( 'prolater' ), array_keys( md_available_dropins( 'upgrade' ) ), 'Installed Drop-ins stay out of the lists.' );
		$this->assertSame( array(), md_dropin_updates(), 'No package, so no update for the installed Pro Drop-in.' );
	}

	public function test_installing_an_included_dropin_claims_it_first() {
		$zip = $this->zip( 'included', array( 'included' => $this->header_dropin( 'included', '1.0' ) ) );
		$this->set_available( 'included', array( 'name' => 'Included', 'status' => 'included' ) );
		$this->mock_mdcom( array(), 'https://example.com/claimed/included.zip' );
		$this->mock_download( 'https://example.com/claimed/included.zip', $zip );

		$result = md_install_available_dropin( 'included' );

		$this->assertIsArray( $result, is_wp_error( $result ) ? $result->get_error_message() : '' );
		$this->assertSame( 'included', $result['slug'] );
		$this->assertSame( array(), md_available_dropins(), 'Installed Drop-ins leave Available.' );
	}

	public function test_failed_claim_installs_nothing() {
		$this->set_available( 'included', array( 'name' => 'Included', 'status' => 'included' ) );
		$this->mock_mdcom( array(), false );

		$result = md_install_available_dropin( 'included' );

		$this->assertWPError( $result, 'md_claim_failed' );
		$this->assertFalse( md_get_dropin_details( 'included' ) );
		$this->assertArrayHasKey( 'included', md_available_dropins() );
	}

	public function test_upgrade_dropins_cannot_be_installed() {
		$this->set_available( 'locked', array( 'name' => 'Locked', 'status' => 'upgrade', 'plan' => 'Pro' ) );
		add_filter( 'pre_http_request', array( $this, 'fail_on_request' ), 10, 3 );

		$this->assertWPError( md_install_available_dropin( 'locked' ), 'md_dropin_unavailable' );
		$this->assertArrayNotHasKey( 'locked', md_available_dropins() );
	}

	public function test_sites_without_a_license_never_contact_mdcom() {
		MD_Dropin_Test_Options::set( 'marketers_delight_license', array() );
		add_filter( 'pre_http_request', array( $this, 'fail_on_request' ), 10, 3 );

		$this->assertSame( 'skipped', md_check_dropin_updates() );
		$this->assertSame( array(), md_license_setting(), 'Nothing is saved, so no error status appears.' );
		$this->assertSame( array(), md_available_dropins() );
	}

	public function fail_on_request( $response, $args, $url ) {
		$this->fail( "Unexpected remote request to $url" );
	}

	public function test_operations_check_capabilities() {
		$this->place( 'alpha', $this->header_dropin( 'alpha' ) );
		wp_set_current_user( 0 );

		$this->assertWPError( md_install_dropin( $this->zip( 'x', array( 'x' => $this->header_dropin( 'x' ) ) ) ), 'md_cannot_install' );
		$this->assertWPError( md_activate_dropins( array( 'alpha' ) ), 'md_cannot_activate' );
		$this->assertWPError( md_deactivate_dropins( array( 'alpha' ) ), 'md_cannot_deactivate' );
		$this->assertWPError( md_delete_dropins( array( 'alpha' ) ), 'md_cannot_delete' );
		$this->assertWPError( md_upgrade_dropins( array( 'alpha' ) ), 'md_cannot_update' );
		$this->assertDirectoryExists( MD_INSTALLED_DROPINS . '/alpha' );
	}

	public function test_daily_check_is_hooked_and_cleared_on_theme_switch() {
		$this->assertSame( 10, has_action( 'md_check_updates', 'md_check_dropin_updates' ) );
		$this->assertSame( 10, has_action( 'switch_theme', 'md_clear_update_check' ) );

		$scheduled = wp_next_scheduled( 'md_check_updates' );

		wp_schedule_single_event( time() + DAY_IN_SECONDS, 'md_check_updates' );
		md_clear_update_check();

		$this->assertFalse( wp_next_scheduled( 'md_check_updates' ) );

		if ( $scheduled )
			wp_schedule_event( $scheduled, 'daily', 'md_check_updates' );
	}

	public function test_requested_dropins_reads_lists_and_arrays() {
		$_REQUEST['dropins'] = 'alpha,beta,,alpha,../x';
		$this->assertSame( array( 'alpha', 'beta', 'x' ), md_requested_dropins( 'dropins' ) );

		$_REQUEST['checked'] = array( 'Gamma', 'delta' );
		$this->assertSame( array( 'gamma', 'delta' ), md_requested_dropins( 'checked' ) );

		unset( $_REQUEST['dropins'], $_REQUEST['checked'] );
		$this->assertSame( array(), md_requested_dropins( 'dropins' ) );
	}

	private function set_available( $slug, $dropin ) {
		$license = md_license_setting();
		$license['available'][$slug] = array_merge( array( 'slug' => $slug ), $dropin );
		md_update_license( $license );
	}

	/**
	 * Answer downloads of $url with the local file $zip.
	 */

	private function mock_download( $url, $zip ) {
		add_filter( 'pre_http_request', array( new MD_Dropin_Test_Download( $url, $zip ), 'respond' ), 10, 3 );
	}

	/**
	 * Answer MD.com's check_license and get_version requests.
	 */

	private function mock_mdcom( $dropins, $claim = null ) {
		add_filter( 'pre_http_request', array( new MD_Dropin_Test_MDcom( $dropins, $claim ), 'respond' ), 10, 3 );
	}

	private function assertWPError( $value, $code ) {
		$this->assertInstanceOf( 'WP_Error', $value );
		$this->assertSame( $code, $value->get_error_code(), $value->get_error_message() );
	}

}

/**
 * A fake download for one URL: copies a local ZIP to the requested file.
 */

class MD_Dropin_Test_Download {

	private $url;
	private $zip;

	public function __construct( $url, $zip ) {
		$this->url = $url;
		$this->zip = $zip;
	}

	public function respond( $response, $args, $url ) {
		if ( $url !== $this->url )
			return $response;

		if ( ! empty( $args['filename'] ) )
			copy( $this->zip, $args['filename'] );

		return array(
			'headers' => array(),
			'body' => '',
			'response' => array( 'code' => 200, 'message' => 'OK' ),
			'cookies' => array(),
			'filename' => isset( $args['filename'] ) ? $args['filename'] : null
		);
	}

}

/**
 * A fake MD.com license API.
 */

class MD_Dropin_Test_MDcom {

	private $dropins;
	private $claim;

	public function __construct( $dropins, $claim = null ) {
		$this->dropins = $dropins;
		$this->claim = $claim;
	}

	public function respond( $response, $args, $url ) {
		if ( strpos( $url, MD_THEME_UPDATER_URL ) !== 0 )
			return $response;

		$action = isset( $args['body']['edd_action'] ) ? $args['body']['edd_action'] : '';

		if ( $action === 'md_claim_dropin' )
			$body = $this->claim ? array( 'success' => true, 'package' => $this->claim ) : array( 'success' => false, 'message' => 'Not in your plan.' );
		elseif ( $action === 'check_license' )
			$body = array( 'success' => true, 'license' => 'valid', 'expires' => 'lifetime', 'site_count' => 1, 'license_limit' => 10 );
		else
			$body = array(
				'new_version' => MD_VERSION,
				'stable_version' => MD_VERSION,
				'name' => 'Marketers Delight',
				'slug' => 'marketers-delight',
				'url' => 'https://example.com/',
				'last_updated' => '2026-11-03',
				'package' => '',
				'download_link' => '',
				'dropins' => $this->dropins
			);

		return array(
			'headers' => array(),
			'body' => wp_json_encode( $body ),
			'response' => array( 'code' => 200, 'message' => 'OK' ),
			'cookies' => array(),
			'filename' => null
		);
	}

}
