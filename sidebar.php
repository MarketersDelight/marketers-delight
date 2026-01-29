<?php if ( md_has_sidebar() ) {

echo '<aside class="sidebar format">';

md_hook_before_sidebar();

dynamic_sidebar( md_get_sidebar_id() );

md_hook_after_sidebar();

echo '</aside>';

}