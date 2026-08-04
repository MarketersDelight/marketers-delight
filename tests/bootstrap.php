<?php
/**
 * Lightweight bootstrap for unit-testing the save/sanitize pipeline
 * (md_sanitize, md_validate, md_save) in isolation, without pulling in
 * the full WordPress test suite. Stubs only the handful of WP functions
 * those three classes actually call.
 *
 * @since 6.0
 */

$GLOBALS['__test_filters'] = array();
$GLOBALS['__test_settings'] = array();
$GLOBALS['__test_options'] = array();
$GLOBALS['__test_post_meta'] = array();
$GLOBALS['__test_term_meta'] = array();
$GLOBALS['__test_user_meta'] = array();
$GLOBALS['__test_dropins'] = array();
$GLOBALS['__test_child_theme'] = false;
$GLOBALS['__test_stylesheet_directory'] = '';

if ( ! defined( 'MD_INSTALLED_DROPINS' ) )
	define( 'MD_INSTALLED_DROPINS', __DIR__ . '/fixtures/dropins' );

/**
 * Test helpers, not WP stubs — call these from a test's setUp()/methods
 * to control what md_register()/md_setting()/get_option() return.
 */

function md_test_reset() {
	$GLOBALS['__test_filters'] = array();
	$GLOBALS['__test_settings'] = array();
	$GLOBALS['__test_options'] = array();
	$GLOBALS['__test_post_meta'] = array();
	$GLOBALS['__test_term_meta'] = array();
	$GLOBALS['__test_user_meta'] = array();
	$GLOBALS['__test_dropins'] = array();
	$GLOBALS['__test_child_theme'] = false;
	$GLOBALS['__test_stylesheet_directory'] = __DIR__ . '/fixtures/child';
	$_POST = array();
	$_GET = array();
}

function md_test_set_filter( $tag, $value ) {
	$GLOBALS['__test_filters'][$tag] = $value;
}

function md_test_set_settings( $settings ) {
	$GLOBALS['__test_settings'] = $settings;
}

function md_test_set_option( $key, $value ) {
	$GLOBALS['__test_options'][$key] = $value;
}

function md_test_set_post_meta( $meta ) {
	$GLOBALS['__test_post_meta'] = $meta;
}

function md_test_set_term_meta( $meta ) {
	$GLOBALS['__test_term_meta'] = $meta;
}

function md_test_set_user_meta( $meta ) {
	$GLOBALS['__test_user_meta'] = $meta;
}

function md_test_set_dropins( $dropins ) {
	$GLOBALS['__test_dropins'] = $dropins;
}

function md_test_set_child_theme( $enabled ) {
	$GLOBALS['__test_child_theme'] = $enabled;
}

// WP function stubs

function apply_filters( $tag, $value ) {
	return array_key_exists( $tag, $GLOBALS['__test_filters'] ) ? $GLOBALS['__test_filters'][$tag] : $value;
}

function md_register( $group = null ) {
	$data = apply_filters( 'md_register', array() );

	if ( isset( $group ) )
		return ! empty( $data[$group] ) ? $data[$group] : array();

	return $data;
}

function md_setting( $keys = null, $default = null ) {
	$value = $GLOBALS['__test_settings'];

	if ( ! isset( $keys ) )
		return $value;

	foreach ( (array) $keys as $key ) {
		if ( is_array( $value ) && array_key_exists( $key, $value ) )
			$value = $value[$key];
		else
			return $default;
	}

	return $value;
}

function get_option( $key, $default = false ) {
	return array_key_exists( $key, $GLOBALS['__test_options'] ) ? $GLOBALS['__test_options'][$key] : $default;
}

function md_get_dropins( $status = null ) {
	return $status === 'active' ? $GLOBALS['__test_dropins'] : array();
}

function is_child_theme() {
	return $GLOBALS['__test_child_theme'];
}

function get_stylesheet_directory() {
	return $GLOBALS['__test_stylesheet_directory'];
}

function locate_template( $template ) {
	$path = dirname( __DIR__ ) . '/' . ltrim( $template, '/' );

	return file_exists( $path ) ? $path : '';
}

/**
 * Walk a $GLOBALS-backed meta array by $keys, same lookup shape as
 * md_setting() above — shared by the md_post_meta/md_term_meta/
 * md_user_meta stubs below.
 */

function md_test_walk_meta( $store, $keys, $default ) {
	$value = $store;

	if ( ! isset( $keys ) )
		return $value;

	foreach ( (array) $keys as $key ) {
		if ( is_array( $value ) && array_key_exists( $key, $value ) )
			$value = $value[$key];
		else
			return $default;
	}

	return $value;
}

function md_post_meta( $keys = null, $id = null, $default = null ) {
	return md_test_walk_meta( $GLOBALS['__test_post_meta'], $keys, $default );
}

function md_term_meta( $keys = null, $id = null, $default = null ) {
	return md_test_walk_meta( $GLOBALS['__test_term_meta'], $keys, $default );
}

function md_user_meta( $keys = null, $id = null, $default = null ) {
	return md_test_walk_meta( $GLOBALS['__test_user_meta'], $keys, $default );
}

if ( ! function_exists( '__' ) ) {
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}

function md_clean_id( $id ) {
	return ! empty( $id ) ? preg_replace( '/^md_/', '', $id ) : '';
}

function md_color_palette() {
	return apply_filters( 'md_color_palette', array() );
}

function md_editor_colors() {
	return ( new md_design_colors )->editor_colors();
}

function sanitize_text_field( $str ) {
	return is_string( $str ) ? trim( strip_tags( $str ) ) : $str;
}

function sanitize_key( $key ) {
	$key = strtolower( (string) $key );

	return preg_replace( '/[^a-z0-9_\-]/', '', $key );
}

function sanitize_title( $title ) {
	$title = strtolower( trim( (string) $title ) );
	$title = preg_replace( '/[^a-z0-9_\-\s]/', '', $title );

	return preg_replace( '/[\s_]+/', '-', $title );
}

function sanitize_html_class( $class ) {
	return preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $class );
}

function sanitize_hex_color( $color ) {
	if ( $color === '' )
		return '';

	return preg_match( '/^#([A-Fa-f0-9]{3}){1,2}$/', $color ) ? $color : null;
}

function wp_kses_post( $str ) {
	return $str;
}

function wp_kses_bad_protocol( $str, $allowed ) {
	return $str;
}

function esc_attr( $str ) {
	return htmlspecialchars( (string) $str, ENT_QUOTES );
}

function wp_verify_nonce( $nonce, $action ) {
	return true;
}

require_once dirname( __DIR__ ) . '/api/sanitize.php';
require_once dirname( __DIR__ ) . '/api/validate.php';
require_once dirname( __DIR__ ) . '/api/save.php';
require_once dirname( __DIR__ ) . '/api/data.php';
require_once dirname( __DIR__ ) . '/api/fields.php';
require_once dirname( __DIR__ ) . '/api/colors.php';
require_once dirname( __DIR__ ) . '/api/design.php';
require_once dirname( __DIR__ ) . '/api/theme-json.php';
require_once dirname( __DIR__ ) . '/api/css.php';

/**
 * Base test case: reflection helper for exercising the private merge/
 * validate methods directly, and a fresh md_test_reset() before every test.
 *
 * @since 6.0
 */

class MD_TestCase extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		md_test_reset();
	}

	protected function call( $object, $method, array $args = array() ) {
		$reflection = new ReflectionMethod( $object, $method );

		return $reflection->invokeArgs( $object, $args );
	}

}
