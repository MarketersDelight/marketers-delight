<?php
/**
 * Collections are a bold interface added to the post editor that
 * let's users create posts from a single post. This featureset
 * is used in Drop-ins suchas the Bookshelf and Stream where creating
 * micro posts within a page are useful.
 *
 * @since 6.0
 */

class md_collection {

	public $id;
	public $parent;
	public $post_type;
	public $fields_callback;
	public $order = 'DESC';
	public $fields = array();
	public $meta_fields = array();
	protected $sources = array( 'meta', 'post_title', 'post_content', 'post_date', 'post_status', 'post_author', 'taxonomy' );

	/**
	 * Setup data passed into this Collection and make sure we know
	 * what we are working with moving downwards.
	 *
	 * @since 6.0
	 */

	public function __construct( $id, $args = null ) {
		$args = wp_parse_args( isset( $args ) ? $args : md_collections( $id ), array(
			'parent' => '',
			'post_type' => '',
			'order' => 'DESC',
			'fields' => array(),
			'fields_callback' => null
		) );

		// Class properties

		$this->id = sanitize_key( $id );
		$this->parent = sanitize_key( $args['parent'] );
		$this->post_type = sanitize_key( $args['post_type'] );
		$this->order = strtoupper( $args['order'] ) === 'ASC' ? 'ASC' : 'DESC';
		$this->fields_callback = $args['fields_callback'];

		// Status and date are editable fields that are added to each collection

		if ( ! isset( $args['fields']['status'] ) )
			$args['fields']['status'] = array(
				'type' => 'select',
				'label' => __( 'Status', 'md' ),
				'source' => 'post_status',
				'options' => array(
					'publish' => __( 'Published', 'md' ),
					'draft' => __( 'Draft', 'md' ),
					'pending' => __( 'Pending Review', 'md' ),
					'private' => __( 'Private', 'md' )
				),
				'default' => 'publish'
			);

		if ( ! isset( $args['fields']['date'] ) )
			$args['fields']['date'] = array(
				'type' => 'date',
				'label' => __( 'Update Date', 'md' ),
				'source' => 'post_date',
				'default' => current_time( 'Y-m-d' )
			);

		$this->fields = $this->clean_fields( $args['fields'] );
	}

	/**
	 * Keep the parent's count of items current as they change.
	 *
	 * @since 6.0
	 */

	public function register() {
		add_action( 'wp_after_insert_post', array( $this, 'item_saved' ), 10, 4 );
		add_action( 'deleted_post', array( $this, 'item_deleted' ), 10, 2 );
	}

	/**
	 * Render a Collection item's fields, custom and predefined. Set $meta_only on
	 * post screens, not the quick editor.
	 *
	 * @since 6.0
	 */

	public function fields( $post_id = null, $meta_only = false ) {
		$values = array();

		foreach ( $this->fields as $id => $field )
			$values[$id] = $post_id ? $this->get( $id, $post_id, $field['default'] ) : $field['default'];

		$fields = new md_collection_fields( $this, $post_id, $values );

		if ( $meta_only ) {
			foreach ( $this->meta_fields as $field )
				$fields->field( $field['id'], array() );

			return;
		}

		call_user_func( $this->fields_callback, $fields, $post_id ? get_post( $post_id ) : null, $values );

		// Status and date go last unless the Collection placed them itself

		$remaining = array_diff( array( 'status', 'date' ), array_keys( $fields->rendered ) );

		if ( ! $remaining )
			return;

		echo '<div class="md-collection-field-columns mt-half">';

		foreach ( $remaining as $field )
			$fields->field( $field, array() );

		echo '</div>';
	}

	/**
	 * Render an individual collection item list, which includes
	 * user defined fields.
	 *
	 * @since 6.0
	 */

	public function render_item( $item ) {
		$collection = $this;
		$item = get_post( $item );
		$labels = get_post_type_object( $this->post_type )->labels;
		$singular = $labels->singular_name;
		$preview = wp_trim_words( wp_strip_all_tags( $item->post_content ), 35 );
		$date = get_the_date( '', $item );
		$status = get_post_status_object( $item->post_status )->label;
		$permalink = is_post_type_viewable( $this->post_type ) ? get_permalink( $item ) : '';
		$edit_link = get_edit_post_link( $item->ID );

		ob_start();
		include md_template( 'admin/fields/collection', true );
		return ob_get_clean();
	}

