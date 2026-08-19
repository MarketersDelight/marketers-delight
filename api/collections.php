<?php
/**
 * Read and write one registered MD Collection.
 *
 * @since 6.0
 */

class md_collection {

	public $id;
	public $parent;
	public $post_type;
	public $fields = array();
	public $count = false;
	public $per_page = 10;
	public $fields_callback;
	public $meta_fields = array();
	protected $sources = array(
		'meta',
		'post_title',
		'post_content',
		'post_date',
		'post_status',
		'post_author',
		'taxonomy'
	);

	/**
	 * Resolve and normalize one Collection definition.
	 *
	 * @since 6.0
	 */

	public function __construct( $id, $args = null ) {
		$args = wp_parse_args( isset( $args ) ? $args : md_collections( $id ), array(
			'parent' => '',
			'post_type' => '',
			'count' => false,
			'per_page' => 10,
			'fields' => array(),
			'fields_callback' => null
		) );

		$this->id = sanitize_key( $id );
		$this->parent = sanitize_key( $args['parent'] );
		$this->post_type = sanitize_key( $args['post_type'] );
		$this->count = (bool) $args['count'];
		$this->per_page = absint( $args['per_page'] );
		$this->fields_callback = $args['fields_callback'];
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
		$args['fields']['date'] = array(
			'type' => 'date',
			'label' => __( 'Update Date', 'md' ),
			'source' => 'post_date',
			'default' => current_time( 'Y-m-d' )
		);
		$this->fields = $this->normalize_fields( $args['fields'] );
	}

	/**
	 * Register standalone Collection fields with WordPress metadata.
	 *
	 * @since 6.0
	 */

	public function register() {
		foreach ( $this->meta_fields as $meta_key => $field ) {
			$args = array(
				'type' => $field['meta_type'],
				'single' => true,
				'sanitize_callback' => array( $this, 'sanitize_meta' )
			);

			if ( $field['default'] !== '' )
				$args['default'] = $field['default'];

			register_post_meta( $this->post_type, $meta_key, $args );
		}

		if ( $this->count ) {
			add_action( 'wp_after_insert_post', array( $this, 'item_saved' ), 10, 4 );
			add_action( 'deleted_post', array( $this, 'item_deleted' ), 10, 2 );
		}

		if ( is_admin() ) {
			$admin = new md_collection_admin( $this );
			$admin->register();
		}
	}

	/**
	 * Render this Collection's field controls.
	 *
	 * @since 6.0
	 */

	public function render_fields( $post_id = null, $meta_only = false ) {
		$values = array();

		foreach ( $this->fields as $id => $field )
			$values[$id] = $post_id ? $this->get( $id, $post_id, $field['default'] ) : $field['default'];

		$fields = new md_fields( array(
			'id' => $this->id,
			'clean_id' => $this->id,
			'prefix' => "md_collection_{$this->id}",
			'collection' => array(
				'object' => $this,
				'post_id' => absint( $post_id ),
				'values' => $values
			)
		) );

		ob_start();

		if ( $meta_only ) {
			foreach ( $this->meta_fields as $field )
				$fields->field( $field['id'], array() );
		}
		else {
			call_user_func( $this->fields_callback, $fields, $post_id ? get_post( $post_id ) : null, $values );

			echo '<div class="md-collection-field-columns mt-half">';
			$fields->field( 'status', array() );
			$fields->field( 'date', array() );
			echo '</div>';
		}

		return ob_get_clean();
	}

	/**
	 * Render one Collection item for an editor list.
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
	 * Query items belonging to a Collection parent.
	 *
	 * @since 6.0
	 */

	public function query( $parent_id, $args = array() ) {
		$args = wp_parse_args( $args, array(
			'post_status' => array( 'publish', 'future', 'draft', 'pending', 'private' ),
			'posts_per_page' => $this->per_page,
			'paged' => 1,
			'orderby' => 'date',
			'order' => 'DESC'
		) );
		$args['post_type'] = $this->post_type;
		$args['post_parent'] = absint( $parent_id );

		return new WP_Query( $args );
	}

	/**
	 * Count Collection items belonging to a parent.
	 *
	 * @since 6.0
	 */

	public function count_items( $parent_id, $post_status = null ) {
		$args = array( 'posts_per_page' => 1 );

		if ( isset( $post_status ) )
			$args['post_status'] = $post_status;

		return (int) $this->query( $parent_id, $args )->found_posts;
	}

	/**
	 * Refresh the cached published item count for a Collection parent.
	 *
	 * @since 6.0
	 */

	public function refresh_count( $parent_id ) {
		$count = $this->count_items( $parent_id, 'publish' );

		update_post_meta( $parent_id, "_md_{$this->post_type}_count", $count );

		return $count;
	}

	/**
	 * Refresh parent counts after Collection item writes.
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
	 * Refresh parent counts after permanent Collection item deletion.
	 *
	 * @since 6.0
	 */

