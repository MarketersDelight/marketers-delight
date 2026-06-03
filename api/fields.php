<?php
/**
 * Organize different admin HTML fields and their respective data
 * into this class. Pulls data from MDAPI $this->field() method and
 * is used exclusively to create admin options throughout MD.
 *
 * @since 4.7
 */

class md_fields {

	protected $_id;
	protected $_clean_id;
	protected $_prefix;
	protected $_option;
	public $_get_screen;
	public $data;

	/**
	 * Set properties of instance.
	 *
	 * @since 5.0
	 */

	public function __construct( $args ) {
		$this->_id = $args['id'];
		$this->_clean_id = $args['clean_id'];
		$this->_prefix = $args['prefix'];
		$this->_option = isset( $args['option'] ) ? $args['option'] : 'marketers_delight';
		$this->data = new md_fields_data;
	}

	/**
	 * Versatile in nature, this is the method called to load every field
	 * within the MD API. To support many nested option formats field()
	 * switches up how it processes data based on the user's needs.
	 * In its simplest form, field accepts the field name ($field) and an array
	 * of arguments ($args), but to create different option groups per page
	 * the argument names get switched up between $parent, $fields, and $args.
	 *
	 * @since 4.0
	 */

	public function field( $field, $args ) {
		$wrap_classes = array( 'md-field' );
		$clean_id = $this->_clean_id;

		if ( isset( $args['id'] ) )
			$clean_id = $args['id'];

		$name = "{$this->_option}[$clean_id]";
		$id = "{$this->_option}_{$clean_id}";
		$args['field'] = $field;
		$screen = $this->_get_screen;

		// Determine screen context and build name/id/settings key

		if ( $screen['is_post'] || wp_doing_ajax() )
			$setting = get_post_meta( get_the_ID(), $this->_option, true );
		elseif ( $screen['is_term'] )
			$setting = get_term_meta( $screen['screen_id'], $this->_option, true );
		elseif ( $screen['is_user'] ) {
			$user_id = isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : 1;
			$setting = get_user_meta( $user_id, $this->_option, true );
		}
		else {
			$setting = get_option( $this->_option );
			$page = $screen['page'];
			$page_types = apply_filters( 'md_admin_groups', array() );

			if ( ! empty( $page_types[$page] ) ) {
				$taxonomy = $screen['is_taxonomy'] ? $screen['md_tab'] : '';
				$page_id  = md_clean_id( $page );

				if ( $clean_id !== $page_id ) {
					$setting = ! empty( $setting[$page_id] ) ? $setting[$page_id] : array();
					$name = "{$this->_option}[$page_id]";
					$id = "{$this->_option}_{$page_id}";

					if ( $taxonomy ) {
						$setting = ! empty( $setting[$taxonomy] ) ? $setting[$taxonomy] : array();
						$name .= "[$taxonomy]";
						$id .= "_{$taxonomy}";
					}

					$name .= "[$clean_id]";
					$id .= "_{$clean_id}";
				}
				elseif ( $taxonomy ) {
					$name = "{$this->_option}[$clean_id][$taxonomy]";
					$id = "{$this->_option}_{$clean_id}_{$taxonomy}";
					$setting = array( $clean_id => ! empty( $setting[$clean_id][$taxonomy] ) ? $setting[$clean_id][$taxonomy] : array() );
				}
			}
		}

		// Walk array and build attributes

		$group = ! empty( $setting[$clean_id] ) ? $setting[$clean_id] : array();

		if ( is_array( $field ) ) {
			foreach ( $field as $key ) {
				$name .= "[$key]";
				$id .= "_{$key}";
				$group = isset( $group[$key] ) ? $group[$key] : '';
			}

			$option = $group;
		}
		else {
			$name .= "[$field]";
			$id .= "_{$field}";
			$option = isset( $setting[$clean_id][$field] ) ? $setting[$clean_id][$field] : '';
		}

		// Render output

		if ( isset( $args['label'] ) && $args['type'] !== 'group' && ! isset( $args['multiple'] ) )
			$this->label( $id, $args );

		$wrap_classes[] = 'md-field-' . esc_attr( $args['type'] );

		if ( isset( $args['wrap_classes'] ) )
			$wrap_classes[] = $args['wrap_classes'];

		if ( isset( $args['hidden'] ) )
			$wrap_classes[] = 'md-hidden';

		$wrap_classes = join( ' ', $wrap_classes );

		echo '<div class="' . esc_attr( $wrap_classes ) . '">';

		$this->field_type( $args['type'], $name, $id, $option, $args );

		if ( isset( $args['description'] ) && $args['type'] !== 'builder' )
			$this->description( $args['description'] );

		echo '</div>';
	}

