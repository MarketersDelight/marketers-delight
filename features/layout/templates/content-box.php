<?php

echo '<main id="main" class="' . md_content_box_classes() . '">';

md_hook_content_box_top();

echo '<div class="content-wrap' . ( ! $has_builder ? ' inner' : '' ) . '">';

echo ( ! $has_builder ? '<div class="content' . ( is_singular() ? " $loop_classes" : '' ) . '">' : '' );

md_hook_before_content();

md_hook_content();

md_hook_after_content();

echo ( ! $has_builder ? '</div>' : '' );

get_sidebar();

if ( md_has_panel() )
    md_template( 'features', 'layout/panel' );

echo '</div>';

md_hook_content_box_bottom();

echo "</main>";
