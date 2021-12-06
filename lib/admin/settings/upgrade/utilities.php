<?php
/**
 * A library of utilities to run on the AJAX updater page.
 *
 * @since 4.7
 */

class md_upgrade_utilities {

	/**
	 * Setup data for other processes.
	 *
	 * @since 4.7
	 */

	public function setup() {
		$data = array();
		$post_types = md_post_type_meta();

		if ( ! isset( $post_types['stream'] ) )
			$post_types[] = 'stream';
		if ( ! isset( $post_types['product'] ) )
			$post_types[] = 'product';

		foreach ( $post_types as $order => $post_type ) {
			$count = wp_count_posts( $post_type );
			$data['post_types'][$post_type] = 0;
			if ( ! empty( $count->publish ) )
				$data['post_types'][$post_type] += $count->publish;
			if ( ! empty( $count->draft ) )
				$data['post_types'][$post_type] += $count->draft;
			if ( ! empty( $count->pending ) )
				$data['post_types'][$post_type] += $count->pending;
			if ( ! empty( $count->future ) )
				$data['post_types'][$post_type] += $count->future;
		}

		update_option( 'md_upgrade', $data );
	}

	/**
	 * Update main MD option as array.
	 *
	 * @since 4.7
	 */

	public function update_option() {
		$option = get_option( 'marketers_delight' );
		$customize = get_option( 'md_customize' );
		$theme = get_theme_mod( 'marketers_delight' );

		// CTA

		if ( ! empty( $option['email']['email_list'] ) ) {
			$option['email']['forms']['main'] = $option['email'];
			$option['email']['forms']['main']['name'] = 'After Post Email Form';
			$option['email']['forms']['main']['cta_type'] = 'email';
			if ( ! empty( $option['email']['forms']['main']['email_title'] ) )
				$option['email']['forms']['main']['title'] = $option['email']['forms']['main']['email_title'];
			if ( ! empty( $option['email']['forms']['main']['email_desc'] ) )
				$option['email']['forms']['main']['text'] = $option['email']['forms']['main']['email_desc'];
			if ( ! empty( $option['email']['forms']['main']['email_code'] ) ) {
				$option['email']['forms']['cta_type'] = 'html';
				$option['email']['forms']['main']['html'] = $option['email']['forms']['main']['email_code'];
			}
			if ( ! empty( $option['email']['forms']['main']['email_classes'] ) )
				$option['email']['forms']['main']['classes'] = $option['email']['forms']['main']['email_classes'];
			if ( ! empty( $option['email']['email_after_post']['display_posts'] ) ) {
				$option['email']['forms']['main']['locations']['post'] = true;
				$option['email']['forms']['main']['position'] = 'content';
			}
			$option['cta'] = $option['email'];
			unset( $option['email'] );
		}

		// Colors

		if ( ! empty( $theme['site'] ) )
			$option['colors']['site'] = $theme['site'];

		if ( $theme['header']['bg_color'] )
			$option['colors']['header']['bg_color'] = $theme['header']['bg_color'];
		if ( $theme['header']['title']['color'] )
			$option['colors']['header']['site_title'] = $theme['header']['title']['color'];
		if ( $theme['header']['menu']['links']['color'] )
			$option['colors']['header']['menu']['links'] = $theme['header']['menu']['links']['color'];
		if ( $theme['header']['menu']['links']['hover'] )
			$option['colors']['header']['menu']['hover'] = $theme['header']['menu']['links']['hover'];
		if ( $theme['header']['menu']['links']['active'] )
			$option['colors']['header']['menu']['active'] = $theme['header']['menu']['links']['active'];
		if ( $theme['header']['submenu']['bg_color'] )
			$option['colors']['header']['submenu']['bg_color'] = $theme['header']['submenu']['bg_color'];
		if ( $theme['header']['submenu']['links']['color'] )
			$option['colors']['header']['submenu']['links'] = $theme['header']['submenu']['links']['color'];
		if ( $theme['header']['submenu']['links']['hover'] )
			$option['colors']['header']['submenu']['hover'] = $theme['header']['submenu']['links']['hover'];

		if ( $theme['main_menu']['bg_color'] )
			$option['colors']['main_menu']['bg_color'] = $theme['main_menu']['bg_color'];
		if ( $theme['main_menu']['icons']['color'] )
			$option['colors']['main_menu']['icons'] = $theme['main_menu']['icons']['color'];
		if ( $theme['main_menu']['social']['color'] )
			$option['colors']['main_menu']['social'] = $theme['main_menu']['social']['color'];
		if ( $theme['main_menu']['links']['color'] )
			$option['colors']['main_menu']['links'] = $theme['main_menu']['links']['color'];
		if ( $theme['main_menu']['links']['subtext']['color'] )
			$option['colors']['main_menu']['subtext'] = $theme['main_menu']['links']['subtext']['color'];
		if ( $theme['main_menu']['links']['active']['bg_color'] )
			$option['colors']['main_menu']['sub_menu'] = $theme['main_menu']['links']['active']['bg_color'];
		if ( $theme['main_menu']['links']['active']['color'] )
			$option['colors']['main_menu']['active'] = $theme['main_menu']['links']['active']['color'];

		if ( $theme['content']['bg_color'] )
			$option['colors']['content']['bg_color'] = $theme['content']['bg_color'];
		if ( $theme['content']['border_color'] )
			$option['colors']['content']['border_color'] = $theme['content']['border_color'];

		if ( $theme['sidebar']['bg_color'] )
			$option['colors']['sidebar']['bg_color'] = $theme['sidebar']['bg_color'];
		if ( $theme['sidebar']['color'] )
			$option['colors']['sidebar']['text'] = $theme['sidebar']['color'];
		if ( $theme['sidebar']['title']['color'] )
			$option['colors']['sidebar']['title'] = $theme['sidebar']['title']['color'];
		if ( $theme['sidebar']['links']['color'] )
			$option['colors']['sidebar']['links'] = $theme['sidebar']['links']['color'];

		if ( $theme['footer']['bg_color'] )
			$option['colors']['footer']['bg_color'] = $theme['footer']['bg_color'];
		if ( $theme['footer']['color'] )
			$option['colors']['footer']['text'] = $theme['footer']['color'];
		if ( $theme['footer']['title']['color'] )
			$option['colors']['footer']['title'] = $theme['footer']['title']['color'];
		if ( $theme['footer']['links']['color'] )
			$option['colors']['footer']['links'] = $theme['footer']['links']['color'];

		// Fonts + Typography

		$groups = array(
			'typography' => array( 'body', 'h1', 'h2', 'h3', 'h4', 'h5', 'title' ),
			'sidebar' => array( 'sidebar', 'sidebar_title' ),
			'footer' => array( 'footer', 'footer_title' )
		);

		foreach ( $groups as $group => $fields )
			foreach ( $fields as $font ) {
				if ( ! empty( $theme[$group][$font] ) ) {
					if ( ! empty( $theme[$group][$font]['font_type'] ) )
						$option['typography'][$font]['font_type'] = $theme[$group][$font]['font_type'];
					if ( ! empty( $theme[$group][$font]['font_family'] ) )
						$option['typography'][$font]['font_family'] = $theme[$group][$font]['font_family'];
					if ( ! empty( $theme[$group][$font]['font_weight'] ) )
						$option['typography'][$font]['font_weight'] = $theme[$group][$font]['font_weight'];
					foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device ) {
						if ( ! empty( $theme[$group][$font]['font_size'][$device] ) )
							$option['typography'][$font]['font_size'][$device] = $theme[$group][$font]['font_size'][$device];
						if ( ! empty( $theme[$group][$font]['line_height'][$device] ) )
							$option['typography'][$font]['line_height'] = $theme[$group][$font]['line_height'][$device];
					}
				}
			}

