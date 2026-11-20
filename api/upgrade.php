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

	private $border = false;

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
	 * 6.2.2: move widths and the box style selects, and remove the storage counter the
	 * 6.0 migrations left. The site-wide loop stays: search, tag and date archives use it.
	 *
	 * @since 6.2.2
	 */

	public function upgrade_622() {
		$settings = get_option( 'marketers_delight', array() );
		$upgraded = $this->move_widths( $settings );
		$upgraded = $this->move_box_style( $upgraded );

		$result = true;

		if ( $upgraded !== $settings && ! update_option( 'marketers_delight', $upgraded ) && get_option( 'marketers_delight' ) !== $upgraded )
			$result = new WP_Error( 'md_upgrade', __( 'The MD 6.2.2 data upgrade couldn\'t save its changes.', 'md' ) );
		else
			delete_option( 'marketers_delight_storage_version' );

		return $result;
	}

	/**
	 * Move colors.width to settings.width, keeping any width already set there,
	 * then remove colors.width, and colors too if nothing else is in it.
	 *
	 * @since 6.2.2
	 */

	public function move_widths( $settings ) {
		if ( ! is_array( $settings ) || ! isset( $settings['colors']['width'] ) )
			return $settings;

		foreach ( array( 'content', 'sidebar', 'site' ) as $key )
			if ( ! empty( $settings['colors']['width'][$key] ) && empty( $settings['settings']['width'][$key] ) )
				$settings['settings']['width'][$key] = $settings['colors']['width'][$key];

		unset( $settings['colors']['width'] );

		if ( empty( $settings['colors'] ) )
			unset( $settings['colors'] );

		return $settings;
	}

	/**
	 * Turn the Box / Border / No style selects into Remove Box Style checkboxes.
	 * No style and Border remove the box; Border keeps its lines as site Dividers
	 * when the site has no box, since dividers are site-wide now.
	 *
	 * @since 6.2.2
	 */

	public function move_box_style( $settings ) {
		if ( ! is_array( $settings ) )
			return $settings;

		$design = $settings['colors']['design'] ?? '';

		if ( $design === 'plain' || $design === 'border' )
			$settings['effects']['box_style'] = $settings['effects']['box_style'] ?? array( 'remove' => true );

		if ( $design === 'border' )
			$settings['effects']['dividers'] = $settings['effects']['dividers'] ?? array( 'divide' => true );

		unset( $settings['colors']['design'] );

		if ( ( $settings['colors'] ?? null ) === array() )
			unset( $settings['colors'] );

		$removed = ! empty( $settings['effects']['box_style']['remove'] );
		$this->border = false;

		foreach ( $settings as $key => $group ) {
			$moved = is_array( $group ) ? $this->move_layout_box_style( $group, $removed, true ) : $group;

			if ( $moved === array() && $group !== array() )
				unset( $settings[$key] );
			else
				$settings[$key] = $moved;
		}

		if ( $this->border && $removed )
			$settings['effects']['dividers'] = $settings['effects']['dividers'] ?? array( 'divide' => true );

		return $settings;
	}

	/**
	 * A post type group, and with $children its taxonomies. No style and Border remove
	 * the box; Box keeps it (0) where the site or post type removes it.
	 *
	 * @since 6.2.2
	 */

	private function move_layout_box_style( $group, $removed, $children = false ) {
		$taxonomies = $children ? $group : array();

		if ( is_array( $group['layout'] ?? null ) && array_key_exists( 'content_style', $group['layout'] ) ) {
			$style = $group['layout']['content_style'];

			if ( $style === 'plain' || $style === 'border' )
				$group['layout']['content']['box_style'] = $group['layout']['content']['box_style'] ?? true;
			elseif ( $style === 'box' && $removed )
				$group['layout']['content']['box_style'] = $group['layout']['content']['box_style'] ?? 0;

			if ( $style === 'border' )
				$this->border = true;

			unset( $group['layout']['content_style'] );

			if ( empty( $group['layout'] ) )
				unset( $group['layout'] );
		}

		if ( isset( $group['layout']['content']['box_style'] ) )
			$removed = (bool) $group['layout']['content']['box_style'];

		foreach ( $taxonomies as $key => $child ) {
			if ( ! is_array( $child ) || ! isset( $child['layout'] ) )
				continue;

			$group[$key] = $this->move_layout_box_style( $child, $removed );

			if ( $group[$key] === array() )
				unset( $group[$key] );
		}

		return $group;
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