	/**
	 * Get an item collection by a post query.
	 *
	 * @since 6.0
	 */

	public function query( $parent_id, $args = array() ) {
		$args = wp_parse_args( $args, array(
			'post_status' => array( 'publish', 'future', 'draft', 'pending', 'private' ),
			'posts_per_page' => -1,
			'orderby' => 'date',
			'order' => 'DESC'
		) );
		$args['post_type'] = $this->post_type;
		$args['post_parent'] = absint( $parent_id );

		return new WP_Query( $args );
	}

	/**
	 * Get a parent's items the way readers see them: published, in the
	 * Collection's order, oldest or newest first.
	 *
	 * @since 6.2
	 */

	public function items( $parent_id, $args = array() ) {
		return $this->query( $parent_id, wp_parse_args( $args, array(
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => array( 'date' => $this->order, 'ID' => $this->order ),
			'order' => $this->order,
			'no_found_rows' => true
		) ) );
	}

	/**
	 * Count number of items in a collection.
	 *
	 * @since 6.0
	 */

	public function count_items( $parent_id, $post_status = null ) {
		$args = array(
			'posts_per_page' => 1,
			'fields' => 'ids'
		);

		if ( isset( $post_status ) )
			$args['post_status'] = $post_status;

		return (int) $this->query( $parent_id, $args )->found_posts;
	}

	/**
	 * Change the latest count of collection items after an official update.
	 *
	 * @since 6.0
	 */

	public function refresh_count( $parent_id ) {
		$count = $this->count_items( $parent_id, 'publish' );

		update_post_meta( $parent_id, "_md_{$this->post_type}_count", $count );

		return $count;
	}

	/**
	 * The actual method hooked into collection altering actions.
	 *
	 * @since 6.0
	 */

	public function item_saved( $post_id, $post, $update, $post_before ) {
		if ( $post->post_type !== $this->post_type )
			return;

		$parents = array( $post->post_parent );

		if ( $post_before )
			$parents[] = $post_before->post_parent;

		foreach ( array_unique( array_filter( array_map( 'absint', $parents ) ) ) as $parent_id )
			$this->refresh_count( $parent_id );
	}

	/**
	 * Refresh parent counts after item deletion.
	 *
	 * @since 6.0
	 */

	public function item_deleted( $post_id, $post ) {
		if ( $post->post_type === $this->post_type && $post->post_parent )
			$this->refresh_count( $post->post_parent );
	}

	/**
	 * Run fields data through formats we know we need later to save
	 * confusion later. Ensure we only keep data we need and not accept
	 * from an unknown source and proper prefixing.
	 *
	 * @since 6.0
	 */

	protected function clean_fields( $fields ) {
		$normalized = array();

		foreach ( (array) $fields as $id => $field ) {
			$id = sanitize_key( $id );

			if ( ! $id )
				continue;

			$has_default = array_key_exists( 'default', $field );
			$field = wp_parse_args( $field, array(
				'type' => 'text',
				'source' => 'meta',
				'required' => false,
				'default' => null
			) );

			$field['id'] = $id;
			$field['source'] = sanitize_key( $field['source'] );

			if ( ! in_array( $field['source'], $this->sources, true ) )
				continue;

			if ( ! $has_default ) {
				$field['default'] = '';

				if ( $field['type'] === 'terms' )
					$field['default'] = array();
			}

			if ( $field['source'] === 'meta' ) {
				$field['meta_key'] = strpos( $id, "{$this->post_type}_" ) === 0 ? $id : "{$this->post_type}_{$id}";
				$this->meta_fields[$field['meta_key']] = $field;
			}

			$normalized[$id] = $field;
		}

		return $normalized;
	}

	/**
	 * The top level helper for accessing collections data.
	 *
	 * @since 6.0
	 */

