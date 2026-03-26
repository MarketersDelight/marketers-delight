<?php

echo "<$html class=\"" .  esc_attr( $classes ) . '">';

foreach ( $items as $item => $groups ) {
	foreach ( $groups as $group => $fields ) {
        $fields['c'] = $c;

        if ( isset( $data[$item]['template'] ) )
			call_user_func( $data[$item]['template'], $fields );
		else
			include md_template( "byline/$item", true );

		$c++;
	}
}

echo "</$html>";