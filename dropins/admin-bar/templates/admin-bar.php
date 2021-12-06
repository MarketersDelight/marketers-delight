<div id="md_admin_bar" class="md-admin-bar">
	
	<ul class="md-admin-bar-actions menu">
	
		<li class="md-admin-bar-item menu-item">
			<a href="<?php echo get_admin_url(); ?>" class="md-admin-bar-link" target="_blank"><?php echo md_icon( 'wordpress' ); ?> <?php echo __( 'Admin', 'md-admin-bar' ); ?></a>
		</li>
	
		<?php if ( ! empty( $edit_url ) ) : ?>
			<li class="md-admin-bar-item menu-item">
				<a href="<?php echo esc_url( $edit_url ); ?>" class="md-admin-bar-link" target="_blank"><?php echo md_icon( 'pencil' ); ?> <?php echo __( 'Edit', 'md-admin-bar' ); ?></a>
			</li>
		<?php endif; ?>
	
		<li class="md-admin-bar-item menu-item menu-item-sep menu-item-has-children">
			<a href="#" class="md-admin-bar-link"><?php echo md_icon( 'star' ); ?> <?php echo __( 'New', 'md-admin-bar' ); ?></a>
			<ul class="sub-menu">
				<?php foreach ( $add_new_links as $slug => $fields ) : ?>
					<li class="menu-item"><a href="<?php echo admin_url( $slug ); ?>" class="md-admin-bar-link"><?php echo sprintf( __( 'New %s', 'md-admin-bar' ), $fields[0] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</li>
	
	</ul>
	
	<ul class="md-admin-bar-links menu">

		<?php foreach ( $links as $link_id => $fields ) :
			$link_type = $fields['link_type'];
			$url = ! empty( $fields['url'] ) ? $fields['url'] : '';
			$icon = ! empty( $fields['icon'] ) ? $fields['icon'] : '';
			if ( empty( $fields['name'] ) || ( $link_type == 'custom' && empty( $fields['url'] ) ) )
				continue;
			if ( ! empty( $link_type ) && $link_type != 'custom' ) {
				$url = $link_types[$link_type]['url'];
				$icon = $link_types[$link_type]['icon'];
			}
		?>
			<li class="md-admin-bar-item menu-item">
				<a href="<?php echo esc_url( $url ); ?>" class="md-admin-bar-link"><i class="<?php echo esc_attr( $icon ); ?>"></i> <?php echo esc_html( $fields['name'] ); ?></a>
			</li>
		<?php endforeach; ?>

		<li class="md-admin-bar-item menu-item menu-item-right">
			<a href="<?php echo wp_logout_url(); ?>" class="md-admin-bar-link"><?php echo md_icon( 'eye' ); ?> <?php echo __( 'Logout', 'md-admin-bar' ); ?></a>
		</li>

		<li class="md-admin-bar-item menu-item menu-item-right">
			<a href="<?php echo admin_url( 'profile.php' ); ?>" class="md-admin-bar-link"><?php echo md_icon( 'user' ); ?> <?php echo esc_html( $display_name ); ?></a>
		</li>
		
	</ul>
	
</div>