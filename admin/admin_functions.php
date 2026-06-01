<?php

/**
 * Default post type screens MD metaboxes are added to.
 *
 * @since 4.3.5
 */

function md_post_type_meta() {
	return apply_filters( 'md_post_type_meta', array( 'post', 'page' ) );
}

/**
 * Default taxonomy screens MD metaboxes are added to.
 *
 * @since 4.5.4
 */

function md_edit_term_meta() {
	return apply_filters( 'md_edit_term_meta', array( 'category' ) );
}

/**
 * Taxonomies that have global settings tabs on post-type admin pages.
 *
 * @since 6.1
 */

function md_taxonomy_meta() {
	return apply_filters( 'md_taxonomy_meta', array() );
}

/**
 * Return ID without MD_ prefix.
 *
 * @since 5.0
 */

function md_clean_id( $id ) {
	return ( ! empty( $id ) ? preg_replace( '/^' . preg_quote( 'md_', '/' ) . '/', '', $id ) : '' );
}

/**
 * A collection of all registered components and fields for build and save.
 *
 * @since 5.0
 */

function md_register( $group = null ) {
	$data = apply_filters( 'md_register', array() );

	if ( isset( $group ) )
		return ! empty( $data[$group] ) ? $data[$group] : array();

	return $data;
}

/**
 * A list of page settings modules to add across various page types.
 *
 * @since 6.0
 */

function md_admin_settings() {
	return apply_filters( 'md_admin_settings', array() );
}

/**
 * A reverse list of admin settings locations by fields.
 *
 * @since 6.0
 */

function md_admin_fields() {
	$fields = array();
	$settings = md_admin_settings();

	foreach ( $settings as $setting => $options )
		foreach ( $options as $field )
			$fields[$field][] = $setting;

	return $fields;
}

/**
 * Run this function to activate drop-ins to the MD Drop-ins
 * Manager. Not recommended for use outside of upgrader utilities.
 *
 * @since 5.4
 */

function md_activate_dropin( $dropin ) {
	$option = md_setting();

	if ( empty( $option['dropins']['installed'][$dropin] ) )
		return false;

	$option['dropins']['installed'][$dropin]['status']['enable'] = true;

	update_option( 'marketers_delight', $option );
}

/**
 * Check if any given drop-in is active by looking up drop-in
 * file path (ex: pass `dropin-name/dropin-name.php` as $path).
 *
 * @since 5.4
 */

function md_is_dropin_active( $path ) {
	$active = md_get_dropins( 'active' );
	$basename = str_replace( '.php', '', basename( $path ) );

	if ( in_array( $basename, $active ) )
		return true;

	return false;
}

/**
 * Return a list of details as entered from Drop-in DocBlock.
 *
 * @note Based off core function get_plugin_data()
 * @since 5.4
 */

function md_get_dropin_data( $dropin_file ) {
	$default_headers = array(
		'Name' => 'Drop-in Name',
		'DropinURI' => 'Drop-in URI',
		'Version' => 'Version',
		'Description' => 'Description',
		'Slug' => 'Drop-in Slug',
		'Author' => 'Author',
		'AuthorURI' => 'Author URI',
		'TextDomain' => 'Text Domain',
		'DomainPath' => 'Domain Path',
		'Network' => 'Network',
		'RequiresWP' => 'Requires at least',
		'RequiresPHP' => 'Requires PHP',
		'UpdateURI' => 'Update URI',
		'_sitewide'   => 'Site Wide Only'
	);

	$dropin_data = get_file_data( $dropin_file, $default_headers, 'dropin' );

	if ( ! $dropin_data['Network'] && $dropin_data['_sitewide'] ) {
		_deprecated_argument( __FUNCTION__, '3.0.0', sprintf( __( 'The %1$s drop-in header is deprecated. Use %2$s instead.' ), '<code>Site Wide Only: true</code>', '<code>Network: true</code>' ) );
		$dropin_data['Network'] = $dropin_data['_sitewide'];
	}

	$dropin_data['Network'] = ( 'true' === strtolower( $dropin_data['Network'] ) );

	unset( $dropin_data['_sitewide'] );

	// If no text domain is defined fall back to the plugin slug.
	if ( ! $dropin_data['TextDomain'] ) {
		$dropin_slug = $dropin_data['Slug'];

		if ( '.' !== $dropin_slug && false === strpos( $dropin_slug, '/' ) )
			$dropin_data['TextDomain'] = $dropin_slug;
	}

	return $dropin_data;
}

/**
 * Render taxonomy tab navigation for an admin page.
 *
 * @since 6.1
 */

function md_taxonomy_tabs( $taxonomy_tabs = null, $active_tab = null ) {
	if ( is_null( $taxonomy_tabs ) ) {
		$page_slug     = isset( $_GET['page'] ) ? sanitize_key( $_GET['page'] ) : '';
		$tax_groups    = apply_filters( 'md_taxonomy_groups', array() );
		$taxonomy_tabs = ! empty( $tax_groups[$page_slug] ) ? array_keys( $tax_groups[$page_slug] ) : array();
	}

	if ( is_null( $active_tab ) )
		$active_tab = isset( $_GET['md_tab'] ) ? sanitize_key( $_GET['md_tab'] ) : '';

	if ( empty( $taxonomy_tabs ) )
		return;

	echo '<div class="md-submenu md-sep">';

	echo '<a href="' . esc_url( remove_query_arg( 'md_tab' ) ) . '" class="md-submenu-item' . ( empty( $active_tab ) ? ' md-submenu-active' : '' ) . '">' . esc_html__( 'Archives', 'md' ) . '</a>';

	foreach ( $taxonomy_tabs as $tax_slug ) {
		echo '<a href="' . esc_url( add_query_arg( 'md_tab', $tax_slug ) ) . '" class="md-submenu-item' . ( $active_tab === $tax_slug ? ' md-submenu-active' : '' ) . '">';
		echo esc_html( ucwords( str_replace( array( '_', '-' ), ' ', $tax_slug ) ) );
		echo '</a>';
	}

	echo '</div>';
}