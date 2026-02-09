<?php

do_action( "md_hook_before_{$context}_description" );

echo '<div class="description">' . wpautop( $description );

if ( isset( $args['show_cta'] ) )
	md_cta( $context );

echo '</div>';

do_action( "md_hook_after_{$context}_description" );