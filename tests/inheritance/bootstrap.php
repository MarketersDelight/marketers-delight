<?php
/**
 * Isolated bootstrap for testing the post-type -> taxonomy -> term option
 * inheritance chain (functions/theme-functions.php, functions/loop-functions.php,
 * md_api::loop_query_vars()) in real code, not a re-implementation of it.
 *
 * Kept separate from tests/bootstrap.php because that bootstrap already
 * declares its own stub md_setting()/md_post_meta()/md_term_meta()/md_user_meta()
 * and requires api/*.php files that would redeclare those names again if this
 * file's real functions/theme-functions.php were pulled into the same process.
 *
 * @since 6.0
 */

$GLOBALS['__test_options'] = array();
$GLOBALS['__test_post_meta'] = array();
$GLOBALS['__test_term_meta'] = array();
$GLOBALS['__test_user_meta'] = array();
$GLOBALS['__test_filters'] = array();
$GLOBALS['__test_term_children'] = array();
$GLOBALS['__test_query'] = array(
	'is_admin' => false,
	'is_singular' => false,
	'is_404' => false,
	'is_home' => false,
	'is_post_type_archive' => false,
	'is_author' => false,
	'is_category' => false,
	'is_tax' => false,
	'queried_object' => null,
	'queried_object_id' => 0,
	'query_var' => array(),
	'post_type' => '',
);

/**
 * Test helpers, not WP stubs.
 */

function md_test_reset() {
	$GLOBALS['__test_options'] = array();
	$GLOBALS['__test_post_meta'] = array();
	$GLOBALS['__test_term_meta'] = array();
	$GLOBALS['__test_user_meta'] = array();
	$GLOBALS['__test_filters'] = array();
	$GLOBALS['__test_term_children'] = array();
	$GLOBALS['__test_query'] = array(
		'is_admin' => false,
		'is_singular' => false,
		'is_404' => false,
		'is_home' => false,
		'is_post_type_archive' => false,
		'is_author' => false,
		'is_category' => false,
		'is_tax' => false,
		'queried_object' => null,
		'queried_object_id' => 0,
		'query_var' => array(),
		'post_type' => '',
	);
}

// Sets the marketers_delight option array wholesale (mirrors get_option()).

function md_test_set_option( $key, $value ) {
	$GLOBALS['__test_options'][$key] = $value;
}

function md_test_set_term_meta( $term_id, $meta ) {
	$GLOBALS['__test_term_meta'][$term_id] = $meta;
}

// Defaults to the fixed post ID get_the_ID() returns below, matching how a
// test sets up "the current post" without needing to pass an ID explicitly.

function md_test_set_post_meta( $meta, $post_id = 1 ) {
	$GLOBALS['__test_post_meta'][$post_id] = $meta;
}

function md_test_set_filter( $tag, $value ) {
	$GLOBALS['__test_filters'][$tag] = $value;
}

// $children = array of child term IDs (non-empty means "has children").

function md_test_set_term_children( $term_id, $taxonomy, $children ) {
	$GLOBALS['__test_term_children']["{$taxonomy}:{$term_id}"] = $children;
}

// Controls is_category()/is_tax()/is_singular()/etc and get_queried_object().

function md_test_set_query( array $state ) {
	$GLOBALS['__test_query'] = array_merge( $GLOBALS['__test_query'], $state );
}

// WP function stubs

function apply_filters( $tag, $value ) {
	return array_key_exists( $tag, $GLOBALS['__test_filters'] ) ? $GLOBALS['__test_filters'][$tag] : $value;
}

function get_option( $key, $default = false ) {
	return array_key_exists( $key, $GLOBALS['__test_options'] ) ? $GLOBALS['__test_options'][$key] : $default;
}

function get_post_meta( $id, $key, $single = false ) {
	return isset( $GLOBALS['__test_post_meta'][$id] ) ? $GLOBALS['__test_post_meta'][$id] : array();
}

function get_term_meta( $id, $key, $single = false ) {
	return isset( $GLOBALS['__test_term_meta'][$id] ) ? $GLOBALS['__test_term_meta'][$id] : array();
}

function get_user_meta( $id, $key, $single = false ) {
	return isset( $GLOBALS['__test_user_meta'][$id] ) ? $GLOBALS['__test_user_meta'][$id] : array();
}

function is_admin() {
	return $GLOBALS['__test_query']['is_admin'];
}

function is_singular() {
	return $GLOBALS['__test_query']['is_singular'];
}

function is_404() {
	return $GLOBALS['__test_query']['is_404'];
}

function is_home() {
	return $GLOBALS['__test_query']['is_home'];
}

function is_post_type_archive() {
	return $GLOBALS['__test_query']['is_post_type_archive'];
}

function is_author() {
	return $GLOBALS['__test_query']['is_author'];
}

function is_category() {
	return $GLOBALS['__test_query']['is_category'];
}

function is_tax() {
	return $GLOBALS['__test_query']['is_tax'];
}

function get_queried_object() {
	return $GLOBALS['__test_query']['queried_object'];
}

function get_queried_object_id() {
	return $GLOBALS['__test_query']['queried_object_id'];
}

function get_the_ID() {
	return 1;
}

function get_query_var( $var ) {
	return isset( $GLOBALS['__test_query']['query_var'][$var] ) ? $GLOBALS['__test_query']['query_var'][$var] : '';
}

function get_post_type( $post_id = null ) {
	return $GLOBALS['__test_query']['post_type'];
}

function get_term_children( $term_id, $taxonomy ) {
	$key = "{$taxonomy}:{$term_id}";

	return isset( $GLOBALS['__test_term_children'][$key] ) ? $GLOBALS['__test_term_children'][$key] : array();
}

function md_has_builder() {
	return false;
}

function md_has_sidebar() {
	return false;
}

function absint( $value ) {
	return abs( (int) $value );
}

function wp_parse_args( $args, $defaults = array() ) {
	return array_merge( $defaults, (array) $args );
}

function sanitize_key( $key ) {
	$key = strtolower( (string) $key );

	return preg_replace( '/[^a-z0-9_\-]/', '', $key );
}

if ( ! function_exists( '__' ) ) {
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}

require_once dirname( __DIR__, 2 ) . '/functions/theme-functions.php';
require_once dirname( __DIR__, 2 ) . '/functions/loop-functions.php';
require_once dirname( __DIR__, 2 ) . '/api/api.php';

/**
 * Base test case for the inheritance suite: reflection helper for invoking
 * md_api's protected/private methods (loop_query_vars() is protected), and a
 * fresh md_test_reset() before every test.
 *
 * @since 6.0
 */

class MD_InheritanceTestCase extends \PHPUnit\Framework\TestCase {

	protected function setUp(): void {
		md_test_reset();
	}

	// md_api::__construct() wires up a large number of WP hooks we don't
	// have stubs for, so build instances without running it.

	protected function make_api( $post_type = 'post', $taxonomy = 'category' ) {
		$reflection = new ReflectionClass( 'md_api' );
		$instance = $reflection->newInstanceWithoutConstructor();
		$instance->post_type = $post_type;
		$instance->taxonomy = $taxonomy;

		return $instance;
	}

	protected function call( $object, $method, array $args = array() ) {
		$reflection = new ReflectionMethod( $object, $method );

		return $reflection->invokeArgs( $object, $args );
	}

}
