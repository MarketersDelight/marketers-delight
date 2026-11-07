<?php
/**
 * Share MD's actions through the WordPress Abilities API (6.9+), so apps and
 * AI assistants can find and use them, including over the MCP Adapter.
 *
 * A Drop-in extends md_abilities, sets $slug and describes its abilities in
 * abilities(). They register as md-{$slug}/name, or marketers-delight/name in
 * the theme.
 *
 * @since 6.2
 */

class md_abilities {

	public $slug = '';
	public static $category = 'marketers-delight';

	/**
	 * Register abilities when the Abilities API asks for them.
	 *
	 * @since 6.2
	 */

	public function __construct() {
		if ( self::enabled() )
			add_action( 'wp_abilities_api_init', array( $this, 'register' ), 20 );
	}

	/**
	 * Whether MD's abilities are on: the site runs the Abilities API and
	 * the Apps & AI setting hasn't turned them off.
	 *
	 * @since 6.2
	 */

	public static function enabled() {
		return function_exists( 'wp_register_ability' ) && ! md_setting( array( 'settings', 'abilities', 'disable' ) );
	}

	/**
	 * Group every MD ability under one category.
	 *
	 * @since 6.2
	 */

	public static function register_category() {
		if ( ! self::enabled() )
			return;

		wp_register_ability_category( self::$category, array(
			'label' => __( 'Marketers Delight', 'md' ),
			'description' => __( 'Actions from the Marketers Delight theme and its Drop-ins.', 'md' )
		) );
	}

	/**
	 * Describe this Drop-in's abilities.
	 *
	 * @since 6.2
	 */

	public function abilities() {
		return array();
	}

	/**
	 * Register each ability under the Drop-in's name.
	 *
	 * @since 6.2
	 */

	public function register() {
		$namespace = $this->slug ? "md-{$this->slug}" : 'marketers-delight';

		foreach ( $this->abilities() as $name => $ability )
			wp_register_ability( "$namespace/$name", $this->args( $ability ) );
	}

	/**
	 * Build the full Abilities API arguments from a short description.
	 *
	 * @since 6.2
	 */

	protected function args( $ability ) {
		$readonly = ! empty( $ability['readonly'] );
		$args = array(
			'label' => $ability['label'],
			'description' => $ability['description'],
			'category' => self::$category,
			'execute_callback' => array( $this, $ability['execute'] ),
			'permission_callback' => array( $this, $ability['permission'] ),
			'meta' => array(
				'public' => true,
				'mcp' => array( 'public' => true ),
				'annotations' => array(
					'readonly' => $readonly,
					'destructive' => ! empty( $ability['destructive'] ),
					'idempotent' => $readonly
				)
			)
		);

		if ( isset( $ability['input'] ) )
			$args['input_schema'] = $ability['input'];

		if ( isset( $ability['output'] ) )
			$args['output_schema'] = $ability['output'];

		return $args;
	}

	/**
	 * Messages mark names with backticks for screens. Apps get plain text.
	 *
	 * @since 6.2
	 */

	public function plain( $messages ) {
		return str_replace( '`', '', $messages );
	}

	/**
	 * An ability's result, with its messages as plain text.
	 *
	 * @since 6.2
	 */

	public function result( $result ) {
		if ( is_wp_error( $result ) )
			return new WP_Error( $result->get_error_code(), $this->plain( $result->get_error_message() ), $result->get_error_data() );

		foreach ( array( 'warnings', 'skipped' ) as $key )
			if ( isset( $result[$key] ) )
				$result[$key] = $this->plain( $result[$key] );

		return $result;
	}

	/**
	 * Internal fields are written by MD itself, never by an app.
	 *
	 * @since 6.2
	 */

	public function without_internal( $values, $fields ) {
		foreach ( $values as $key => $value ) {
			if ( ! isset( $fields[$key] ) || ! is_array( $fields[$key] ) )
				continue;

			if ( ! empty( $fields[$key]['internal'] ) )
				unset( $values[$key] );
			elseif ( ! isset( $fields[$key]['type'] ) && is_array( $value ) )
				$values[$key] = $this->without_internal( $value, $fields[$key] );
		}

		return $values;
	}

	/**
	 * The input for small text changes.
	 *
	 * @since 6.2
	 */

	public function edits_input() {
		return array(
			'type' => 'array',
			'description' => __( 'Exact text replacements in the content. Each find must appear exactly once.', 'md' ),
			'items' => array(
				'type' => 'object',
				'properties' => array(
					'find' => array( 'type' => 'string', 'minLength' => 1 ),
					'replace' => array( 'type' => 'string' )
				),
				'required' => array( 'find', 'replace' )
			)
		);
	}

	/**
	 * Apply exact text replacements, refusing any that don't match once.
	 *
	 * @since 6.2
	 */

	public function edited_text( $text, $edits ) {
		foreach ( $edits as $edit ) {
			$matches = substr_count( $text, $edit['find'] );

			if ( $matches !== 1 )
				return new WP_Error( 'md_abilities_edit', sprintf( __( '"%1$s" appears %2$d times. Use longer text that appears once.', 'md' ), $edit['find'], $matches ) );

			$text = str_replace( $edit['find'], $edit['replace'], $text );
		}

		return $text;
	}

	/**
	 * With the Trash turned off, WordPress deletes what it's told to trash,
	 * so abilities refuse rather than delete for good.
	 *
	 * @since 6.2
	 */

	public function trash_off() {
		return new WP_Error( 'md_abilities_trash_off', __( 'This site has the Trash turned off (EMPTY_TRASH_DAYS), so this would delete it permanently. Delete it from wp-admin instead.', 'md' ) );
	}

	/**
	 * Check a post type capability for the current user, such as
	 * 'edit_posts', 'create_posts' or 'publish_posts'.
	 *
	 * @since 6.2
	 */

	public function can( $capability, $post_type ) {
		$object = get_post_type_object( $post_type );

		return $object && current_user_can( $object->cap->$capability );
	}

	/**
	 * Turn HTML entities in a title into plain text for apps.
	 *
	 * @since 6.2
	 */

	public function plain_title( $post ) {
		return html_entity_decode( get_the_title( $post ), ENT_QUOTES, 'UTF-8' );
	}

	/**
	 * Turn a date an app sends into WordPress's local and GMT post dates.
	 * A date without a timezone is read in the site's timezone. Only past
	 * dates or now, so a published post never turns into a scheduled one.
	 *
	 * @since 6.2
	 */

	public function post_dates( $date ) {
		try {
			$time = new DateTimeImmutable( $date, wp_timezone() );
		}
		catch ( Exception $e ) {
			return new WP_Error( 'md_date', __( 'That isn\'t a date WordPress understands. Use a format like 2026-10-23T09:30:00-05:00.', 'md' ) );
		}

		if ( $time->getTimestamp() > time() + MINUTE_IN_SECONDS )
			return new WP_Error( 'md_date_future', __( 'The date must be in the past or now.', 'md' ) );

		return array(
			'post_date' => wp_date( 'Y-m-d H:i:s', $time->getTimestamp() ),
			'post_date_gmt' => gmdate( 'Y-m-d H:i:s', $time->getTimestamp() )
		);
	}

}

add_action( 'wp_abilities_api_categories_init', array( 'md_abilities', 'register_category' ) );
