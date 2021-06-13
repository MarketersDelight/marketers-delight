<div id="md_admin_bar" class="md-admin-bar">
	
	<ul class="md-admin-bar-actions menu">
	
		<li class="md-admin-bar-item menu-item">
			<a href="<?php echo get_admin_url(); ?>" class="md-admin-bar-link" target="_blank"><?php echo md_icon( 'wordpress', array( 'classes' => 'mr-small' ) ); ?> <?php echo __( 'Admin', 'md' ); ?></a>
		</li>
	
		<?php if ( ! empty( $edit_url ) ) : ?>
			<li class="md-admin-bar-item menu-item">
				<a href="<?php echo esc_url( $edit_url ); ?>" class="md-admin-bar-link" target="_blank"><?php echo md_icon( 'pencil', array( 'classes' => 'mr-small' ) ); ?> <?php echo __( 'Edit', 'md' ); ?></a>
			</li>
		<?php endif; ?>
	
		<li class="md-admin-bar-item menu-item menu-item-sep menu-item-has-children">
			<a href="#" class="md-admin-bar-link"><?php echo md_icon( 'star', array( 'classes' => 'mr-small' ) ); ?> <?php echo __( 'New', 'md' ); ?></a>
			<ul class="sub-menu">
				<?php foreach ( $add_new_links as $slug => $fields ) : ?>
					<li class="menu-item"><a href="<?php echo admin_url( $slug ); ?>" class="md-admin-bar-link"><?php echo sprintf( __( 'New %s', 'md' ), $fields[0] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</li>
	
	</ul>
	
	<ul class="md-admin-bar-links menu">
		
		<?php foreach ( $links as $link_id => $fields ) : if ( empty( $fields['url'] ) ) continue; ?>
			<li class="md-admin-bar-item menu-item">
				<a href="<?php echo esc_url( $fields['url'] ); ?>" class="md-admin-bar-link"><i class="<?php echo esc_attr( $fields['icon'] ); ?> mr-small"></i> <?php echo esc_html( $fields['name'] ); ?></a>
			</li>
		<?php endforeach; ?>

		<li class="md-admin-bar-item menu-item menu-item-right">
			<a href="<?php echo wp_logout_url(); ?>" class="md-admin-bar-link"><?php echo md_icon( 'eye', array( 'classes' => 'mr-small' ) ); ?> <?php echo __( 'Logout', 'md' ); ?></a>
		</li>

		<li class="md-admin-bar-item menu-item menu-item-right">
			<a href="<?php echo admin_url( 'profile.php' ); ?>" class="md-admin-bar-link"><?php echo md_icon( 'user', array( 'classes' => 'mr-small' ) ); ?> <?php echo esc_html( $display_name ); ?></a>
		</li>
		
	</ul>
	
</div>