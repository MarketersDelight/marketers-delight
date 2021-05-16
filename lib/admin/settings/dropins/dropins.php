<?php
/**
 * Create Drop-ins settings panel.
 *
 * @since 5.2.1
 */

class md_dropins extends md_api
{

	/**
	 * List of Core Drop-ins.
	 */

	public $core = array(
			'share' => array(
					'name' => 'Share',
					'author' => 'Alex, Marketers Delight',
					'version' => '1.0',
					'description' => 'Add precise share buttons around your site. Add inline or floating icons across post types and choose your design style. Add/remove from individual posts and pages.',
					'dropin_url' => 'https://marketersdelight.com/dropins/share/',
					'author_url' => 'https://marketersdelight.com/',
					'settings_url' => 'admin.php?page=md_settings&tab=md_share',
					'icon' => 'dashicons-share',
					'colors' => '#09b73c'
			),
			'admin-bar' => array(
					'name' => 'Admin bar',
					'author' => 'Alex, Marketers Delight',
					'version' => '1.0',
					'description' => 'A simple take on the standard WordPress admin bar, this drop-in loads a script-free and minimal admin bar with the ability to add your own custom admin links yourself. Recommended for performance enthusiasts and those who want to setup their own admin navigation experience.',
					'dropin_url' => 'https://marketersdelight.com/dropins/md-admin-bar/',
					'author_url' => 'https://marketersdelight.com/',
					'settings_url' => 'admin.php?page=md_settings&tab=md_admin_bar',
					'icon' => 'dashicons-admin-settings',
					'colors' => '#1d2327'
			),
			'scripts' => array(
					'name' => 'Tracking scripts',
					'author' => 'Alex, Marketers Delight',
					'version' => '1.0',
					'description' => 'Add custom scripts to the body and footer of your pages. Offers sitewide scripts and the ability to adds scripts to specific posts and pages from the post editor.',
					'dropin_url' => 'https://marketersdelight.com/wordpress-tracking-scripts/',
					'author_url' => 'https://marketersdelight.com/',
					'settings_url' => 'admin.php?page=md_settings',
					'icon' => 'dashicons-media-code',
					'colors' => '#444'
			),
			'stream' => array(
					'name' => 'Stream',
					'author' => 'Alex, Marketers Delight',
					'version' => '1.0',
					'description' => 'Create your own stream timeline of short posts, status updates, latest activity feeds, and other fun micro-blogging features for a more addicting blogging experience.',
					'dropin_url' => 'https://marketersdelight.com/stream/',
					'author_url' => 'https://marketersdelight.com/',
					'settings_url' => 'edit.php?post_type=stream&page=md_stream',
					'icon' => 'dashicons-schedule'
			),
			'bookshelf' => array(
					'name' => 'Books',
					'author' => 'Alex, Marketers Delight',
					'version' => '1.0',
					'description' => 'Share your favorite books on your site. Write book reviews and organize your collection in the nicely designed bookshelf page.',
					'dropin_url' => 'https://marketersdelight.com/dropins/bookshelf/',
					'author_url' => 'https://marketersdelight.com/',
					'settings_url' => 'edit.php?post_type=bookshelf&page=md_bookshelf',
					'icon' => 'dashicons-book-alt',
					'colors' => '#e7f0b6, #aa4509'
			),
			'woocommerce' => array(
					'name' => 'MD + WooCommerce',
					'author' => 'Alex, Marketers Delight',
					'description' => 'Give your WooCommerce store a quick style and full integration with MD power features to give you more control over your store listing.',
					'version' => '1.0',
					'dropin_url' => 'https://marketersdelight.com/dropins/woocommerce/',
					'author_url' => 'https://marketersdelight.com/',
					'icon' => 'dashicons-cart',
					'colors' => '#7f54b3',
					'plugin_name' => 'WooCommerce',
					'plugin_class' => 'WooCommerce'
			)
	);

	/**
	 * Include additional files.
	 *
	 * @since 5.3
	 */

	public function includes()
	{
		require_once('store.php');
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.2.1
	 */

	public function register()
	{
		return array(
				'admin_page' => array(
						'name' => __('Drop-ins', 'md'),
						'admin_header' => true,
						'admin_tab' => __('Dropin-ins', 'md'),
						'admin_tab_parent' => 'md_dropins',
						'fields' => array(
								'core' => array(
										'type' => 'group',
										'fields' => array(
												'status' => array(
														'type' => 'checkbox',
														'options' => array('enable')
												)
										)
								),
								'installed' => array(
										'type' => 'group',
										'fields' => array(
												'name' => array('type' => 'text'),
												'version' => array('type' => 'text'),
												'description' => array('type' => 'text'),
												'dropin_url' => array('type' => 'url'),
												'author_url' => array('type' => 'url'),
												'settings_url' => array('type' => 'url'),
												'icon' => array('type' => 'text'),
												'author' => array('type' => 'text'),
												'colors' => array('type' => 'text'),
												'status' => array(
														'type' => 'checkbox',
														'options' => array('enable')
												),
												'plugin_name' => array('type' => 'text'),
												'plugin_class' => array('type' => 'text')
										)
								)
						)
				)
		);
	}

	/**
	 * Load simple toggle script to <head>.
	 *
	 * @since 5.3
	 */

	public function admin_scripts()
	{ ?>
		<script>
			jQuery(document).ready(function ($) {
				$('#md_upload_dropin_button').on('click', function (e) {
					e.preventDefault();
					$('#md_upload_dropin').toggle();
				});
			});
		</script>
	<?php }

	/**
	 * Build admin fields.
	 *
	 * @since 5.2.1
	 */

	public function admin_page()
	{
		$core = $this->core;
		$core_count = count($core);
		$core_active_count = count(md_get_dropins('core', 'active'));
		$installed = md_setting(array('dropins', 'installed'), array());
		include('admin-page.php');
	}

}

new md_dropins;
