<?php

/**
 * A list of classes to add to the header.
 *
 * @since 4.1
 */

function md_header_classes() {
	$classes = array();
	$classes[] = 'header';

	if ( ! md_has_menu() || ! md_has_logo() || ! md_has_header_elements() )
		$classes[] = 'simple';
	else
		$classes[] = md_setting( array( 'header', 'layout' ), 'left' );

	if ( md_setting( array( 'header', 'display', 'sticky' ) ) )
		$classes[] = 'sticky';

	$classes = apply_filters( 'md_filter_header_classes', $classes );

	return join( ' ', $classes );
}

/**
 * Add classes to the Header Wrap area.
 *
 * @since 4.6
 */

function md_header_wrap_classes() {
	$classes = array();
	$classes[] = 'wrap';
	$classes = apply_filters( 'md_header_wrap_classes', $classes );

	return join( ' ', $classes );
}

/**
 * Checks if header is enabled.
 *
 * @since 4.1
 */

function md_has_header() {
	if ( ! md_module( array( 'layout', 'header', 'remove' ) ) && ( md_has_logo() || md_has_menu() ) )
		return apply_filters( 'md_filter_has_header', true );
}

/**
 * Check if removing all header elements.
 *
 * @since 6.0
 */

function md_has_header_elements() {
	$elements = md_get_builder( 'header', 'elements' );

	if ( ! md_has_menu() )
		unset( $elements['menu'] );

	if ( $elements &&
		! md_module( array( 'layout', 'header', 'remove' ) ) &&
		! md_module( array( 'layout', 'header', 'elements' ) )
	) return true;
}

/**
 * Outputs the menu name assigned to the specified Menu area.
 *
 * @since 4.0
 */

function md_get_menu_name( $menu ) {
	$menus = get_nav_menu_locations();

	if ( ! empty( $menus[$menu] ) ) {
		$menu_object = wp_get_nav_menu_object( $menus[$menu] );
		$menu_name = isset( $menu_object->name ) ? $menu_object->name : '';
	}

	if ( empty( $menu_name ) )
		$menu_name = __( 'Menu', 'md' );

	return esc_html( $menu_name );
}

/**
 * Checks if menu is enabled.
 *
 * @since 4.1
 */

function md_has_menu() {
	$elements = md_get_builder( 'header', 'elements' );

	if (
		! md_module( array( 'layout', 'header', 'remove' ) ) &&
		! md_module( array( 'layout', 'header', 'menu' ) ) &&
		( ! empty( $elements['menu'] ) || ( empty( $elements ) && has_nav_menu( 'header' ) ) )
	) return true;
}

/**
 * Markup for a WP nav menu that accepts customized parameters.
 *
 * @since 4.0
 */

function md_menu( $fields = array() ) {
	if ( ! md_has_menu() )
		return;

	$location = isset( $fields['area'] ) ? $fields['area'] : 'primary';
	$menu_id = isset( $fields['menu'] ) ? $fields['menu'] : '';
	$menu_class = 'menu menu-' . esc_attr( $location );

	if ( ( isset( $fields['layout'] ) || isset( $fields['location'] ) ) && ( $fields['layout'] == 'right' || ( $fields['layout'] == 'center' && $fields['location'] == 'primary' ) ) )
		$menu_class .= ' sub-alt';

	$args = array(
		'menu' => md_module( array( 'layout', 'header_menu' ), $menu_id ),
		'menu_class' => esc_attr( $menu_class ),
		'container' => 'nav',
		'container_class' => "header-menu $location-menu",
		'echo' => false,
		'fallback_cb' => false,
		'walker' => new md_menu_walker( true, true )
	);

	if ( empty( $menu_id ) )
		$args['theme_location'] = is_user_logged_in() && has_nav_menu( 'header_loggedin' ) ? 'header_loggedin' : 'header';

	$menu = wp_nav_menu( $args );

	if ( $menu ) echo
		( isset( $fields['wrap'] ) ? '<div class="' . esc_attr( $fields['wrap'] ) . '">' : '' ).
		$menu.
		( isset( $fields['wrap'] ) ? '</div>' : '' );
}

/**
 * Render trigger elements to control states of header items.
 *
 * @since 6.0
 */