	public function get( $field = null, $post_id = null, $default = null ) {
		$post_id = $post_id ? absint( $post_id ) : get_the_ID();

		if ( ! $post_id || get_post_type( $post_id ) !== $this->post_type )
			return $default;

		if ( isset( $field ) ) {
			$definition = $this->fields[$field] ?? array();

			return $definition ? $this->read_field( $post_id, $definition, $default ?? $definition['default'] ) : $default;
		}

		$item = array();

		foreach ( $this->fields as $id => $definition )
			$item[$id] = $this->read_field( $post_id, $definition, $definition['default'] );

		return $item;
	}

	/**
	 * Collections access various WP core fields and meta, so
	 * figure out which source to pull from.
	 *
	 * @since 6.0
	 */

	protected function read_field( $post_id, $field, $default = null ) {
		$source = $field['source'];

		if ( $source === 'meta' ) {
			$value = get_post_meta( $post_id, $field['meta_key'], true );

			return $value === '' ? $default : $value;
		}

		if ( $source === 'taxonomy' ) {
			$terms = wp_get_post_terms( $post_id, $field['taxonomy'], array( 'fields' => 'names' ) );

			return is_wp_error( $terms ) ? $default : $terms;
		}

		$value = get_post_field( $source, $post_id, 'raw' );

		if ( $value === '' || $value === null )
			return $default;

		if ( $field['type'] === 'date' && $source === 'post_date' )
			return substr( $value, 0, 10 );

		return $value;
	}

	/**
	 * Sanitize and validate Collection item values before anything is saved.
	 * New items have no post yet, so required fields fall back to defaults.
	 *
	 * @since 6.1
	 */

	public function validate( $values, $post_id = 0 ) {
		$values = is_array( $values ) ? $values : array();
		$resolved = array();

		foreach ( $values as $id => $value ) {
			if ( ! isset( $this->fields[$id] ) )
				continue;

			$field = $this->fields[$id];
			$value = $this->sanitize_field( $field, $value );

			if ( $field['type'] === 'date' && $value !== '' && ( ! preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $value, $date ) || ! wp_checkdate( $date[2], $date[3], $date[1], $value ) ) )
				return new WP_Error( 'md_collection_date', sprintf( __( '%s must be a valid date.', 'md' ), $field['label'] ?? $field['id'] ), array( 'status' => 400 ) );

			if ( $field['source'] === 'post_status' && ! isset( $field['options'][$value] ) )
				return new WP_Error( 'md_collection_status', __( 'Select a valid publication status.', 'md' ), array( 'status' => 400 ) );

			if ( $field['type'] === 'select' && $value !== '' && ! empty( $field['options'] ) && ! in_array( (string) $value, md_sanitize::choices( $field['options'] ), true ) )
				return new WP_Error( 'md_collection_option', sprintf( __( '%s isn\'t one of its options.', 'md' ), $field['label'] ?? $id ), array( 'status' => 400 ) );

			if ( $field['source'] === 'post_author' && $value !== '' && (int) $value !== get_current_user_id() && ! current_user_can( get_post_type_object( $this->post_type )->cap->edit_others_posts ) )
				return new WP_Error( 'md_collection_author', __( 'You cannot assign this item to another user.', 'md' ), array( 'status' => 403 ) );

			$resolved[$id] = $value;
		}

		foreach ( $this->fields as $id => $field ) {
			if ( ! $field['required'] )
				continue;

			if ( array_key_exists( $id, $resolved ) )
				$value = $resolved[$id];
			else
				$value = $post_id ? $this->read_field( $post_id, $field, $field['default'] ) : $field['default'];

			$is_empty = is_string( $value ) ? trim( wp_strip_all_tags( $value ) ) === '' : in_array( $value, array( '', null, array() ), true );

			if ( $is_empty )
				return new WP_Error( 'md_collection_required', sprintf( __( '%s is required.', 'md' ), $field['label'] ?? $id ), array( 'status' => 400 ) );
		}

