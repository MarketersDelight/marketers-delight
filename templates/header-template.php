<?php

$md_header_template = new md_header_template;

add_action( 'template_redirect', array( $md_header_template, 'actions' ) );

/**
 * Build header template in conditional parts and build each component.
 *
 * This class is hooked to template_redirect and contains various
 * template routes to different pieces of a possible header layout.
 * The base markup loads a structured header that contains:
 *
 * 1) Header controls
 * 2) Primary header content
 * 3) Secondary header content
 *
 * Populated based on user-filled settings in WP admin > Edit Theme > Header
 *
 * @since 6.0
 */

class md_header_template {

	/**
	 * Load header HTML to page and additional high-level hooks and filters.
	 *
	 * @since 6.0
	 */

	public function actions() {
		add_action( 'md_hook_header', array( $this, 'html' ) );

		if ( has_nav_menu( 'main_menu' ) )
			add_action( 'md_hook_before_content_box', array( $this, 'main_menu' ) );
	}

	/**
	 * Main header template hooked into to md_hook_header
	 * from /lib/header.php->template().
	 *
	 * @since 6.0
	 */

	public function html() {
		$data = md_get_builder( 'header', 'data' );
		$fields = md_setting( array( 'header', 'builder' ), array() );
		$fields_args = array( 'layout' => md_setting( array( 'header', 'layout' ), 'standard' ) );
		$areas = array(
			'header' => array( 'key' => 'primary' ),
			'header_aside' => array( 'key' => 'aside' )
		);

		echo '<div class="header-controls">';

		if ( md_has_menu() && md_setting( array( 'header', 'layout_mobile' ) ) == 'expanded' )
			$this->menu_trigger();

		if ( md_has_logo() )
			md_logo();

		if ( md_has_menu() )
			$this->header_triggers();

		echo '</div>';

		foreach ( $areas as $area_id => $area_field ) {
			if ( empty( $data[$area_id] ) )
				continue;

			echo '<div class="header-' . esc_attr( $area_field['key'] ) . '">';

			foreach ( $data[$area_id] as $order => $items ) {
				$type = esc_attr( $items['type'] );
				$id = esc_attr( $items['id'] );

				if ( ! empty( $fields[$id] ) ) {
					$fields_args['location'] = $area_id;
					$fields[$id]['args'] = $fields_args;

					call_user_func( array( $this, esc_attr( $type ) ), $fields[$id] );
				}
			}

			do_action( 'md_hook_header_' . $area_field['key'] );

			echo '</div>';
		}

		if ( empty( $data ) )
			$this->menu();
	}

	/**
 	 * Renders known header triggers (think: nav menu toggle, search open/close, etc.).
 	 *
 	 * @since 4.8
	 * Formerly md_header_triggers() #6.0
 	 */

	public function header_triggers( $args = null ) {
		$elements = md_get_builder( 'header' );

		echo '<div class="header-triggers">';

		if ( md_has_header_search() )
			$this->search_trigger();

		if ( md_has_menu() && md_setting( array( 'header', 'layout_mobile' ) ) !== 'expanded' )
			$this->menu_trigger();

		if ( ! empty( $elements['link'] ) )
			foreach ( $elements['link'] as $c => $link_id ) {
				$fields = md_setting( array( 'header', 'builder', $link_id ) );

				$this->link( $fields );
			}

		do_action( 'md_hook_header_triggers' );

		echo '</div>';
	}

	/**
	 * Renders the Link/Button markup, which hooks to header_triggers().
	 *
	 * @since 6.0
	 */

	public function link( $fields ) {
		md_link( $fields );
	}

	/**
	 * Render a menu instance with a customized wp_nav_menu().
	 *
	 * @since 6.0
	 */

	public function menu( $fields = array() ) {
		$parent = isset( $fields['area'] ) ? $fields['area'] : 'header';
		$menu_id = isset( $fields['menu'] ) ? $fields['menu'] : '';
		$menu_class = 'menu menu-' . esc_attr( $parent );

		if ( isset( $fields['args'] ) && ( $fields['args']['layout'] == 'rtl' || ( $fields['args']['layout'] == 'flyer' && $fields['args']['location'] == 'header' ) ) )
			$menu_class .= ' sub-alt';

		$args = array(
			'menu' => md_module( array( 'layout', 'header_menu' ), $menu_id ),
			'container' => false,
			'fallback_cb' => false,
			'menu_class' => esc_attr( $menu_class ),
			'walker' => new md_menu_walker( true, true )
		);

		if ( empty( $menu_id ) ) {
			$menu_location = is_user_logged_in() && has_nav_menu( 'header_loggedin' ) ? 'header_loggedin' : 'header';
			$args['theme_location'] = $menu_location;
		}

		echo '<nav class="' . esc_attr( $parent ) . '-menu">';

		wp_nav_menu( $args );

		echo '</nav>';
	}

