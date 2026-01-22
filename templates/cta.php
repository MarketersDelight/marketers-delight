<?php $html = '';

if ( $type == 'links' && ! empty( $cta['links'] ) ) {
	foreach ( $links as $group => $fields )
		if ( ! empty( $links[$group] ) ) {
			$links[$group]['classes'] = 'cta-link';
			$html .= md_get_link( $links[$group] );
		}
}
elseif ( $type == 'custom' && ! empty( $cta['custom_html'] ) )
	$html = $cta['custom_html'];

if ( empty( $html ) )
	return;

do_action( "md_hook_before_{$context}_cta" );

echo '<div class="cta">';

do_action( "md_hook_{$context}_cta_top" );

echo $html;

do_action( "md_hook_{$context}_cta_bottom" );

echo '</div>';

do_action( "md_hook_after_{$context}_cta" );