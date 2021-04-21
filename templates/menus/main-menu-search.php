<form role="search" method="get" class="menu-content menu-search" action="<?php echo home_url( '/' ); ?>">
	<span class="menu-trigger md-icon-search"  data-menu-trigger="search"></span>
	<div class="main-menu-search clear">
		<input type="search" id="main_menu_search_input" class="search-input" placeholder="<?php esc_attr_e( 'To search, type and hit enter&hellip;', 'md' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" id="s" />
		<button type="submit" class="search-submit md-icon-search" id="searchsubmit"></button>
	</div>
</form>