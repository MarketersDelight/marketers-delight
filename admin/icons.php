<?php
/**
 * Create font icons library.
 *
 * @since 5.2.3
 */

class md_icons extends md_api {

	/**
	 * Register admin page.
	 *
	 * @since 5.2.3
	 */

	public function register() {
		return array(
			'admin_page' => array(
				'name' => __( 'Icons', 'md' ),
				'tab_name' => __( 'Icons', 'md' ),
				'parent' => 'md_settings',
				'order' => 3,
				'fields' => array(
					'data' => array(
						'type' => 'group',
						'fields' => array(
							'label' => array( 'type' => 'text' ),
							'unicode' => array( 'type' => 'text' )
						)
					)
				)
			)
		);
	}

	/**
	 * Load icons files and styles to settings page.
	 *
	 * @since 5.2.3
	 */

	public function admin_enqueue() { ?>
		<style type="text/css">
			@font-face {
				font-family: md-icon;
				font-display: swap;
				src: url('<?php echo md_font_icons_url(); ?>') format('woff');
				font-style: normal;
				font-weight: 400;
			}
			[class*="md-icon"]:before {
				display: inline-block;
				font-family: md-icon;
				font-size: 30px;
				font-style: normal;
				font-variant: normal;
				font-weight: 400;
				line-height: 1;
				speak: none;
				text-align: center;
				text-decoration: inherit;
				text-transform: none;
			}
			.md-icon.icon-data:before { content: attr(data-md-icon); }
			<?php
				foreach ( md_icons() as $icon => $fields ) {
					if ( ! isset( $fields['unicode'] ) ) continue;
					$selectors = '';
					if ( isset( $fields['classes'] ) )
						foreach ( $fields['classes'] as $selector )
							$selectors .= ",{$selector}:before";
					echo '.md-icon-' . $icon . ":before{$selectors}{content:'\\" . $fields['unicode'] . '\'}';
				}
			?>
		</style>
	<?php }

	/**
	 * Create admin settings fields.
	 *
	 * @since 5.2.3
	 */

	public function admin_page() {
		$icons = md_icons();
		$icons_count = count( $icons );
		$default_icons_ids = md_get_icons( 'ids', true );
		include md_template( 'admin/icons', true );
	}

}

new md_icons;
