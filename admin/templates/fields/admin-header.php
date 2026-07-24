<?php

$tokens = $tabs = '';
$screen = $this->_get_screen;
$view = $screen['is_taxonomy'] ? 'term' : 'archive';

if ( empty( $views[$view] ) )
    return;

foreach ( md_parse_tokens( array( 'context' => $view, 'list' => true ) ) as $token => $label )
    $tokens .= "<span class=\"md-token\"><code>$token</code> $label</span>";

$tax_groups = $screen['taxonomy_groups'];
$taxonomy_tabs = ! empty( $tax_groups[$screen['page']] ) ? array_keys( $tax_groups[$screen['page']] ) : array();

if ( ! empty( $taxonomy_tabs ) ) {
    $tabs = '<hr class="mb-none" />';
    $tabs .= '<div class="md-submenu md-sep">';
    $tabs .= '<a href="' . esc_url( remove_query_arg( 'md_tab' ) ) . '" class="md-submenu-item' . ( ! $screen['is_taxonomy'] ? ' md-submenu-active' : '' ) . '">' . esc_html__( 'Settings', 'md' ) . '</a>';

    foreach ( $taxonomy_tabs as $tax_slug )
        $tabs .= '<a href="' . esc_url( add_query_arg( 'md_tab', $tax_slug ) ) . '" class="md-submenu-item' . ( $screen['md_tab'] === $tax_slug ? ' md-submenu-active' : '' ) . '">' . esc_html( ucwords( str_replace( array( '_', '-' ), ' ', $tax_slug ) ) ) . '</a>';

    $tabs .= '</div>';
}
else
    $tabs .= '<hr class="md-sep-small" />';

echo
    '<div class="md-admin-header">'.
    '<div class="md-admin-header-content">'.
    '<h1>' . $views[$view]['title'] . '</h1>'.
    '<p class="md-description">' . $views[$view]['description'] . '</p>'.
    '</div>'.
    '<div class="md-admin-header-meta notice notice-info is-dismissible inline">'.
    '<p><b>' . __( 'Available tokens on this page:', 'md-docs' ) . '</b></p>'.
    '<div class="md-tokens-list">' . $tokens . '</div>'.
    '</div>'.
    '</div>'.
    $tabs;
