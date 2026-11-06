<?php
	$theme_version = trim( (string) MD_VERSION );

	do_action( 'md_admin_page_before_form' );
	do_action( "{$hook}_admin_page_before_form" );

	$form_page = md_clean_id( $hook );
	$has_form = ! isset( $admin_pages[$form_page]['form'] ) || $admin_pages[$form_page]['form'] !== false;
?>

<?php if ( $has_form ) : ?>
<form id="md-form" class="md md-form wrap" method="post" action="<?php echo esc_url( md_save_settings::url() ); ?>">
<?php else : ?>
<div id="md-form" class="md md-form wrap">
<?php endif; ?>

	<?php if ( ! empty( $admin_pages[$page_id]['admin_header'] ) ) : ?>

	<div class="md-header md-content-wrap-wide md-clear">

		<div class="md-header-title">
			<h1><?php echo __( 'Marketers Delight', 'md' ); ?><?php if ( ! empty( $theme_version ) ) : ?> <a href="https://marketersdelight.com/changelog/" target="_blank" class="title-count theme-count"><?php echo esc_html( $theme_version ); ?></a><?php endif; ?></h1>
			<?php settings_errors(); ?>
		</div>

		<div class="nav-tab-wrapper">

			<p class="md-links">
				<a href="https://marketersdelight.com/docs/" target="_blank" title="Try reading the docs"><?php echo __( 'Docs', 'md' ); ?></a> &nbsp;&middot;&nbsp;
				<a href="https://marketersdelight.com/?xfwp_forum=1" target="_blank" title="Get tutorials & ask questions"><?php echo __( 'Get support', 'md' ); ?></a> &nbsp;&middot;&nbsp;
				<a href="https://marketersdelight.com/account/" target="_blank" title="Go to your MD.com account"><?php echo __( 'My account', 'md' ); ?></a>
				<a href="https://kolakube.com/" target="_blank" title="by Kolakube"><span class="kolakube"></span></a>
			</p>

			<h2 class="md-header-nav md-clear">

				<?php foreach ( $admin_pages as $admin_page => $fields ) :
					if ( ( isset( $fields['parent'] ) && $fields['parent'] == $page ) || ( isset( $fields['admin_tab_parent'] ) && $fields['admin_tab_parent'] == $page ) ) {
						$admin_tabs[$fields['id']] = $fields;
						$admin_order[$fields['id']] = isset( $fields['order'] ) ? $fields['order'] : 10;
					}

					if ( ! isset( $fields['admin_header'] ) || isset( $fields['hide_tab'] ) )
						continue;

					$name = isset( $fields['tab_name'] ) ? $fields['tab_name'] : $fields['name'];
					$slug = isset( $fields['menu_slug'] ) ? $fields['menu_slug'] : $fields['id'];
					$parent_slug = isset( $fields['parent_slug'] ) && substr( $fields['parent_slug'], -4 ) === '.php' ? $fields['parent_slug'] : 'admin.php';
				?>

				<a href="<?php echo admin_url( "{$parent_slug}?page={$slug}" ); ?>" class="nav-tab<?php echo $page == $fields['id'] ? ' nav-tab-active' : ''; ?>"><?php echo esc_html( $name ); ?></a>

				<?php endforeach; ?>

			</h2>

		</div>

	</div>

	<?php
		$save_actions = md_save_settings::actions();
		$self_tab = ! empty( $admin_pages[$page_id]['self_tab'] );
		$bar_content = ! empty( $admin_pages[$page_id]['bar_content'] ) && is_callable( $admin_pages[$page_id]['bar_content'] ) ? call_user_func( $admin_pages[$page_id]['bar_content'] ) : '';

		if ( ! empty( $admin_pages[$page_id]['bar_actions'] ) && is_callable( $admin_pages[$page_id]['bar_actions'] ) )
			$save_actions .= call_user_func( $admin_pages[$page_id]['bar_actions'] );
	?>

	<?php if ( ! empty( $admin_tabs ) || $self_tab || $save_actions ) : ?>

	<div class="md-save-bar md-content-wrap-wide">

		<?php if ( ! empty( $admin_tabs ) || $self_tab ) : asort( $admin_order ); ?>

		<div class="md-submenu">

			<?php if ( $bar_content ) : ?>

			<?php echo $bar_content; ?>

			<?php elseif ( $self_tab ) : ?>

			<a href="?page=<?php echo urlencode( $page ); ?>" class="md-submenu-item<?php echo $tab === '' ? ' md-submenu-active' : ''; ?>">
				<?php echo esc_html( wp_strip_all_tags( isset( $admin_pages[$page_id]['tab_name'] ) ? $admin_pages[$page_id]['tab_name'] : $admin_pages[$page_id]['name'] ) ); ?>
			</a>

			<?php endif; ?>

			<?php foreach ( (array) $admin_order as $admin_tab => $order ) :
				$child = $admin_tabs[$admin_tab];
				$tab_url = "&tab=$admin_tab";

				if ( isset( $child['admin_tab_parent'] ) && ! isset( $_GET['tab'] ) ) {
					$admin_tab = $tab;
					$tab_url = '';
				}

				if ( isset( $child['admin_tab_parent'] ) && $child['admin_tab_parent'] == $page )
					$tab_url = '';
			?>

			<a href="?page=<?php echo urlencode( $page ) . $tab_url; ?>" class="md-submenu-item<?php echo $tab == $admin_tab ? ' md-submenu-active' : ''; ?>" title="<?php echo esc_attr( wp_strip_all_tags( $child['name'] ) ); ?>">
				<?php echo esc_html( wp_strip_all_tags( isset( $child['tab_name'] ) ? $child['tab_name'] : $child['name'] ) ); ?>
			</a>

			<?php endforeach; ?>

		</div>

		<?php endif; ?>

		<?php echo $save_actions; ?>

	</div>

	<?php endif; ?>

	<?php endif;

	if ( $has_form )
		md_save_settings::fields();

	if ( isset( $admin_pages[$page_id]['callback'] ) )
		call_user_func( $admin_pages[$page_id]['callback'] );
	else
		do_action( "{$hook}_admin_page" );
	?>

<?php echo $has_form ? '</form>' : '</div>'; ?>
