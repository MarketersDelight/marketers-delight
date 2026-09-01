<?php
/**
 * Renders a call to action select field with filterable
 * admin controls.
 *
 * @since 6.0
 */

class md_page_cta extends md_api {

	/**
	 * Return a list of CTA types with save schema and admin render callback.
	 *
	 * @since 6.0
	 */

	public function cta_types() {
		$types = array(
			'links' => array(
				'label' => __( 'Links', 'md' ),
				'fields' => array(
					'links_sort' => array( 'type' => 'text' ),
					'links' => array(
						'type' => 'group',
						'fields' => $this->fields->data->links( array( 'sort' => 'save' ) )
					)
				),
				'admin_callback' => array( $this->fields, 'links_group' )
			),
			'custom' => array(
				'label' => __( 'Custom HTML', 'md' ),
				'fields' => array(
					'custom_html' => array( 'type' => 'code' )
				),
				'admin_callback' => function() {
					$this->fields->field( 'custom_html', array(
						'type' => 'code',
						'label' => __( 'Custom HTML', 'md' )
					) );
				}
			)
		);

		return apply_filters( 'md_page_cta_types', $types );
	}

	/**
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		$this->name = __( 'Call to Action', 'md' );

		$args = array(
			'name' => $this->name,
			'child_of' => array( 'hero', 'page_settings' ),
			'fields' => $this->fields()
		);

		return array(
			'admin_page' => $args,
			'term' => $args,
			'meta_box' => $args
		);
	}

	/**
	 * Set options for save.
	 *
	 * @since 4.3.5
	 */

	public function fields() {
		$types = $this->cta_types();
		$save = array(
			'page_cta' => array(
				'type' => 'select',
				'options' => array_keys( $types )
			)
		);

		foreach ( $types as $type_id => $fields )
			if ( ! empty( $fields['fields'] ) && is_array( $fields['fields'] ) )
				$save = array_merge( $save, $fields['fields'] );

		return $save;
	}

	/**
	 * General fields admin template.
	 *
	 * @since 4.7
	 */

	public function admin_fields() {
		$prefix = $this->_prefix;
		$cta_type = $this->fields->module( 'page_cta' );
		$cta_types = $this->cta_types();

		$cta_options = array();
		foreach ( $cta_types as $type_id => $type )
			$cta_options[$type_id] = ! empty( $type['label'] ) ? $type['label'] : $type_id;

		echo "<div class=\"md-$this->_clean_id md-tab-content\">";

		include md_template( 'admin/page-cta', true );

		echo '</div>';
	}
}

new md_page_cta;
