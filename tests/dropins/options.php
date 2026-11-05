<?php
/**
 * Keeps the test process's option writes in memory, so the site the
 * Drop-ins tests load is never changed. update_option() calls land here
 * (pre_update_option returns the old value, so nothing is written) and
 * get_option() reads them back (pre_option).
 *
 * @since 6.2
 */

class MD_Dropin_Test_Options {

	public static $store = array();

	public static function write( $value, $option, $old_value ) {
		self::$store[$option] = $value;

		if ( function_exists( 'md_option_cache' ) )
			md_option_cache( $option, true );

		return $old_value;
	}

	public static function read( $pre, $option, $default ) {
		return array_key_exists( $option, self::$store ) ? self::$store[$option] : $pre;
	}

	public static function set( $option, $value ) {
		self::$store[$option] = $value;

		if ( function_exists( 'md_option_cache' ) )
			md_option_cache( $option, true );
	}

	public static function uploads( $uploads ) {
		$root = defined( 'MD_DROPIN_TESTS_ROOT' ) ? MD_DROPIN_TESTS_ROOT : sys_get_temp_dir();

		$uploads['path'] = "$root/uploads";
		$uploads['basedir'] = "$root/uploads";
		$uploads['url'] = 'https://example.com/uploads';
		$uploads['baseurl'] = 'https://example.com/uploads';
		$uploads['subdir'] = '';

		return $uploads;
	}

}
