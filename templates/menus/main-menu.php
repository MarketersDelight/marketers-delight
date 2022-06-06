<nav id="main_menu" class="main-menu">
	<div class="inner">

		<?php if ( has_nav_menu( 'main' ) ) : ?>
			<div class="menu-main-wrap">
				<?php wp_nav_menu( array(
					'theme_location' => 'main',
					'menu' => md_meta( array( 'layout', 'main_menu_menu' ) ),
					'container' => false,
					'fallback_cb' => false,
					'items_wrap' => '<ul id="menu_main" class="%2$s">%3$s</ul>',
					'menu_class' => 'menu menu-main',
					'walker' => new md_menu_walker( true, true )
				) ); ?>
			</div>
		<?php endif; ?>

		<div id="main_menu_controls" class="main-menu-side">

			<span class="menu-scroller menu-trigger <?php echo md_icon( 'angle-right', true ); ?>" data-menu-trigger="scrolled"></span>

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

			<?php if ( md_main_menu_has_search() ) : ?>
				<?php md_template( 'menus/main-menu-search' ); ?>
			<?php endif; ?>

			<div class="menu-triggers">

				<span class="menu-trigger menu-trigger-social <?php echo md_icon( 'user-add', true ); ?>" data-menu-trigger="social"></span>

				<?php do_action( 'md_main_menu_side_triggers' ); ?>				

				<?php if ( md_main_menu_has_search() ) : ?>
					<span class="menu-trigger menu-trigger-search <?php echo md_icon( 'search', true ); ?>" data-menu-trigger="search"></span>
				<?php endif; ?>

			</div>

		</div>

	</div>
</nav>