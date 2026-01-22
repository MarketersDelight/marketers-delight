<?php
/**
 * Organize different admin HTML fields and their respective data
 * into this class. Pulls data from MDAPI $this->field() method and
 * is used exclusively to create admin options throughout MD.
 *
 * @since 4.7
 */

class md_fields {

	public $_id;
	public $_clean_id;
	public $_prefix;
	public $_option;
	public $data;

	/**
	 * Set properties of instance.
	 *
	 * @since 5.0
	 */

	public function __construct( $args ) {
		$this->_id       = $args['id'];
		$this->_clean_id = $args['clean_id'];
		$this->_prefix   = $args['prefix'];
		$this->_option   = isset( $args['option'] ) ? $args['option'] : 'marketers_delight';
		$this->data      = new md_fields_data;
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
		$page = $has_parent = false;
		$wrap_classes = array( 'md-field' );
		$clean_id = $this->_clean_id;
		if ( isset( $args['id'] ) )
			$clean_id = $args['id'];
		$name = "{$this->_option}[$clean_id]";
		$id = "{$this->_option}_{$clean_id}";
		$screen = get_current_screen();
		$args['field'] = $field;

		if ( wp_doing_ajax() || in_array( $screen->base, array( 'post', 'post-new' ) ) )
			$setting = get_post_meta( get_the_ID(), $this->_option, true );
		elseif ( $screen->base == 'term' ) {
			$tag_id = esc_attr( $_GET['tag_ID'] );
			$setting = get_term_meta( $tag_id, $this->_option, true );
		}
		elseif ( in_array( $screen->base, array( 'profile', 'user-edit' ) ) && isset( $args['user_meta'] ) ) {
			$user_meta = $args['user_meta'];
			$user_id = esc_attr( $user_meta->data->ID );
			$setting = get_user_meta( $user_id, $this->_option, true );
		}
		else {
			$setting = get_option( $this->_option );
			$page = esc_attr( $_GET['page'] );
			$page_types = apply_filters( 'md_admin_groups', array() );

			if ( ! empty( $page_types[$page] ) ) {
				if ( $clean_id !== md_clean_id( $page ) ) {
					$page = md_clean_id( $page );
					$setting = ! empty( $setting[$page] ) ? $setting[$page] : '';
					$name = "{$this->_option}[{$page}][$clean_id]";
					$id = "{$this->_option}_{$page}_{$clean_id}";
				}
			}
		}

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

		if ( isset( $args['label'] ) && $args['type'] !== 'group' )
			$this->label( $id, $args );

		$wrap_classes[] = 'md-field-' . esc_attr( $args['type'] );

		if ( isset( $args['wrap_classes'] ) )
			$wrap_classes[] = $args['wrap_classes'];

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
		$screen = get_current_screen();

		if ( in_array( $screen->base, array( 'post', 'post-new' ) ) )
			$option = md_post_meta();
		elseif ( $screen->base == 'term' )
			$option = md_term_meta();
		elseif ( in_array( $screen->base, array( 'profile', 'user-edit' ) ) )
			$option = md_user_meta();
		elseif ( ! empty( $_GET['page'] ) ) {
			$page = esc_attr( $_GET['page'] );
			$page_types = apply_filters( 'md_admin_groups', array() );
			$option = md_setting();

			if ( ! empty( $page_types[$page] ) ) {
				if ( $this->_clean_id !== md_clean_id( $page ) ) {
					$page = md_clean_id( $page );
					$option = ! empty( $option[$page] ) ? $option[$page] : array();
				}
			}
		}

		if ( isset( $keys ) ) {
			if ( is_string( $keys ) )
				$keys = (array) $keys;
			foreach ( $keys as $key ) {
				$option = ! empty( $option[$key] ) ? $option[$key] : ( $c == 0 ? array() : '' );
				$c++;
			}
		}

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

		$c = 0;
		$fields = array();
		$screen = get_current_screen();
		$page_types = apply_filters( 'md_admin_groups', array() );

		$page = isset( $_GET['page'] ) ? esc_attr( $_GET['page'] ) : '';
		$tag_id = isset( $_GET['tag_ID'] ) ? esc_attr( $_GET['tag_ID'] ) : '';

		if ( in_array( $screen->base, array( 'post', 'post-new' ) ) ) {
			array_unshift( $keys, $this->_clean_id );
			$fields = md_post_meta( $keys, null, $default );
		}
		elseif ( $screen->base == 'term' && ! empty( $tag_id ) ) {
			array_unshift( $keys, $this->_clean_id );
			$fields = md_term_meta( $keys, $tag_id, $default );
		}
		elseif ( ! empty( $page_types[$page] ) ) {
			$page_id = md_clean_id( $page );

			if ( $page_id == $this->_clean_id )
				array_unshift( $keys, $page_id );
			else
				array_unshift( $keys, $page_id, $this->_clean_id );

			$fields = md_setting( $keys, $default );
		}
		else {
			array_unshift( $keys, $this->_clean_id );
			$fields = md_setting( $keys, $default );
		}

		return $fields;
	}

	/**
	 * Get field based on type and trickle data down to display field HTML.
	 *
	 * @since 4.7
	 */

