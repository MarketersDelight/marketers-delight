<?php
/**
 * Create Site Design admin page.
 *
 * @todo Merge $this->colors lists and phase out $this->options
 * @since 5.0
 */

class md_colors extends md_api {

	public $data;
	public $colors;

	/**
	 * Include admin files.
	 *
	 * @since 4.7
	 */

	public function includes() {
		require_once( 'icons-functions.php' );
		require_once( 'design-functions.php' );
	}

	/**
	 * Actions, filters, and properties.
	 *
	 * @since 5.0
	 */

	public function actions() {
		$this->data = $this->_data();
		$this->colors = array(
			'site' => array(
				'bg_color' => array( 'type' => 'color' ),
				'links' => array( 'type' => 'color' ),
				'links_sec' => array( 'type' => 'color' ),
				'primary' => array( 'type' => 'color' ),
				'secondary' => array( 'type' => 'color' ),
				'tertiary' => array( 'type' => 'color' ),
				'action' => array( 'type' => 'color' ),
				'accent' => array( 'type' => 'color' ),
				'text' => array( 'type' => 'color' ),
				'text-sec' => array( 'type' => 'color' ),
				'headline' => array( 'type' => 'color' ),
				'headline-links' => array( 'type' => 'color' ),
				'button' => array( 'type' => 'color' ),
				'button-text' => array( 'type' => 'color' ),
				'button-sec' => array( 'type' => 'color' ),
				'button-sec-text' => array( 'type' => 'color' )
			),
			'content' => array(
				'bg_color' => array( 'type' => 'color' ),
				'border_color' => array( 'type' => 'color' )
			),
			'sidebar' => array(
				'bg_color' => array( 'type' => 'color' ),
				'text' => array( 'type' => 'color' ),
				'title' => array( 'type' => 'color' ),
				'title_link' => array( 'type' => 'color' ),
				'links' => array( 'type' => 'color' )
			),
			'footer' => array(
				'bg_color' => array( 'type' => 'color' ),
				'border_color' => array( 'type' => 'color' ),
				'text' => array( 'type' => 'color' ),
				'title' => array( 'type' => 'color' ),
				'links' => array( 'type' => 'color' )
			)
		);

		// Add default colors

		foreach ( $this->colors as $group => $options ) {
			foreach ( $options as $field => $fields ) {
				if ( ! empty( $this->data['defaults']['colors'][$group][$field] ) ) {
					$default_value = $this->data['defaults']['colors'][$group][$field];

					if ( is_array( $default_value ) )
						foreach ( $default_value as $sub => $sub_value )
							$this->colors[$group][$field][$sub]['default'] = esc_attr( $sub_value );
					else
						$this->colors[$group][$field]['default'] = esc_attr( $default_value );
				}
			}
		}
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		$fields = $this->colors;
		$fields['width']['site'] = array( 'type' => 'range' );
		$fields['width']['content'] = array( 'type' => 'range' );
		$fields['width']['sidebar'] = array( 'type' => 'range' );
		$fields = array_merge( $fields, array(
			'design' => array(
				'type' => 'checkbox',
				'options' => array( 'box_style' )
			)
		) );

		return array(
			'admin_page' => array(
				'name' => __( 'Design', 'md' ),
				'parent' => 'md_settings',
				'admin_header' => true,
				'hide_tab' => true,
				'fields' => $fields
			)
		);
	}

	/**
	 * Organize options with labels.
	 *
	 * @since 5.0
	 */

	public function options() {
		return array(
			'site' => array(
				'bg_color' => __( 'Background', 'md' ),
				'primary' => __( 'Primary', 'md' ),
				'secondary' => __( 'Secondary', 'md' ),
				'tertiary' => __( 'Tertiary', 'md' ),
				'action' => __( 'Action', 'md' ),
				'accent' => __( 'Accent', 'md' )
			),
			'text' => array(
				'text' => __( 'Text', 'md' ),
				'text-sec' => __( 'Text Secondary', 'md' ),
				'links' => __( 'Links', 'md' ),
				'links_sec' => __( 'Links Secondary', 'md' ),
				'headline' => __( 'Headline', 'md' ),
				'headline-links' => __( 'Headline Links', 'md' )
			),
			'button' => array(
				'button' => __( 'Background color', 'md' ),
				'button-text' => __( 'Text color', 'md' ),
				'button-sec' => __( 'Background color', 'md' ),
				'button-sec-text' => __( 'Text color', 'md' )
			),
			'sidebar' => array(
				'bg_color' => __( 'Background', 'md' ),
				'text' => __( 'Text', 'md' ),
				'title' => __( 'Title', 'md' ),
				'title_link' => __( 'Title Link', 'md' ),
				'links' => __( 'Links', 'md' )
			),
			'footer' => array(
				'bg_color' => __( 'Background', 'md' ),
				'border_color' => __( 'Border', 'md' ),
				'text' => __( 'Text', 'md' ),
				'title' => __( 'Title', 'md' ),
				'links' => __( 'Links', 'md' )
			)
		);
	}

	/**
	 * Create admin settings fields.
	 *
	 * @since 5.0
	 */

	public function admin_page() {
		$options = $this->options();
		$defaults = $this->data['defaults'];
		$values = $this->data['values'];
		$line_height = $values['typography']['body']['line_height']['desktop'];
		$layout_spacing = $line_height + round( $line_height / 2 );

		include( 'templates/admin-page.php' );
	}

}

new md_colors;
