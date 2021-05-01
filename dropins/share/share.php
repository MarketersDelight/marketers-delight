<?php
/**
 * Main class for MD share.
 *
 * @since 4.6
 */

class md_share extends md_api {

	/**
	 * Fire actions, filters, and set properties.
	 *
	 * @since 5.0
	 */

	public function actions() {
		$this->dir = 'dropins';
		$this->floating = array(
			'post' => __( 'Side of post', 'md' ),
			'left' => __( 'Left side of screen', 'md' ),
			'bottom' => __( 'Bottom of screen', 'md' ),
			'right' => __( 'Right side of screen', 'md' )
		);
		$this->inline_style = array(
			'bold' => __( 'Bold (default)', 'md' ),
			'minimal' => __( 'Minimal', 'md' )
		);
		$this->inline = array(
			'before_headline' => __( 'Before Headline', 'md' ),
			'after_headline' => __( 'After Headline', 'md' ),
			'after_post' => __( 'After Post', 'md' )
		);
		add_action( 'wp_ajax_md_like', 'md_like' );
		add_action( 'wp_ajax_nopriv_md_like', 'md_like' );
	}

	/**
	 * Organize all available share buttons into data array
	 * for template output.
	 *
	 * @since 4.6
	 */

	public function data() {
		$page = md_page_data();
		$site_title = get_bloginfo( 'name' );
		$title = urlencode( $page['title'] );
		$link = $page['link'];
		$permalink = urlencode( $link );
		$excerpt = urlencode( $page['excerpt'] );
		$image = urlencode( $page['image'] );
		$twitter_username = md_setting( array( 'share', 'twitter', 'username' ) );
		$fields = array(
			'twitter' => array(
				'label' => __( 'Twitter', 'md' ),
				'share' => true,
				'url' => "https://twitter.com/intent/tweet?text={$title}" . ( ! empty( $twitter_username ) ? "&via={$twitter_username}" : '' ) . "&url={$permalink}",
				'color' => '#1da1f2',
				'icon' => 'md-icon-twitter',
				'status' => 'active',
				'fields' => array( 'username', 'url' )
			),
			'facebook' => array(
				'label' => __( 'Facebook', 'md' ),
				'share' => true,
				'url' => "https://www.facebook.com/sharer.php?u={$permalink}&t={$title}",
				'color' => '#3b5998',
				'icon' => 'md-icon-facebook',
				'status' => 'active',
				'fields' => array( 'url' )
			),
			'linkedin' => array(
				'label' => __( 'LinkedIn', 'md' ),
				'share' => true,
				'url' => "https://www.linkedin.com/sharing/share-offsite/?url={$permalink}",
				'color' => '#0077B5',
				'icon' => 'md-icon-linkedin',
				'status' => 'active',
				'fields' => array( 'url' )
			),
			'email' => array(
				'label' => __( 'Email', 'md' ),
				'url' => 'mailto:?subject=' . __( 'Sharing this link with you', 'md' ) . "&body={$permalink}",
				'color' => '#222',
				'icon' => 'md-icon-mail-alt',
				'status' => 'active',
				'context' => 'side',
				'fields' => array( 'url', 'popup' )
			),
			'like' => array(
				'label' => __( 'Like', 'md' ),
				'color' => '#FFEF40',
				'icon' => 'md-icon-heart-empty',
				'status' => 'active',
				'fields'  => array( 'text', 'icon' ),
				'icons' => array(
					'md-icon-heart-empty' => __( 'Empty Heart (default)', 'md' ),
					'md-icon-heart' => __( 'Heart', 'md' ),
					'md-icon-like' => __( 'Like', 'md' ),
					'md-icon-hand' => __( 'Hand', 'md' )
				)
			),
			'pinterest' => array(
				'label' => __( 'Pinterest', 'md' ),
				'share' => true,
				'url' => "https://pinterest.com/pin/create/button/?url={$permalink}&description={$excerpt}&media={$image}&is_video=false",
				'color' => '#bd081c',
				'icon' => 'md-icon-pinterest',
				'context' => 'side',
				'status' => 'inactive',
				'fields' => array( 'url' )
			),
			'whatsapp' => array(
				'label' => __( 'WhatsApp', 'md' ),
				'url' => "whatsapp://send?text={$permalink}",
				'share' => true,
				'color' => '#00E676',
				'icon' => 'md-icon-whatsapp',
				'context' => 'side',
				'status' => 'inactive',
				'fields' => array( 'url' )
			),
			'rss' => array(
				'label' => __( 'RSS', 'md' ),
				'url' => '/feed/',
				'color' => '#FF9900',
				'icon' => 'md-icon-rss',
				'status' => 'inactive',
				'fields' => array( 'url' )
			),
			'comments' => array(
				'label' => __( 'Comments', 'md' ),
				'url' => get_comments_link(),
				'color' => '#777',
				'icon' => 'md-icon-chat',
				'context' => 'side',
				'status' => 'inactive',
				'fields' => array( 'url', 'disable' )
			)
		);

		$fields = array_merge( $fields, apply_filters( 'md_share_buttons', array() ) );

		return $fields;
	}

