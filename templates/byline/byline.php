<?php

ob_start();

foreach ( $items as $item => $groups )
	foreach ( $groups as $group => $fields ) {
        $fields['c'] = $c;
		$fields['post_type'] = $fields['post_type'] ?? $post_type;

        if ( isset( $data[$item]['template'] ) )
			call_user_func( $data[$item]['template'], $fields );
		else
			include md_template( "byline/$item", true );

		$c++;
	}

$content = trim( ob_get_clean() );

if ( empty( $content ) )
	return;

echo "<$html class=\"" .  esc_attr( $classes ) . '">' . $content . "</$html>";
