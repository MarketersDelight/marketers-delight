<?php
/**
 * Draws MD's settings fields on every screen: settings pages, post type and taxonomy
 * tabs, and the post, term and user editors. Values are read through md_scope, the
 * same place md_save writes them.
 *
 * In order: drawing a field, where its value lives, reading values, what registration
 * adds, inherited values, then template helpers and the group and builder controls.
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
	 * Keys put in front of every field() and module() path. Set through
	 * within(), so a panel written for its own screen can save inside
	 * another setting, like a builder row.
	 *
	 * @since 6.1
	 */

	protected $root = array();

	/**
	 * Registered fields per settings type, read once per instance when
	 * the first field draws.
	 *
	 * @since 6.2
	 */

	protected $registered = array();

	/**
	 * The context worked out for the screen in $context_screen, reused until
	 * md_api hands these fields another screen.
	 *
	 * @since 6.2.2
	 */

	protected $context = null;
	protected $context_screen = null;

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
	 * Get a copy of these fields that saves within the given keys, e.g.
	 * array( 'builder', $row, 'section_cta' ). The original is unchanged.
	 *
	 * @since 6.1
	 */

	public function within( $keys ) {
		$fields = clone $this;
		$fields->root = array_merge( $this->root, (array) $keys );

		return $fields;
	}

	/**
	 * Load any MD field. Takes a field name and arguments, which switch between
	 * $parent, $fields and $args to create option groups per page.
	 *
	 * @since 4.0
	 */

	public function field( $field, $args = array() ) {
		$args['path'] = $field;

		if ( $this->root )
			$field = array_merge( $this->root, (array) $field );

		$clean_id = $args['id'] ?? $this->_clean_id;
		$args = $args + $this->registered_args( $field, $clean_id );
		$args['field'] = $field;

		if ( ! empty( $args['tokens'] ) )
			$args['tokens'] = $this->token_set( $args );

		$context = $this->get_context();
		$stored = $this->stored( array_merge( array( $clean_id ), (array) $field ) );
		$option = $stored['value'];
		$name = 'md[' . $this->scope( $context ) . ']';
		$id = $this->_option;

		if ( $context['is_child'] ) {
			$name .= "[{$context['page_id']}]";
			$id .= "_{$context['page_id']}" . ( $context['taxonomy'] ? "_{$context['taxonomy']}" : '' );
		}

		$name .= "[$clean_id]";
		$id .= "_$clean_id" . ( ! $context['is_child'] && $context['taxonomy'] ? "_{$context['taxonomy']}" : '' );

		foreach ( (array) $field as $key ) {
			$name .= "[$key]";
			$id .= "_$key";
		}

		if ( $args['type'] === 'builder' && ! $stored['found'] && ! empty( $args['populate'] ) && array_key_exists( 'defaults', $args ) )
			$option = is_array( $args['defaults'] ) ? $args['defaults'] : array();
		elseif ( ! $stored['found'] && is_array( $field ) && array_key_exists( 'default', $args ) && $args['type'] !== 'color' )
			$option = $args['default'];

		if ( array_key_exists( 'value', $args ) )
			$option = $args['value'];

		if ( $args['type'] === 'builder' && ! isset( $args['save_empty'] ) )
			$args['save_empty'] = $stored['found'];

		if ( ! empty( $args['inherit'] ) )
			$args = $this->prepare_inheritance( $field, $option, $args );

		$this->render_field( $name, $id, $option, $args, array( 'md-field' ) );
	}

	/**
	 * Determine the admin screeen context to render the correct field
	 * settings based on the admin pages needs. We also check if a field
	 * belongs to an admin group, nested, or a taxonomy settings screen.
	 *
	 * @since 6.0
	 */

	protected function get_context() {
		if ( $this->context !== null && $this->context_screen === $this->_get_screen )
			return $this->context;

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

		$page_types = apply_filters( 'md_admin_groups', array() );
		$is_meta = $context['is_post'] || $context['is_term'] || $context['is_user'];

		if ( ! $is_meta && ! empty( $page_types[$screen['page']] ) ) {
			$context['is_group'] = true;
			$context['page_id'] = md_clean_id( $screen['page'] );
			$context['is_child'] = $this->_clean_id !== $context['page_id'];
			$context['taxonomy'] = $screen['is_taxonomy'] ? $screen['md_tab'] : '';
		}

		$this->context = $context;
		$this->context_screen = $screen;

		return $context;
	}

	/**
	 * The scope a field saves to, which leads its name: md[scope][group][...].
	 * md_save::apply() reads the same scopes. Post, term and user screens
	 * name the object type only, their save hook knows the ID.
	 *
	 * @since 6.2
	 */

	protected function scope( $context ) {
		if ( $context['is_post'] || wp_doing_ajax() )
			return 'post';

		if ( $context['is_term'] )
			return 'term';

		if ( $context['is_user'] )
			return 'user';

		if ( $context['is_group'] )
			return $context['taxonomy'] ? "taxonomy:{$context['page_id']}/{$context['taxonomy']}" : "post_type:{$context['page_id']}";

		return 'site';
	}

	/**
	 * Where this screen's values are stored, read through md_scope like a save
	 * writes them: post, term and user screens name their object's ID.
	 *
	 * @since 6.2.2
	 */

	protected function storage() {
		$context = $this->get_context();
		$scope = $this->scope( $context );

		if ( $scope === 'post' )
			$scope .= ':' . get_the_ID();
		elseif ( $scope === 'term' )
			$scope .= ':' . $this->_get_screen['screen_id'];
		elseif ( $scope === 'user' )
			$scope .= ':' . ( isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : get_current_user_id() );

		return md_scope::parse( $scope );
	}

	/**
	 * What's saved at a path that starts with a group, and whether it's there.
	 * Groups on a post type screen sit inside its own group.
	 *
	 * @since 6.2.2
	 */

	protected function stored( $keys ) {
		$context = $this->get_context();
		$keys = (array) $keys;
		$group = $context['is_child'] ? $context['page_id'] : array_shift( $keys );
		$scope = $this->storage();
		$value = is_wp_error( $scope ) ? $scope : $scope->read( $group );
		$found = ! is_wp_error( $value );

		foreach ( $keys as $key ) {
			$found = $found && is_array( $value ) && array_key_exists( $key, $value );
			$value = $found ? $value[$key] : '';
		}

		return array(
			'found' => $found,
			'value' => $found ? $value : ''
		);
	}

	/**
	 * What's saved on this screen at a path that starts with a group, without
	 * inheriting.
	 *
	 * @since 5.0.9
	 */

	public function get_field( $keys, $default = null ) {
		$stored = $this->stored( $keys );

		return $stored['found'] ? $stored['value'] : $default;
	}

	/**
	 * Get only what's saved on the current screen, without inheriting.
	 * Use it where a control shows this screen's own choice, like which
	 * CTA type panel is open.
	 *
	 * @since 6.1
	 */

	public function saved( $keys, $default = null ) {
		return $this->lookup( $keys, $default, false );
	}

	/**
	 * Get field values based on current admin screen, inheriting from the
	 * levels above it (term, then taxonomy, then post type) when unset.
	 *
	 * @since 4.1
	 */

	public function module( $keys, $default = null ) {
		return $this->lookup( $keys, $default, true );
	}

	/**
	 * Resolve a setting for the current screen, optionally inheriting.
	 *
	 * @since 6.1
	 */

	protected function lookup( $keys, $default, $inherit ) {
		$keys = array_merge( $this->root, (array) $keys );
		$screen = $this->_get_screen;
		$context = $this->get_context();
		$field_keys = array_merge( array( $this->_clean_id ), $keys );

		if ( $context['is_post'] || ( $context['is_term'] && ! empty( $screen['screen_id'] ) ) ) {
			$stored = $this->stored( $field_keys );
			$value = $stored['found'] ? $stored['value'] : null;

			if ( $value === null && $inherit && $context['is_term'] )
				$value = md_value( array(
					'key' => $field_keys,
					'default' => $default,
					'taxonomy' => $screen['taxonomy'],
					'post_type' => $screen['post_type']
				) );
		}
		elseif ( $context['is_group'] ) {
			$group_keys = $context['is_child'] ? $field_keys : $keys;
			$value = null;

			if ( $context['taxonomy'] )
				$value = md_value( array(
					'key' => $group_keys,
					'taxonomy' => $context['taxonomy'],
					'post_type' => $context['page_id'],
					'inherit_post_type' => false
				) );

			if ( $value === null && ( $inherit || ! $context['taxonomy'] ) )
				$value = md_value( array(
					'key' => $group_keys,
					'default' => $default,
					'post_type' => $context['page_id']
				) );
		}
		else
			$value = md_option( $this->_option, $field_keys, $default );

		return $value ?? $default;
	}

	/**
	 * What a field's registration says about how it draws, for keys the template
	 * didn't pass. A list of option values has no labels, so those stay with the
	 * template.
	 *
	 * @since 6.2
	 */

	protected function registered_args( $field, $clean_id ) {
		$registered = $this->registered_field( $field, $clean_id );
		$keys = array( 'type', 'label', 'min', 'max', 'step', 'upload_type', 'multiple', 'tokens' );
		$args = array_intersect_key( $registered, array_flip( $keys ) );

		if ( ! empty( $registered['options'] ) && md_sanitize::labels( $registered['options'] ) )
			$args['options'] = $registered['options'];

		return $args;
	}

	/**
	 * Find a field's registration for this screen by its path. A group or
	 * builder field's path holds the item key after the field's own key.
	 *
	 * @since 6.2
	 */

	protected function registered_field( $field, $clean_id ) {
		$context = $this->get_context();
		$settings = $context['is_post'] ? 'meta_boxes' : ( $context['is_term'] ? 'terms' : ( $context['is_user'] ? 'user_meta' : 'admin_pages' ) );

		if ( ! isset( $this->registered[$settings] ) )
			$this->registered[$settings] = md_register( $settings );

		$node = isset( $this->registered[$settings][$clean_id]['fields'] ) ? $this->registered[$settings][$clean_id]['fields'] : array();

		$definition = array();
		$item = false;

		foreach ( (array) $field as $key ) {
			if ( $item ) {
				$item = false;
				$node = isset( $definition['fields'] ) ? $definition['fields'] : array();
				$definition = array();
				continue;
			}

			if ( ! is_array( $node ) || ! isset( $node[$key] ) || ! is_array( $node[$key] ) )
				return array();

			$node = $node[$key];
			$definition = isset( $node['type'] ) ? $node : array();
			$item = isset( $node['type'] ) && in_array( $node['type'], array( 'group', 'builder' ), true );
		}

		return $definition;
	}

	/**
	 * The token set a field's `tokens` key asks for: a set name as given,
	 * or for `true` the set this screen's archive text uses. Only plain
	 * text and textarea fields outside groups and builders get tokens.
	 *
	 * @since 6.2
	 */

	protected function token_set( $args ) {
		$type = isset( $args['type'] ) ? $args['type'] : '';

		if ( ! is_string( $args['path'] ) || ! in_array( $type, array( 'text', 'textarea' ), true ) )
			return '';

		if ( is_string( $args['tokens'] ) )
			return $args['tokens'];

		$context = $this->get_context();

		if ( $context['is_term'] || $context['taxonomy'] )
			return 'term';

		if ( $context['is_post'] || $context['is_user'] )
			return '';

		if ( md_clean_id( isset( $this->_get_screen['page'] ) ? $this->_get_screen['page'] : '' ) === 'author' )
			return 'author';

		return 'archive';
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

				$option_parent = $this->parent_value( array_merge( (array) $field, array( $key ) ), $inherit[$key]['default'] ?? false );

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
		$args = array( 'default' => $default );

		if ( $context['is_post'] && ! empty( $screen['post_type'] ) ) {
			array_unshift( $keys, $this->_clean_id );
			$args['post_type'] = $screen['post_type'];
		}
		elseif ( $context['is_term'] && ! empty( $screen['post_type'] ) && ! empty( $screen['taxonomy'] ) ) {
			array_unshift( $keys, $this->_clean_id );
			$args['taxonomy'] = $screen['taxonomy'];
			$args['post_type'] = $screen['post_type'];
		}
		elseif ( $context['is_group'] && ! $context['is_user'] ) {
			if ( $context['is_child'] )
				array_unshift( $keys, $this->_clean_id );

			$args['post_type'] = $context['taxonomy'] ? $context['page_id'] : md_post_type_settings_parent( $context['page_id'] );
		}

		if ( empty( $args['post_type'] ) )
			return array( 'available' => false, 'value' => $default );

		$args['key'] = $keys;

		return array( 'available' => true, 'value' => md_value( $args ) );
	}

	/**
	 * The value this screen inherits from the level above it, or $default
	 * when nothing is above it.
	 *
	 * @since 6.2.3
	 */

	public function inherited( $keys, $default = null ) {
		$parent = $this->parent_value( array_merge( $this->root, (array) $keys ), $default );

		return $parent['value'];
	}

	/**
	 * Settings templates call this where the Save button used to print.
	 * Save now lives in the save bar at the top of every settings screen
	 * (md_save_settings::bar()), so this prints nothing.
	 *
	 * @since 5.0
	 */

	public function save( $label = null, $args = null ) {}

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
	 * Render checkbox groups for visibility conditions: PHP-gated (check) and
	 * CSS-based (class). Field paths are passed directly, so it works in any
	 * field group.
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
	 * A Page Title text box with its Title size select on the right.
	 *
	 * @since 6.2.3
	 */

	public function title_field( $args = array() ) {
		include md_template( 'admin/fields/title', true );
	}

	/**
	 * The post type a settings screen is for: the screen's post type, or the
	 * post type a top-level MD settings page belongs to (md_docs is docs).
	 * Set $author to also allow the Author archive settings page.
	 *
	 * @since 6.0.3
	 */

	public function screen_post_type( $author = false ) {
		$post_type = $this->_get_screen['post_type'] ?? '';
		$page_post_type = md_clean_id( $this->_get_screen['page'] ?? '' );

		if ( ! empty( $this->_get_screen['is_admin'] ) && ( ( $author && $page_post_type === 'author' ) || get_post_type_object( $page_post_type ) ) )
			$post_type = $page_post_type;

		return $post_type;
	}

	/**
	 * Render the Byline Position field. The Byline builder adds it to
	 * every item, so item callbacks don't call it themselves.
	 *
	 * @since 6.0
	 */

	public function byline_fields( $group, $args = array() ) {
		$post_type = $this->screen_post_type();
		$row = md_get_post_type_builder( 'byline', $post_type )[$group] ?? array();
		$title_positions = md_title_meta_positions();
		$entry_positions = array(
			'entry_top' => __( 'Entry Top', 'md' ),
			'before_content' => __( 'Before Content', 'md' ),
			'after_content' => __( 'After Content', 'md' ),
			'entry_footer' => __( 'Entry Footer', 'md' )
		);

		$position = array(
			'id' => 'byline',
			'type' => 'select',
			'label' => __( 'Position', 'md' ),
			'wrap_classes' => $args['wrap_classes'] ?? 'mb-third',
			'options' => array_merge( $title_positions, $entry_positions ),
			'groups' => array(
				__( 'Title', 'md' ) => array_keys( $title_positions ),
				__( 'Entry', 'md' ) => array_keys( $entry_positions )
			)
		);

		if ( array_key_exists( 'position', $row ) )
			$position['default'] = $row['position'];

		$this->field( array( 'builder', $group, 'position' ), $position );

		return $row;
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

	protected function builder_field( $key, $group, $type, $fields, $row = array(), $builder_args = array() ) {
		$icon = ! empty( $fields['icon'] ) ? $fields['icon'] : 'move';
		$color = ! empty( $fields['color'] ) ? $fields['color'] : '';
		$scope = ! empty( $builder_args['scope'] );

		include md_template( 'admin/fields/builder-field', true );
	}

	/**
	 * A row this screen can't show, ex: from an inactive Drop-in, posted back as
	 * its type and area so saving keeps the whole stored row in its place.
	 *
	 * @since 6.2.2
	 */

	protected function builder_kept( $key, $group, $row ) {
		echo '<div class="md-builder-kept" hidden>';

		foreach ( array( 'builder_type', 'builder_area' ) as $field ) {
			$value = $row[$field] ?? '';

			$this->field( array( $key, $group, $field ), array(
				'type' => 'text',
				'hidden' => true,
				'value' => is_scalar( $value ) ? (string) $value : ''
			) );
		}

		echo '</div>';
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
