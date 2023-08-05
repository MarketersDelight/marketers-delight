<?php do_action( 'md_admin_page_before_form' ); ?>

<?php do_action( "{$hook}_admin_page_before_form" ); ?>

<form id="md-form" class="md wrap" method="post" action="options.php">

	<?php if ( ! empty( $admin_pages[$page_id]['admin_header'] ) ) : ?>
		<div class="md-header md-clear md-content-wrap-med">

			<div class="md-header-title">
				<h1><?php echo __( 'Marketers Delight', 'md' ); ?> <a href="https://marketersdelight.com/changelog/" target="_blank" class="title-count theme-count"><?php echo MD_VERSION; ?></a></h1>
				<?php settings_errors(); ?>
			</div>

			<div class="nav-tab-wrapper">

				<p class="md-links">
					<a href="https://marketersdelight.com/docs/" target="_blank" title="Try reading the docs"><?php echo __( 'Docs', 'md' ); ?></a> &nbsp;&middot;&nbsp;
					<a href="https://marketersdelight.com/support/" target="_blank" title="Get tutorials & ask questions"><?php echo __( 'Get support', 'md' ); ?></a> &nbsp;&middot;&nbsp;
					<a href="https://marketersdelight.com/account/" target="_blank" title="Go to your MD.com account"><?php echo __( 'My account', 'md' ); ?></a>
					<a href="https://kolakube.com/" target="_blank" title="by Kolakube"><span class="kolakube"></span></a>
				</p>

				<h2 class="md-header-nav md-clear">
					<?php foreach ( $admin_pages as $admin_page => $fields ) :
						if ( ( isset( $fields['parent'] ) && $fields['parent'] == $page ) || ( isset( $fields['admin_tab_parent'] ) && $fields['admin_tab_parent'] == $page ) ) {
							$admin_tabs[$fields['id']] = $fields;
							$admin_order[$fields['id']] = isset( $fields['order'] ) ? $fields['order'] : 10;
						}
						if ( ! isset( $fields['admin_header'] ) )
							continue;
						$name = isset( $fields['tab_name'] ) ? $fields['tab_name'] : $fields['name'];
						$slug = isset( $fields['menu_slug'] ) ? $fields['menu_slug'] : $fields['id'];
					?>
						<a href="<?php echo admin_url( "admin.php?page={$slug}" ); ?>" class="nav-tab<?php echo $page == $fields['id'] ? ' nav-tab-active' : ''; ?>"><?php echo esc_html( $name ); ?></a>
					<?php endforeach; ?>
				</h2>

			</div>

			<?php if ( ! empty( $admin_tabs ) ) :
				asort( $admin_order );
			?>
				<div class="md-submenu">
					<?php foreach ( $admin_order as $admin_tab => $order ) :
						$child = $admin_tabs[$admin_tab];
						$tab_url = "&tab=$admin_tab";
						if ( isset( $child['admin_tab_parent'] ) && ! isset( $_GET['tab'] ) ) {
							$admin_tab = $tab;
							$tab_url = '';
						}
						if ( isset( $child['admin_tab_parent'] ) && $child['admin_tab_parent'] == $page )
							$tab_url = '';
					?>
						<a href="?page=<?php echo urlencode( $page ) . $tab_url; ?>" class="md-submenu-item<?php echo $tab == $admin_tab ? ' md-submenu-active' : ''; ?>" title="<?php echo esc_attr( $child['name'] ); ?>">
							<?php echo isset( $child['tab_name'] ) ? $child['tab_name'] : $child['name']; ?>
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
