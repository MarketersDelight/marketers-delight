<?php
/**
 * Isolated bootstrap for testing the post-type -> taxonomy -> term option
 * inheritance chain (functions/meta-functions.php, functions/loop-functions.php,
 * md_api::loop_query_vars()) in real code, not a re-implementation of it.
 *
 * Kept separate from tests/bootstrap.php because that bootstrap already
 * declares its own stub md_setting()/md_post_meta()/md_term_meta()/md_user_meta()
 * and requires api/*.php files that would redeclare those names again if this
 * real option/meta function files were pulled into the same process.
 *
 * @since 6.0
 */

$GLOBALS['__test_options'] = array();
$GLOBALS['__test_post_meta'] = array();
$GLOBALS['__test_term_meta'] = array();
$GLOBALS['__test_user_meta'] = array();
$GLOBALS['__test_filters'] = array();
$GLOBALS['__test_term_children'] = array();
$GLOBALS['__test_object_taxonomies'] = array();
$GLOBALS['__test_taxonomies'] = array();
$GLOBALS['__test_post_terms'] = array();
$GLOBALS['__test_ancestors'] = array();
$GLOBALS['__test_query'] = array(
	'is_admin' => false,
	'is_singular' => false,
	'is_404' => false,
	'is_home' => false,
	'is_post_type_archive' => false,
	'is_author' => false,
	'is_category' => false,
	'is_tag' => false,
	'is_tax' => false,
	'is_search' => false,
	'is_archive' => false,
	'is_page' => false,
	'post_parent_id' => 0,
	'previous_post' => false,
	'next_post' => false,
	'comments_open' => false,
	'comments_number' => 0,
	'post_password_required' => false,
	'queried_object' => null,
	'queried_object_id' => 0,
	'query_var' => array(),
	'post_type' => '',
);
$GLOBALS['__test_active_sidebars'] = array();

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
	$GLOBALS['__test_object_taxonomies'] = array();
	$GLOBALS['__test_taxonomies'] = array();
	$GLOBALS['__test_post_terms'] = array();
	$GLOBALS['__test_ancestors'] = array();
	$GLOBALS['__test_query'] = array(
		'is_admin' => false,
		'is_singular' => false,
		'is_404' => false,
		'is_home' => false,
		'is_post_type_archive' => false,
		'is_author' => false,
		'is_category' => false,
		'is_tag' => false,
		'is_tax' => false,
		'is_search' => false,
		'is_archive' => false,
		'is_page' => false,
		'post_parent_id' => 0,
		'previous_post' => false,
		'next_post' => false,
		'comments_open' => false,
		'comments_number' => 0,
		'post_password_required' => false,
		'queried_object' => null,
		'queried_object_id' => 0,
		'query_var' => array(),
		'post_type' => '',
	);
	$GLOBALS['__test_active_sidebars'] = array();
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

function md_test_set_user_meta( $user_id, $meta ) {
	$GLOBALS['__test_user_meta'][$user_id] = $meta;
}

function md_test_set_filter( $tag, $value ) {
	$GLOBALS['__test_filters'][$tag] = $value;
}

// $children = array of child term IDs (non-empty means "has children").

function md_test_set_term_children( $term_id, $taxonomy, $children ) {
	$GLOBALS['__test_term_children']["{$taxonomy}:{$term_id}"] = $children;
}

function md_test_set_taxonomy( $taxonomy, $public = true ) {
	$GLOBALS['__test_taxonomies'][$taxonomy] = (object) array( 'public' => $public );
}

function md_test_set_object_taxonomies( $post_type, array $taxonomies ) {
	$GLOBALS['__test_object_taxonomies'][$post_type] = $taxonomies;
}

function md_test_set_post_terms( $post_id, $taxonomy, array $terms ) {
	$GLOBALS['__test_post_terms']["{$post_id}:{$taxonomy}"] = array_map( function( $term_id ) use ( $taxonomy ) {
		return (object) array(
			'term_id' => $term_id,
			'taxonomy' => $taxonomy
		);
	}, $terms );
}