		if ( $theme['typography']['title']['color'] )
			$option['colors']['header']['site_title'] = $theme['typography']['title']['color'];
		if ( $theme['typography']['title'] )
			$option['typography']['site_title'] = $theme['typography']['title'];
		if ( $theme['google_fonts'] )
			$option['typography']['google_fonts'] = $theme['google_fonts'];
		unset( $option['typography']['title'] );

		// Header

		if ( ! empty( $theme['header']['spacing_tb']['desktop'] ) ) {
			$option['header']['spacing_top'] = $theme['header']['spacing_tb']['desktop'];
			$option['header']['spacing_bottom'] = $theme['header']['spacing_tb']['desktop'];
		}

		if ( ! empty( $theme['header']['menu']['links']['spacing_lr'] ) )
			$option['header']['menu']['spacing_lr'] = $theme['header']['menu']['links']['spacing_lr'];

		if ( ! empty( $theme['main_menu']['links']['spacing_tb'] ) )
			$option['header']['main_menu']['spacing_tb'] = $theme['main_menu']['links']['spacing_tb'];

		if ( ! empty( $theme['main_menu']['links']['spacing_lr'] ) )
			$option['header']['main_menu']['spacing_lr'] = $theme['main_menu']['links']['spacing_lr'];

		if ( ! empty( $customize['title_tagline']['hide_title'] ) )
			$option['header']['display']['site_title'] = true;

