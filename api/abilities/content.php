<?php
/**
 * Abilities for posts, pages, images and terms of any post type, including a
 * Drop-in's, for apps and AI assistants. WordPress is adding its own: each of
 * these registers only until the core ability named in $bridges exists.
 *
 * @since 6.2
 */

class md_content_abilities extends md_abilities {

	public $bridges = array(
		'find-content' => 'core/content-query',
		'save-content' => 'core/content-update',
		'trash-content' => 'core/content-delete',
		'find-terms' => 'core/terms-query',
		'save-term' => 'core/term-create'
	);

	/**
	 * Describe the content abilities.
	 *
	 * @since 6.2
	 */

	public function abilities() {
		$terms = array(
			'type' => 'object',
			'description' => __( 'Taxonomy names and their term names, slugs or IDs, ex: { "category": ["News"] }.', 'md' )
		);

		return array(
			'find-content' => array(
				'label' => __( 'Find content', 'md' ),
				'description' => __( 'Finds posts, pages, any Drop-in\'s content, or images (type attachment), including drafts and trash. Give an id to get one item with its full content. Results include IDs for Get settings, Update settings and Find items.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'id' => array( 'type' => 'integer' ),
						'search' => array( 'type' => 'string' ),
						'type' => array(
							'type' => 'string',
							'description' => __( 'A post type, or attachment for images. Leave out for all content.', 'md' )
						),
						'status' => array(
							'type' => 'array',
							'items' => array( 'type' => 'string' ),
							'description' => __( 'Leave out for anything but trash.', 'md' )
						),
						'taxonomy' => array( 'type' => 'string' ),
						'term' => array(
							'type' => 'string',
							'description' => __( 'A term slug in taxonomy.', 'md' )
						),
						'content' => array(
							'type' => 'boolean',
							'description' => __( 'Include each result\'s full content.', 'md' )
						),
						'limit' => array( 'type' => 'integer', 'minimum' => 1, 'maximum' => 50, 'default' => 10 ),
						'offset' => array( 'type' => 'integer', 'minimum' => 0 )
					),
					'additionalProperties' => false,
					'default' => array()
				),
				'execute' => 'find_content',
				'permission' => 'can_find',
				'readonly' => true
			),
			'save-content' => array(
				'label' => __( 'Save content', 'md' ),
				'description' => __( 'Creates content when given a type (a draft unless status says otherwise), or changes it when given an id: only what\'s given changes. terms replaces a taxonomy\'s terms and add_terms keeps the others. Use edits for small text changes. settings saves MD settings on it in the same call, shaped { group: values }; Describe settings lists them.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'id' => array( 'type' => 'integer' ),
						'type' => array( 'type' => 'string' ),
						'title' => array( 'type' => 'string' ),
						'content' => array(
							'type' => 'string',
							'description' => __( 'Block markup or HTML.', 'md' )
						),
						'edits' => $this->edits_input(),
						'excerpt' => array( 'type' => 'string' ),
						'slug' => array( 'type' => 'string' ),
						'status' => array( 'type' => 'string', 'enum' => array( 'draft', 'pending', 'publish', 'private' ) ),
						'parent' => array( 'type' => 'integer' ),
						'date' => array(
							'type' => 'string',
							'description' => __( 'When it was posted, in the past or now.', 'md' )
						),
						'image_id' => array(
							'type' => 'integer',
							'description' => __( 'A Media Library image for the featured image, 0 to remove it.', 'md' )
						),
						'terms' => $terms,
						'add_terms' => $terms,
						'settings' => array( 'type' => 'object' )
					),
					'additionalProperties' => false
				),
				'execute' => 'save_content',
				'permission' => 'can_save'
			),
			'trash-content' => array(
				'label' => __( 'Trash content', 'md' ),
				'description' => __( 'Moves content to the Trash, or brings it back with restore. Nothing is deleted permanently. Warns about menu items pointing at it.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'id' => array( 'type' => 'integer' ),
						'restore' => array( 'type' => 'boolean' )
					),
					'required' => array( 'id' ),
					'additionalProperties' => false
				),
				'execute' => 'trash_content',
				'permission' => 'can_trash',
				'destructive' => true
			),
			'find-terms' => array(
				'label' => __( 'Find terms', 'md' ),
				'description' => __( 'Lists a taxonomy\'s terms, ex: its categories or tags, with IDs for term:ID settings.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'taxonomy' => array( 'type' => 'string' ),
						'search' => array( 'type' => 'string' ),
						'limit' => array( 'type' => 'integer', 'minimum' => 1, 'maximum' => 100, 'default' => 50 )
					),
					'required' => array( 'taxonomy' ),
					'additionalProperties' => false
				),
				'execute' => 'find_terms',
				'permission' => 'can_find',
				'readonly' => true
			),
			'save-term' => array(
				'label' => __( 'Save term', 'md' ),
				'description' => __( 'Creates a term, or renames or changes one when given its id.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'taxonomy' => array( 'type' => 'string' ),
						'id' => array( 'type' => 'integer' ),
						'name' => array( 'type' => 'string' ),
						'slug' => array( 'type' => 'string' ),
						'description' => array( 'type' => 'string' ),
						'parent' => array( 'type' => 'integer' )
					),
					'required' => array( 'taxonomy' ),
					'additionalProperties' => false
				),
				'execute' => 'save_term',
				'permission' => 'can_save_term'
			)
		);
	}

	/**
	 * Finding content is for anyone who edits it. Results are limited to
	 * what the user can see.
	 *
	 * @since 6.2
	 */

	public function can_find() {
		return current_user_can( 'edit_posts' );
	}

	/**
	 * Check the user can make this change.
	 *
	 * @since 6.2
	 */

	public function can_save( $input = array() ) {
		$status = $input['status'] ?? '';

		if ( ! empty( $input['id'] ) ) {
			if ( ! current_user_can( 'edit_post', absint( $input['id'] ) ) )
				return false;

			$type = get_post_type( absint( $input['id'] ) );
		}
		else {
			$type = $input['type'] ?? '';

			if ( ! $this->can( 'create_posts', $type ) )
				return false;
		}

		return ! in_array( $status, array( 'publish', 'private' ), true ) || $this->can( 'publish_posts', $type );
	}

	/**
	 * Check the user can trash this content.
	 *
	 * @since 6.2
	 */

	public function can_trash( $input = array() ) {
		return current_user_can( 'delete_post', absint( $input['id'] ?? 0 ) );
	}

	/**
	 * Check the user can manage this taxonomy's terms.
	 *
	 * @since 6.2
	 */

	public function can_save_term( $input = array() ) {
		$taxonomy = get_taxonomy( $input['taxonomy'] ?? '' );

		if ( ! $taxonomy )
			return false;

		return ! empty( $input['id'] ) ? current_user_can( 'edit_term', absint( $input['id'] ) ) : current_user_can( $taxonomy->cap->manage_terms );
	}

	/**
	 * Find content. Post types are given as an array so a Drop-in's archive
	 * query leaves the search alone.
	 *
	 * @since 6.2
	 */

	public function find_content( $input = array() ) {
		$type = $input['type'] ?? '';
		$limit = absint( $input['limit'] ?? 10 );
		$offset = absint( $input['offset'] ?? 0 );
		$default = $type === 'attachment' ? array( 'inherit' ) : array( 'publish', 'future', 'draft', 'pending', 'private' );
		$args = array(
			'post_type' => $type ? array( $type ) : array_values( array_diff( get_post_types( array( 'show_ui' => true ) ), array( 'attachment', 'wp_block', 'wp_navigation' ) ) ),
			'post_status' => $input['status'] ?? $default,
			's' => $input['search'] ?? '',
			'posts_per_page' => $limit,
			'offset' => $offset
		);

		if ( ! empty( $input['id'] ) ) {
			$args = array( 'p' => absint( $input['id'] ), 'post_type' => 'any', 'post_status' => array_values( get_post_stati() ) );
			$input['content'] = true;
		}

		if ( ! empty( $input['taxonomy'] ) && ! empty( $input['term'] ) )
			$args['tax_query'] = array( array( 'taxonomy' => $input['taxonomy'], 'field' => 'slug', 'terms' => $input['term'] ) );

		$query = new WP_Query( $args );
		$items = array();

		foreach ( $query->posts as $post )
			if ( current_user_can( 'edit_post', $post->ID ) || ( is_post_publicly_viewable( $post ) && $post->post_password === '' ) )
				$items[] = $this->content_result( $post, ! empty( $input['content'] ) );

		return array(
			'items' => $items,
			'total' => (int) $query->found_posts,
			'next_offset' => $query->found_posts > $offset + $limit ? $offset + $limit : null
		);
	}

	/**
	 * Create or change content.
	 *
	 * @since 6.2
	 */

	public function save_content( $input = array() ) {
		$id = absint( $input['id'] ?? 0 );
		$post = array();
		$warnings = array();

		if ( ! $id && ( empty( $input['type'] ) || ! post_type_exists( $input['type'] ) ) )
			return new WP_Error( 'md_abilities_type', __( 'Give an id to change content, or a post type to create it.', 'md' ) );

		foreach ( array( 'title' => 'post_title', 'content' => 'post_content', 'excerpt' => 'post_excerpt', 'slug' => 'post_name', 'status' => 'post_status', 'parent' => 'post_parent' ) as $key => $field )
			if ( isset( $input[$key] ) )
				$post[$field] = $input[$key];

		if ( ! empty( $input['edits'] ) ) {
			$post['post_content'] = $this->edited_text( (string) get_post_field( 'post_content', $id, 'raw' ), $input['edits'] );

			if ( is_wp_error( $post['post_content'] ) )
				return $post['post_content'];
		}

		if ( ! empty( $input['date'] ) ) {
			$dates = $this->post_dates( $input['date'] );

			if ( is_wp_error( $dates ) )
				return $dates;

			$post = array_merge( $post, $dates, array( 'edit_date' => true ) );
		}

		if ( isset( $input['image_id'] ) && $input['image_id'] && ! wp_attachment_is_image( $input['image_id'] ) )
			return new WP_Error( 'md_abilities_image', __( 'That ID isn\'t an image in the Media Library. Find content with type attachment lists them.', 'md' ) );

		$type = $id ? get_post_type( $id ) : $input['type'];
		$terms = $this->resolve_terms( $input['terms'] ?? array(), $type );
		$add_terms = is_wp_error( $terms ) ? $terms : $this->resolve_terms( $input['add_terms'] ?? array(), $type );

		if ( is_wp_error( $add_terms ) )
			return $this->result( $add_terms );

		if ( $id ) {
			if ( $post ) {
				$saved = wp_update_post( wp_slash( array_merge( array( 'ID' => $id ), $post ) ), true );

				if ( is_wp_error( $saved ) )
					return $saved;
			}
		}
		else {
			$id = wp_insert_post( wp_slash( array_merge( array(
				'post_type' => $type,
				'post_status' => 'draft',
				'post_author' => get_current_user_id()
			), $post ) ), true );

			if ( is_wp_error( $id ) )
				return $id;
		}

		foreach ( $terms as $taxonomy => $ids )
			wp_set_object_terms( $id, $ids, $taxonomy );

		foreach ( $add_terms as $taxonomy => $ids )
			wp_set_object_terms( $id, $ids, $taxonomy, true );

		if ( isset( $input['image_id'] ) )
			$input['image_id'] ? set_post_thumbnail( $id, $input['image_id'] ) : delete_post_thumbnail( $id );

		$save = new md_save;

		foreach ( (array) ( $input['settings'] ?? array() ) as $group => $values ) {
			$target = md_scope::parse( "post:$id" )->target( $group );
			$values = is_wp_error( $target ) ? (array) $values : $this->without_internal( (array) $values, $target['fields'] );
			$applied = $save->apply( "post:$id", $group, $values, array( 'merge_items' => true ) );
			$warnings = array_merge( $warnings, is_wp_error( $applied ) ? array( $applied->get_error_message() ) : $applied['warnings'] );
		}

		return $this->result( array_merge( $this->content_result( get_post( $id ), true ), array( 'warnings' => $warnings ) ) );
	}

	/**
	 * Trash or restore content, with the status it had before.
	 *
	 * @since 6.2
	 */

	public function trash_content( $input = array() ) {
		$id = absint( $input['id'] );

		if ( empty( $input['restore'] ) && ! EMPTY_TRASH_DAYS )
			return $this->trash_off();

		$done = empty( $input['restore'] ) ? wp_trash_post( $id ) : md_collection::untrash( $id );

		if ( ! $done )
			return new WP_Error( 'md_abilities_trash', __( 'That content couldn\'t be changed.', 'md' ) );

		$menu_items = empty( $input['restore'] ) ? count( wp_get_associated_nav_menu_items( $id, 'post_type' ) ) : 0;

		return array_merge( $this->content_result( get_post( $id ), false ), array(
			'warnings' => $menu_items ? array( sprintf( _n( '%d menu item points at this.', '%d menu items point at this.', $menu_items, 'md' ), $menu_items ) ) : array()
		) );
	}

	/**
	 * List a taxonomy's terms.
	 *
	 * @since 6.2
	 */

	public function find_terms( $input = array() ) {
		$terms = get_terms( array(
			'taxonomy' => $input['taxonomy'],
			'hide_empty' => false,
			'search' => $input['search'] ?? '',
			'number' => absint( $input['limit'] ?? 50 )
		) );

		if ( is_wp_error( $terms ) )
			return $terms;

		$found = array();

		foreach ( $terms as $term )
			$found[] = $this->term_result( $term );

		return array( 'taxonomy' => $input['taxonomy'], 'terms' => $found );
	}

	/**
	 * Create or change a term.
	 *
	 * @since 6.2
	 */

	public function save_term( $input = array() ) {
		$args = wp_slash( array_intersect_key( $input, array_flip( array( 'name', 'slug', 'description', 'parent' ) ) ) );

		if ( ! empty( $input['id'] ) )
			$saved = wp_update_term( absint( $input['id'] ), $input['taxonomy'], $args );
		elseif ( empty( $input['name'] ) )
			return new WP_Error( 'md_abilities_term', __( 'A new term needs a name.', 'md' ) );
		else
			$saved = wp_insert_term( wp_slash( $input['name'] ), $input['taxonomy'], $args );

		return is_wp_error( $saved ) ? $saved : $this->term_result( get_term( $saved['term_id'], $input['taxonomy'] ) );
	}

	/**
	 * Turn term names, slugs or IDs into IDs. A category that doesn't exist
	 * is refused, so a typo can't create one. Tags are created if the user
	 * may. Returns IDs by taxonomy or a WP_Error.
	 *
	 * @since 6.2
	 */

	protected function resolve_terms( $terms, $post_type ) {
		$resolved = array();

		foreach ( (array) $terms as $name => $values ) {
			$taxonomy = get_taxonomy( $name );

			if ( ! $taxonomy || ! is_object_in_taxonomy( $post_type, $name ) )
				return new WP_Error( 'md_abilities_taxonomy', sprintf( __( '%1$s doesn\'t have a taxonomy named %2$s.', 'md' ), $post_type, $name ) );

			if ( ! current_user_can( $taxonomy->cap->assign_terms ) )
				return new WP_Error( 'md_abilities_terms', __( 'You cannot assign these terms.', 'md' ) );

			$resolved[$name] = array();

			foreach ( (array) $values as $value ) {
				$term = is_numeric( $value ) ? get_term( (int) $value, $name ) : ( get_term_by( 'slug', sanitize_title( $value ), $name ) ?: get_term_by( 'name', $value, $name ) );

				if ( ! $term && ! $taxonomy->hierarchical && current_user_can( $taxonomy->cap->manage_terms ) ) {
					$created = wp_insert_term( wp_slash( $value ), $name );

					if ( is_wp_error( $created ) )
						return $created;

					$term = get_term( $created['term_id'], $name );
				}

				if ( ! $term || is_wp_error( $term ) )
					return new WP_Error( 'md_abilities_term', sprintf( __( 'No %1$s term named %2$s. Find terms lists them, and Save term adds one.', 'md' ), $name, $value ) );

				$resolved[$name][] = (int) $term->term_id;
			}
		}

		return $resolved;
	}

	/**
	 * A term as apps get it.
	 *
	 * @since 6.2
	 */

	protected function term_result( $term ) {
		return array(
			'id' => (int) $term->term_id,
			'name' => html_entity_decode( $term->name, ENT_QUOTES, 'UTF-8' ),
			'slug' => $term->slug,
			'description' => $term->description,
			'parent' => (int) $term->parent,
			'count' => (int) $term->count
		);
	}

	/**
	 * Content as apps get it: enough to judge it, and its full content on
	 * request.
	 *
	 * @since 6.2
	 */

	protected function content_result( $post, $content ) {
		$result = array(
			'id' => $post->ID,
			'type' => $post->post_type,
			'title' => $this->plain_title( $post ),
			'slug' => $post->post_name,
			'status' => $post->post_status,
			'date' => get_post_time( 'c', false, $post ),
			'link' => get_permalink( $post ),
			'parent' => (int) $post->post_parent,
			'first_line' => wp_trim_words( wp_strip_all_tags( $post->post_content ), 25 ),
			'image_id' => (int) get_post_thumbnail_id( $post ),
			'terms' => array()
		);

		foreach ( get_object_taxonomies( $post->post_type ) as $taxonomy ) {
			$names = wp_get_post_terms( $post->ID, $taxonomy, array( 'fields' => 'names' ) );

			if ( $names && ! is_wp_error( $names ) )
				$result['terms'][$taxonomy] = $names;
		}

		foreach ( md_collections() as $id => $collection )
			if ( $collection['parent'] === $post->post_type )
				$result['items'][$id] = (int) get_post_meta( $post->ID, "_md_{$collection['post_type']}_count", true );

		if ( $post->post_type === 'attachment' ) {
			$result['alt'] = (string) get_post_meta( $post->ID, '_wp_attachment_image_alt', true );
			$result['url'] = wp_get_attachment_url( $post->ID );
		}

		if ( $content )
			$result['content'] = $post->post_content;

		return $result;
	}

}

new md_content_abilities;
