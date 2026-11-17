<?php
/**
 * Brings saved data up to the running MD version on the first request after
 * any update (dashboard, zip, FTP, rsync), then schedules a fresh compile.
 *
 * The version lives in marketers_delight['version']. Steps must be safe to
 * run twice.
 *
 * @since 6.2.2
 */

class md_upgrade {

	/**
	 * Check before md_enqueue::compile_after_upgrade() reads the compile flag.
	 *
	 * @since 6.2.2
	 */

	public function init() {
		add_action( 'wp_loaded', array( $this, 'maybe_run' ), 90 );
	}

	/**
	 * Data steps by the MD version that ships them: version => method.
	 *
	 * @since 6.2.2
	 */

	public function steps() {
		return array(
			'6.2.2' => 'upgrade_622'
		);
	}

	/**
	 * 6.2.2: move widths out of colors, remove the MD 5 site-wide loop (no field
	 * edits it) and the storage counter the 6.0 migrations left, which nothing reads.
	 *
	 * @since 6.2.2
	 */

	public function upgrade_622() {
		$settings = get_option( 'marketers_delight', array() );
		$upgraded = $this->move_widths( $settings );

		if ( is_array( $upgraded ) )
			unset( $upgraded['loop'] );

		if ( $upgraded !== $settings )
			update_option( 'marketers_delight', $upgraded );

		delete_option( 'marketers_delight_storage_version' );

		return true;
	}

	/**
	 * Move colors.width to layout.width, keeping any layout width already set,
	 * then remove colors.width, and colors too if nothing else is in it.
	 *
	 * @since 6.2.2
	 */

	public function move_widths( $settings ) {
		if ( ! is_array( $settings ) || ! isset( $settings['colors']['width'] ) )
			return $settings;

		foreach ( array( 'content', 'sidebar', 'site' ) as $key )
			if ( ! empty( $settings['colors']['width'][$key] ) && empty( $settings['layout']['width'][$key] ) )
				$settings['layout']['width'][$key] = $settings['colors']['width'][$key];

		unset( $settings['colors']['width'] );

		if ( empty( $settings['colors'] ) )
			unset( $settings['colors'] );

		return $settings;
	}

	/**
	 * The MD version the saved data was last brought up to, or 0 when never recorded.
	 *
	 * @since 6.2.2
	 */

	public function saved_version() {
		$settings = get_option( 'marketers_delight', array() );

		return is_array( $settings ) && ! empty( $settings['version'] ) ? (string) $settings['version'] : '0';
	}

	/**
	 * Run only when the saved version differs from the running code.
	 *
	 * @since 6.2.2
	 */

	public function maybe_run() {
		if ( $this->saved_version() !== MD_VERSION )
			return $this->run();
	}

	/**
	 * Run steps newer than the saved version, flag a compile, then save the version.
	 * A failed step stops here and returns its WP_Error, so it retries next request.
	 *
	 * @since 6.2.2
	 */

	public function run() {
		$saved = $this->saved_version();

		foreach ( $this->steps() as $version => $method ) {
			if ( version_compare( $version, $saved, '<=' ) || version_compare( $version, MD_VERSION, '>' ) )
				continue;

			$result = call_user_func( array( $this, $method ) );

			if ( $result !== true )
				return is_wp_error( $result ) ? $result : new WP_Error( 'md_upgrade', sprintf( __( 'The MD %s data upgrade did not finish.', 'md' ), $version ) );
		}

		update_option( 'md_compile_after_upgrade', true, false );

		$settings = get_option( 'marketers_delight', array() );
		$settings = is_array( $settings ) ? $settings : array();
		$settings['version'] = MD_VERSION;

		update_option( 'marketers_delight', $settings );

		return true;
	}
}