		if ( ! empty( $customize['title_tagline']['hide_tagline'] ) )
			$option['header']['display']['site_tagline'] = true;

		foreach ( array( 'desktop', 'tablet', 'mobile' ) as $device )
			if ( ! empty( $theme['title_tagline']['logo']['width'][$device] ) )
				$option['header']['logo_width'][$device] = $theme['title_tagline']['logo']['width'][$device];

		if ( ! empty( $theme['title_tagline']['secondary_logo'] ) )
			$option['header']['logo_alt']['url'] = $theme['title_tagline']['secondary_logo'];

		$custom_logo = get_theme_mod( 'custom_logo' );

		if ( ! empty( $custom_logo ) ) {
			$image = wp_get_attachment_image_src( $custom_logo , 'full' );
			$option['header']['logo']['id'] = esc_attr( $custom_logo );
			$option['header']['logo']['url'] = esc_url( $image[0] );
		}

		// Content

		if ( ! empty( $theme['layout']['width']['site'] ) )
			$option['content']['width']['site'] = $theme['layout']['width']['site'];

		if ( ! empty( $theme['layout']['width']['content'] ) )
			$option['content']['width']['content'] = $theme['layout']['width']['content'];

		if ( ! empty( $theme['layout']['width']['sidebar'] ) )
			$option['content']['width']['sidebar'] = $theme['layout']['width']['sidebar'];

		if ( ! empty( $customize['layout']['content_box'] ) )
			$option['content']['layout'] = $customize['layout']['content_box'];

		if ( ! empty( $customize['layout']['sidebar_enable'] ) )
			$option['content']['sidebar']['archives_single'] = true;

		if ( ! empty( $customize['layout']['teasers'] ) )
			$option['content']['loop'] = 'teasers';

		if ( ! empty( $customize['layout']['featured_image_position_default'] ) )
			$option['content']['featured_image']['position'] = $customize['layout']['featured_image_position_default'];

		if ( ! empty( $theme['post']['featured_image']['default'] ) )
			$option['content']['featured_image']['cover']['url'] = $theme['post']['featured_image']['default'];

		if ( ! empty( $theme['post']['featured_image']['repeat'] ) )
			$option['content']['featured_image']['styles']['repeat'] = true;

		if ( ! empty( $theme['post']['featured_image']['text_color'] ) )
			$option['content']['featured_image']['styles']['text_color'] = true;

		if ( ! empty( $theme['layout']['author_box'] ) )
			$option['content']['author_box']['enable'] = true;

		if ( ! empty( $customize['layout']['byline_items'] ) )
			$option['content']['author_box']['enable'] = $customize['layout']['byline_items'];

		// Sidebars

		if ( ! empty( $option['sidebars']['areas'] ) )
			foreach ( $option['sidebars']['areas'] as $order => $fields ) {
				$option['sidebars']['areas'][$fields['id']] = $option['sidebars']['areas'][$order];
				unset( $option['sidebars']['areas'][$order] );
			}

		// Popups

		if ( ! empty( $option['popups']['popups'] ) )
			foreach ( $option['popups']['popups'] as $order => $fields ) {
				$option['popups']['popups'][$fields['id']] = $option['popups']['popups'][$order];
				unset( $option['popups']['popups'][$order] );
			}

		if ( ! empty( $option['popups_data'] ) )
			foreach ( $option['popups_data'] as $popup => $fields )
				if ( ! empty( $fields['content']['bullets'] ) )
					$fields['content']['description'] .= $fields['content']['bullets'];

		// Stream
		if ( ! empty( $option['stream_settings'] ) ) {
			$option['stream'] = $option['stream_settings'];
			unset( $option['stream_settings'] );
		}

