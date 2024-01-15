<div class="main-menu">

	<div class="inner">

		<nav class="menu-wrap">

			<?php wp_nav_menu( array(
				'theme_location' => 'main_menu',
				'container' => false,
				'fallback_cb' => false,
				'menu_class' => 'menu menu-main menu-primary sub-alt',
				'walker' => new md_menu_walker( true, true )
			) ); ?>

		</nav>

	</div>

</div>