	public function item_deleted( $post_id, $post ) {
		if ( $post->post_type === $this->post_type && $post->post_parent )
			$this->refresh_count( $post->post_parent );
	}

	/**
	 * Normalize field definitions and generate standalone meta keys.
	 *
	 * @since 6.0
	 */

	protected function normalize_fields( $fields ) {
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

			$is_number = in_array( $field['type'], array( 'number', 'range', 'upload' ), true );

			if ( ! $has_default ) {
				$field['default'] = '';

				if ( $field['type'] === 'terms' )
					$field['default'] = array();
			}

			if ( $field['source'] === 'meta' ) {
				$field['meta_key'] = strpos( $id, "{$this->post_type}_" ) === 0 ? $id : "{$this->post_type}_{$id}";
				$field['meta_type'] = $is_number ? 'integer' : 'string';
				$this->meta_fields[$field['meta_key']] = $field;
			}

			$normalized[$id] = $field;
		}

		return $normalized;
	}

	/**
	 * Sanitize registered Collection meta from its field definition.
	 *
	 * @since 6.0
	 */

	public function sanitize_meta( $value, $meta_key ) {
		return $this->sanitize_field( $this->meta_fields[$meta_key], $value );
	}

	/**
	 * Read one field or a full normalized Collection item.
	 *
	 * @since 6.0
	 */

	public function get( $field = null, $post_id = null, $default = null ) {
		$post_id = $post_id ? absint( $post_id ) : get_the_ID();

		if ( ! $post_id || get_post_type( $post_id ) !== $this->post_type )
			return $default;

		if ( isset( $field ) ) {
			$definition = $this->fields[$field] ?? array();

			return $definition ? $this->read_field( $post_id, $definition, $default ) : $default;
		}

		$item = array();

		foreach ( $this->fields as $id => $definition )
			$item[$id] = $this->read_field( $post_id, $definition, $definition['default'] );

		return $item;
	}

	/**
	 * Read a field from its registered source.
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
	 * Validate, sanitize, and save a normalized Collection item.
	 *
	 * @since 6.0
	 */

	public function save( $post_id, $values ) {
		if ( get_post_type( $post_id ) !== $this->post_type )
			return new WP_Error( 'md_collection_item', __( 'Collection item not found.', 'md' ) );

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

			$resolved[$id] = $value;
		}

		foreach ( $this->fields as $id => $field ) {
			if ( ! $field['required'] )
				continue;

			$value = array_key_exists( $id, $resolved ) ? $resolved[$id] : $this->read_field( $post_id, $field, $field['default'] );

			$is_empty = is_string( $value ) ? trim( wp_strip_all_tags( $value ) ) === '' : in_array( $value, array( '', null, array() ), true );

			if ( $is_empty )
				return new WP_Error( 'md_collection_required', sprintf( __( '%s is required.', 'md' ), $field['label'] ?? $id ), array( 'status' => 400 ) );
		}

		$post = array( 'ID' => $post_id );

		foreach ( $resolved as $id => $value ) {
			$field = $this->fields[$id];
			$source = $field['source'];

			if ( $source === 'meta' ) {
				if ( $value === '' )
					delete_post_meta( $post_id, $field['meta_key'] );
				else
					update_post_meta( $post_id, $field['meta_key'], $value );
			}
			elseif ( $source === 'taxonomy' ) {
				$terms = wp_set_object_terms( $post_id, (array) $value, $field['taxonomy'], false );

				if ( is_wp_error( $terms ) )
					return $terms;
			}
			else {
				if ( $field['type'] === 'date' && $source === 'post_date' ) {
					$value .= ' ' . get_post_time( 'H:i:s', false, $post_id );
					$post['edit_date'] = true;
				}

				$post[$source] = $value;
			}
		}

		$uses_post_title = false;

		foreach ( $this->fields as $field )
			if ( $field['source'] === 'post_title' ) {
				$uses_post_title = true;
				break;
			}

		if ( ! $uses_post_title )
			foreach ( $resolved as $id => $value )
				if ( $this->fields[$id]['source'] === 'post_content' ) {
					$post['post_title'] = $this->item_title( $resolved );
					break;
				}

		if ( count( $post ) > 1 ) {
			$saved = wp_update_post( $post, true );

			if ( is_wp_error( $saved ) )
				return $saved;
		}

		return $resolved;
	}

	/**
	 * Resolve a Collection item's native post title.
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
	 * Sanitize a field using its declared callback, source, or control type.
	 *
	 * @since 6.0
	 */

	protected function sanitize_field( $field, $value ) {
		if ( ! empty( $field['sanitize_callback'] ) )
			return call_user_func( $field['sanitize_callback'], $value );

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

			return intval( $value );
		}

		if ( $field['type'] === 'textarea' )
			return sanitize_textarea_field( $value );

		return sanitize_text_field( $value );
	}
}
