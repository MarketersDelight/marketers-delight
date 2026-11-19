<?php

$stack_html = '';

if ( $stack ) {
	ob_start();

	foreach ( $stack as $item => $groups )
		foreach ( $groups as $group => $fields ) {
			$fields['post_type'] = $fields['post_type'] ?? $post_type;
			md_byline_render( $item, $fields, $data );
		}

	$stack_html = trim( ob_get_clean() );
}

ob_start();

foreach ( $items as $item => $groups )
	foreach ( $groups as $group => $fields ) {
        $fields['c'] = $c;
		$fields['post_type'] = $fields['post_type'] ?? $post_type;

		if ( $item === 'author' && $stack_html ) {
			$fields['stack'] = $stack_html;
			$stack_html = '';
		}

		md_byline_render( $item, $fields, $data );

		$c++;
	}

$content = trim( ob_get_clean() );

if ( empty( $content ) )
	return;

echo "<$html class=\"" .  esc_attr( $classes ) . '">' . $content . "</$html>";
