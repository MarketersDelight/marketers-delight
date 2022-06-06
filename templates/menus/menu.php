<?php md_hook_before_header_menu(); ?>

<nav class="header-menu">
	<?php wp_nav_menu( array(
		'theme_location' => $header_menu_location,
		'menu' => md_meta( array( 'layout', 'header_menu' ) ),
		'container' => false,
		'fallback_cb' => false,
		'menu_class' => 'menu menu-header',
		'walker' => new md_menu_walker( true, true )
	) ); ?>
</nav>

<?php md_hook_after_header_menu(); ?>