		return $resolved;
	}

	/**
	 * Validate, sanitize, and save a Collection item.
	 *
	 * @since 6.0
	 */

	public function save( $post_id, $values ) {
		if ( get_post_type( $post_id ) !== $this->post_type )
			return new WP_Error( 'md_collection_item', __( 'Collection item not found.', 'md' ) );

		$values = is_array( $values ) ? $values : array();
		$resolved = $this->validate( $values, $post_id );

		if ( is_wp_error( $resolved ) )
			return $resolved;

		$saved = $this->save_terms_and_meta( $post_id, $resolved );

		if ( is_wp_error( $saved ) )
			return $saved;

		$post = $this->post_fields( $resolved, $post_id );

		if ( $post ) {
			$saved = wp_update_post( wp_slash( array_merge( array( 'ID' => $post_id ), $post ) ), true );

			if ( is_wp_error( $saved ) )
				return $saved;
		}

		return $resolved;
	}

	/**
	 * The values stored outside the post itself.
	 *
	 * @since 6.2
	 */

	protected function save_terms_and_meta( $post_id, $resolved ) {
		foreach ( $resolved as $id => $value ) {
			$field = $this->fields[$id];

			if ( $field['source'] === 'meta' ) {
				if ( $value === '' )
					delete_post_meta( $post_id, $field['meta_key'] );
				else
					update_post_meta( $post_id, $field['meta_key'], wp_slash( $value ) );
			}
			elseif ( $field['source'] === 'taxonomy' ) {
				$terms = wp_set_object_terms( $post_id, (array) $value, $field['taxonomy'], false );

				if ( is_wp_error( $terms ) )
					return $terms;
			}
		}

		return true;
	}

	/**
	 * The values stored on the post itself. A date keeps the item's time,
	 * or now for a new item, and an item without a title field takes one
	 * from its content.
	 *
	 * @since 6.2
	 */

	protected function post_fields( $resolved, $post_id = 0 ) {
		$post = array();

		foreach ( $resolved as $id => $value ) {
			$field = $this->fields[$id];

			if ( in_array( $field['source'], array( 'meta', 'taxonomy' ), true ) )
				continue;

			if ( $field['type'] === 'date' && $field['source'] === 'post_date' ) {
				if ( $value === '' )
					continue;

				$value .= ' ' . ( $post_id ? get_post_time( 'H:i:s', false, $post_id ) : current_time( 'H:i:s' ) );
				$post['edit_date'] = true;
			}

			$post[$field['source']] = $value;
		}

		if ( isset( $post['post_content'] ) && ! in_array( 'post_title', wp_list_pluck( $this->fields, 'source' ), true ) )
			$post['post_title'] = $this->item_title( $resolved );

		return $post;
	}

	/**
	 * Whether the current user can read, create, update or trash this
	 * Collection's items under a parent. Returns true or a WP_Error.
	 *
	 * @since 6.2
	 */

	public function can( $action, $parent_id, $item_id = 0, $values = array() ) {
		if ( ! $this->parent || ! $this->post_type || get_post_type( $parent_id ) !== $this->parent )
			return new WP_Error( 'md_collection_parent', __( 'Collection parent not found.', 'md' ), array( 'status' => 404 ) );

		if ( ! current_user_can( 'edit_post', $parent_id ) )
			return new WP_Error( 'md_collection_permission', __( 'You cannot edit this Collection.', 'md' ), array( 'status' => 403 ) );

		if ( $item_id ) {
			if ( get_post_type( $item_id ) !== $this->post_type || wp_get_post_parent_id( $item_id ) !== $parent_id )
				return new WP_Error( 'md_collection_item', __( 'Collection item not found.', 'md' ), array( 'status' => 404 ) );

			if ( ! current_user_can( $action === 'trash' ? 'delete_post' : 'edit_post', $item_id ) )
				return new WP_Error( 'md_collection_permission', __( 'You cannot modify this Collection item.', 'md' ), array( 'status' => 403 ) );
		}

		if ( ! in_array( $action, array( 'create', 'update' ), true ) )
			return true;

		$post_type = get_post_type_object( $this->post_type );

		if ( ! $post_type )
			return true;

		if ( $action === 'create' && ! current_user_can( $post_type->cap->create_posts ) )
			return new WP_Error( 'md_collection_permission', __( 'You cannot create Collection items.', 'md' ), array( 'status' => 403 ) );

		$status = null;

		foreach ( (array) $values as $id => $value )
			if ( isset( $this->fields[$id] ) && $this->fields[$id]['source'] === 'post_status' )
				$status = is_scalar( $value ) ? sanitize_key( $value ) : null;

		if ( ( ! $item_id || in_array( $status, array( 'publish', 'private', 'future' ), true ) ) && ! current_user_can( $post_type->cap->publish_posts ) )
			return new WP_Error( 'md_collection_permission', __( 'You cannot publish Collection items.', 'md' ), array( 'status' => 403 ) );

		return true;
	}

	/**
	 * Create an item under a published parent, or update one when given
	 * its ID. Returns the item's ID or a WP_Error.
	 *
	 * @since 6.2
	 */

	public function save_item( $parent_id, $item_id, $values ) {
		$values = is_array( $values ) ? $values : array();

		if ( ! $item_id && get_post_status( $parent_id ) !== 'publish' )
			return new WP_Error( 'md_collection_parent_status', __( 'Publish the parent before adding Collection items.', 'md' ), array( 'status' => 400 ) );

		$terms_permission = $this->can_assign_terms( $values );

		if ( is_wp_error( $terms_permission ) )
			return $terms_permission;

		if ( $item_id ) {
			$saved = $this->save( $item_id, $values );

			return is_wp_error( $saved ) ? $saved : $item_id;
		}

		$resolved = $this->validate( $values );

		if ( is_wp_error( $resolved ) )
			return $resolved;

		$item_id = wp_insert_post( wp_slash( array_merge( array(
			'post_type' => $this->post_type,
			'post_parent' => $parent_id,
			'post_status' => 'publish',
			'post_author' => get_current_user_id(),
			'post_title' => $this->item_title( $resolved )
		), $this->post_fields( $resolved ) ) ), true );

		if ( is_wp_error( $item_id ) )
			return $item_id;

		$saved = $this->save_terms_and_meta( $item_id, $resolved );

		if ( is_wp_error( $saved ) ) {
			wp_delete_post( $item_id, true );

			return $saved;
		}

		return $item_id;
	}

	/**
	 * Move an item to the Trash.
	 *
	 * @since 6.2
	 */

	public function trash_item( $item_id ) {
		if ( ! wp_trash_post( $item_id ) )
			return new WP_Error( 'md_collection_trash', __( 'The Collection item could not be moved to Trash.', 'md' ), array( 'status' => 500 ) );

		return true;
	}

	/**
	 * Bring an item back from the Trash with the status it had. WordPress
	 * restores to draft by default.
	 *
	 * @since 6.2
	 */

	public function restore_item( $item_id ) {
		if ( ! self::untrash( $item_id ) )
			return new WP_Error( 'md_collection_restore', __( 'The Collection item could not be restored.', 'md' ), array( 'status' => 500 ) );

		return true;
	}

	/**
	 * Take a post out of the Trash with the status it had before.
	 *
	 * @since 6.2
	 */

	public static function untrash( $post_id ) {
		add_filter( 'wp_untrash_post_status', 'wp_untrash_post_set_previous_status', 10, 3 );
		$restored = wp_untrash_post( $post_id );
		remove_filter( 'wp_untrash_post_status', 'wp_untrash_post_set_previous_status', 10 );

		return $restored;
	}

	/**
	 * Put items in a new reading order. Items sort by date, so out-of-order ones
	 * get a date next to their new neighbor: the fewest changes, never in the
	 * future.
	 *
	 * @since 6.2
	 */

	public function reorder( $parent_id, $ids ) {
		$dates = wp_list_pluck( $this->items( $parent_id, array(
			'post_status' => array( 'publish', 'draft', 'pending', 'private' )
		) )->posts, 'post_date', 'ID' );
		$ids = array_values( array_intersect( array_map( 'absint', (array) $ids ), array_keys( $dates ) ) );

		if ( $this->order === 'DESC' )
			$ids = array_reverse( $ids );

		$times = array();

		foreach ( $ids as $id )
			$times[] = strtotime( $dates[$id] . ' UTC' );

		$later = $earlier = $times;

		for ( $i = 1; $i < count( $later ); $i++ )
			$later[$i] = max( $later[$i], $later[$i - 1] + 1 );

		for ( $i = count( $earlier ) - 2; $i >= 0; $i-- )
			$earlier[$i] = min( $earlier[$i], $earlier[$i + 1] - 1 );

		$is_past = ! $later || max( $later ) <= strtotime( current_time( 'mysql' ) . ' UTC' );
		$new = $is_past && count( array_diff_assoc( $later, $times ) ) < count( array_diff_assoc( $earlier, $times ) ) ? $later : $earlier;

		foreach ( array_diff_assoc( $new, $times ) as $i => $time ) {
			$date = gmdate( 'Y-m-d H:i:s', $time );
			$saved = wp_update_post( array(
				'ID' => $ids[$i],
				'post_date' => $date,
				'post_date_gmt' => get_gmt_from_date( $date ),
				'edit_date' => true
			), true );

			if ( is_wp_error( $saved ) )
				return $saved;
		}

		return true;
	}

	/**
	 * Check assign and create capabilities before changing an item's terms.
	 *
	 * @since 6.0
	 */

	protected function can_assign_terms( $values ) {
		foreach ( $this->fields as $id => $field ) {
			if ( $field['source'] !== 'taxonomy' || ! array_key_exists( $id, $values ) )
				continue;

			$taxonomy = get_taxonomy( $field['taxonomy'] ?? '' );

			if ( ! $taxonomy || ! current_user_can( $taxonomy->cap->assign_terms ) )
				return new WP_Error( 'md_collection_terms', __( 'You cannot assign these terms.', 'md' ), array( 'status' => 403 ) );

			foreach ( (array) $values[$id] as $term )
				if ( ! term_exists( $term, $taxonomy->name ) && ! current_user_can( $taxonomy->cap->manage_terms ) )
					return new WP_Error( 'md_collection_terms', __( 'You cannot create new terms.', 'md' ), array( 'status' => 403 ) );
		}

		return true;
	}

	/**
	 * Not all collection items come with titles, so this is a way to
	 * fill out potentially useful titles (not sure the best, though...).
	 *
	 * @since 6.0
	 */

	public function item_title( $values ) {
		foreach ( $this->fields as $id => $field )
			if ( $field['source'] === 'post_title' )
				return isset( $values[$id] ) ? sanitize_text_field( $values[$id] ) : '';

		foreach ( $this->fields as $id => $field ) {
			if ( $field['source'] !== 'post_content' || empty( $values[$id] ) )
				continue;

			$title = wp_trim_words( wp_strip_all_tags( $values[$id] ), 12, '' );

			if ( $title )
				return $title;
		}

		return __( 'Collection Item', 'md' );
	}

	/**
	 * Sanitize specific fields based on their declared type.
	 *
	 * @since 6.0
	 */

	protected function sanitize_field( $field, $value ) {
		if ( $field['source'] === 'post_content' )
			return wp_kses_post( $value );

		if ( $field['type'] === 'terms' ) {
			$values = is_array( $value ) ? $value : explode( ',', (string) $value );
			$values = array_map( 'sanitize_text_field', $values );

			return array_values( array_unique( array_filter( array_map( 'trim', $values ) ) ) );
		}

		if ( $field['type'] === 'upload' && is_array( $value ) )
			$value = $value['id'] ?? '';

		if ( in_array( $field['type'], array( 'number', 'range', 'upload' ), true ) ) {
			if ( $value === '' || $value === null )
				return '';

			$value = intval( $value );

			if ( isset( $field['max'] ) )
				$value = min( $value, (int) $field['max'] );

			return isset( $field['min'] ) ? max( $value, (int) $field['min'] ) : $value;
		}

		if ( $field['type'] === 'textarea' )
			return sanitize_textarea_field( $value );

		return sanitize_text_field( $value );
	}
}
