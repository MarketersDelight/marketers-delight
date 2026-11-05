<?php
/**
 * Base case for Drop-ins manager tests: a clean temporary Drop-ins folder,
 * fresh MD options (in memory) and an administrator for every test, and
 * helpers to build Drop-in folders and ZIPs.
 *
 * @since 6.2
 */

class MD_Dropin_TestCase extends \PHPUnit\Framework\TestCase {

	protected static $baseline = null;
	protected static $maintenance = null;

	/**
	 * Remove the temporary Drop-ins and uploads, and a maintenance file the
	 * tests left. Options were never written (see options.php).
	 */

	public static function shutdown() {
		if ( self::$maintenance === false && file_exists( ABSPATH . '.maintenance' ) )
			unlink( ABSPATH . '.maintenance' );

		self::remove( MD_DROPIN_TESTS_ROOT );
	}

	protected function setUp(): void {
		if ( defined( 'MD_DROPIN_TESTS_SKIP' ) )
			$this->markTestSkipped( MD_DROPIN_TESTS_SKIP );

		if ( self::$baseline === null ) {
			self::$baseline = get_option( 'marketers_delight', array() );
			self::$maintenance = file_exists( ABSPATH . '.maintenance' );
		}

		self::remove( MD_INSTALLED_DROPINS );
		mkdir( MD_INSTALLED_DROPINS, 0755, true );

		MD_Dropin_Test_Options::set( 'marketers_delight', self::$baseline );
		MD_Dropin_Test_Options::set( 'marketers_delight_dropins', array( 'dropins' => array() ) );
		MD_Dropin_Test_Options::set( 'marketers_delight_license', array( 'key' => 'test-key', 'status' => 'valid' ) );
		MD_Dropin_Test_Options::set( 'md_compile_after_upgrade', false );

		md_flush_dropin_details();
		$this->as_admin();
	}

	protected function tearDown(): void {
		if ( defined( 'MD_DROPIN_TESTS_SKIP' ) )
			return;

		remove_all_filters( 'pre_http_request' );
		md_flush_dropin_details();
	}

	protected function as_admin() {
		$admins = get_users( array( 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ) );

		wp_set_current_user( $admins ? (int) $admins[0] : 1 );
	}

	/**
	 * Header text for a test Drop-in's main file.
	 */

	protected function header( $slug, $headers ) {
		$lines = array();

		foreach ( $headers as $key => $value )
			$lines[] = " * $key: $value";

		return "<?php\n/**\n" . implode( "\n", $lines ) . "\n */\n";
	}

	/**
	 * Files for a Drop-in with a full 6.2 header.
	 */

	protected function header_dropin( $slug, $version = '1.0', $extra = array() ) {
		$headers = array_merge( array(
			'Drop-in Name' => 'Test ' . $slug,
			'Description' => 'A test Drop-in.',
			'Author' => 'Kolakube',
			'Drop-in Slug' => $slug,
			'Version' => $version,
			'Requires PHP' => '7.4'
		), $extra );

		return array( "$slug.php" => $this->header( $slug, $headers ) );
	}

	/**
	 * Files for a Drop-in described only by config.json.
	 */

	protected function config_dropin( $slug, $version = '1.0', $config = array() ) {
		$config = array_merge( array(
			'name' => 'Legacy ' . $slug,
			'author' => 'Kolakube',
			'version' => $version,
			'description' => 'Old config.json Drop-in.',
			'icon' => 'dashicons-archive',
			'colors' => '#b45309'
		), $config );

		return array(
			"$slug.php" => "<?php\n/**\n * Dropin Name: Old key\n * Version: 0.1\n */\n",
			'config.json' => wp_json_encode( $config )
		);
	}

	/**
	 * Write a Drop-in folder into $directory.
	 */

	protected function write( $directory, $slug, $files ) {
		$folder = "$directory/$slug";

		if ( ! is_dir( $folder ) )
			mkdir( $folder, 0755, true );

		foreach ( $files as $name => $contents )
			file_put_contents( "$folder/$name", $contents );

		return $folder;
	}

	/**
	 * Install files straight into the Drop-ins folder.
	 */

	protected function place( $slug, $files ) {
		$folder = $this->write( MD_INSTALLED_DROPINS, $slug, $files );
		md_flush_dropin_details();

		return $folder;
	}

	/**
	 * A ZIP holding folders: array( slug => files ).
	 */

	protected function zip( $name, $folders ) {
		$file = MD_DROPIN_TESTS_ROOT . "/$name.zip";
		$zip = new ZipArchive();
		$zip->open( $file, ZipArchive::CREATE | ZipArchive::OVERWRITE );

		foreach ( $folders as $slug => $files )
			foreach ( $files as $path => $contents )
				$zip->addFromString( "$slug/$path", $contents );

		$zip->close();

		return $file;
	}

	/**
	 * Save an update entry the way the update check does.
	 */

	protected function offer_update( $slug, $version, $package ) {
		$license = md_license_setting();
		$license['updates']['dropins']["$slug/$slug.php"] = array(
			'name' => $slug,
			'slug' => $slug,
			'version' => $version,
			'new_version' => $version,
			'package' => $package
		);

		md_update_license( $license );
	}

	protected function active() {
		return md_get_dropins( 'active' );
	}

	protected static function remove( $path ) {
		if ( ! file_exists( $path ) && ! is_link( $path ) )
			return;

		if ( is_dir( $path ) && ! is_link( $path ) ) {
			foreach ( array_diff( scandir( $path ), array( '.', '..' ) ) as $item )
				self::remove( "$path/$item" );

			rmdir( $path );
		}
		else
			unlink( $path );
	}

}
