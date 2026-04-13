<?php

echo "<main class=\"" . md_content_box_classes() . '">';

echo is_singular() && md_has_header_cover() ? '<article class="article-wrap">' : '';

md_hook_content_box_top();

echo md_has_sidebar() ? '<div class="content-wrap inner">' : '';

echo '<div class="content' . ( is_singular() ? ' ' . md_loop_classes() : '' ) . '">';

md_hook_before_content();

md_hook_content();

md_hook_after_content();

echo '</div>';

get_sidebar();

echo md_has_sidebar() ? '</div>' : '';

md_hook_content_box_bottom();

echo is_singular() && md_has_header_cover() ? '</article>' : '';

echo "</main>";