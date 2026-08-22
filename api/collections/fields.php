<?php
/**
 * Render registered Collection fields from a Collection's own values
 * instead of MD option storage.
 *
 * @since 6.0
 */

class md_collection_fields extends md_fields_render {

	protected $collection;
	protected $post_id;
	protected $values;

	public function __construct( $collection, $post_id, $values ) {
		parent::__construct();

		$this->collection = $collection;
		$this->post_id = absint( $post_id );
		$this->values = $values;
	}

	/**
	 * Render one registered Collection field control.
	 *
	 * @since 6.0
	 */

	public function field( $field, $args ) {
		$definition = $this->collection->fields[$field];
		$args = wp_parse_args( $args, $definition );
		$suffix = $this->post_id ? $this->post_id : 'new';
		$name = "md_collection[{$this->collection->id}][$field]";
		$id = "md_collection_{$this->collection->id}_{$suffix}_{$field}";
		$option = $this->values[$field];
		$attributes = $args['attributes'] ?? array();
		$attributes['data-md-collection-field'] = $field;
		$attributes['data-md-collection-type'] = $definition['type'];

		if ( $definition['required'] )
			$attributes['aria-required'] = 'true';

		$args['field'] = $field;
		$args['attributes'] = $attributes;

		echo '<div class="md md-collection-control mt-small">';

		$this->render_field( $name, $id, $option, $args, array( 'md-field', 'md-collection-field' ) );

		echo '</div>';
	}

}
