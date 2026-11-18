<?php

$tabs = '';
$screen = $this->_get_screen;
$view = $screen['is_taxonomy'] ? 'term' : 'archive';

if ( empty( $views[$view] ) )
    return;

$tax_groups = $screen['taxonomy_groups'];
$taxonomy_tabs = ! empty( $tax_groups[$screen['page']] ) ? array_keys( $tax_groups[$screen['page']] ) : array();
$screen_type = strpos( $screen['page'], 'md_' ) === 0 ? substr( $screen['page'], 3 ) : '';
$shared_tabs = array();

foreach ( $screen_type ? md_taxonomy_meta() : array() as $tax_slug )
	if ( is_object_in_taxonomy( $screen_type, $tax_slug ) && md_is_shared_taxonomy( $tax_slug ) && md_taxonomy_post_type( $tax_slug ) !== $screen_type )
		$shared_tabs[$tax_slug] = add_query_arg( 'md_tab', $tax_slug, menu_page_url( 'md_' . md_taxonomy_post_type( $tax_slug ), false ) );

if ( $taxonomy_tabs || $shared_tabs ) {
    $tabs .= '<div class="md-submenu">';
    $tabs .= '<a href="' . esc_url( remove_query_arg( 'md_tab' ) ) . '" class="md-submenu-item' . ( ! $screen['is_taxonomy'] ? ' md-submenu-active' : '' ) . '">' . esc_html__( 'Settings', 'md' ) . '</a>';

	foreach ( $taxonomy_tabs as $tax_slug ) {
		$taxonomy = get_taxonomy( $tax_slug );
		$tax_label = $taxonomy ? $taxonomy->labels->name : ucwords( str_replace( array( '_', '-' ), ' ', $tax_slug ) );
		$tabs .= '<a href="' . esc_url( add_query_arg( 'md_tab', $tax_slug ) ) . '" class="md-submenu-item' . ( $screen['md_tab'] === $tax_slug ? ' md-submenu-active' : '' ) . '">' . esc_html( $tax_label ) . '</a>';
	}

	foreach ( $shared_tabs as $tax_slug => $tax_url ) {
		$taxonomy = get_taxonomy( $tax_slug );
		$tabs .= '<a href="' . esc_url( $tax_url ) . '" class="md-submenu-item">' . esc_html( $taxonomy->labels->name ) . '</a>';
	}

    $tabs .= '</div>';
}

echo
    '<div class="md-admin-header">'.
    '<div class="md-admin-header-content">'.
    '<h1>' . $views[$view]['title'] . '</h1>'.
    '<p class="md-description">' . $views[$view]['description'] . '</p>'.
    '</div>'.
    '</div>'.
    md_save_settings::bar( $tabs );