function md_test_set_ancestors( $term_id, $taxonomy, array $ancestors ) {
	$GLOBALS['__test_ancestors']["{$taxonomy}:{$term_id}"] = $ancestors;
}

// Controls is_category()/is_tax()/is_singular()/etc and get_queried_object().

function md_test_set_query( array $state ) {
	$GLOBALS['__test_query'] = array_merge( $GLOBALS['__test_query'], $state );
}

function md_test_set_active_sidebars( array $sidebars ) {
	$GLOBALS['__test_active_sidebars'] = $sidebars;
}

// WP function stubs

function apply_filters( $tag, $value ) {
	return array_key_exists( $tag, $GLOBALS['__test_filters'] ) ? $GLOBALS['__test_filters'][$tag] : $value;
}

function add_filter() {
	return true;
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

function is_tag() {
	return $GLOBALS['__test_query']['is_tag'];
}

function is_tax() {
	return $GLOBALS['__test_query']['is_tax'];
}

function is_search() {
	return $GLOBALS['__test_query']['is_search'];
}

function is_archive() {
	return $GLOBALS['__test_query']['is_archive'] ||
		$GLOBALS['__test_query']['is_post_type_archive'] ||
		$GLOBALS['__test_query']['is_author'] ||
		$GLOBALS['__test_query']['is_category'] ||
		$GLOBALS['__test_query']['is_tag'] ||
		$GLOBALS['__test_query']['is_tax'];
}

function is_page() {
	return $GLOBALS['__test_query']['is_page'];
}

function is_active_sidebar( $sidebar ) {
	return in_array( $sidebar, $GLOBALS['__test_active_sidebars'], true );
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

function wp_get_post_parent_id( $post_id ) {
	return $GLOBALS['__test_query']['post_parent_id'];
}

function get_previous_post() {
	return $GLOBALS['__test_query']['previous_post'];
}

function get_next_post() {
	return $GLOBALS['__test_query']['next_post'];
}

function comments_open() {
	return $GLOBALS['__test_query']['comments_open'];
}

function get_comments_number() {
	return $GLOBALS['__test_query']['comments_number'];
}

function post_password_required() {
	return $GLOBALS['__test_query']['post_password_required'];
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

function get_object_taxonomies( $post_type ) {
	return isset( $GLOBALS['__test_object_taxonomies'][$post_type] ) ? $GLOBALS['__test_object_taxonomies'][$post_type] : array();
}

function get_taxonomy( $taxonomy ) {
	return isset( $GLOBALS['__test_taxonomies'][$taxonomy] ) ? $GLOBALS['__test_taxonomies'][$taxonomy] : null;
}

function wp_get_post_terms( $post_id, $taxonomy ) {
	if ( is_array( $taxonomy ) ) {
		$terms = array();

		foreach ( $taxonomy as $name )
			$terms = array_merge( $terms, wp_get_post_terms( $post_id, $name ) );

		return $terms;
	}

	$key = "{$post_id}:{$taxonomy}";

	return isset( $GLOBALS['__test_post_terms'][$key] ) ? $GLOBALS['__test_post_terms'][$key] : array();
}

function get_ancestors( $term_id, $taxonomy, $resource_type = '' ) {
	$key = "{$taxonomy}:{$term_id}";

	return isset( $GLOBALS['__test_ancestors'][$key] ) ? $GLOBALS['__test_ancestors'][$key] : array();
}

function is_wp_error( $value ) {
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

function esc_attr( $value ) {
	return (string) $value;
}

if ( ! function_exists( '__' ) ) {
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}

require_once dirname( __DIR__, 2 ) . '/functions/option-functions.php';
require_once dirname( __DIR__, 2 ) . '/functions/meta-functions.php';
require_once dirname( __DIR__, 2 ) . '/functions/page-functions.php';
require_once dirname( __DIR__, 2 ) . '/functions/comment-functions.php';
require_once dirname( __DIR__, 2 ) . '/functions/layout-functions.php';
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
