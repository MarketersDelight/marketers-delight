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
$GLOBALS['__test_object_meta'] = array();
$GLOBALS['__test_registered_meta'] = array();
$GLOBALS['__test_posts'] = array();
$GLOBALS['__test_terms'] = array();
$GLOBALS['__test_thumbnails'] = array();
$GLOBALS['__test_current_post'] = 0;
$GLOBALS['__test_capabilities'] = array();
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
	$GLOBALS['__test_object_meta'] = array();
	$GLOBALS['__test_registered_meta'] = array();
	$GLOBALS['__test_posts'] = array();
	$GLOBALS['__test_terms'] = array();
	$GLOBALS['__test_thumbnails'] = array();
	$GLOBALS['__test_current_post'] = 0;
	$GLOBALS['__test_capabilities'] = array();
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

function md_test_set_post( $post ) {
	$post = (object) $post;
	$GLOBALS['__test_posts'][$post->ID] = $post;
	$GLOBALS['__test_current_post'] = $post->ID;
}

function md_test_set_capability( $capability, $allowed ) {
	$GLOBALS['__test_capabilities'][$capability] = $allowed;
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

function md_collections( $id = null ) {
	$collections = apply_filters( 'md_filter_collections', array() );

	if ( isset( $id ) )
		return $collections[sanitize_key( $id )] ?? array();

	return $collections;
}

function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}

function is_admin() {
	return false;
}

function wp_parse_args( $args, $defaults = array() ) {
	return array_merge( $defaults, (array) $args );
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

function md_post_type_field( $keys = null, $default = null, $post_type = null ) {
	return md_setting( array_merge( (array) $post_type, (array) $keys ), $default );
}

function md_taxonomy_field( $keys = null, $default = null, $post_type = null, $taxonomy = null ) {
	return md_setting( array_merge( (array) $post_type, (array) $taxonomy, (array) $keys ), $default );
}

function get_option( $key, $default = false ) {
	return array_key_exists( $key, $GLOBALS['__test_options'] ) ? $GLOBALS['__test_options'][$key] : $default;
}

function register_post_meta( $post_type, $meta_key, $args ) {
	$GLOBALS['__test_registered_meta'][$post_type][$meta_key] = $args;

	return true;
}

function get_post_meta( $post_id, $meta_key, $single = false ) {
	$value = $GLOBALS['__test_object_meta'][$post_id][$meta_key] ?? '';

	return $single ? $value : array( $value );
}

function update_post_meta( $post_id, $meta_key, $value ) {
	$post_type = $GLOBALS['__test_posts'][$post_id]->post_type ?? '';
	$registered = $GLOBALS['__test_registered_meta'][$post_type][$meta_key] ?? array();

	if ( isset( $registered['sanitize_callback'] ) )
		$value = call_user_func( $registered['sanitize_callback'], $value, $meta_key, 'post', $post_type );

	$GLOBALS['__test_object_meta'][$post_id][$meta_key] = $value;

	return true;
}

function delete_post_meta( $post_id, $meta_key ) {
	unset( $GLOBALS['__test_object_meta'][$post_id][$meta_key] );

	return true;
}

function metadata_exists( $meta_type, $post_id, $meta_key ) {
	return array_key_exists( $meta_key, $GLOBALS['__test_object_meta'][$post_id] ?? array() );
}

function get_post_type( $post_id = null ) {
	$post_id = $post_id ?: $GLOBALS['__test_current_post'];

	return $GLOBALS['__test_posts'][$post_id]->post_type ?? false;
}

function get_post_field( $field, $post_id, $context = 'display' ) {
	return $GLOBALS['__test_posts'][$post_id]->$field ?? '';
}

function get_the_ID() {
	return $GLOBALS['__test_current_post'];
}

function absint( $value ) {
	return abs( intval( $value ) );
}

function current_user_can( $capability, $post_id = null ) {
	return array_key_exists( $capability, $GLOBALS['__test_capabilities'] ) ? $GLOBALS['__test_capabilities'][$capability] : true;
}

function get_post_type_object( $post_type ) {
	return (object) array(
		'name' => $post_type,
		'cap' => (object) array(
			'create_posts' => "edit_{$post_type}s",
			'edit_others_posts' => "edit_others_{$post_type}s"
		)
	);
}

function get_current_user_id() {
	return $GLOBALS['__test_current_user'] ?? 1;
}

function wp_get_post_parent_id( $post_id ) {
	return intval( $GLOBALS['__test_posts'][$post_id]->post_parent ?? 0 );
}

function get_post_status( $post_id ) {
	return $GLOBALS['__test_posts'][$post_id]->post_status ?? false;
}

function current_time( $format ) {
	return $format === 'Y-m-d' ? '2026-08-18' : '';
}

function wp_get_post_terms( $post_id, $taxonomy, $args = array() ) {
	return $GLOBALS['__test_terms'][$post_id][$taxonomy] ?? array();
}

function wp_set_object_terms( $post_id, $terms, $taxonomy, $append = false ) {
	$GLOBALS['__test_terms'][$post_id][$taxonomy] = array_values( (array) $terms );

	return $GLOBALS['__test_terms'][$post_id][$taxonomy];
}

function get_taxonomy( $taxonomy ) {
	if ( ! $taxonomy )
		return false;

	return (object) array(
		'name' => $taxonomy,
		'cap' => (object) array(
			'assign_terms' => "assign_{$taxonomy}",
			'manage_terms' => "manage_{$taxonomy}"
		)
	);
}

function term_exists( $term, $taxonomy = '' ) {
	return false;
}

function get_post_thumbnail_id( $post_id ) {
	return $GLOBALS['__test_thumbnails'][$post_id] ?? 0;
}

function set_post_thumbnail( $post_id, $thumbnail_id ) {
	$GLOBALS['__test_thumbnails'][$post_id] = $thumbnail_id;

	return true;
}

function delete_post_thumbnail( $post_id ) {
	unset( $GLOBALS['__test_thumbnails'][$post_id] );

	return true;
}

function get_post_time( $format, $gmt, $post_id ) {
	return '12:34:56';
}

function wp_checkdate( $month, $day, $year, $source_date ) {
	return checkdate( intval( $month ), intval( $day ), intval( $year ) );
}

function wp_update_post( $post, $wp_error = false ) {
	$post_id = $post['ID'];

	foreach ( $post as $key => $value )
		$GLOBALS['__test_posts'][$post_id]->$key = $value;

	return $post_id;
}

function is_wp_error( $thing ) {
	return $thing instanceof WP_Error;
}

class WP_Error {
	protected $code;
	protected $message;
	protected $data;

	public function __construct( $code = '', $message = '', $data = null ) {
		$this->code = $code;
		$this->message = $message;
		$this->data = $data;
	}

	public function get_error_code() {
		return $this->code;
	}

	public function get_error_message() {
		return $this->message;
	}
}

class WP_REST_Controller {
	protected $namespace;
	protected $rest_base;
	protected $schema;

	public function get_public_item_schema() {
		return $this->get_item_schema();
	}
}

class WP_REST_Server {
	const READABLE = 'GET';
	const CREATABLE = 'POST';
	const EDITABLE = 'POST, PUT, PATCH';
	const DELETABLE = 'DELETE';
}

class WP_REST_Request implements ArrayAccess {
	protected $params;

	public function __construct( $params = array() ) {
		$this->params = $params;
	}

	public function get_param( $key ) {
		return $this->params[$key] ?? null;
	}

	public function get_method() {
		return $this->params['_method'] ?? 'GET';
	}

	#[\ReturnTypeWillChange]
	public function offsetExists( $offset ) {
		return isset( $this->params[$offset] );
	}

	#[\ReturnTypeWillChange]
	public function offsetGet( $offset ) {
		return $this->params[$offset] ?? null;
	}

	#[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ) {
		$this->params[$offset] = $value;
	}

	#[\ReturnTypeWillChange]
	public function offsetUnset( $offset ) {
		unset( $this->params[$offset] );
	}
}