function md_trigger( $type = 'menu', $args = array() ) {
	$classes = array( 'trigger', "trigger-{$type}" );
	$elements = md_get_builder( 'header', 'elements' );
	$id = ! empty( $elements[$type][0] ) ? $elements[$type][0] : '';
	$location = md_get_builder( 'header', 'locations', $id );
	$location = ! empty( $location ) ? $location : 'primary';
	$parent = isset( $args['parent'] ) ? $args['parent'] : 'header';

	$title = isset( $args['title'] ) ? $args['title'] : '';
	$title = empty( $title ) && $type == 'menu' ? md_get_menu_name( 'header' ) : $title;
	$title = md_setting( array( 'header', 'builder', $id, 'name' ), $title );

	$hide_label = md_setting( array( 'header', 'builder', $id, 'toggle', 'hide_label' ) );
	$hide_label_mobile = md_setting( array( 'header', 'builder', $id, 'toggle', 'hide_label_mobile' ) );

	if ( $hide_label )
		$classes[] = 'hide-label';
	elseif ( $hide_label_mobile )
		$classes[] = 'hide-label-mobile';

	$classes = join( ' ', $classes );

	echo
		'<span class="' . esc_attr( $classes ) . '" title="' . esc_attr( $title ) . '" data-md-parent="' . esc_attr( $parent ) . '" data-md-trigger="' . esc_attr( $type ) . '" data-md-location="' . esc_attr( $location ) . '">'.
			md_icon( $type, array( 'classes' => 'trigger-icon' ) ).
			'<span class="trigger-text">' . md_text_field( $title ) . '</span>'.
		'</span>';
}

/**
 * Displays the logo, used in header by default.
 *
 * @since 4.1
 */

function md_logo() {
	include md_template( 'logo', true );
}

/**
 * Check for MD Site Title.
 *
 * @since 4.5.4
 */

function md_has_site_title() {
	if ( ! md_setting( array( 'header', 'display', 'site_title' ) ) )
		return true;
}

/**
 * Render Site Title as default WP text or custom title.
 * Only use to display text on page, not recommended in <title>.
 *
 * @since 5.5.8
 */

function md_site_title() {
	return md_setting( array( 'logo', 'site_title', 'text' ), get_bloginfo( 'name' ) );
}

/**
 * Checks if page has tagline.
 *
 * @since 4.4.2
 */

function md_has_tagline() {
	if ( get_bloginfo( 'description' ) && ! md_setting( array( 'header', 'display', 'site_tagline' ) ) && ! md_module( array( 'layout', 'header', 'tagline' ) ) )
		return true;
}

/**
 * Render Site Tagline as default WP text or custom tagline.
 *
 * @since 5.5.8
 */

function md_site_tagline() {
	return md_setting( array( 'logo', 'site_tagline', 'text' ), get_bloginfo( 'description' ) );
}

/**
 * Checks if logo is enabled through custom options.
 *
 * @since 4.1
 */

function md_has_logo() {
	if ( ( md_custom_logo() || md_has_site_title() || md_has_tagline() ) && ! md_module( array( 'layout', 'header', 'logo' ) ) )
		return true;
}

/**
 * Render custom logo image/markup.
 *
 * @since 4.5.4
 * @renamed 6.0 (formerly md_has_custom_logo)
 */

function md_custom_logo() {
	$custom_logo = false;
	$logo = md_setting( 'logo' );

	if ( ! empty( $logo['logo_html_display']['enable'] ) && ! empty( $logo['logo_html'] ) )
		$custom_logo = $logo['logo_html'];
	elseif ( ! empty( $logo['logo']['id'] ) ) {
        $logo_id = $logo['logo']['id'];
		$cover = md_cover();

		if ( ! empty( $logo['logo_alt']['id'] ) && $cover['position'] == 'header_cover_full' )
			$logo_id = $logo['logo_alt']['id'];

		$custom_logo = wp_get_attachment_image( $logo_id, 'full' );
	}

	if ( $custom_logo )
		$custom_logo =
        '<div class="logo">'.
        ( ! md_has_site_title() ? '<a href="' . home_url( '/' ) . '">' : '' ).
        $custom_logo.
        ( ! md_has_site_title() ? '</a>' : '' ).
        '</div>';

	return $custom_logo;
}