<?php
/**
 * Where settings are saved. A scope names a place settings live, and
 * target() finds where one group's values are stored there and the
 * fields registered for them.
 *
 *   site                    — the site's settings, an option per group
 *   post_type:NAME          — a post type's settings
 *   taxonomy:POST_TYPE/NAME — a taxonomy's settings, under its post type
 *   post:ID, term:ID, user:ID — one object's meta
 *
 * md_save::apply(), the settings forms and the abilities all read
 * scopes the same way.
 *
 * @since 6.2
 */

class md_scope {

	public $type = '';
	public $name = '';
	public $post_type = '';
	public $id = 0;

	/**
	 * Read a scope string. post, term and user without an ID describe
	 * their fields only, ex: for md_api::schema().
	 *
	 * @since 6.2
	 */

	public static function parse( $string ) {
		$string = is_string( $string ) ? trim( $string ) : '';
		$parts = explode( ':', $string, 2 );
		$scope = new md_scope;
		$scope->type = $parts[0];
		$value = isset( $parts[1] ) ? $parts[1] : '';

		if ( $scope->type === 'site' && count( $parts ) === 1 )
			return $scope;

		if ( in_array( $scope->type, array( 'post', 'term', 'user' ), true ) && ( count( $parts ) === 1 || ( is_numeric( $value ) && (int) $value > 0 ) ) ) {
			$scope->id = (int) $value;

			return $scope;
		}

		if ( $scope->type === 'post_type' && $value !== '' && sanitize_key( $value ) === $value ) {
			$scope->name = $scope->post_type = $value;

			return $scope;
		}

		$taxonomy = explode( '/', $value, 2 );

		$is_key = count( $taxonomy ) === 2 && $taxonomy[0] !== '' && $taxonomy[1] !== '' && sanitize_key( $taxonomy[0] ) === $taxonomy[0] && sanitize_key( $taxonomy[1] ) === $taxonomy[1];

		if ( $scope->type === 'taxonomy' && $is_key ) {
			$scope->post_type = $taxonomy[0];
			$scope->name = $taxonomy[1];

			return $scope;
		}

		return new WP_Error( 'md_save_scope', sprintf( __( 'Unknown settings scope: %s', 'md' ), $string ), array( 'status' => 400 ) );
	}

	/**
	 * Settings, post type and taxonomy screens change the whole site,
	 * posts, terms and users their own object.
	 *
	 * @since 6.2
	 */

	public function can() {
		if ( $this->type === 'post' )
			return current_user_can( 'edit_post', $this->id );

		if ( $this->type === 'term' )
			return current_user_can( 'manage_options' ) && current_user_can( 'edit_term', $this->id );

		if ( $this->type === 'user' )
			return current_user_can( 'manage_options' ) && current_user_can( 'edit_user', $this->id );

		return current_user_can( 'edit_theme_options' );
	}

	/**
	 * Where a group's values are stored in this scope: the option (meta
	 * scopes store in their object's marketers_delight meta), the keys to
	 * the group's branch, and the fields registered for it.
	 *
	 * @since 6.2
	 */

	public function target( $group ) {
		if ( $this->type === 'site' )
			return $this->site_target( $group );

		if ( in_array( $this->type, array( 'post_type', 'taxonomy' ), true ) )
			return $this->post_type_target( $group );

		return $this->meta_target( $group );
	}

	/**
	 * Site settings: each group is a branch of its option.
	 *
	 * @since 6.2
	 */

	private function site_target( $group ) {
		$pages = md_register( 'admin_pages' );

		if ( empty( $pages[$group]['fields'] ) )
			return $this->unknown( $group );

		return array(
			'option' => isset( $pages[$group]['_option'] ) ? $pages[$group]['_option'] : 'marketers_delight',
			'path' => array( $group ),
			'fields' => $pages[$group]['fields']
		);
	}

