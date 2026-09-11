<?php
/**
 * Shapes the data of markup rendered on custom fields. The same fields
 * can be used across multiple WordPress APIs, and flat/structured levels
 * of fields for groups/repeater/builder/collections need their own handling.
 *
 * @since 4.7
 */

class md_fields extends md_fields_render {

	protected $_id;
	protected $_clean_id;
	protected $_prefix;
	protected $_option;
	public $_get_screen;
	public $post_type = array();

	/**
	 * Set properties of instance.
	 *
	 * @since 5.0
	 */

	public function __construct( $args ) {
		parent::__construct();

		$this->_id = $args['id'];
		$this->_clean_id = $args['clean_id'];
		$this->_prefix = $args['prefix'];
		$this->_option = isset( $args['option'] ) ? $args['option'] : 'marketers_delight';
		$this->post_type = $args['post_type'] ?? array();
	}

	/**
	 * Determine the admin screeen context to render the correct field
	 * settings based on the admin pages needs. We also check if a field
	 * belongs to an admin group, nested, or a taxonomy settings screen.
	 *
	 * @since 6.0
	 */

	protected function get_context() {
		$screen = $this->_get_screen;

		$context = array(
			'is_post' => $screen['is_post'],
			'is_term' => $screen['is_term'],
			'is_user' => $screen['is_user'],
			'is_group' => false,
			'page_id' => null,
			'is_child' => false,
			'taxonomy' => ''
		);

		if ( $context['is_post'] || $context['is_term'] || $context['is_user'] )
			return $context;

		$page = $screen['page'];
		$page_types = apply_filters( 'md_admin_groups', array() );

		if ( ! empty( $page_types[$page] ) ) {
			$context['is_group'] = true;
			$context['page_id']  = md_clean_id( $page );
			$context['is_child'] = $this->_clean_id !== $context['page_id'];
			$context['taxonomy'] = $screen['is_taxonomy'] ? $screen['md_tab'] : '';
		}

		return $context;
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
		$has_option = false;

		if ( isset( $args['id'] ) )
			$clean_id = $args['id'];

		$name = "{$this->_option}[$clean_id]";
		$id = "{$this->_option}_{$clean_id}";
		$args['field'] = $field;
		$screen = $this->_get_screen;

		// Determine screen context and build name/id/settings key

		$context = $this->get_context();

		if ( $context['is_post'] || wp_doing_ajax() ) {
			$setting = get_post_meta( get_the_ID(), $this->_option, true );

			if ( is_string( $field ) && $this->is_standalone( $field, $clean_id ) ) {
				$standalone = get_post_meta( get_the_ID(), $field, true );

				if ( $standalone !== '' ) {
					$setting = is_array( $setting ) ? $setting : array();
					$setting[$clean_id][$field] = $standalone;
				}
			}
		}
		elseif ( $context['is_term'] )
			$setting = get_term_meta( $screen['screen_id'], $this->_option, true );
		elseif ( $context['is_user'] ) {
			$user_id = isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : 1;
			$setting = get_user_meta( $user_id, $this->_option, true );
		}
		else {
			$setting = $this->stored_option();

			if ( $context['is_group'] ) {
				$taxonomy = $context['taxonomy'];
				$page_id  = $context['page_id'];

				if ( $context['is_child'] ) {
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

		$has_group = is_array( $setting ) && array_key_exists( $clean_id, $setting );
		$group = $has_group && is_array( $setting[$clean_id] ) ? $setting[$clean_id] : array();

		if ( is_array( $field ) ) {
			$has_option = $has_group;

			foreach ( $field as $key ) {
				$name .= "[$key]";
				$id .= "_{$key}";
				$has_option = $has_option && is_array( $group ) && array_key_exists( $key, $group );
				$group = $has_option ? $group[$key] : '';
			}

			$option = $group;
		}
		else {
			$name .= "[$field]";
			$id .= "_{$field}";
			$has_option = $has_group && is_array( $setting[$clean_id] ) && array_key_exists( $field, $setting[$clean_id] );
			$option = $has_option ? $setting[$clean_id][$field] : '';
		}

		if ( $args['type'] === 'builder' && ! isset( $args['save_empty'] ) )
			$args['save_empty'] = $has_option;

		if ( isset( $args['inherit'] ) )
			$args = $this->prepare_inheritance( $field, $option, $args );

		$this->render_field( $name, $id, $option, $args, $wrap_classes );
	}

	/**
	 * Prepare inherited field presentation without replacing the raw local value.
	 * Empty text/number/select controls continue to mean "inherit", while an
	 * inherited checkbox submits the inverse of its effective parent value.
	 *
	 * @since 6.0
	 */

	protected function prepare_inheritance( $field, $option, $args ) {
		$type = $args['type'];
		$inherit = is_array( $args['inherit'] ) ? $args['inherit'] : array();
		$default = array_key_exists( 'default', $inherit ) ? $inherit['default'] : null;

		if ( $type === 'checkbox' ) {
			$option = is_array( $option ) ? $option : array();

			foreach ( $args['options'] as $key => $label ) {
				if ( ! isset( $inherit[$key] ) || ! is_array( $inherit[$key] ) )
					continue;

				$option_parent = $this->parent_value( array_merge( (array) $field, array( $key ) ), false );

				if ( ! $option_parent['available'] )
					continue;

				$enabled = (bool) $option_parent['value'];
				$target = $enabled ? 0 : 1;
				$target_label = $enabled ? ( $inherit[$key]['off'] ?? $label ) : ( $inherit[$key]['on'] ?? $label );
				$has_override = array_key_exists( $key, $option );

				$args['_inherit'][$key] = array(
					'value' => $target,
					'label' => $target_label,
					'checked' => $has_override && intval( $option[$key] ) === $target,
					'parent' => intval( $enabled )
				);
			}

			return $args;
		}

		$parent = $this->parent_value( (array) $field, $default );

		if ( in_array( $type, array( 'text', 'number' ), true ) ) {
			$value = $parent['available'] ? $parent['value'] : $default;

			if ( is_scalar( $value ) )
				$args['placeholder'] = $value;
		}
		elseif ( $type === 'select' && $parent['available'] ) {
			$value = $parent['value'];

			if ( is_scalar( $value ) && isset( $args['options'] ) && is_array( $args['options'] ) && array_key_exists( $value, $args['options'] ) ) {
				$format = isset( $inherit['format'] ) ? $inherit['format'] : __( 'Use default (%s)', 'md' );
				$args['empty_label'] = sprintf( $format, $args['options'][$value] );
			}
		}

		return $args;
	}

	/**
	 * Resolve the effective value immediately above the current admin screen.
	 * The availability flag distinguishes a root field from a falsey parent.
	 *
	 * @since 6.0
	 */

	protected function parent_value( $keys, $default = null ) {
		$screen = $this->_get_screen;
		$context = $this->get_context();
		$result = array(
			'available' => false,
			'value' => $default
		);

		if ( $context['is_user'] )
			return $result;

		if ( $context['is_post'] ) {
			if ( empty( $screen['post_type'] ) )
				return $result;

			array_unshift( $keys, $this->_clean_id );
			$result['available'] = true;
			$result['value'] = md_post_type_field( $keys, $default, $screen['post_type'] );

			return $result;
		}

		if ( $context['is_term'] ) {
			if ( empty( $screen['post_type'] ) || empty( $screen['taxonomy'] ) )
				return $result;

			array_unshift( $keys, $this->_clean_id );
			$value = md_taxonomy_field( $keys, null, $screen['post_type'], $screen['taxonomy'] );

			$result['available'] = true;

			if ( is_null( $value ) )
				$value = md_post_type_field( $keys, $default, $screen['post_type'] );

			$result['value'] = $value;

			return $result;
		}

		if ( ! $context['is_group'] )
			return $result;

		$field_keys = $keys;

		if ( $context['is_child'] )
			array_unshift( $field_keys, $this->_clean_id );

		if ( $context['taxonomy'] ) {
			$result['available'] = true;
			$result['value'] = md_post_type_field( $field_keys, $default, $context['page_id'] );

			return $result;
		}

		$parent = md_post_type_settings_parent( $context['page_id'] );

		if ( ! $parent )
			return $result;

		$result['available'] = true;
		$result['value'] = md_post_type_field( $field_keys, $default, $parent );

		return $result;
	}

	/**
	 * Get stored field values based on current screen.
	 *
	 * @since 5.0.9
	 */

	public function get_field( $keys, $default = null ) {
		// Determine page context and set option level

		$context = $this->get_context();
		$option = $this->stored_option();

		if ( $context['is_post'] )
			$option = md_post_meta();
		elseif ( $context['is_term'] )
			$option = md_term_meta();
		elseif ( $context['is_user'] )
			$option = md_user_meta();
		elseif ( $context['is_group'] ) {
			$taxonomy = $context['taxonomy'];
			$page_id = $context['page_id'];

			if ( $context['is_child'] ) {
				$option = ! empty( $option[$page_id] ) ? $option[$page_id] : array();

				if ( $taxonomy )
					$option = ! empty( $option[$taxonomy] ) ? $option[$taxonomy] : array();
			}
			elseif ( $taxonomy )
				$option = ! empty( $option[$page_id][$taxonomy] ) ? $option[$page_id][$taxonomy] : array();
		}

		// Walk options array

		foreach ( (array) $keys as $key ) {
			if ( ! is_array( $option ) || ! array_key_exists( $key, $option ) )
				return $default;

			$option = $option[$key];
		}

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
		$context = $this->get_context();

		// Return if post meta

		if ( $context['is_post'] ) {
			array_unshift( $keys, $this->_clean_id );

			return md_post_meta( $keys, null, $default );
		}

		// Return for term and taxonomy inheritance

		if ( $context['is_term'] && ! empty( $screen['screen_id'] ) ) {
			$field_keys = array_merge( array( $this->_clean_id ), $keys );
			$value = md_term_meta( $field_keys, $screen['screen_id'], null );

			if ( is_null( $value ) )
				$value = md_taxonomy_field( $field_keys, null, $screen['post_type'], $screen['taxonomy'] );

			if ( is_null( $value ) )
				$value = md_post_type_field( $field_keys, $default, $screen['post_type'] );

			return $value;
		}

		// Determine if admin group setting, taxonomy group, or just normal setting

		if ( $context['is_group'] ) {
			$field_keys = $keys;

			if ( $context['is_child'] )
				array_unshift( $field_keys, $this->_clean_id );

			if ( $context['taxonomy'] ) {
				$value = md_taxonomy_field( $field_keys, null, $context['page_id'], $context['taxonomy'] );

				if ( ! is_null( $value ) )
					return $value;
			}

			return md_post_type_field( $field_keys, $default, $context['page_id'] );
		}

		array_unshift( $keys, $this->_clean_id );

		return md_option( $this->_option, $keys, $default );
	}

	/**
	 * Check if a field is saving as its own post meta key.
	 *
	 * @since 6.0
	 */

	protected function is_standalone( $field, $clean_id ) {
		$meta_box = md_register( 'meta_boxes' )[$clean_id] ?? array();

		return ! empty( $meta_box['fields'][$field]['standalone'] );
	}

	/**
	 * Read raw option data for admin controls without merging defaults.
	 *
	 * @since 6.0
	 */

	protected function stored_option() {
		$option = get_option( $this->_option, array() );

		return is_array( $option ) ? $option : array();
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
	 * Renders two checkbox groups for visibility conditions — one for PHP-gated
	 * conditions (check), one for CSS-based visibility (class). Reusable across
	 * any admin field template. Field paths are passed directly so this works
	 * in any context regardless of field group structure.
	 *
	 * @since 6.0
	 */

	public function visibility_condition( $field ) {
		$options = array();

		foreach ( md_visibility_conditions() as $key => $item )
			$options[$key] = $item['label'];

		$this->field( $field, array(
			'type' => 'checkbox',
			'multi' => true,
			'label' => __( 'Visibility', 'md' ),
			'options' => $options
		) );
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
	 * Use this method to call a repeatable link group fields.
	 *
	 * @since 6.0
	 */

	public function links_group( $field = 'links', $args = array() ) {
		$this->field( $field, array_merge( array(
			'type' => 'group',
			'style' => 'boxes',
			'subtitle' => true,
			'new_label' => __( 'Edit link name...', 'md' ),
			'fields' => $this->data->links( array( 'sort' => 'save' ) ),
			'callback' => function( $group_id, $group ) {
				$this->link_fields( array(
					'group' => array_merge( (array) $group_id, array( $group ) )
				) );
			}
		), $args ) );
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
				'entry_top' => __( 'Entry Top', 'md' ),
				'before_title' => __( 'Before Title', 'md' ),
				'after_title' =>  __( 'After Title', 'md' ),
				'before_content' => __( 'Before Content', 'md' ),
				'entry_footer' => __( 'Entry Footer', 'md' )
			)
		) );
	}

	/**
	 * Render admin page header pattern often used on post type admin pages.
	 *
	 * @since 6.0
	 */

	public function admin_header( $views = array() ) {
		$post_type = $this->post_type;
		$plural = $post_type['plural'] ?? ( $post_type['singular'] ?? ucfirst( $post_type['name'] ?? $this->_clean_id ) );

		$views['archive'] = array_merge( array(
			'title' => sprintf( __( '%s Settings', 'md' ), $plural ),
			'description' => sprintf( __( 'Adjust the global settings for the %s post type. Most settings apply to the archive page, and categories inherit these defaults. Override these settings from any Edit Category or Post screen.', 'md' ), "<strong>$plural</strong>" )
		), $views['archive'] ?? array() );

		if ( ! empty( $post_type['taxonomy'] ) )
			$views['term'] = array_merge( array(
				'title' => sprintf( __( '%s Category Settings', 'md' ), $plural ),
				'description' => sprintf( __( 'Set the defaults for every category page in the %s post type. Settings will be inherited from the post type settings, and you can override further from any Edit Category screen.', 'md' ), "<strong>$plural</strong>" )
			), $views['term'] ?? array() );

		include md_template( 'admin/fields/admin-header', true );
	}

	/**
	 * Wrapper for clone/group fields. Group and builder controls read
	 * nested MD option storage, so only md_fields can render them.
	 *
	 * @since 5.0
	 */

	protected function group( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/group', true );
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
		$design = new md_design;
		$menus = $this->data->menus();
		$values = $design->values();

		include md_template( 'admin/fields/builder-menu', true );
	}

}