	public function main_menu() {
		$menus = md_get_builder( 'header', null, 'menu' );
		$header = md_setting( array( 'header', 'builder' ), array() );

		include( md_template( 'main-menu', true ) );
	}

	/**
	 * Renders the Menu Trigger markup, which hooks to header_triggers().
	 *
	 * @since 6.0
	 */

	public function menu_trigger() {
		$menu_id = 'header_menu';

		if ( has_nav_menu( 'main_menu' ) )
			$menu_id = 'main_menu';

		$elements = md_get_builder( 'header' );
		$element_id = ! empty( $elements['menu'][0] ) ? $elements['menu'][0] : '';
		$nav_menu_title = md_get_menu_name( 'header' );
		$header = md_setting( array( 'header', 'builder', $element_id ) );
		$title = ! empty( $header['title'] ) ? $header['title'] : $nav_menu_title;
		$classes = array( 'trigger', 'trigger-menu' );

		if ( ! empty( $header['toggle']['hide_label'] ) )
			$classes[] = 'hide-label';
		elseif ( ! empty( $header['toggle']['hide_label_mobile'] ) )
			$classes[] = 'hide-label-mobile';

		$classes = join( ' ', $classes );

		echo '<span id="' . esc_attr( $menu_id ) . '_trigger" class="' . esc_attr( $classes ) . '" title="' . esc_attr( $title ) . '">'.
			md_icon( 'menu', array( 'classes' => 'trigger-icon' ) ).
			'<span class="trigger-text">' . md_text_field( $title ) . '</span>'.
		'</span>';
	}

	/**
	 * Renders the Search Form markup for each instance.
	 *
	 * @since 6.0
	 */

	public function search( $fields ) {
		$parent = isset( $fields['parent'] ) ? $fields['parent'] : 'header';
		$title = isset( $fields['title'] ) ? $fields['title'] : __( 'Search', 'md' );
		$placeholder = isset( $fields['placeholder'] ) ? $fields['placeholder'] : __( 'Type to search...', 'md' );
		$submit_text = isset( $fields['submit_text'] ) ? $fields['submit_text'] : __( 'Search', 'md' );

		$fields['classes'][] = 'search-form';
		$fields['classes'][] = 'form-icons';

		if ( ! empty( $fields['toggle']['search'] ) )
			$fields['classes'][] = 'form-toggle';

		if ( ! empty( $fields['toggle']['hide_label'] ) )
			$fields['classes'][] = 'hide-label';

		if ( ! empty( $fields['toggle']['hide_label_mobile'] ) )
			$fields['classes'][] = 'hide-label-mobile';

		$classes = join( ' ', $fields['classes'] );

		include( md_template( 'searchform', true ) );
	}

	/**
	 * Renders the Search Trigger markup, which hooks to header_triggers().
	 *
	 * @since 6.0
	 */

	public function search_trigger() {
		$elements = md_get_builder( 'header' );
		$element_id = ! empty( $elements['search'][0] ) ? $elements['search'][0] : '';
		$title = md_setting( array( 'header', 'builder', $element_id, 'title' ), __( 'Search', 'md' ) );

		$hide_label = md_setting( array( 'header', 'builder', $element_id, 'toggle', 'hide_label' ) );
		$hide_label_mobile = md_setting( array( 'header', 'builder', $element_id, 'toggle', 'hide_label_mobile' ) );
		$label_classes = array( 'trigger', 'trigger-search' );

		if ( $hide_label )
			$label_classes[] = 'hide-label';
		elseif ( $hide_label_mobile )
			$label_classes[] = 'hide-label-mobile';

		$label_classes = join( ' ', $label_classes );

		echo '<span class="' . esc_attr( $label_classes ) . '" title="' . esc_attr( $title ) . '" data-md-parent="header">'.
			md_icon( 'search', array( 'classes' => 'trigger-icon' ) ).
			'<span class="trigger-text">' . md_text_field( $title ) . '</span>'.
		'</span>';
	}

}