	/**
	 * Post type settings live in the post type's branch, taxonomy settings
	 * in a branch inside it. The group is the post type's own settings or
	 * one of the groups it hosts, ex: layout.
	 *
	 * @since 6.2
	 */

	private function post_type_target( $group ) {
		$pages = md_register( 'admin_pages' );
		$host = $this->post_type;
		$taxonomies = apply_filters( 'md_taxonomy_groups', array() );

		if ( empty( $pages[$host]['has_groups'] ) || empty( $pages[$host]['fields'] ) )
			return new WP_Error( 'md_save_scope', sprintf( __( 'No settings are registered for %s.', 'md' ), $host ), array( 'status' => 400 ) );

		if ( $this->type === 'taxonomy' && empty( $taxonomies["md_$host"][$this->name] ) )
			return new WP_Error( 'md_save_scope', sprintf( __( 'No settings are registered for the %1$s taxonomy of %2$s.', 'md' ), $this->name, $host ), array( 'status' => 400 ) );

		$path = $this->type === 'taxonomy' ? array( $host, $this->name ) : array( $host );
		$fields = $pages[$host]['fields'];

		if ( $group !== $host ) {
			if ( ! isset( $fields[$group] ) || ! is_array( $fields[$group] ) || isset( $fields[$group]['type'] ) )
				return $this->unknown( $group );

			$path[] = $group;
			$fields = $fields[$group];
		}

		return array(
			'option' => isset( $pages[$host]['_option'] ) ? $pages[$host]['_option'] : 'marketers_delight',
			'path' => $path,
			'fields' => $fields
		);
	}

	/**
	 * Post, term and user settings: each group is a branch of the object's
	 * marketers_delight meta.
	 *
	 * @since 6.2
	 */

	private function meta_target( $group ) {
		$settings = array(
			'post' => 'meta_boxes',
			'term' => 'terms',
			'user' => 'user_meta'
		);
		$registered = md_register( $settings[$this->type] );

		if ( empty( $registered[$group]['fields'] ) )
			return $this->unknown( $group );

		return array(
			'option' => 'marketers_delight',
			'path' => array( $group ),
			'fields' => $registered[$group]['fields']
		);
	}

	/**
	 * A group this scope has no fields registered for.
	 *
	 * @since 6.2
	 */

	private function unknown( $group ) {
		return new WP_Error( 'md_save_group', sprintf( __( 'Unknown settings group: %s', 'md' ), is_string( $group ) ? $group : '' ), array( 'status' => 400 ) );
	}

	/**
	 * Every scope a group can be saved to, ex: for md_api::schema().
	 *
	 * @since 6.2
	 */

	public static function all( $group ) {
		$registered = md_register();
		$pages = isset( $registered['admin_pages'] ) ? $registered['admin_pages'] : array();
		$taxonomies = apply_filters( 'md_taxonomy_groups', array() );
		$scopes = array();

		if ( ! empty( $pages[$group]['fields'] ) && empty( $pages[$group]['has_groups'] ) )
			$scopes[] = 'site';

		foreach ( $pages as $host => $page ) {
			$is_child = isset( $page['fields'][$group] ) && is_array( $page['fields'][$group] ) && ! isset( $page['fields'][$group]['type'] );

			if ( empty( $page['has_groups'] ) || empty( $page['fields'] ) || ( $group !== $host && ! $is_child ) )
				continue;

			$scopes[] = "post_type:$host";

			foreach ( array_keys( isset( $taxonomies["md_$host"] ) ? $taxonomies["md_$host"] : array() ) as $taxonomy )
				$scopes[] = "taxonomy:$host/$taxonomy";
		}

		foreach ( array( 'meta_boxes' => 'post', 'terms' => 'term', 'user_meta' => 'user' ) as $settings => $scope )
			if ( ! empty( $registered[$settings][$group]['fields'] ) )
				$scopes[] = $scope;

		return $scopes;
	}

}
