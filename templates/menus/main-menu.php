<nav id="main_menu" class="main-menu">
	<div class="inner">

		<div class="main-menu-triggers main-menu-triggers-<?php echo md_main_menu_items(); ?> clear">

			<?php if ( has_nav_menu( 'main' ) ) : ?>
				<span class="menu-trigger menu-trigger-menu" data-menu-trigger="menu">
					<?php echo md_icon( 'menu' ); ?> <span class="menu-trigger-text"><?php echo md_get_menu_name( 'main' ); ?></span>
				</span>
			<?php endif; ?>

			<?php do_action( 'md_hook_main_menu_triggers' ); ?>

			<?php if ( md_main_menu_has_search() ) : ?>
				<span class="menu-trigger menu-trigger-search" data-menu-trigger="search">
					<?php echo md_icon( 'search' ); ?> <span class="menu-trigger-text"><?php _e( 'Search', 'md' ); ?></span>
				</span>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<span class="menu-trigger menu-trigger-social" data-menu-trigger="social">
					<?php echo md_icon( 'user-add' ); ?> <span class="menu-trigger-text"><?php echo md_get_menu_name( 'social' ); ?></span>
				</span>
			<?php endif; ?>

			<?php do_action( 'md_main_menu_triggers_bottom' ); ?>

		</div>

		<div class="main-menu-side">

			<?php do_action( 'md_main_menu_side_triggers' ); ?>

			<?php if ( md_main_menu_has_search() ) : ?>
				<?php md_template( 'menus/main-menu-search' ); ?>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<?php wp_nav_menu( array(
					'theme_location' => 'social',
					'container' => false,
					'fallback_cb' => false,
					'menu_class' => 'menu menu-content menu-social',
					'depth' => 1,
					'walker' => new md_menu_walker( false )
				) ); ?>
			<?php endif; ?>

		</div>

		<?php if ( has_nav_menu( 'main' ) ) : ?>
			<?php wp_nav_menu( array(
				'theme_location' => 'main',
				'menu' => md_main_menu_custom_menu(),
				'container' => false,
				'fallback_cb' => false,
				'menu_class' => 'menu menu-content menu-main',
				'walker' => new md_menu_walker( true, true )
			) ); ?>
		<?php endif; ?>

	</div>
</nav>