	/**
	 * Get order from default data set or from saved post/term meta.
	 *
	 * @since 4.7
	 */

	public function get_order() {
		$order = array();
		if ( is_admin() )
			$option = $this->fields->module( 'icons' );
		else {
			$buttons = md_meta( array( 'share', 'buttons' ) );
			if ( ! empty( $buttons['custom'] ) )
				$option = md_meta( array( 'share', 'icons' ) );
			else
				$option = md_setting( array( 'share', 'icons' ) );
		}

		if ( ! empty( $option ) )
			$icons = $option;
		else
			$icons = $this->data();

		foreach ( $icons as $icon => $fields ) {
			$order['order'][] = $icon;
			$order[$fields['status']][] = $icon;
		}

		$order['fields'] = $option;

		return $order;
	}

	/**
	 * Register admin page, meta box, and terms.
	 *
	 * @since 5.0
	 */

	public function register() {
		$this->name = __( 'Share', 'md' );
		$post_types = md_share_post_types();
		$fields = $this->register_fields();
		$fields['floating']['options'][] = 'remove';
		$meta = array_merge( array(
			'buttons' => array(
				'type' => 'checkbox',
				'options' => array( 'custom' )
			)
		), $fields );
		$save = array(
			'admin_page' => array(
				'name' => $this->name,
				'parent' => 'md_settings',
				'fields' => array_merge( array(
					'post_types' => array(
						'type' => 'checkbox',
						'options' => $post_types
					)
				), $fields )
			),
			'meta_box' => array(
				'name' => $this->name,
				'fields' => $meta
			),
			'term' => array(
				'name' => $this->name,
				'fields' => $meta
			)
		);
		foreach ( $post_types as $post_type )
			$save['admin_page']['fields']["{$post_type}_likes"]['type'] = 'number';
		return $save;
	}

	/**
	 * Compile list of fields for sanitization.
	 *
	 * @since 4.6
	 */

	public function register_fields() {
		$icons = array();
		foreach ( $this->data() as $share => $args )
			foreach ( $args['fields'] as $field ) {
				$icons['status']['type'] = 'text';
				if ( in_array( $field, array( 'text', 'username', 'url' ) ) )
					$icons[$field]['type'] = 'text';
				elseif ( $field == 'popup' ) {
					$popups = array();
					$option = md_setting( array( 'popups' ) );
					if ( ! empty( $option['popups'] ) )
						foreach ( $option['popups'] as $popup => $fields )
							$popups[] = $popup;
					$icons[$field] = array(
						'type' => 'select',
						'options' => $popups
					);
				}
				elseif ( $field == 'disable' )
					$icons[$field] = array(
						'type' => 'checkbox',
						'options' => array( 'inline', 'floating' )
					);
				elseif ( $field == 'icon' && is_array( $args['icons'] ) )
					$icons[$field] = array(
						'type' => 'select',
						'options' => array_keys( $args['icons'] )
					);
			}
		return array(
			'floating' => array(
				'type' => 'select',
				'options' => array_keys( $this->floating )
			),
			'inline' => array(
				'type' => 'checkbox',
				'options' => array_keys( $this->inline )
			),
			'remove' => array(
				'type' => 'checkbox',
				'options' => array_keys( $this->inline )
			),
			'inline_style' => array(
				'type' => 'select',
				'options' => array_keys( $this->inline_style )
			),
			'icons' => array(
				'type' => 'group',
				'fields' => $icons
			),
			'likes' => array( 'type' => 'number' )
		);
	}

	/**
	 * Built the HTML for the admin page. This includes MD admin header,
	 * inline Display Options HTML, meta boxes hooks, and save button.
	 *
	 * @since 4.6
	 */

