<?php

echo '<main id="main" class="' . md_content_box_classes() . '">';

md_hook_content_box_top();

echo ( md_has_sidebar() ? '<div class="content-wrap inner">' : '' );

echo ( ! md_has_builder() ? '<div class="content' . ( is_singular() ? ' ' . md_loop_classes() : '' ) . '">' : '' );

md_hook_before_content();

md_hook_content();

md_hook_after_content();

echo ( ! md_has_builder() ? '</div>' : '' );

get_sidebar();

if ( md_has_panel() )
    md_template( 'panel' );

echo ( md_has_sidebar() ? '</div>' : '' );

md_hook_content_box_bottom();

echo "</main>";