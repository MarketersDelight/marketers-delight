<?php
/**
 * Builds the rendering/display HTML of our own custom fields.
 * Excludes storage concerns across various screens with different
 * requirements, so markup can molded around storage types.
 *
 * @since 6.0
 */

abstract class md_fields_render {

	public $data;

	/**
	 * Set shared field data available to all renderers.
	 *
	 * @since 6.0
	 */

	public function __construct() {
		$this->data = new md_fields_data;
	}

	/**
	 * Resolve one field's storage context and render its control.
	 *
	 * @since 4.0
	 */

	abstract public function field( $field, $args );

	/**
	 * Render the common label, control, description, and field wrapper.
	 *
	 * @since 6.0
	 */

	protected function render_field( $name, $id, $option, $args, $wrap_classes ) {
		if ( isset( $args['label'] ) && $args['type'] !== 'group' && ! isset( $args['multiple'] ) )
			$this->label( $id, $args );

		$wrap_classes[] = 'md-field-' . esc_attr( $args['type'] );

		if ( isset( $args['wrap_classes'] ) )
			$wrap_classes[] = $args['wrap_classes'];

		if ( isset( $args['hidden'] ) )
			$wrap_classes[] = 'md-hidden';

		echo '<div class="' . esc_attr( join( ' ', $wrap_classes ) ) . '">';

		$this->field_type( $args['type'], $name, $id, $option, $args );

		if ( isset( $args['description'] ) && $args['type'] !== 'builder' )
			$this->description( $args['description'] );

		echo '</div>';
	}

	/**
	 * Get field based on type and trickle data down to display field HTML.
	 *
	 * @since 4.7
	 */

	protected function field_type( $type, $name, $id, $option, $args ) {
		if ( in_array( $type, array( 'text', 'date' ), true ) )
			$this->text( $name, $id, $option, $args );

		if ( $type === 'textarea' )
			$this->textarea( $name, $id, $option, $args );

		if ( $type === 'number' )
			$this->number( $name, $id, $option, $args );

		if ( $type === 'code' )
			$this->code( $name, $id, $option, $args );

		if ( $type === 'url' )
			$this->url( $name, $id, $option, $args );

		if ( $type === 'checkbox' )
			$this->checkbox( $name, $id, $option, $args );

		if ( $type === 'radio' )
			$this->radio( $name, $id, $option, $args );

		if ( $type === 'select' )
			$this->select( $name, $id, $option, $args );

		if ( $type === 'range' )
			$this->range( $name, $id, $option, $args );

		if ( $type === 'color' )
			$this->color( $name, $id, $option, $args );

		if ( $type === 'editor' )
			$this->editor( $name, $id, $option, $args );

		if ( in_array( $type, array( 'media', 'upload' ), true ) )
			$this->upload( $name, $id, $option, $args );

		if ( $type === 'group' )
			$this->group( $name, $id, $option, $args );

		if ( $type === 'builder' )
			$this->builder( $name, $id, $option, $args );

		if ( $type === 'terms' )
			$this->terms( $name, $id, $option, $args );
	}

	/**
	 * Easily create a label for fields.
	 *
	 * @since 4.0
	 */

	public function label( $id, $args ) {
		include md_template( 'admin/fields/label', true );
	}

	/**
	 * Easily create a description for fields.
	 *
	 * @since 5.0
	 */

	public function description( $description ) {
		echo '<p class="description">' . wp_kses_data( $description ) . '</p>';
	}

	/**
	 * Outputs a simple text input field with attributes.
	 *
	 * @since 4.0
	 */

	protected function text( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/text', true );
	}

	/**
	 * Outputs a simple textarea.
	 *
	 * @since 4.0
	 */

	protected function textarea( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/textarea', true );
	}

	/**
	 * Outputs a simple number input field with attributes.
	 *
	 * @since 4.0
	 */

	protected function number( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/number', true );
	}

	/**
	 * Outputs a simple textarea to paste code into.
	 *
	 * @since 4.0
	 */

	protected function code( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/code', true );
	}

	/**
	 * Outputs a simple text field for URL entry.
	 *
	 * @since 4.0
	 */

	protected function url( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/url', true );
	}

	/**
	 * Outputs a multi-checkbox field.
	 *
	 * @since 4.0
	 */

	protected function checkbox( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/checkbox', true );
	}

	/**
	 * Outputs single-select radio fields.
	 *
	 * @since 5.0
	 */

	protected function radio( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/radio', true );
	}

	/**
	 * Outputs a simple select field.
	 *
	 * @since 4.0
	 */

	protected function select( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/select', true );
	}

	/**
	 * Create a range field with reset value.
	 *
	 * @since 5.0
	 */

	protected function range( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/range', true );
	}

	/**
	 * Outputs upload field. Only built to support
	 * media, will be expanding to other file types soon.
	 *
	 * @since 4.8.4
	 */

	protected function upload( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/upload', true );
	}

	/**
	 * Outputs a simple text input field with attributes.
	 *
	 * @since 4.0
	 */

	protected function color( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/color', true );
	}

	/**
	 * Return terms hierarchy category structure.
	 *
	 * @since 5.3.1
	 */

	protected function terms( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/terms', true );
	}

	/**
	 * WP Editor field. Accepts _WP_Editors::parse_settings( $settings ).
	 *
	 * @since 5.3.1
	 */

	protected function editor( $name, $id, $option, $args ) {
		if ( isset( $args['init'] ) ) {
			$args['classes'] = 'md-toggle-wp-editor';
			$this->textarea( $name, $id, $option, $args );
		}
		else {
			$settings = wp_parse_args( $args, array(
				'textarea_name' => $name,
				'textarea_rows' => 10
			) );
			wp_editor( $option, $id, $settings );
		}

		wp_enqueue_editor();
	}

}