	/**
	 * Get field values based on current screen.
	 *
	 * @since 5.0.9
	 */

	public function get_field( $keys, $default = null ) {
		$c = 0;
		$screen = $this->_get_screen;

		// Determine page context and set option level

		if ( $screen['is_post'] )
			$option = md_post_meta();
		elseif ( $screen['is_term'] )
			$option = md_term_meta();
		elseif ( $screen['is_user'] )
			$option = md_user_meta();
		else {
			$page = $screen['page'];
			$page_types = apply_filters( 'md_admin_groups', array() );
			$option = md_setting();

			if ( ! empty( $page_types[$page] ) ) {
				$taxonomy = $screen['is_taxonomy'] ? $screen['md_tab'] : '';
				$page_id = md_clean_id( $page );
				$is_child = $this->_clean_id !== $page_id;

				if ( $is_child ) {
					$option = ! empty( $option[$page_id] ) ? $option[$page_id] : array();

					if ( $taxonomy )
						$option = ! empty( $option[$taxonomy] ) ? $option[$taxonomy] : array();
				}
				elseif ( $taxonomy )
					$option = ! empty( $option[$page_id][$taxonomy] ) ? $option[$page_id][$taxonomy] : array();
			}
		}

		// Walk options array

		if ( isset( $keys ) ) {
			if ( is_string( $keys ) )
				$keys = (array) $keys;
			foreach ( $keys as $key ) {
				$option = ! empty( $option[$key] ) ? $option[$key] : ( $c == 0 ? array() : '' );
				$c++;
			}
		}

		// Set option or default

		if ( empty( $option ) && isset( $default ) )
			$option = $default;

		return $option;
	}

	/**
	 * Get field values based on current admin screen.
	 *
	 * @since 4.1
	 */

	public function module( $keys, $default = null ) {
		if ( is_string( $keys ) )
			$keys = (array) $keys;

		$screen = $this->_get_screen;

		// Return if post meta

		if ( $screen['is_post'] ) {
			array_unshift( $keys, $this->_clean_id );

			return md_post_meta( $keys, null, $default );
		}

		// Return if term meta

		if ( $screen['is_term'] && ! empty( $screen['screen_id'] ) ) {
			array_unshift( $keys, $this->_clean_id );

			return md_term_meta( $keys, $screen['screen_id'], $default );
		}

		// Determine if admin group setting, taxonomy group, or just normal setting

		$page = $screen['page'];
		$page_types = apply_filters( 'md_admin_groups', array() );

		if ( ! empty( $page_types[$page] ) ) {
			$page_id = md_clean_id( $page );
			$prefix = array( $page_id );

			if ( $screen['is_taxonomy'] )
				$prefix[] = $screen['md_tab'];

			if ( $page_id !== $this->_clean_id )
				$prefix[] = $this->_clean_id;

			$keys = array_merge( $prefix, $keys );
		}
		else array_unshift( $keys, $this->_clean_id );

		return md_setting( $keys, $default );
	}

	/**
	 * Get field based on type and trickle data down to display field HTML.
	 *
	 * @since 4.7
	 */

