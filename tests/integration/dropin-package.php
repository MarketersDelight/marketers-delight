<?php
/**
 * Run with a real WordPress bootstrap:
 * wp --skip-plugins --skip-themes eval-file wp-content/themes/marketers-delight/tests/integration/dropin-package.php
 *
 * Uses temporary package fixtures only; does not save site settings.
 */
require_once dirname( __DIR__, 2 ) . '/functions/dropin-functions.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once dirname( __DIR__, 2 ) . '/admin/upgrade/dropin-upgrader.php';

$upgrader = new MD_Dropin_Upgrader( new WP_Upgrader_Skin() );
$method = new ReflectionMethod( $upgrader, 'discover_package' );
$method->setAccessible( true );
$discover = function( $directory ) use ( $upgrader, $method ) {
	return $method->invoke( $upgrader, $directory );
};

$root = sys_get_temp_dir() . '/md-package-test-' . uniqid();
mkdir( $root );
$checks = 0;
$check = function( $condition, $message ) use ( &$checks ) {
	if ( ! $condition )
		throw new RuntimeException( $message );
	$checks++;
};
$fixture = function( $name, $slug = 'stream', $header_slug = 'stream' ) use ( $root ) {
	$directory = "$root/$name";
	mkdir( "$directory/$slug", 0755, true );
	file_put_contents( "$directory/$slug/$slug.php", "<?php\n/*\nDrop-in Name: Display name independent of slug\nDrop-in Slug: $header_slug\nVersion: 2.0\n*/\n" );
	return $directory;
};

try {
	$valid = $fixture( 'valid' );
	$package = $discover( $valid );
	$check( $package === 'stream', 'Resolve the internal folder slug.' );
	$check( ! is_wp_error( md_validate_dropin_package( "$valid/stream", 'stream' ) ), 'Accept the expected automatic-update identity.' );
	$check( is_wp_error( md_validate_dropin_package( "$valid/stream", 'other' ) ), 'Reject an automatic update for another Drop-in.' );

	$legacy = $fixture( 'legacy', 'stream', '' );
	$check( ! is_wp_error( $discover( $legacy ) ), 'Allow legacy packages without a slug header.' );

	$conflict = $discover( $fixture( 'conflict', 'stream', 'other' ) );
	$check( is_wp_error( $conflict ) && $conflict->get_error_code() === 'invalid_dropin_identity', 'Reject conflicting metadata.' );

	$missing = $fixture( 'missing' );
	unlink( "$missing/stream/stream.php" );
	$check( is_wp_error( $discover( $missing ) ), 'Reject missing main files.' );

	mkdir( "$valid/other" );
	$check( is_wp_error( $discover( $valid ) ), 'Reject multiple top-level folders.' );

	$invalid = $fixture( 'invalid', 'Stream' );
	$check( is_wp_error( $discover( $invalid ) ), 'Reject invalid internal folder names.' );

	$link = "$root/link";
	mkdir( $link );
	symlink( "$legacy/stream", "$link/stream" );
	$check( is_wp_error( $discover( $link ) ), 'Reject linked package folders.' );

	echo "Passed $checks package validation checks.\n";
}
finally {
	$files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $root, FilesystemIterator::SKIP_DOTS ), RecursiveIteratorIterator::CHILD_FIRST );
	foreach ( $files as $file ) {
		if ( $file->isDir() && ! $file->isLink() )
			rmdir( $file->getPathname() );
		else
			unlink( $file->getPathname() );
	}
	rmdir( $root );
}
