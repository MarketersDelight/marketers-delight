<?php
/**
 * Abilities for Collection items, the entries that belong to a parent post,
 * ex: a Stream post's thread updates or a book's highlights. Any Drop-in that
 * registers a Collection gets these without writing an ability.
 *
 * @since 6.2
 */

class md_item_abilities extends md_abilities {

	/**
	 * Describe the item abilities.
	 *
	 * @since 6.2
	 */

	public function abilities() {
		$collection = array(
			'type' => 'string',
			'description' => __( 'The Collection, from Describe site, ex: stream_threads.', 'md' )
		);
		$parent = array(
			'type' => 'integer',
			'description' => __( 'The ID of the post the items belong to.', 'md' )
		);

		return array(
			'find-items' => array(
				'label' => __( 'Find items', 'md' ),
				'description' => __( 'Lists a post\'s Collection items in the order readers see them, with each one\'s ID, fields and position. Check it before adding, changing or moving items so nothing is duplicated.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'collection' => $collection,
						'parent_id' => $parent,
						'include' => array(
							'type' => 'array',
							'items' => array( 'type' => 'string', 'enum' => array( 'drafts', 'trash' ) ),
							'description' => __( 'Also list unpublished or trashed items.', 'md' )
						)
					),
					'required' => array( 'collection', 'parent_id' ),
					'additionalProperties' => false
				),
				'execute' => 'find_items',
				'permission' => 'can_read_items',
				'readonly' => true
			),
			'save-item' => array(
				'label' => __( 'Save item', 'md' ),
				'description' => __( 'Adds an item to a published post, or changes one when given item_id: only the fields given change, and null clears one. A new item goes at the end. To put items in another order, send order with their IDs in the order you want; the server handles the dates, so don\'t change dates to move items. To add between two items, add it, then send order. Use edits for small text changes.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'collection' => $collection,
						'parent_id' => $parent,
						'item_id' => array(
							'type' => 'integer',
							'description' => __( 'The item to change. Leave out to add a new one.', 'md' )
						),
						'fields' => array(
							'type' => 'object',
							'description' => __( 'The field values, using the field names from Describe site or Find items.', 'md' )
						),
						'edits' => $this->edits_input(),
						'date' => array(
							'type' => 'string',
							'description' => __( 'When it was posted, in the past or now, for backdating. Not for moving items.', 'md' )
						),
						'order' => array(
							'type' => 'array',
							'items' => array( 'type' => 'integer' ),
							'description' => __( 'Item IDs in reading order. Items left out stay where their dates put them.', 'md' )
						)
					),
					'required' => array( 'collection', 'parent_id' ),
					'additionalProperties' => false
				),
				'execute' => 'save_item',
				'permission' => 'can_save_item'
			),
			'trash-item' => array(
				'label' => __( 'Trash item', 'md' ),
				'description' => __( 'Moves an item to the Trash, or brings it back with restore. Nothing is deleted permanently.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'collection' => $collection,
						'item_id' => array( 'type' => 'integer' ),
						'restore' => array( 'type' => 'boolean' )
					),
					'required' => array( 'collection', 'item_id' ),
					'additionalProperties' => false
				),
				'execute' => 'trash_item',
				'permission' => 'can_trash_item',
				'destructive' => true
			)
		);
	}

	/**
	 * The Collection an ability was given.
	 *
	 * @since 6.2
	 */

	public function collection( $id ) {
		if ( ! md_collections( $id ) )
			return new WP_Error( 'md_abilities_collection', sprintf( __( 'No Collection named %s. Describe site lists them.', 'md' ), $id ) );

		return new md_collection( $id );
	}

	/**
	 * Check the user can read a parent's items.
	 *
	 * @since 6.2
	 */

	public function can_read_items( $input = array() ) {
		return $this->can_collection( $input, 'read', absint( $input['parent_id'] ?? 0 ) );
	}

	/**
	 * Check the user can add or change this item.
	 *
	 * @since 6.2
	 */

	public function can_save_item( $input = array() ) {
		$item_id = absint( $input['item_id'] ?? 0 );

		return $this->can_collection( $input, $item_id ? 'update' : 'create', absint( $input['parent_id'] ?? 0 ), $item_id, $input['fields'] ?? array() );
	}

	/**
	 * Check the user can trash or restore this item.
	 *
	 * @since 6.2
	 */

	public function can_trash_item( $input = array() ) {
		$item_id = absint( $input['item_id'] ?? 0 );

		return $this->can_collection( $input, 'trash', wp_get_post_parent_id( $item_id ), $item_id );
	}

	/**
	 * Ask the Collection for its permissions.
	 *
	 * @since 6.2
	 */

	protected function can_collection( $input, $action, $parent_id, $item_id = 0, $values = array() ) {
		$collection = $this->collection( $input['collection'] ?? '' );

		return is_wp_error( $collection ) ? $collection : $collection->can( $action, $parent_id, $item_id, $values );
	}

	/**
	 * A parent's items in reading order.
	 *
	 * @since 6.2
	 */

	public function find_items( $input = array() ) {
		$collection = $this->collection( $input['collection'] );
		$include = $input['include'] ?? array();
		$statuses = array( 'publish' );

		if ( in_array( 'drafts', $include, true ) )
			$statuses = array_merge( $statuses, array( 'draft', 'pending', 'private' ) );

		if ( in_array( 'trash', $include, true ) )
			$statuses[] = 'trash';

		return $this->items_result( $collection, absint( $input['parent_id'] ), $statuses );
	}

	/**
	 * Add or change an item, then put items in the order given.
	 *
	 * @since 6.2
	 */

	public function save_item( $input = array() ) {
		$collection = $this->collection( $input['collection'] );
		$parent_id = absint( $input['parent_id'] );
		$item_id = absint( $input['item_id'] ?? 0 );
		$fields = (array) ( $input['fields'] ?? array() );

		if ( ! empty( $input['edits'] ) ) {
			$field = $this->content_field( $collection );
			$fields[$field] = $this->edited_text( (string) $collection->get( $field, $item_id, '' ), $input['edits'] );

			if ( is_wp_error( $fields[$field] ) )
				return $fields[$field];
		}

		$fields = apply_filters( 'md_abilities_save_item', $fields, $collection->id, $item_id );

		if ( is_wp_error( $fields ) )
			return $this->result( $fields );

		if ( $fields || ( ! $item_id && empty( $input['order'] ) ) ) {
			$item_id = $collection->save_item( $parent_id, $item_id, $fields );

			if ( is_wp_error( $item_id ) )
				return $this->result( $item_id );
		}

		if ( ! empty( $input['date'] ) ) {
			$dates = $this->post_dates( $input['date'] );

			if ( is_wp_error( $dates ) )
				return $dates;

			wp_update_post( array_merge( array( 'ID' => $item_id, 'edit_date' => true ), $dates ) );
		}

		if ( ! empty( $input['order'] ) ) {
			$ordered = $collection->reorder( $parent_id, $input['order'] );

			if ( is_wp_error( $ordered ) )
				return $this->result( $ordered );
		}

		return array_merge( array( 'saved_id' => $item_id ), $this->items_result( $collection, $parent_id ) );
	}

	/**
	 * Trash or restore an item.
	 *
	 * @since 6.2
	 */

	public function trash_item( $input = array() ) {
		$collection = $this->collection( $input['collection'] );
		$item_id = absint( $input['item_id'] );

		$done = empty( $input['restore'] ) ? $collection->trash_item( $item_id ) : $collection->restore_item( $item_id );

		if ( is_wp_error( $done ) )
			return $this->result( $done );

		return array_merge( array( 'saved_id' => $item_id ), $this->items_result( $collection, wp_get_post_parent_id( $item_id ) ) );
	}

	/**
	 * The field holding an item's main text.
	 *
	 * @since 6.2
	 */

	protected function content_field( $collection ) {
		foreach ( $collection->fields as $id => $field )
			if ( $field['source'] === 'post_content' )
				return $id;

		return 'content';
	}

	/**
	 * Items as apps get them: reading order, position among the published
	 * ones, and every field.
	 *
	 * @since 6.2
	 */

	protected function items_result( $collection, $parent_id, $statuses = array( 'publish' ) ) {
		$items = array();
		$position = 0;
		$hidden = $statuses === array( 'publish' ) ? array() : $collection->hidden_items( $parent_id );

		foreach ( $collection->items( $parent_id, array( 'post_status' => $statuses, 'post__not_in' => $hidden ) )->posts as $post ) {
			$item = array(
				'id' => $post->ID,
				'status' => $post->post_status,
				'date' => get_post_time( 'c', false, $post ),
				'link' => get_permalink( $post ),
				'fields' => $collection->get( null, $post->ID, array() )
			);

			if ( $post->post_status === 'publish' )
				$item['position'] = ++$position;

			$items[] = $item;
		}

		return array(
			'collection' => $collection->id,
			'parent_id' => $parent_id,
			'published' => $position,
			'items' => $items
		);
	}

}

new md_item_abilities;
