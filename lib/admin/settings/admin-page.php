<form id="md-form" class="md wrap" method="post" action="options.php">
	<?php if ( ! empty( $admin_pages[$page_id]['admin_header'] ) ) : ?>
		<div class="md-header md-clear md-content-wrap-med">
			<div class="md-header-title">
				<h1><?php echo __( 'Marketers Delight', 'md' ); ?> <a href="https://marketersdelight.com/changelog/" target="_blank" class="title-count theme-count"><?php echo MD_VERSION; ?></a></h1>
				<?php settings_errors(); ?>
			</div>
			<div class="nav-tab-wrapper">
				<p class="md-links">
					<a href="https://marketersdelight.com/downloads/" target="_blank"><?php echo __( 'Account', 'md' ); ?></a> &nbsp;&middot;&nbsp;
					<a href="https://marketersdelight.com/docs/" target="_blank"><?php echo __( 'Docs', 'md' ); ?></a> &nbsp;&middot;&nbsp;
					<a href="https://marketersdelight.com/affiliates/" target="_blank"><?php echo __( 'Affiliates', 'md' ); ?></a> &nbsp;&middot;&nbsp;
					<a href="https://mdforums.org/" target="_blank"><?php echo __( 'Forums', 'md' ); ?></a>
				</p>
				<h2 class="md-header-nav md-clear">
					<?php foreach ( $admin_pages as $admin_page => $fields ) :
						if ( isset( $fields['parent'] ) && $fields['parent'] == $page )
							$admin_tabs[$fields['id']] = $fields;
						if ( ! isset( $fields['admin_header'] ) )
							continue;
						$name = isset( $fields['tab_name'] ) ? $fields['tab_name'] : $fields['name'];
						$slug = isset( $fields['menu_slug'] ) ? $fields['menu_slug'] : $fields['id'];
					?>
						<a href="<?php echo admin_url( "admin.php?page={$slug}" ); ?>" class="nav-tab<?php echo $page == $fields['id'] ? ' nav-tab-active' : ''; ?>"><?php echo esc_html( $name ); ?></a>
					<?php endforeach; ?>
				</h2>
			</div>
			<?php if ( ! empty( $admin_tabs ) ) : ?>
				<div class="md-submenu">
					<?php foreach ( $admin_tabs as $admin_tab => $child ) : ?>
						<a href="?page=<?php echo urlencode( $page ); ?>&tab=<?php echo $admin_tab; ?>" class="md-submenu-item<?php echo $tab == $admin_tab ? ' md-submenu-active' : ''; ?>" title="<?php echo $child['name']; ?>">
							<?php echo $child['name']; ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php settings_fields( 'marketers_delight' ); ?>
	<?php if ( isset( $admin_pages[$page_id]['callback'] ) ) : ?>
		<?php call_user_func( $admin_pages[$page_id]['callback'] ); ?>
	<?php else : ?>
		<?php do_action( "{$hook}_admin_page" ); ?>
	<?php endif; ?>
</form>