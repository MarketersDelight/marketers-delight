<?php
/**
 * Abilities for what only Marketers Delight knows: its settings, where they
 * can be saved and how they inherit, for apps and AI assistants. Every read
 * and write goes through md_scope and md_save::apply(), like the admin.
 *
 * @since 6.2
 */

class md_settings_abilities extends md_abilities {

	/**
	 * Describe the settings abilities.
	 *
	 * @since 6.2
	 */

	public function abilities() {
		$scope = array(
			'type' => 'string',
			'description' => __( 'Where the settings live: site, post_type:NAME, taxonomy:POST_TYPE/NAME, post:ID, term:ID or user:ID.', 'md' )
		);
		$group = array(
			'type' => 'string',
			'description' => __( 'A settings group from Describe site, ex: layout.', 'md' )
		);

		return array(
			'describe-site' => array(
				'label' => __( 'Describe site', 'md' ),
				'description' => __( 'Call this first. Explains how Marketers Delight settings work on this site: active and installed Drop-ins, settings groups and where each can be saved, post types with settings, Collections, and how settings inherit.', 'md' ),
				'execute' => 'describe_site',
				'permission' => 'can_read_site',
				'readonly' => true
			),
			'describe-settings' => array(
				'label' => __( 'Describe settings', 'md' ),
				'description' => __( 'Lists one settings group\'s fields: type, label, allowed options, default and limits. Use it before Update settings. A post type group lists the groups it hosts by name; ask for each one with the post type\'s scope.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array( 'group' => $group, 'scope' => $scope ),
					'required' => array( 'group' ),
					'additionalProperties' => false
				),
				'execute' => 'describe_settings',
				'permission' => 'can_read_site',
				'readonly' => true
			),
			'get-settings' => array(
				'label' => __( 'Get settings', 'md' ),
				'description' => __( 'Reads the values saved for a group at a scope, and at each scope it inherits from, nearest first. A value missing from a scope comes from the next one. Use it to find which level sets something before changing it. At post:ID, term:ID or user:ID, leave out group to see everything set on that one item.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array( 'scope' => $scope, 'group' => $group ),
					'required' => array( 'scope' ),
					'additionalProperties' => false
				),
				'execute' => 'get_settings',
				'permission' => 'can_use_scope',
				'readonly' => true
			),
			'update-settings' => array(
				'label' => __( 'Update settings', 'md' ),
				'description' => __( 'Changes only the values given. null resets a value, so it inherits again. On a checkbox with options, true turns an option on and 0 turns it off over an inherited setting. Colors take a hex, rgba() or palette key; images take { "id": attachment ID }. Group and builder items are keyed by ID: a new ID adds an item, null removes one, others stay. Set dry_run to see the changes without saving.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'scope' => $scope,
						'group' => $group,
						'values' => array(
							'type' => 'object',
							'description' => __( 'The values to change, shaped like the group\'s fields.', 'md' )
						),
						'order' => array(
							'type' => 'object',
							'description' => __( 'A new order for items: the field\'s name and its item IDs in order, ex: { "list": ["b", "a"] }.', 'md' )
						),
						'dry_run' => array( 'type' => 'boolean' )
					),
					'required' => array( 'scope', 'group' ),
					'additionalProperties' => false
				),
				'execute' => 'update_settings',
				'permission' => 'can_use_scope'
			),
			'export-settings' => array(
				'label' => __( 'Export settings', 'md' ),
				'description' => __( 'Returns this site\'s MD settings as a file Import settings can read, for a backup or another site: design, theme, post type and Drop-in settings, and widgets. Export design alone to share a look. Never includes license or integration keys.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'sections' => array(
							'type' => 'array',
							'items' => array( 'type' => 'string', 'enum' => array_keys( md_transfer::sections() ) ),
							'description' => __( 'Leave out for everything.', 'md' )
						)
					),
					'additionalProperties' => false,
					'default' => array()
				),
				'execute' => 'export_settings',
				'permission' => 'can_transfer',
				'readonly' => true
			),
			'import-settings' => array(
				'label' => __( 'Import settings', 'md' ),
				'description' => __( 'Applies a file from Export settings. Each selected part replaces this site\'s values, so export a backup first. Set check to see what the file holds and which Drop-ins it needs, without changing anything. Posts, pages and menus aren\'t in these files.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'file' => array( 'type' => 'object' ),
						'parts' => array(
							'type' => 'array',
							'items' => array( 'type' => 'string' ),
							'description' => __( 'Parts to import, from check. Leave out for all.', 'md' )
						),
						'check' => array( 'type' => 'boolean' )
					),
					'required' => array( 'file' ),
					'additionalProperties' => false
				),
				'execute' => 'import_settings',
				'permission' => 'can_transfer',
				'destructive' => true
			)
		);
	}

	/**
	 * Settings structure is for people who can change site settings or
	 * edit posts.
	 *
	 * @since 6.2
	 */

	public function can_read_site() {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Check the scope the ability was given.
	 *
	 * @since 6.2
	 */

	public function can_use_scope( $input = array() ) {
		$scope = md_scope::parse( isset( $input['scope'] ) ? $input['scope'] : '' );

		return is_wp_error( $scope ) ? $this->result( $scope ) : $scope->can();
	}

	/**
	 * Files hold every setting, including code fields.
	 *
	 * @since 6.2
	 */

	public function can_transfer() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * What MD is doing on this site.
	 *
	 * @since 6.2
	 */

	public function describe_site() {
		$dropins = $groups = $post_types = $collections = array();

		foreach ( md_get_dropin_details() as $slug => $details )
			$dropins[] = array(
				'slug' => $slug,
				'name' => isset( $details['Name'] ) ? $details['Name'] : $slug,
				'description' => isset( $details['Description'] ) ? wp_strip_all_tags( $details['Description'] ) : '',
				'active' => md_has( $slug )
			);

		$registered = md_register();

		foreach ( array( 'admin_pages' => 'fields', 'meta_boxes' => 'post_fields', 'terms' => 'term_fields', 'user_meta' => 'user_fields' ) as $settings => $list )
			foreach ( isset( $registered[$settings] ) ? $registered[$settings] : array() as $group => $data ) {
				if ( empty( $data['fields'] ) )
					continue;

				if ( ! isset( $groups[$group] ) )
					$groups[$group] = array(
						'group' => $group,
						'label' => isset( $data['name'] ) ? wp_strip_all_tags( $data['name'] ) : $group,
						'source' => isset( $data['_source'] ) ? $data['_source'] : '',
						'saves_to' => array_values( array_unique( array_map( array( $this, 'scope_type' ), md_scope::all( $group ) ) ) )
					);

				if ( empty( $data['has_groups'] ) )
					$groups[$group][$list] = array_keys( $data['fields'] );
			}

		foreach ( $groups as $group => $data )
			foreach ( array( 'post_fields', 'term_fields', 'user_fields' ) as $list )
				if ( isset( $data[$list], $data['fields'] ) && $data[$list] === $data['fields'] )
					unset( $groups[$group][$list] );

		$taxonomies = apply_filters( 'md_taxonomy_groups', array() );

		foreach ( isset( $registered['admin_pages'] ) ? $registered['admin_pages'] : array() as $host => $page ) {
			if ( empty( $page['has_groups'] ) || empty( $page['fields'] ) )
				continue;

			$scopes = array();

			foreach ( array_keys( isset( $taxonomies["md_$host"] ) ? $taxonomies["md_$host"] : array() ) as $taxonomy )
				$scopes[] = "taxonomy:$host/$taxonomy";

			$post_types[] = array( 'scope' => "post_type:$host", 'taxonomies' => $scopes );
		}

		foreach ( array_keys( md_collections() ) as $id ) {
			$collection = new md_collection( $id );
			$collections[] = array(
				'collection' => $id,
				'parent' => $collection->parent,
				'post_type' => $collection->post_type,
				'fields' => array_keys( $collection->fields )
			);
		}

		return array(
			'md_version' => MD_VERSION,
			'inheritance' => __( 'A value saved at a nearer scope wins: post or term, then taxonomy, then post type. A group lists where it saves in saves_to. Groups without site there have no site-wide value: Layout, Loop and others are set per post type, and post_type:post is the blog. Change something at the level it should apply to, and use null to remove an override.', 'md' ),
			'good_to_know' => array(
				__( 'A group\'s fields can differ by scope: post_fields are what a single post of that type saves, ex: post:ID. Use them instead of writing those details into the content.', 'md' ),
				__( 'Brand colors live in colors at site scope, in palette: changing primary there changes everything that uses it.', 'md' )
			),
			'dropins' => $dropins,
			'groups' => array_values( $groups ),
			'post_types' => $post_types,
			'collections' => $collections
		);
	}

	/**
	 * A scope's kind, without its name or ID.
	 *
	 * @since 6.2
	 */

	public function scope_type( $scope ) {
		$parts = explode( ':', $scope );

		return $parts[0];
	}

	/**
	 * One group's fields, generated from what it registers.
	 *
	 * @since 6.2
	 */

	public function describe_settings( $input = array() ) {
		$group = $input['group'];
		$scopes = md_scope::all( $group );

		if ( ! $scopes )
			return new WP_Error( 'md_abilities_group', sprintf( __( 'No settings group named %s. Describe site lists them.', 'md' ), $group ) );

		$scope = isset( $input['scope'] ) ? $input['scope'] : $scopes[0];
		$where = md_scope::parse( $scope );
		$target = is_wp_error( $where ) ? $where : $this->target( $where, $group );

		if ( is_wp_error( $target ) )
			return $this->result( $target );

		$pages = md_register( 'admin_pages' );
		$is_host = $where->type !== 'site' && $where->post_type === $group && ! empty( $pages[$group]['has_groups'] );

		return array(
			'group' => $group,
			'scope' => $scope,
			'scopes' => $scopes,
			'fields' => $this->fields( $target['fields'], $is_host ),
			'hosted_groups' => $is_host ? $this->hosted_groups( $target['fields'] ) : array(),
			'values_by_type' => array(
				'checkbox with options' => __( 'An object: { "option": true } turns an option on, { "option": 0 } turns it off over an inherited setting, and null resets it. Not a list of options.', 'md' ),
				'group or builder' => __( 'An object of items keyed by ID, each shaped like item_fields. Pick a new ID to add one.', 'md' ),
				'color' => __( 'A hex, rgba() or palette key.', 'md' ),
				'upload' => __( '{ "id": attachment ID }.', 'md' )
			)
		);
	}

	/**
	 * The parts of registered fields an app needs to send a valid value.
	 *
	 * @since 6.2
	 */

	public function fields( $fields, $is_host = false ) {
		$described = array();
		$keys = array( 'type' => 1, 'default' => 1, 'min' => 1, 'max' => 1, 'step' => 1, 'multiple' => 1, 'hex_only' => 1, 'upload_type' => 1 );

		foreach ( $fields as $key => $field ) {
			if ( ! is_array( $field ) || ! empty( $field['internal'] ) )
				continue;

			if ( ! isset( $field['type'] ) ) {
				if ( ! $is_host )
					$described[$key] = array( 'fields' => $this->fields( $field ) );

				continue;
			}

			$item = array_intersect_key( $field, $keys );

			if ( ! empty( $field['label'] ) )
				$item['label'] = wp_strip_all_tags( $field['label'] );

			if ( ! empty( $field['options'] ) && is_array( $field['options'] ) ) {
				$labels = md_sanitize::labels( $field['options'] );
				$item['options'] = $labels ? array_map( 'wp_strip_all_tags', $labels ) : md_sanitize::choices( $field['options'] );
			}

			if ( ! empty( $field['fields'] ) && is_array( $field['fields'] ) )
				$item['item_fields'] = $this->fields( $field['fields'] );

			$described[$key] = $item;
		}

		return $described;
	}

	/**
	 * The groups a post type's settings host, by name.
	 *
	 * @since 6.2
	 */

	public function hosted_groups( $fields ) {
		$hosted = array();

		foreach ( $fields as $key => $field )
			if ( is_array( $field ) && ! isset( $field['type'] ) )
				$hosted[] = $key;

		return $hosted;
	}

	/**
	 * A group's saved values at a scope and the scopes above it.
	 *
	 * @since 6.2
	 */

	public function get_settings( $input = array() ) {
		$where = md_scope::parse( $input['scope'] );

		if ( empty( $input['group'] ) ) {
			if ( ! $where->is_meta() )
				return new WP_Error( 'md_abilities_group', __( 'Give a group. Only post:ID, term:ID and user:ID can list everything at once.', 'md' ) );

			return array( 'scope' => $input['scope'], 'groups' => $where->stored( 'marketers_delight' ) );
		}

		$target = $this->target( $where, $input['group'] );

		if ( is_wp_error( $target ) )
			return $this->result( $target );

		$values = $where->read( $input['group'] );

		$inherits = array();

		foreach ( $this->parents( $where, $input['group'] ) as $parent ) {
			$scope = md_scope::parse( $parent );
			$read = $scope->can() ? $scope->read( $input['group'] ) : array();

			if ( ! is_wp_error( $read ) && $read )
				$inherits[] = array( 'scope' => $parent, 'values' => $read );
		}

		return array(
			'scope' => $input['scope'],
			'group' => $input['group'],
			'values' => $values,
			'inherits_from' => $inherits
		);
	}

	/**
	 * Where a group lives at a scope. A site value for a group with no
	 * site level would save and never show, so it's refused.
	 *
	 * @since 6.2
	 */

	public function target( $where, $group ) {
		if ( $where->type === 'site' && ! in_array( 'site', md_scope::all( $group ), true ) && md_scope::all( $group ) )
			return new WP_Error( 'md_abilities_no_site', sprintf( __( '%1$s has no site-wide value, so this wouldn\'t change anything. It\'s set per post type: use post_type:post for the blog, or one of: %2$s.', 'md' ), $group, implode( ', ', array_slice( md_scope::all( $group ), 0, 8 ) ) ) );

		return $where->target( $group );
	}

	/**
	 * The scopes a scope inherits from, nearest first.
	 *
	 * @since 6.2
	 */

	public function parents( $where, $group ) {
		$post_type = $where->post_type;
		$parents = array();

		if ( $where->type === 'post' )
			$post_type = get_post_type( $where->id );

		if ( $where->type === 'term' ) {
			$term = get_term( $where->id );
			$taxonomy = $term && ! is_wp_error( $term ) ? get_taxonomy( $term->taxonomy ) : false;

			if ( $taxonomy && $taxonomy->object_type ) {
				$post_type = $taxonomy->object_type[0];
				$parents[] = "taxonomy:$post_type/{$term->taxonomy}";
			}
		}

		if ( $where->type === 'post_type' )
			$post_type = md_post_type_settings_parent( $post_type );

		$seen = array();

		while ( $post_type && ! isset( $seen[$post_type] ) ) {
			$seen[$post_type] = true;
			$parents[] = "post_type:$post_type";
			$post_type = md_post_type_settings_parent( $post_type );
		}

		if ( ! in_array( $where->type, array( 'site', 'user' ), true ) && in_array( 'site', md_scope::all( $group ), true ) )
			$parents[] = 'site';

		return $parents;
	}

	/**
	 * Patch a group through the same save as the admin.
	 *
	 * @since 6.2
	 */

	public function update_settings( $input = array() ) {
		$where = md_scope::parse( $input['scope'] );
		$target = $this->target( $where, $input['group'] );

		if ( is_wp_error( $target ) )
			return $this->result( $target );

		$values = isset( $input['values'] ) ? (array) $input['values'] : array();
		$save = new md_save;

		return $this->result( $save->apply( $input['scope'], $input['group'], $this->without_internal( $values, $target['fields'] ), array(
			'dry_run' => ! empty( $input['dry_run'] ),
			'merge_items' => true,
			'order' => isset( $input['order'] ) ? (array) $input['order'] : array()
		) ) );
	}

	/**
	 * Build an export file.
	 *
	 * @since 6.2
	 */

	public function export_settings( $input = array() ) {
		return md_transfer::export( $input['sections'] ?? null );
	}

	/**
	 * Check or import a file.
	 *
	 * @since 6.2
	 */

	public function import_settings( $input = array() ) {
		$file = md_transfer::read( wp_json_encode( $input['file'] ) );

		if ( is_wp_error( $file ) )
			return $this->result( $file );

		if ( ! empty( $input['check'] ) )
			return $this->result( md_transfer::check( $file ) );

		return $this->result( md_transfer::import( $file, $input['parts'] ?? null ) );
	}

}

new md_settings_abilities;
