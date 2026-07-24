<?php if ( ! md_has_sidebar() ) return;

echo '<aside class="sidebar">';

md_hook_before_sidebar();

dynamic_sidebar( md_get_layout_id( 'sidebar' ) );

md_hook_after_sidebar();

echo '</aside>';
