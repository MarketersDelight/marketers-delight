<div class="main-menu">

	<div class="inner">

		<div class="main-menu-bar">
			<span class="main-menu-title"><?php echo md_get_menu_name( 'main_menu' ); ?></span>
			<span id="main_menu_close" class="close circle-icon"><?php echo md_icon( 'cancel' ); ?></span>
		</div>

		<?php if ( ! empty( $menus ) )
			foreach ( $menus as $menu_id )
				if ( ! empty( $header[$menu_id]['menu'] ) )
					$this->menu( array(
						'area' => 'main-menu',
						'menu' => $header[$menu_id]['menu']
					) );
		?>

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

<div id="main_menu_overlay" class="main-menu-overlay"></div>