<?php
/**
 * Widgets outside WordPress's own screens: what the settings file carries
 * and what the widget abilities check against.
 *
 * Sidebars can come from settings saved moments earlier, ex: an import that
 * brings custom sidebars and their widgets together.
 *
 * @since 6.2
 */

class md_widgets {

	/**
	 * Widgets with their settings and the sidebar each sits in.
	 *
	 * @since 6.2
	 */

	public function export() {
		$sidebars = get_option( 'sidebars_widgets', array() );
		$widgets = array( 'sidebars' => array(), 'instances' => array() );

		foreach ( is_array( $sidebars ) ? $sidebars : array() as $sidebar => $ids ) {
			if ( ! is_array( $ids ) || $sidebar === 'wp_inactive_widgets' || strpos( $sidebar, 'orphaned_widgets' ) === 0 )
				continue;

			$widgets['sidebars'][$sidebar] = array();

			foreach ( $ids as $id ) {
				$instance = $this->instance( $id );

				if ( $instance === null )
					continue;

				$widgets['sidebars'][$sidebar][] = $id;
				$widgets['instances'][$id] = $instance;
			}
		}

		return $widgets;
	}

	/**
	 * A widget's settings by its ID, ex: text-2, or null.
	 *
	 * @since 6.2
	 */

	private function instance( $id ) {
		if ( ! is_string( $id ) || ! preg_match( '/^(.+)-(\d+)$/', $id, $match ) )
			return null;

		$option = get_option( "widget_{$match[1]}", array() );

		return isset( $option[$match[2]] ) && is_array( $option[$match[2]] ) ? $option[$match[2]] : null;
	}

	/**
	 * Replace the widgets of each sidebar in the file that exists here.
	 * Each widget gets a new number in its type's option; the sidebar's
	 * old widgets are deleted.
	 *
	 * @since 6.2
	 */

	public function import( $widgets, &$skipped ) {
		$sidebars = get_option( 'sidebars_widgets', array() );
		$sidebars = is_array( $sidebars ) ? $sidebars : array();
		$instances = isset( $widgets['instances'] ) && is_array( $widgets['instances'] ) ? $widgets['instances'] : array();
		$types = $this->types();
		$areas = $this->areas();
		$options = array();

		foreach ( isset( $widgets['sidebars'] ) && is_array( $widgets['sidebars'] ) ? $widgets['sidebars'] : array() as $sidebar => $ids ) {
			if ( ! in_array( $sidebar, $areas, true ) ) {
				if ( ! $ids )
					continue;

				$skipped[] = sprintf( __( 'The `%s` sidebar doesn\'t exist here, so its widgets weren\'t placed. Select Theme settings to create it.', 'md' ), $sidebar );
				continue;
			}

			foreach ( isset( $sidebars[$sidebar] ) && is_array( $sidebars[$sidebar] ) ? $sidebars[$sidebar] : array() as $old ) {
				if ( preg_match( '/^(.+)-(\d+)$/', $old, $match ) ) {
					$options[$match[1]] = isset( $options[$match[1]] ) ? $options[$match[1]] : get_option( "widget_{$match[1]}", array() );
					unset( $options[$match[1]][$match[2]] );
				}
			}

			$sidebars[$sidebar] = array();

			foreach ( (array) $ids as $id ) {
				if ( ! is_string( $id ) || ! preg_match( '/^(.+)-(\d+)$/', $id, $match ) || ! isset( $instances[$id] ) || ! is_array( $instances[$id] ) )
					continue;

				$type = $match[1];

				if ( ! in_array( $type, $types, true ) ) {
					$skipped[] = sprintf( __( 'The `%s` widget isn\'t available on this site.', 'md' ), $type );
					continue;
				}

				$options[$type] = isset( $options[$type] ) ? $options[$type] : get_option( "widget_$type", array() );
				$options[$type] = is_array( $options[$type] ) ? $options[$type] : array();
				$numbers = array_filter( array_keys( $options[$type] ), 'is_int' );
				$number = max( array_merge( array( 1 ), $numbers ) ) + 1;

				$instance = $this->update( $type, $number, current_user_can( 'unfiltered_html' ) ? $instances[$id] : $this->kses( $instances[$id] ) );

				if ( $instance === false ) {
					$skipped[] = sprintf( __( 'The `%s` widget refused its settings, so it wasn\'t placed.', 'md' ), $type );
					continue;
				}

				$options[$type][$number] = $instance;
				$options[$type]['_multiwidget'] = 1;
				$sidebars[$sidebar][] = "$type-$number";
			}
		}

		foreach ( $options as $type => $option )
			update_option( "widget_$type", $option );

		update_option( 'sidebars_widgets', $sidebars );
	}

	/**
	 * Run settings through a widget's update() and core's filter, as the
	 * Widgets screen does. Returns what to save, or false if refused.
	 *
	 * @since 6.2
	 */

	public function update( $type, $number, $new, $old = array() ) {
		global $wp_widget_factory;

		foreach ( isset( $wp_widget_factory->widgets ) ? $wp_widget_factory->widgets : array() as $widget ) {
			if ( $widget->id_base !== $type )
				continue;

			$widget->_set( $number );
			$instance = apply_filters( 'widget_update_callback', $widget->update( $new, $old ), $new, $old, $widget );

			return is_array( $instance ) ? $instance : false;
		}

		return false;
	}

	/**
	 * Widget text through wp_kses_post() for users who can't post
	 * unfiltered HTML.
	 *
	 * @since 6.2
	 */

	public function kses( $instance ) {
		foreach ( $instance as $key => $value ) {
			if ( is_array( $value ) )
				$instance[$key] = $this->kses( $value );
			elseif ( is_string( $value ) )
				$instance[$key] = wp_kses_post( $value );
		}

		return $instance;
	}

	/**
	 * Widget types registered here, by ID base.
	 *
	 * @since 6.2
	 */

	public function types() {
		global $wp_widget_factory;

		$types = array();

		if ( isset( $wp_widget_factory->widgets ) )
			foreach ( $wp_widget_factory->widgets as $widget )
				$types[] = $widget->id_base;

		return $types;
	}

	/**
	 * Sidebars that exist here: the registered ones, and MD's custom
	 * sidebars and panels from settings the same import just saved.
	 *
	 * @since 6.2
	 */

	public function areas() {
		global $wp_registered_sidebars;

		$settings = get_option( 'marketers_delight', array() );
		$areas = is_array( $wp_registered_sidebars ) ? array_keys( $wp_registered_sidebars ) : array();

		foreach ( array( 'sidebars', 'panels' ) as $type )
			if ( isset( $settings['settings'][$type] ) && is_array( $settings['settings'][$type] ) )
				$areas = array_merge( $areas, array_map( 'strval', array_keys( $settings['settings'][$type] ) ) );

		return $areas;
	}

}
