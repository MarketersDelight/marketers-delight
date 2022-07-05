<?php if ( md_has_menu() ) : ?>
	<span id="header-menu-trigger" class="header-menu-trigger header-trigger">
		<?php echo md_icon( 'menu', array( 'classes' => 'header-menu-trigger-icon' ) ); ?>
		<span class="header-trigger-text"><?php echo md_get_menu_name( 'header' ); ?></span>
	</span>
<?php endif; ?>