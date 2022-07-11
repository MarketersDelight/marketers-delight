<form role="search" method="get" class="menu-content menu-search" action="<?php echo home_url( '/' ); ?>">
	<span class="menu-trigger <?php echo md_icon( 'search', true ); ?>" data-menu-trigger="search"></span>
	<div class="main-menu-search">
		<input type="search" id="main_menu_search_input" class="search-input" placeholder="<?php esc_attr_e( 'To search, type and hit enter&hellip;', 'md' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" id="s" />
		<button type="submit" class="search-submit <?php echo md_icon( 'search', true ); ?>" id="searchsubmit"></button>
	</div>
</form>