class WP_REST_Response {
	protected $data;
	protected $status = 200;

	public function __construct( $data ) {
		$this->data = $data;
	}

	public function get_data() {
		return $this->data;
	}

	public function set_status( $status ) {
		$this->status = $status;
	}
}

function rest_ensure_response( $data ) {
	return $data instanceof WP_REST_Response ? $data : new WP_REST_Response( $data );
}

function register_rest_route( $namespace, $route, $args = array(), $override = false ) {
	return true;
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

function sanitize_textarea_field( $str ) {
	return sanitize_text_field( $str );
}

function wp_strip_all_tags( $str ) {
	return strip_tags( $str );
}

function wp_trim_words( $text, $num_words = 55, $more = null ) {
	$words = preg_split( '/\s+/', trim( $text ) );

	return implode( ' ', array_slice( $words, 0, $num_words ) );
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

require_once dirname( __DIR__ ) . '/api/save/sanitize.php';
require_once dirname( __DIR__ ) . '/api/save/validate.php';
require_once dirname( __DIR__ ) . '/api/save/save.php';
require_once dirname( __DIR__ ) . '/api/fields/data.php';
require_once dirname( __DIR__ ) . '/api/fields/render.php';
require_once dirname( __DIR__ ) . '/api/fields/fields.php';
require_once dirname( __DIR__ ) . '/api/collections/fields.php';
require_once dirname( __DIR__ ) . '/api/collections/collections.php';
require_once dirname( __DIR__ ) . '/api/collections/rest.php';
require_once dirname( __DIR__ ) . '/api/collections/admin.php';
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
