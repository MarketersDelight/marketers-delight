<?php

echo "<$html id=\"content\" class=\"" . md_content_box_classes() . '">';

md_hook_content_box_top();

echo md_has_sidebar() ? '<div class="inner">' : '';

md_hook_content_top();

echo "<$inner_html class=\"" . md_content_classes() . '">';

md_hook_before_content();

md_hook_content();

md_hook_after_content();

echo "</$inner_html>";

get_sidebar();

md_hook_content_bottom();

echo md_has_sidebar() ? '</div>' : '';

md_hook_content_box_bottom();

echo "</$html>";