	public function field_type( $type, $name, $id, $option, $args ) {
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

		if ( $type == 'hidden' )
			$this->hidden( $name, $id, $option, $args );

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

		if ( $type == 'upload' )
			$this->upload( $name, $id, $option, $args );

		if ( $type == 'group' )
			$this->group( $name, $id, $option, $args );

		if ( $type == 'builder' )
			$this->builder( $name, $id, $option, $args );

		if ( $type == 'terms' )
			$this->terms( $name, $id, $option, $args );

		#deprecated 4.8.4
		if ( $type == 'media' )
			$this->media( $name, $id, $option, $args );
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
		echo '<p class="description">' . md_text_field( $description ) . '</p>';
	}

	/**
	 * Outputs a simple text input field with attributes.
	 *
	 * @since 4.0
	 */

	public function text( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/text', true );
	}

	/**
	 * Outputs a simple textarea.
	 *
	 * @since 4.0
	 */

	public function textarea( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/textarea', true );
	}

	/**
	 * Outputs a simple number input field with attributes.
	 *
	 * @since 4.0
	 */

	public function number( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/number', true );
	}

	/**
	 * Outputs a simple textarea to paste code into.
	 *
	 * @since 4.0
	 */

	public function code( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/code', true );
	}

	/**
	 * Outputs a simple text field for URL entry.
	 *
	 * @since 4.0
	 */

	public function url( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/url', true );
	}

	/**
	 * Outputs a multi-checkbox field.
	 *
	 * @since 4.0
	 */

	public function checkbox( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/checkbox', true );
	}

	/**
	 * Outputs single-select radio fields.
	 *
	 * @since 5.0
	 */

	public function radio( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/radio', true );
	}

	/**
	 * Outputs a simple select field.
	 *
	 * @since 4.0
	 */

	public function select( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/select', true );
	}

	/**
	 * Create a range field with reset value.
	 *
	 * @since 5.0
	 */

	public function range( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/range', true );
	}

	/**
	 * Outputs upload field. Only built to support
	 * media, will be expanding to other file types soon.
	 *
	 * @since 4.8.4
	 */

	public function upload( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/uploader', true );
	}

	/**
	 * Outputs a simple text input field with attributes.
	 *
	 * @since 4.0
	 */

	public function color( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/color', true );
	}

	/**
	 * WP Editor field. Accepts _WP_Editors::parse_settings( $settings ).
	 *
	 * @since 5.3.1
	 */

	public function editor( $name, $id, $option, $args ) {
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
	 * Return terms hierarchy category structure.
	 *
	 * @since 5.3.1
	 */

	public function terms( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/terms', true );
	}

	/**
	 * Disable the automatic output of the clone button in $this->group()
	 * to call this anywhere in custom settings controls.
	 *
	 * @since 5.0
	 */

	public function clone_button( $field, $args = null ) {
		$label = ! empty( $args['button_text'] ) ? $args['button_text'] : __( 'Add New', 'md' );
		$classes = ! empty( $args['classes'] ) ? ' ' . $args['classes'] : '';
	?>
		<span class="md-clone-add button<?php echo esc_attr( $classes ); ?>" data-clone-group="<?php echo esc_attr( "{$this->_id}_" .  $field ); ?>"><?php echo $label; ?></span>
	<?php }

	/**
	 * Wrapper for clone/group fields.
	 *
	 * @since 5.0
	 */

	public function group( $name, $id, $option, $args ) {
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
	 * Apply Builder template. Holds Elements tray for dragging new
	 * elements and programmatically display drop areas.
	 *
	 * @since 6.0
	 */

	public function builder( $name, $id, $option, $args ) {
		include md_template( 'admin/fields/builder', true );
	}

	/**
	 * Render callback for each Builder Field Group.
	 *
	 * @since 6.0
	 */

	public function builder_field( $key, $group, $type, $fields ) {
		$icon = ! empty( $fields['icon'] ) ? $fields['icon'] : 'move';
		$color = ! empty( $fields['color'] ) ? $fields['color'] : '';

		include md_template( 'admin/fields/builder-field', true );
	}

	/**
	 * Builder Fields.
	 *
	 * @since 6.0
	 */

	public function builder_menu( $group ) {
		$sanitize = new md_sanitize;
		$design = new md_design;
		$menus = $sanitize->menus();
		$values = $design->values();
		include md_template( 'admin/fields/builder-menu', true );
	}

	public function builder_search( $group ) {
		include md_template( 'admin/fields/builder-search', true );
	}

	public function builder_link( $group ) {
		$this->link_fields( array( 'group' => array( 'builder', $group ) ) );
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
				'before_headline' => __( 'Before Headline', 'md' ),
				'after_headline' =>  __( 'After Headline', 'md' ),
				'before_post' =>  __( 'Before Post', 'md' ),
				'after_post' =>  __( 'After Post', 'md' )
			)
		) );
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
	 * Output MD Save button with optional flush rules.
	 *
	 * @since 5.0
	 */

	public function save( $label = null, $args = null ) {
		include md_template( 'admin/fields/save', true );
	}

}