	protected function field_type( $type, $name, $id, $option, $args ) {
		if ( $type == 'text' )
			$this->text( $name, $id, $option, $args );

		if ( $type == 'textarea' )
			$this->textarea( $name, $id, $option, $args );

		if ( $type == 'number' )
			$this->number( $name, $id, $option, $args );

		if ( $type == 'code' )
			$this->code( $name, $id, $option, $args );

		if ( $type == 'url' )
			$this->url( $name, $id, $option, $args );

		if ( $type == 'checkbox' )
			$this->checkbox( $name, $id, $option, $args );

		if ( $type == 'radio' )
			$this->radio( $name, $id, $option, $args );

		if ( $type == 'select' )
			$this->select( $name, $id, $option, $args );

		if ( $type == 'range' )
			$this->range( $name, $id, $option, $args );

		if ( $type == 'color' )
			$this->color( $name, $id, $option, $args );

		if ( $type == 'editor' )
			$this->editor( $name, $id, $option, $args );

		if ( in_array( $type, array( 'media', 'upload' ) ) )
			$this->upload( $name, $id, $option, $args );

		if ( $type == 'group' )
			$this->group( $name, $id, $option, $args );

		if ( $type == 'builder' )
			$this->builder( $name, $id, $option, $args );

		if ( $type == 'terms' )
			$this->terms( $name, $id, $option, $args );
	}

	/**
	 * Easily create a label for fields.
	 *
	 * @since 4.0
	 */

	protected function label( $id, $args ) {
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
	 * Output MD Save button with optional flush rules.
	 *
	 * @since 5.0
	 */

	public function save( $label = null, $args = null ) {
		include md_template( 'admin/fields/save', true );
	}

	/**
	 * Devices toggle controls, adds device classes to .md.wrap
	 * to toggle controls for different screen sizes.
	 *
	 * @since 5.0
	 */

	public function devices( $spacing = true ) {
		include md_template( 'admin/fields/devices', true );
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
	 * Wrapper for clone/group fields.
	 *
	 * @since 5.0
	 */

	protected function group( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/group', true );
	}

	/**
	 * When registering parent/child admin groups, render tab interface.
	 *
	 * @since 6.0
	 */

	public function settings_group( $context ) {
		include md_template( 'admin/fields/group-settings', true );
	}

	/**
	 * Create group typography fields.
	 *
	 * @since 5.0
	 */

	public function typography( $field = array(), $args = null ) {
		include md_template( 'admin/fields/typography', true );
	}

	/**
	 * Use this Field Group to display Link admin fields.
	 *
	 * @since 6.0
	 */

	public function link_fields( $args = array() ) {
		include md_template( 'admin/fields/link', true );
	}

	/**
	 * Build generic Page fields for standard components of a web page.
	 *
	 * @since 6.0
	 */

	public function page_fields() {
		include md_template( 'admin/fields/page', true );
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

	/**
	 * A valet method to render the Byline Position field
	 * when adding custom byline items.
	 *
	 * @since 6.0
	 */

	public function byline_fields( $group, $args = array() ) {
		if ( isset( $args['dropin'] ) )
			$this->field( array( 'builder', $group, 'dropin' ), array(
				'id' => 'byline',
				'type' => 'text',
				'hidden' => true,
				'default' => $args['dropin']
			) );

		$this->field( array( 'builder', $group, 'position' ), array(
			'id' => 'byline',
			'type' => 'select',
			'label' => __( 'Position', 'md' ),
			'wrap_classes' => 'md-sep-micro',
			'options' => array(
				'before_title' => __( 'Before Title', 'md' ),
				'after_title' =>  __( 'After Title', 'md' ),
				'before_post' => __( 'Before Post', 'md' ),
				'after_post' => __( 'After Post', 'md' )
			)
		) );
	}

	/**
	 * Builder templates, including main wrapper, field group render,
	 * and some valet methods for different elements.
	 *
	 * @since 6.0
	 */

	protected function builder( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/builder', true );
	}

	protected function builder_field( $key, $group, $type, $fields ) {
		$icon = ! empty( $fields['icon'] ) ? $fields['icon'] : 'move';
		$color = ! empty( $fields['color'] ) ? $fields['color'] : '';

		include md_template( 'admin/fields/builder-field', true );
	}

	protected function builder_search( $group ) {
		include md_template( 'admin/fields/builder-search', true );
	}

	protected function builder_link( $group ) {
		$this->link_fields( array( 'group' => array( 'builder', $group ) ) );
	}

	protected function builder_menu( $group ) {
		$sanitize = new md_sanitize;
		$design = new md_design;
		$menus = $sanitize->menus();
		$values = $design->values();

		include md_template( 'admin/fields/builder-menu', true );
	}

}