		// Update MD
		update_option( 'marketers_delight_50', $option );
	}

	/**
	 * Run through post meta and reassign keys in batch processes.
	 *
	 * @since 4.7
	 */

	public function post_meta( $item ) {
		$increment = 20;
		$upgrade = get_option( 'md_upgrade' );

		if ( ! empty( $upgrade['post_types'] ) ) {
			foreach ( $upgrade['post_types'] as $post_type => $total ) {
				if ( $item < $total ) {
					$posts = get_posts( array(
						'fields' => 'ids',
						'post_type' => $post_type,
						'offset' => $item,
						'post_status' => array( 'draft', 'publish', 'future', 'pending', 'private' ),
						'posts_per_page' => $increment
					) );
					foreach ( $posts as $post_id ) {
						$post_meta = get_post_meta( $post_id, 'marketers_delight', true );
						if ( ! empty( $post_meta ) ) {
							foreach ( $post_meta as $key => $fields ) {
								if ( $key == 'share_meta' ) {
									if ( isset( $fields['likes'] ) )
										$post_meta['share']['likes'] = $fields['likes'];
									unset( $post_meta['share_meta'] );
								}
								if ( $key == 'stream' ) {
									if ( ! empty( $fields['thread'] ) ) {
										foreach ( $fields['thread'] as $order => $thread_fields ) {
											$post_meta['stream']['thread']["thread_$order"] = $thread_fields;
											unset( $post_meta['stream']['thread']['thread_0'] );
											unset( $post_meta['stream']['thread'][$order] );
										}
										$post_meta['stream']['update_50'] = true;
									}
								}
								if ( $key == 'gallery_blocks' ) {
									if ( ! empty( $fields['blocks'] ) ) {
										foreach ( $fields['blocks'] as $order => $gallery_blocks ) {
											$post_meta['gallery_blocks']['blocks']["gallery_block_$order"] = $gallery_blocks;
											unset( $post_meta['gallery_blocks']['blocks'][$order] );
										}
									}
								}
								if ( $key == 'page_blocks' ) {
									if ( ! empty( $fields['blocks'] ) ) {
										foreach ( $fields['blocks'] as $order => $page_blocks ) {
											$post_meta['page_blocks']['blocks']["page_block_$order"] = $page_blocks;
											unset( $post_meta['page_blocks']['blocks'][$order] );
										}
									}
								}
							}
							update_post_meta( $post_id, 'marketers_delight', $post_meta );
						}
					}
					$process = 'post_meta';
					if ( $item == 'done' )
						$item = 0;
					$item += $increment;
					break;
				}
				else {
					unset( $upgrade['post_types'][$post_type] );
					update_option( 'md_upgrade', $upgrade );
					$process = 'post_meta';
					$item = 0;
					break;
				}
			}
		}
		else {
			$process = 'term_meta';
			$item = 'done';
		}

		return array(
			'process' => $process,
			'item' => $item
		);
	}

	/**
	 * Convert old options to proper Term meta.
	 *
	 * @since 5.0
	 */

	public function term_meta() {
		$terms = array();
		$wp_options = array_filter( wp_load_alloptions() );

		foreach ( $wp_options as $wp_option => $wp_option_field ) {
			if ( substr( $wp_option, 0, strlen( 'md_taxonomy_' ) ) === 'md_taxonomy_' )
				$terms[] = $wp_option;
		}

		if ( ! empty( $terms ) )
			foreach ( $terms as $order => $term ) {
				$old_option = get_option( $term );
				$clean_id = explode( '_', preg_replace( '/^' . preg_quote( 'md_taxonomy_', '/' ) . '/', '', $term ) );
				$term_id = $clean_id[1];
				update_term_meta( $term_id, 'marketers_delight', $old_option );
				delete_option( $term );
			}
	}

	/**
	 * Clean up leftover data after upgrade and set new
	 * version number.
	 *
	 * @since 4.7
	 */

	public function clean() {
		$upgrade = get_option( 'marketers_delight_50' );
		delete_option( 'marketers_delight' );
		update_option( 'marketers_delight', $upgrade );
		delete_option( 'marketers_delight_50' );
		remove_theme_mod( 'marketers_delight' );
		delete_option( 'md_upgrade' );
		delete_option( 'md_taxonomy__' );
		delete_option( 'md_customize' );
		delete_option( 'md_email_data' );
		delete_option( 'md_popups' );
		delete_option( 'md_license' );
		delete_option( 'md_integrations' );
		delete_option( 'md_popups_edit' );
		md_compile_css();
	}

}