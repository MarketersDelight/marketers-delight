<form role="search" method="get" class="menu-content menu-search clear" action="<?php echo home_url( '/' ); ?>">
	<input type="search" id="main_menu_search_input" class="search-input" placeholder="<?php esc_attr_e( 'To search, type and hit enter&hellip;', 'md' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" id="s" />
	<button type="submit" class="search-submit <?php echo md_icon( 'search', true ); ?>" id="searchsubmit"></button>
</form>