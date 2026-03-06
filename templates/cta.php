<?php

do_action( "md_hook_before_{$context}_cta" );

echo '<div class="cta">';

do_action( "md_hook_{$context}_cta_top" );

echo wp_kses_post( $html );

do_action( "md_hook_{$context}_cta_bottom" );

echo '</div>';

do_action( "md_hook_after_{$context}_cta" );