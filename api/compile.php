<?php
/**
 * Store and locate site-specific compiled MD assets.
 *
 * @since 6.0
 */

class md_compiled_assets {

	/**
	 * Return the site-specific compiled assets directory.
	 *
	 * @since 6.0
	 */

	public function directory() {
		$uploads = $this->uploads();

		return trailingslashit( $uploads['basedir'] ) . 'marketers-delight/';
	}

	/**
	 * Return the site-specific compiled assets URL.
	 *
	 * @since 6.0
	 */

	public function url( $file = '' ) {
		$uploads = $this->uploads();
		$file = $this->validate( $file );

		if ( is_wp_error( $file ) )
			return '';

		return trailingslashit( $uploads['baseurl'] ) . 'marketers-delight/' . $file;
	}

	/**
	 * Return the full filesystem path to a compiled asset.
	 *
	 * @since 6.0
	 */

	public function path( $file ) {
		$file = $this->validate( $file );

		if ( is_wp_error( $file ) )
			return '';

		return $this->directory() . $file;
	}

	/**
	 * Whether a compiled asset exists and is readable.
	 *
	 * @since 6.0
	 */

	public function exists( $file ) {
		$path = $this->path( $file );

		return $path && is_readable( $path );
	}

	/**
	 * Read a compiled asset, or return false when unavailable.
	 *
	 * @since 6.0
	 */

	public function read( $file ) {
		$path = $this->path( $file );

		if ( ! $path || ! is_readable( $path ) )
			return false;

		return file_get_contents( $path );
	}

	/**
	 * Return a cache version for a compiled asset without emitting
	 * filesystem warnings when the file has not been generated yet.
	 *
	 * @since 6.0
	 */

	public function version( $file ) {
		$path = $this->path( $file );

		return $path && is_file( $path ) ? filemtime( $path ) : MD_VERSION;
	}

	/**
	 * Safely publish a compiled asset through WP_Filesystem.
	 *
	 * @since 6.0
	 */

	public function write( $file, $contents ) {
		$file = $this->validate( $file );

		if ( is_wp_error( $file ) )
			return $file;

		$uploads = $this->uploads();

		if ( ! empty( $uploads['error'] ) )
			return new WP_Error( 'md_compiled_uploads', $uploads['error'] );

		$filesystem = $this->filesystem();

		if ( is_wp_error( $filesystem ) )
			return $filesystem;

		$directory = $this->directory();

		if ( ! $filesystem->is_dir( $directory ) && ! $filesystem->mkdir( $directory, FS_CHMOD_DIR ) )
			return new WP_Error( 'md_compiled_directory', sprintf( __( 'The compiled assets directory could not be created: %s', 'md' ), $directory ) );

		$path = $directory . $file;
		$suffix = function_exists( 'wp_generate_uuid4' ) ? wp_generate_uuid4() : uniqid( 'md-', true );
		$temporary = "{$path}.{$suffix}.tmp";
		$backup = "{$path}.{$suffix}.bak";

		if ( ! $filesystem->put_contents( $temporary, $contents, FS_CHMOD_FILE ) )
			return new WP_Error( 'md_compiled_write', sprintf( __( 'The compiled asset could not be written: %s', 'md' ), $file )
			);

		if ( $filesystem->exists( $path ) && ! $filesystem->copy( $path, $backup, true, FS_CHMOD_FILE ) ) {
			$filesystem->delete( $temporary );

			return new WP_Error( 'md_compiled_backup', sprintf( __( 'The existing compiled asset could not be preserved: %s', 'md' ), $file ) );
		}

		if ( ! $filesystem->move( $temporary, $path, true ) ) {
			$filesystem->delete( $temporary );

			if ( $filesystem->exists( $backup ) && ! $filesystem->move( $backup, $path, true ) )
				return new WP_Error( 'md_compiled_restore', sprintf( __( 'The previous compiled asset could not be restored: %s', 'md' ), $file ) );

			return new WP_Error( 'md_compiled_publish', sprintf( __( 'The compiled asset could not be published: %s', 'md' ), $file ) );
		}

		if ( $filesystem->exists( $backup ) )
			$filesystem->delete( $backup );

		return true;
	}

	/**
	 * Delete an obsolete compiled asset.
	 *
	 * @since 6.0
	 */

	public function delete( $file ) {
		$file = $this->validate( $file );

		if ( is_wp_error( $file ) )
			return $file;

		$path = $this->directory() . $file;

		if ( ! file_exists( $path ) )
			return true;

		$filesystem = $this->filesystem();

		if ( is_wp_error( $filesystem ) )
			return $filesystem;

		if ( ! $filesystem->delete( $path ) )
			return new WP_Error( 'md_compiled_delete', sprintf( __( 'The compiled asset could not be deleted: %s', 'md' ), $file ) );

		return true;
	}

	/**
	 * Return the WordPress uploads base path and URL without date folders.
	 *
	 * @since 6.0
	 */

	private function uploads() {
		$uploads = function_exists( 'wp_get_upload_dir' ) ? wp_get_upload_dir() : wp_upload_dir( null, false );

		return apply_filters( 'md_compiled_assets_uploads', $uploads );
	}

	/**
	 * Initialize the filesystem against the writable uploads context.
	 * Automatic compiles never request interactive credentials.
	 *
	 * @since 6.0
	 */

	protected function filesystem() {
		require_once ABSPATH . 'wp-admin/includes/file.php';

		$context = dirname( untrailingslashit( $this->directory() ) );

		if ( ! WP_Filesystem( false, $context, true ) )
			return new WP_Error( 'md_compiled_filesystem', __( 'WordPress could not initialize the filesystem for compiled assets.', 'md' ) );

		global $wp_filesystem;

		return $wp_filesystem;
	}

	/**
	 * Accept flat CSS and JavaScript filenames only.
	 *
	 * @since 6.0
	 */

	private function validate( $file ) {
		$file = ltrim( (string) $file, '/' );
		$extension = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );

		if ( ! $file || basename( $file ) !== $file || validate_file( $file ) !== 0 || ! in_array( $extension, array( 'css', 'js' ), true ) )
			return new WP_Error( 'md_compiled_file', __( 'Invalid compiled asset filename.', 'md' ) );

		return $file;
	}
}
