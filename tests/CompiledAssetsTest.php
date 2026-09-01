<?php
/**
 * Tests compiled asset paths and atomic publication behavior.
 *
 * @since 6.0
 */

class CompiledAssetsTest extends MD_TestCase {

	private $directory;
	private $filesystem;
	private $compiled;

	protected function setUp(): void {
		parent::setUp();

		$this->directory = sys_get_temp_dir() . '/md-compiled-' . uniqid();
		$GLOBALS['__test_upload_dir'] = array(
			'basedir' => $this->directory,
			'baseurl' => 'https://example.com/uploads',
			'error' => false
		);

		$this->filesystem = new MD_Test_Filesystem;
		$this->compiled = new class( $this->filesystem ) extends md_compiled_assets {
			private $test_filesystem;

			public function __construct( $filesystem ) {
				$this->test_filesystem = $filesystem;
			}

			protected function filesystem() {
				return $this->test_filesystem;
			}
		};
	}

	protected function tearDown(): void {
		$directory = $this->directory . '/marketers-delight';

		if ( is_dir( $directory ) ) {
			foreach ( scandir( $directory ) as $file )
				if ( $file !== '.' && $file !== '..' )
					unlink( $directory . '/' . $file );

			rmdir( $directory );
		}

		if ( is_dir( $this->directory ) )
			rmdir( $this->directory );

		parent::tearDown();
	}

	public function test_uses_one_flat_site_specific_directory() {
		$this->assertSame( $this->directory . '/marketers-delight/', $this->compiled->directory() );
		$this->assertSame( $this->directory . '/marketers-delight/style.css', $this->compiled->path( 'style.css' ) );
		$this->assertSame( 'https://example.com/uploads/marketers-delight/style.css', $this->compiled->url( 'style.css' ) );
	}

	public function test_rejects_nested_and_non_asset_filenames() {
		$this->assertSame( '', $this->compiled->path( '../style.css' ) );
		$this->assertSame( '', $this->compiled->path( 'compile/style.css' ) );
		$this->assertSame( '', $this->compiled->url( 'settings.php' ) );
	}

	public function test_write_creates_and_publishes_a_new_asset() {
		$result = $this->compiled->write( 'style.css', 'body { color: red; }' );

		$this->assertTrue( $result );
		$this->assertTrue( $this->compiled->exists( 'style.css' ) );
		$this->assertSame( 'body { color: red; }', $this->compiled->read( 'style.css' ) );
		$this->assertIsInt( $this->compiled->version( 'style.css' ) );
	}

	public function test_failed_temporary_write_preserves_the_last_asset() {
		$this->assertTrue( $this->compiled->write( 'scripts.js', 'window.old = true;' ) );

		$this->filesystem->fail_writes = true;
		$result = $this->compiled->write( 'scripts.js', 'window.new = true;' );

		$this->assertInstanceOf( WP_Error::class, $result );
		$this->assertSame( 'md_compiled_write', $result->get_error_code() );
		$this->assertSame( 'window.old = true;', $this->compiled->read( 'scripts.js' ) );
	}

	public function test_failed_publish_restores_the_last_asset() {
		$this->assertTrue( $this->compiled->write( 'style.css', 'body { color: blue; }' ) );

		$this->filesystem->fail_publish = true;
		$result = $this->compiled->write( 'style.css', 'body { color: red; }' );

		$this->assertInstanceOf( WP_Error::class, $result );
		$this->assertSame( 'md_compiled_publish', $result->get_error_code() );
		$this->assertSame( 'body { color: blue; }', $this->compiled->read( 'style.css' ) );
	}
}

class MD_Test_Filesystem {

	public $fail_writes = false;
	public $fail_publish = false;

	public function is_dir( $path ) {
		return is_dir( $path );
	}

	public function mkdir( $path, $chmod = false ) {
		return mkdir( $path, $chmod ?: 0755, true );
	}

	public function put_contents( $path, $contents, $mode = false ) {
		if ( $this->fail_writes )
			return false;

		return file_put_contents( $path, $contents ) !== false;
	}

	public function exists( $path ) {
		return file_exists( $path );
	}

	public function copy( $source, $destination, $overwrite = false, $mode = false ) {
		return copy( $source, $destination );
	}

	public function move( $source, $destination, $overwrite = false ) {
		if ( $overwrite && file_exists( $destination ) )
			unlink( $destination );

		if ( $this->fail_publish && substr( $source, -4 ) === '.tmp' )
			return false;

		return rename( $source, $destination );
	}

	public function delete( $path ) {
		return ! file_exists( $path ) || unlink( $path );
	}
}
