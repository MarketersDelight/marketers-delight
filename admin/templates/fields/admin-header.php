<?php

$tabs = '';
$screen = $this->_get_screen;
$view = $screen['is_taxonomy'] ? 'term' : 'archive';

if ( empty( $views[$view] ) )
    return;

$tax_groups = $screen['taxonomy_groups'];
$taxonomy_tabs = ! empty( $tax_groups[$screen['page']] ) ? array_keys( $tax_groups[$screen['page']] ) : array();

if ( ! empty( $taxonomy_tabs ) ) {
    $tabs .= '<div class="md-submenu">';
    $tabs .= '<a href="' . esc_url( remove_query_arg( 'md_tab' ) ) . '" class="md-submenu-item' . ( ! $screen['is_taxonomy'] ? ' md-submenu-active' : '' ) . '">' . esc_html__( 'Settings', 'md' ) . '</a>';

	foreach ( $taxonomy_tabs as $tax_slug ) {
		$taxonomy = get_taxonomy( $tax_slug );
		$tax_label = $taxonomy ? $taxonomy->labels->name : ucwords( str_replace( array( '_', '-' ), ' ', $tax_slug ) );
		$tabs .= '<a href="' . esc_url( add_query_arg( 'md_tab', $tax_slug ) ) . '" class="md-submenu-item' . ( $screen['md_tab'] === $tax_slug ? ' md-submenu-active' : '' ) . '">' . esc_html( $tax_label ) . '</a>';
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
