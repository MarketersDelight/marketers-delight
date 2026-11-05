<?php
/**
 * Bootstrap for the Drop-ins manager tests. These run against a real
 * WordPress (the local site this theme sits in, or MD_TEST_WP_LOAD), because
 * installs and updates go through WP_Upgrader and the filesystem API.
 *
 * Nothing in that site changes:
 * - Drop-ins install into a temporary folder (MD_INSTALLED_DROPINS is
 *   defined before the theme loads), never the site's md-dropins.
 * - Every option write, from the moment WordPress starts loading, is kept
 *   in memory and read back from there (MD_Dropin_Test_Options). The theme
 *   switches off active Drop-ins it can't find while it loads, and that
 *   write must not reach the database.
 * - Uploads (compiled CSS/JS) go to the temporary folder, and WP-Cron is off.
 * - MD.com and package downloads are mocked.
 *
 * @since 6.2
 */

require_once __DIR__ . '/options.php';

$wp_load = getenv( 'MD_TEST_WP_LOAD' ) ? getenv( 'MD_TEST_WP_LOAD' ) : dirname( __DIR__, 5 ) . '/wp-load.php';

if ( ! is_file( $wp_load ) ) {
	define( 'MD_DROPIN_TESTS_SKIP', "No WordPress install at $wp_load (set MD_TEST_WP_LOAD)." );

	require_once __DIR__ . '/test-case.php';

	return;
}

$md_dropin_tests_root = sys_get_temp_dir() . '/md-dropin-tests-' . getmypid();

if ( ! is_dir( "$md_dropin_tests_root/md-dropins" ) )
	mkdir( "$md_dropin_tests_root/md-dropins", 0755, true );

define( 'MD_DROPIN_TESTS_ROOT', $md_dropin_tests_root );
define( 'MD_INSTALLED_DROPINS', "$md_dropin_tests_root/md-dropins" );
define( 'MD_INSTALLED_DROPINS_URL', 'https://example.com/md-dropins' );

if ( ! defined( 'FS_METHOD' ) )
	define( 'FS_METHOD', 'direct' );

if ( ! defined( 'DISABLE_WP_CRON' ) )
	define( 'DISABLE_WP_CRON', true );

$_SERVER['HTTP_HOST'] = isset( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : 'localhost';
$_SERVER['REQUEST_URI'] = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '/';
$_SERVER['SERVER_NAME'] = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : 'localhost';

// Hooks registered before WordPress loads (core turns these into WP_Hook)

$GLOBALS['wp_filter']['pre_update_option'][1][] = array( 'function' => array( 'MD_Dropin_Test_Options', 'write' ), 'accepted_args' => 3 );
$GLOBALS['wp_filter']['pre_option'][1][] = array( 'function' => array( 'MD_Dropin_Test_Options', 'read' ), 'accepted_args' => 3 );
$GLOBALS['wp_filter']['upload_dir'][1][] = array( 'function' => array( 'MD_Dropin_Test_Options', 'uploads' ), 'accepted_args' => 1 );

require_once $wp_load;
require_once ABSPATH . 'wp-admin/includes/admin.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
require_once dirname( __DIR__, 2 ) . '/admin/dropins/functions.php';

if ( ! function_exists( 'md_get_dropin_details' ) )
	define( 'MD_DROPIN_TESTS_SKIP', 'Marketers Delight is not the active theme of the test site.' );

require_once __DIR__ . '/test-case.php';

register_shutdown_function( array( 'MD_Dropin_TestCase', 'shutdown' ) );