	public function admin_page() {
		$order = $types = array();
		$data = $this->data();
		$icons = $this->get_order();
		$post_types = md_share_post_types();
		foreach ( $post_types as $post_type )
			$types[$post_type] = ucwords( $post_type );
		include( md_template( $this->dir, 'share/admin/share-settings', true ) );
	}

	/**
	 * Post Meta box callback function and scripts.
	 *
	 * @since 5.0
	 */

	public function meta_box() {
		$this->module();
	}

	public function meta_scripts() {
		$this->module_scripts();
	}

	/**
	 * Terms Meta callback function and scripts.
	 *
	 * @since 5.0
	 */

	public function term() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo $this->name; ?></h3>
			<div class="md-widget-item">
				<?php $this->module(); ?>
			</div>
		</div>
	<?php }

	public function term_scripts() {
		$this->module_scripts();
	}

	/**
	 * Built the HTML for the admin page. This includes MD admin header,
	 * inline Display Options HTML, meta boxes hooks, and save button.
	 *
	 * @since 4.6
	 */

	public function module() {
		$order = $types = array();
		$data = $this->data();
		$icons = $this->get_order();
		$screen = get_current_screen();
		$post_types = md_setting( array( 'share', 'post_types' ) );
		$post_types = ! empty( $post_types ) ? $post_types : array();
		$inline = md_setting( array( 'share', 'inline' ) );
		$inline = ! empty( $inline ) ? $inline : array();
		$floating = md_setting( array( 'share', 'floating' ) );
		foreach ( $post_types as $post_type )
			$types[$post_type] = ucwords( $post_type );
		include( md_template( $this->dir, 'share/admin/share-meta', true ) );
	}

	/**
	 * Meta box scripts.
	 *
	 * @since 5.0
	 */

	public function module_scripts() { ?>
		<script>
			( function() {
				document.getElementById( '<?php echo $this->_prefix; ?>_buttons_custom' ).onchange = function() {
					document.getElementById( 'md_share_buttons' ).style.display = this.checked ? 'block' : 'none';
				}
			})();
		</script>
	<?php }

	/**
	 * Load CSS template to style.css.
	 *
	 * @since 4.9.4
	 */

	public function css( $templates ) {
		$templates['share'] = md_css( 'dropins', 'share/css', true );
		return $templates;
	}

	/**
	 * Add floating and (or) inline icons to different hooks based
	 * on added post types.
	 *
	 * @since 4.6
	 */

	public function template() {
		$post_type = get_post_type();
		$post_types = md_setting( array( 'share', 'post_types' ) );
		$post_types = ! empty( $post_types ) ? $post_types : array();
		$floating_option = md_setting( array( 'share', 'floating' ) );
		$floating_meta = md_meta( array( 'share', 'floating' ) );
		$inline = md_module( array( 'share', 'inline' ) );

		if ( ( is_singular() || is_category() || is_tax() ) && ! empty( $floating_option ) && $floating_meta !== 'remove' && (
			( in_array( $post_type, array_keys( $post_types ) ) ) ||
			( md_meta( array( 'share', 'floating' ) ) )
		) ) {
			$floating = md_module( array( 'share', 'floating' ) );
			$order = '';
			$hook = 'wp_footer';
			if ( $floating == 'post' ) {
				$order = 95;
				$hook = 'md_hook_content';
			}
			add_action( $hook, array( $this, 'floating' ), $order );
			add_action( 'wp_enqueue_scripts', array( $this, 'script' ) );
		}

		if ( ! empty( $inline ) && is_singular() && (
			( in_array( $post_type, array_keys( $post_types ) ) && is_singular() ) ||
			( is_singular() && md_meta( array( 'share', 'inline' ) ) )
		) ) {
			foreach ( $inline as $position => $value ) {
				$inline_remove = md_meta( array( 'share', 'remove' ) );
				if ( empty( $inline_remove[$position] ) ) {
					$order = '';
					$hook = 'md_hook_content_item';
					if ( $position == 'before_headline' )
						$order = 10;
					elseif ( $position == 'after_headline' )
						$order = 30;
					elseif ( $position == 'after_post' )
						$order = 40;
					add_action( $hook, array( $this, 'share_buttons' ), $order );
				}
			}
			add_action( 'wp_enqueue_scripts', array( $this, 'script' ) );
		}
	}

	/**
	 * Load Share scripts when active on page.
	 *
	 * @since 4.6
	 */

	public function script() {
		wp_add_inline_script( 'marketers-delight', "\tMD.share.init();" );
	}

	/**
	 * HTML wrapper and callback for loading floating buttons.
	 *
	 * @since 4.6
	 */

	public function floating() {
		$classes = array();
		$location = md_module( array( 'share', 'floating' ) );

		if ( ! empty( $location ) ) {
			if ( $location == 'post' ) {
				$classes[] = 'inline';
				$classes[] = 'side-v';
			}
			else {
				$classes[] = 'side';
				$classes[] = "side-{$location}";
				if ( $location !== 'bottom' )
					$classes[] = 'side-v';
			}
		}
		$classes = join( ' ', $classes );
	?>
		<div id="share_side" class="share-sticky <?php echo $classes; ?>">
			<?php $this->share_button( array(
				'type' => 'floating',
				'style' => 'bold'
			) ); ?>
		</div>
	<?php }

	/**
	 * Buid frontend HTML for each share button.
	 *
	 * @since 4.6
	 */

	public function share_buttons( $location = null, $style = null ) {
		$args['style'] = $style;
		$this->share_button( $args );
	}

	/**
	 * Build individual share icon with passed data.
	 *
	 * @since 5.2
	 */

	public function share_button( $args = null ) {
		$default = $this->data();
		$option = $this->get_order();
		$active = isset( $args['show'] ) ? $args['show'] : $option['active'];

		$post_id = isset( $args['post_id'] ) ? $args['post_id'] : get_the_ID();
		$post_type = isset( $args['post_type'] ) ? $args['post_type'] : get_post_type();
		$style = isset( $args['style'] ) ? $args['style'] : null;
		$type = isset( $args['type'] ) ? $args['type'] : 'inline';

		$count = count( $active );
		$count = ( ! empty( $option['fields']['comments']['disable'] ) && $type == 'inline' ) || ( in_array( 'comments', $active ) && ! md_has_comments() ) ? $count - 1 : $count;

		$inline_style = md_module( array( 'share', 'inline_style' ) );
		$inline_style = ! empty( $inline_style ) ? $inline_style : 'bold';

		$style_class = ! empty( $style ) ? $style : $inline_style;
		$classes = ( $count >= 7 ? ' share-disable' : '' ) . " share-{$style_class}";
		$html = isset( $args['html'] ) ? $args['html'] : 'div';

		echo "<$html class=\"share" . esc_attr( $classes ) . '">';

		foreach ( $active as $share ) {
			$action = $class = '';
			$fields = ! empty( $option['fields'][$share] ) ? $option['fields'][$share] : array();
			$disable = ! empty( $fields['disable'] ) ? $fields['disable'] : array();
			if ( ! empty( $disable[$type] ) || ( $share == 'comments' && ! md_has_comments() ) )
				continue;
			$data = $default[$share];
			$default_url = ! empty( $data['url'] ) ? $data['url'] : '';
			$url = ! empty( $fields['url'] ) ? $fields['url'] : $default_url;
			$text = ! empty( $fields['text'] ) ? $option['text'] : '';
			$color = ! empty( $fields['color'] ) ? $fields['color'] : $data['color'];
			$color_prop = $style_class == 'minimal' ? 'color' : 'background-color';
			$icon = ! empty( $fields['icon'] ) ? $fields['icon'] : $data['icon'];
			if ( ! empty( $fields['popup'] ) ) {
				$url = '#';
				$class = ' md-popup-trigger';
				$action = ' data-popup="md_popup_' . esc_attr( $fields['popup'] ) . '"';
				md_popup( array( 'id' => $fields['popup'] ) );
			}
			elseif ( isset( $data['share'] ) && empty( $fields['url'] ) )
				$action = ' data-share="true" rel="nofollow"';
			elseif ( $share == 'like' ) {
				$liked = ! empty( $_COOKIE['md_likes'] ) ? json_decode( stripslashes( $_COOKIE['md_likes'] ) ) : array();
				$class = in_array( $post_id, $liked ) ? ' liked' : '';
				$action = ' data-share-id="' . esc_attr( $post_id ) . '" data-share-type="' . esc_attr( $post_type ) . '"';
				$action .= ' data-share-archive="' . ( is_category() || is_tax() ? 'true' : 'false' ) . '"';
			}
			include( md_template( $this->dir, 'share/share', true ) );
		}

		echo "</$html>";
	}

}

new md_share;