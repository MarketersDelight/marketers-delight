<?php
/**
 * Abilities for the parts of a site MD arranges but doesn't store in its
 * settings: menus and widgets.
 *
 * @since 6.2
 */

class md_site_abilities extends md_abilities {

	private $settings_file;

	/**
	 * Set up the settings file these abilities read and write.
	 *
	 * @since 6.2
	 */

	public function __construct() {
		$this->settings_file = new md_settings_file;

		parent::__construct();
	}

	/**
	 * Describe the site abilities.
	 *
	 * @since 6.2
	 */

	public function abilities() {
		$order = array(
			'type' => 'array',
			'items' => array( 'type' => array( 'integer', 'string' ) ),
			'description' => __( 'IDs in the order they should be.', 'md' )
		);

		return array(
			'get-menu' => array(
				'label' => __( 'Get menu', 'md' ),
				'description' => __( 'With no input, lists menus and menu locations. Given a menu ID or name, or a location, lists its items in order and flags any that point at trashed or unpublished content.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'menu' => array( 'type' => array( 'integer', 'string' ) ),
						'location' => array( 'type' => 'string' )
					),
					'additionalProperties' => false,
					'default' => array()
				),
				'execute' => 'get_menu',
				'permission' => 'can_edit_site',
				'readonly' => true
			),
			'update-menu' => array(
				'label' => __( 'Update menu', 'md' ),
				'description' => __( 'Changes a menu, or creates one with create. items is a list of operations: { op: add, title, url } or { op: add, object: post type or taxonomy, object_id, title }; { op: update, id, title, url, parent }; { op: remove, id }. New items go at the end; send order to arrange them, and items left out follow the ones listed. location puts the menu in a theme location.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'menu' => array( 'type' => array( 'integer', 'string' ) ),
						'create' => array( 'type' => 'string', 'description' => __( 'The name of a new menu.', 'md' ) ),
						'location' => array( 'type' => 'string' ),
						'items' => array( 'type' => 'array', 'items' => array( 'type' => 'object' ) ),
						'order' => $order
					),
					'additionalProperties' => false
				),
				'execute' => 'update_menu',
				'permission' => 'can_edit_site',
				'destructive' => true
			),
			'get-widgets' => array(
				'label' => __( 'Get widgets', 'md' ),
				'description' => __( 'Lists sidebars with their widgets and settings, and the widget types available.', 'md' ),
				'execute' => 'get_widgets',
				'permission' => 'can_edit_site',
				'readonly' => true
			),
			'update-widgets' => array(
				'label' => __( 'Update widgets', 'md' ),
				'description' => __( 'Changes a sidebar\'s widgets. widgets is a list of operations: { op: add, type, settings }; { op: update, id, settings }, which changes only the settings given; { op: remove, id }. New widgets go at the end; send order to arrange them.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'sidebar' => array( 'type' => 'string' ),
						'widgets' => array( 'type' => 'array', 'items' => array( 'type' => 'object' ) ),
						'order' => $order
					),
					'required' => array( 'sidebar' ),
					'additionalProperties' => false
				),
				'execute' => 'update_widgets',
				'permission' => 'can_edit_site',
				'destructive' => true
			)
		);
	}

	/**
	 * Menus and widgets take the capability their screens do.
	 *
	 * @since 6.2
	 */

	public function can_edit_site() {
		return current_user_can( 'edit_theme_options' );
	}

	/**
	 * List menus, or one menu's items.
	 *
	 * @since 6.2
	 */

	public function get_menu( $input = array() ) {
		if ( empty( $input['menu'] ) && empty( $input['location'] ) ) {
			$menus = array();
			$assigned = get_nav_menu_locations();

			foreach ( wp_get_nav_menus() as $menu )
				$menus[] = array(
					'id' => (int) $menu->term_id,
					'name' => $menu->name,
					'locations' => array_keys( $assigned, $menu->term_id, false )
				);

			return array( 'menus' => $menus, 'locations' => get_registered_nav_menus() );
		}

		$menu = $this->menu( $input );

		return is_wp_error( $menu ) ? $menu : $this->menu_result( $menu );
	}

	/**
	 * Create or change a menu.
	 *
	 * @since 6.2
	 */

	public function update_menu( $input = array() ) {
		if ( ! empty( $input['create'] ) ) {
			$menu_id = wp_create_nav_menu( $input['create'] );

			if ( is_wp_error( $menu_id ) )
				return $menu_id;

			$menu = wp_get_nav_menu_object( $menu_id );
		}
		else
			$menu = $this->menu( $input );

		if ( is_wp_error( $menu ) )
			return $menu;

		// WordPress can give a first item position 0, then reads 0 as "add to the end"

		$posts = $this->menu_posts( (int) $menu->term_id );

		if ( $posts && ! $posts[0]->menu_order ) {
			$done = $this->order_menu( (int) $menu->term_id, array() );

			if ( is_wp_error( $done ) )
				return $done;
		}

		foreach ( (array) ( $input['items'] ?? array() ) as $operation ) {
			$done = $this->menu_operation( (int) $menu->term_id, (array) $operation );

			if ( is_wp_error( $done ) )
				return $done;
		}

		if ( ! empty( $input['order'] ) ) {
			$done = $this->order_menu( (int) $menu->term_id, (array) $input['order'] );

			if ( is_wp_error( $done ) )
				return $done;
		}

		if ( ! empty( $input['location'] ) ) {
			$locations = get_nav_menu_locations();
			$locations[$input['location']] = (int) $menu->term_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}

		$save = new md_save;
		$save->after_save();

		return $this->menu_result( $menu );
	}

	/**
	 * The menu an ability named.
	 *
	 * @since 6.2
	 */

	protected function menu( $input ) {
		if ( ! empty( $input['location'] ) && empty( $input['menu'] ) ) {
			$locations = get_nav_menu_locations();
			$input['menu'] = $locations[$input['location']] ?? 0;
		}

		$menu = wp_get_nav_menu_object( $input['menu'] ?? 0 );

		return $menu ? $menu : new WP_Error( 'md_abilities_menu', __( 'That menu doesn\'t exist. Get menu with no input lists them.', 'md' ) );
	}

	/**
	 * Run one menu item operation.
	 *
	 * @since 6.2
	 */

	protected function menu_operation( $menu_id, $operation ) {
		$op = $operation['op'] ?? '';
		$id = absint( $operation['id'] ?? 0 );

		if ( ! in_array( $op, array( 'add', 'update', 'remove' ), true ) )
			return new WP_Error( 'md_abilities_menu_op', __( 'Each item needs an op: add, update or remove.', 'md' ) );

		if ( $op !== 'add' && ! is_object_in_term( $id, 'nav_menu', $menu_id ) )
			return $this->no_menu_item( $id );

		if ( $op === 'remove' )
			return wp_delete_post( $id, true ) ? true : new WP_Error( 'md_abilities_menu_remove', sprintf( __( 'Menu item %d couldn\'t be removed.', 'md' ), $id ) );

		$item = array();

		foreach ( array( 'title' => 'menu-item-title', 'url' => 'menu-item-url', 'parent' => 'menu-item-parent-id' ) as $key => $field )
			if ( isset( $operation[$key] ) )
				$item[$field] = $operation[$key];

		if ( $op === 'add' ) {
			$item['menu-item-status'] = 'publish';

			if ( ! empty( $operation['object_id'] ) ) {
				$item['menu-item-object-id'] = absint( $operation['object_id'] );
				$item['menu-item-object'] = $operation['object'] ?? 'page';
				$item['menu-item-type'] = taxonomy_exists( $item['menu-item-object'] ) ? 'taxonomy' : 'post_type';
			}
			else
				$item['menu-item-type'] = 'custom';

			$saved = wp_update_nav_menu_item( $menu_id, 0, $item );

			return $saved;
		}

		return $this->save_menu_item( $menu_id, $id, $item );
	}

	/**
	 * Change a menu item. WordPress resets whatever it isn't given, so this
	 * sends the item's current values with the changes.
	 *
	 * @since 6.2
	 */

	protected function save_menu_item( $menu_id, $id, $changes ) {
		$item = wp_setup_nav_menu_item( get_post( $id ) );

		return wp_update_nav_menu_item( $menu_id, $id, array_merge( array(
			'menu-item-title' => $item->title,
			'menu-item-url' => $item->url,
			'menu-item-object' => $item->object,
			'menu-item-object-id' => $item->object_id,
			'menu-item-type' => $item->type,
			'menu-item-parent-id' => $item->menu_item_parent,
			'menu-item-position' => $item->menu_order,
			'menu-item-description' => $item->description,
			'menu-item-attr-title' => $item->attr_title,
			'menu-item-target' => $item->target,
			'menu-item-classes' => implode( ' ', (array) $item->classes ),
			'menu-item-xfn' => $item->xfn,
			'menu-item-status' => 'publish'
		), $changes ) );
	}

	/**
	 * Put the listed items first, in the order given, with the rest after
	 * them in their current order.
	 *
	 * @since 6.2
	 */

	protected function order_menu( $menu_id, $order ) {
		$order = array_map( 'absint', $order );
		$current = wp_list_pluck( $this->menu_posts( $menu_id ), 'ID' );

		foreach ( $order as $id )
			if ( ! in_array( $id, $current, true ) )
				return $this->no_menu_item( $id );

		foreach ( array_values( array_unique( array_merge( $order, $current ) ) ) as $index => $id ) {
			$done = $this->save_menu_item( $menu_id, $id, array( 'menu-item-position' => $index + 1 ) );

			if ( is_wp_error( $done ) )
				return $done;
		}

		return true;
	}

	/**
	 * The error for an item that isn't in the menu being changed.
	 *
	 * @since 6.2
	 */

	protected function no_menu_item( $id ) {
		return new WP_Error( 'md_abilities_menu_item', sprintf( __( 'This menu has no item with ID %d. Get menu lists its items.', 'md' ), $id ) );
	}

	/**
	 * A menu's item posts in order. wp_get_nav_menu_items() hides items
	 * pointing at trashed content on these requests.
	 *
	 * @since 6.2
	 */

	protected function menu_posts( $menu_id ) {
		return get_posts( array(
			'post_type' => 'nav_menu_item',
			'post_status' => 'any',
			'numberposts' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'tax_query' => array( array( 'taxonomy' => 'nav_menu', 'terms' => $menu_id ) )
		) );
	}

	/**
	 * A menu as apps get it.
	 *
	 * @since 6.2
	 */

	protected function menu_result( $menu ) {
		$items = array();

		foreach ( $this->menu_posts( $menu->term_id ) as $post ) {
			$item = wp_setup_nav_menu_item( $post );
			$row = array(
				'id' => (int) $item->ID,
				'title' => $item->title,
				'url' => $item->url,
				'object' => $item->object,
				'object_id' => (int) $item->object_id,
				'parent' => (int) $item->menu_item_parent,
				'order' => (int) $item->menu_order
			);

			if ( $item->type === 'post_type' && get_post_status( $item->object_id ) !== 'publish' )
				$row['problem'] = sprintf( __( 'Points at content with status %s.', 'md' ), (string) get_post_status( $item->object_id ) );

			$items[] = $row;
		}

		return array( 'id' => (int) $menu->term_id, 'name' => $menu->name, 'items' => $items );
	}

	/**
	 * Sidebars and their widgets.
	 *
	 * @since 6.2
	 */

	public function get_widgets() {
		global $wp_registered_sidebars;

		$placed = wp_get_sidebars_widgets();
		$sidebars = array();

		foreach ( $this->settings_file->widget_areas() as $id ) {
			$widgets = array();

			foreach ( isset( $placed[$id] ) ? (array) $placed[$id] : array() as $widget ) {
				$parts = $this->widget_parts( $widget );

				if ( $parts )
					$widgets[] = array(
						'id' => $widget,
						'type' => $parts['type'],
						'settings' => $this->widget_settings( $parts['type'], $parts['number'] )
					);
			}

			$sidebars[] = array(
				'id' => $id,
				'name' => $wp_registered_sidebars[$id]['name'] ?? $id,
				'widgets' => $widgets
			);
		}

		return array( 'sidebars' => $sidebars, 'types' => $this->settings_file->widget_types() );
	}

	/**
	 * Change a sidebar's widgets. Only that sidebar changes.
	 *
	 * @since 6.2
	 */

	public function update_widgets( $input = array() ) {
		$sidebar = $input['sidebar'];

		if ( ! in_array( $sidebar, $this->settings_file->widget_areas(), true ) )
			return new WP_Error( 'md_abilities_sidebar', sprintf( __( 'No sidebar named %s. Get widgets lists them.', 'md' ), $sidebar ) );

		$placed = wp_get_sidebars_widgets();
		$widgets = isset( $placed[$sidebar] ) ? array_values( (array) $placed[$sidebar] ) : array();

		foreach ( (array) ( $input['widgets'] ?? array() ) as $operation ) {
			$widgets = $this->widget_operation( $widgets, (array) $operation );

			if ( is_wp_error( $widgets ) )
				return $widgets;
		}

		if ( ! empty( $input['order'] ) )
			$widgets = array_values( array_unique( array_merge( array_intersect( array_map( 'strval', $input['order'] ), $widgets ), $widgets ) ) );

		$placed[$sidebar] = $widgets;
		wp_set_sidebars_widgets( $placed );

		return $this->get_widgets();
	}

	/**
	 * Run one widget operation, returning the sidebar's widgets.
	 *
	 * @since 6.2
	 */

	protected function widget_operation( $widgets, $operation ) {
		$op = $operation['op'] ?? '';
		$settings = isset( $operation['settings'] ) ? (array) $operation['settings'] : array();

		if ( ! current_user_can( 'unfiltered_html' ) )
			$settings = $this->settings_file->kses_widget( $settings );

		if ( $op === 'add' ) {
			$type = $operation['type'] ?? '';

			if ( ! in_array( $type, $this->settings_file->widget_types(), true ) )
				return new WP_Error( 'md_abilities_widget', sprintf( __( 'No widget type named %s. Get widgets lists them.', 'md' ), $type ) );

			$instances = get_option( "widget_$type", array() );
			$instances = is_array( $instances ) ? $instances : array();
			$number = max( array_merge( array( 1 ), array_filter( array_keys( $instances ), 'is_int' ) ) ) + 1;
			$instances[$number] = $settings;
			$instances['_multiwidget'] = 1;
			update_option( "widget_$type", $instances );
			$widgets[] = "$type-$number";

			return $widgets;
		}

		$id = (string) ( $operation['id'] ?? '' );
		$parts = $this->widget_parts( $id );

		if ( ! $parts || ! in_array( $id, $widgets, true ) )
			return new WP_Error( 'md_abilities_widget', sprintf( __( 'This sidebar has no widget %s.', 'md' ), $id ) );

		$instances = get_option( "widget_{$parts['type']}", array() );

		if ( $op === 'update' )
			$instances[$parts['number']] = array_merge( (array) ( $instances[$parts['number']] ?? array() ), $settings );
		elseif ( $op === 'remove' ) {
			unset( $instances[$parts['number']] );
			$widgets = array_values( array_diff( $widgets, array( $id ) ) );
		}
		else
			return new WP_Error( 'md_abilities_widget_op', __( 'Each widget needs an op: add, update or remove.', 'md' ) );

		update_option( "widget_{$parts['type']}", $instances );

		return $widgets;
	}

	/**
	 * Split a widget ID into its type and number.
	 *
	 * @since 6.2
	 */

	protected function widget_parts( $id ) {
		return preg_match( '/^(.+)-(\d+)$/', (string) $id, $match ) ? array( 'type' => $match[1], 'number' => (int) $match[2] ) : false;
	}

	/**
	 * One widget's saved settings.
	 *
	 * @since 6.2
	 */

	protected function widget_settings( $type, $number ) {
		$instances = get_option( "widget_$type", array() );

		return isset( $instances[$number] ) ? $instances[$number] : array();
	}

}

new md_site_abilities;
