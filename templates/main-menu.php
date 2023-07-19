<div id="main_menu" class="main-menu">
	<div class="inner">

		<div class="main-menu-wrap">

			<?php if ( has_nav_menu( 'main' ) ) : ?>
				<?php wp_nav_menu( array(
					'theme_location' => 'main',
					'menu' => $menu,
					'container' => false,
					'fallback_cb' => false,
					'menu_class' => 'menu menu-main',
					'walker' => $walker
				) ); ?>
			<?php endif; ?>

			<?php do_action( 'md_hook_main_menu' ); ?>

		</div>

	</div>
</div>