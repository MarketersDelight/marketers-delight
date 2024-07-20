<?php
/**
 * Construct the admin settings and frontend templates for the
 * Page Cover concept, which is an evolution of MD's classic
 * Featured Image Position controls that allows for custom design
 * of Page Title's of any kind of page throughout WordPress.
 *
 * @since 6.0
 */

class md_page_title extends md_api {

	/**
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		$this->name = __( 'Page Title', 'md' );

		return array(
			'meta_box' => array(
				'name' => $this->name,
				'page_settings' => true,
				'fields' => $this->fields()
			),
			'term' => array(
				'name' => $this->name,
				'position' => 20,
				'fields' => $this->fields()
			)
		);
	}

	/**
	 * Register custom admin fields for Page Title, set other
	 * attributes and sanitize keys.
	 *
	 * @since 4.3.5
	 */

	public function fields() {
		$sanitize = $this->_data( 'sanitize' );
		$fields = array(
			'title' => array( 'type' => 'text' ),
			'description' => array( 'type' => 'textarea' ),
			'cover_photo' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'cover_position' => array(
				'type' => 'select',
				'options' => array_keys( $sanitize->values['covers'] )
			),
			'cover_bg_color' => array( 'type' => 'color' ),
			'cover_display' => array(
				'type' => 'checkbox',
				'options' => array( 'inline', 'alternate', 'disable_cover', 'bg_repeat' )
			),
			'image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'image_width' => array(
				'desktop' => array( 'type' => 'range' ),
				'tablet' => array( 'type' => 'range' ),
				'mobile' => array( 'type' => 'range' )
			),
			'image_position' => array(
				'type' => 'select',
				'options' => array_keys( $sanitize->values['featured_image'] )
			),
			'page_cta' => array(
				'type' => 'select',
				'options' => array( 'links', 'custom' )
			),
			'custom_html' => array( 'type' => 'code' ),
			'links_sort' => array( 'type' => 'text' ),
			'links' => array(
				'type' => 'group',
				'fields' => $this->fields->data->links( array( 'sort' => 'save' ) )
			)
		);

		return $fields;
	}

	/**
	 * Load featured image in various positions across templates.
	 *
	 * @since 4.8.3
	 */

	public function template() {
		$cover = $this->cover();
		$featured_image = md_get_featured_image( 'page' );

		// Insert Featured Image on non-Post pages (archives, category, etc.)

		if ( ! empty( $featured_image['id'] ) ) {
			$page_image_hook = 'md_hook_page_headline_bottom';

			if ( md_post_type_field( array( 'page_title', 'cover_display', 'inline' ) ) && md_has_sidebar() )
				$page_image_hook = 'md_hook_page_headline_wrap_bottom';

			if ( $featured_image['position'] == 'above_headline' )
				$page_image_hook = 'md_hook_page_headline_top';
			elseif ( $featured_image['position'] == 'below_headline' )
				$page_image_hook = 'md_hook_after_page_title';

			add_action( $page_image_hook, 'md_page_featured_image' );
		}

		// Convert Header and Headline to a Page Cover

		if ( $cover['position'] == 'header_cover' ) {
			add_action( 'md_hook_page_headline_top', array( $this, 'inner' ) );
			add_action( 'md_hook_page_headline_bottom', array( $this, 'close_div' ) );

			if ( md_has_headline() )
				add_action( 'md_hook_content_box_top', array( $this, 'html' ) );
		}
		elseif ( $cover['position'] == 'header_cover_full' ) {
			add_action( 'wp_head', array( $this, 'inline_css' ) );
			add_action( 'md_hook_header_top', array( $this, 'overlay' ) );
			add_filter( 'md_filter_header_classes', array( $this, 'header_classes' ) );

			if ( md_has_headline() )
				add_action( 'md_hook_after_header', array( $this, 'html' ) );
		}
		elseif ( ! is_singular() ) {
			$hook = 'md_hook_content';

			if ( md_has_sidebar() && ! md_post_type_field( array( 'page_title', 'cover_display', 'inline' ) ) )
				$hook = 'md_hook_content_top';

			add_action( $hook, array( $this, 'html' ) );
		}
	}

	/**
	 * Add header wrap classes when needed to page main Header.
	 *
	 * @since 4.8.3
	 */

	public function header_classes( $classes ) {
		$classes[] = 'has-cover';
		$cover = $this->cover();

		if ( ! empty( $cover['display']['alternate'] ) )
			$classes[] = 'alt';

		return $classes;
	}

	/**
 	 * Call the Headline template within the Loop.
 	 *
	 * @since 6.0
 	 */

	public function html() {
		if ( ( is_singular() || is_404() ) && have_posts() ) {
			while ( have_posts() ) {
				the_post();
				md_headline();
			}
		}
		else {
			$args['context'] = 'page';
			$featured_image = md_get_featured_image( 'page' );
			$cover = $this->cover();

			if ( ! empty( $cover['position'] ) && md_module( array( 'page_title', 'cover_display', 'inline' ) ) && md_has_sidebar() )
				$args['inline'] = true;

			if ( ! empty( $featured_image['id'] ) && $featured_image['position'] !== 'remove' ) {
				$image_position = $image_class = $featured_image['position'];

				if ( $image_position == 'above_headline' )
					$image_class = 'before';
				elseif ( $image_position == 'below_headline' )
					$image_class = 'after';

				$args['classes'] = 'image-' . str_replace( '_', '-', $image_class );
			}

			md_headline( $args );
		}
	}

	/**
	 * Print inline CSS to wp_head when needed.
	 *
	 * @since 4.8.3
	 */

	public function inline_css() {
		$cover = $this->cover();

		if ( ! empty( $cover['photo'] ) ) {
			$bg_image = array( 'background-image' => "url('" . esc_url( $cover['photo']['url'] ) . "')" );

			if ( ! empty( $cover['display']['bg_repeat'] ) )
				$bg_image['background-size'] = 'auto';

			echo md_inline_css( array( '.header.has-cover' => $bg_image ) );
		}
	}

	/**
	 * To detect a Full Header Cover, manually set a context.
	 *
	 * @since 6.0
	 */

	public function cover() {
		$context = is_singular() ? 'post' : 'page';

		return md_cover( $context );
	}

	/**
	 * Send set context to overlay with looser logic than md_overlay().
	 *
	 * @since 6.0
	 */

	public function overlay() {
		$style = array();
		$cover = $this->cover();

		if ( ! empty( $cover['bg_color'] ) )
			$style['bg_color'] = $cover['bg_color'];

		echo '<div class="overlay"' . md_style( $style ) . '></div>';
	}

	/**
 	 * Inner HTML element and closing div.
 	 *
 	 * @since 6.0
 	 */

	public function inner() {
		echo '<div class="inner">';
	}

	public function close_div() {
		echo '</div>';
	}

	/**
	 * Meta box template callback.
	 *
	 * @since 5.0
	 */

	public function meta_box() {
		echo "<div class=\"md-$this->_clean_id md-tab-content\">";
		$this->admin_fields();
		echo '</div>';
	}

	/**
	 * Term meta template callback.
	 *
	 * @since 6.0
	 */

	public function term() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo $this->name; ?></h3>
			<div class="md-widget-item">
				<?php $this->admin_fields(); ?>
			</div>
		</div>
	<?php }

	/**
	 * Add settings template and script to Page Settings sections.
	 *
	 * @since 6.0
	 */

	public function admin_fields() {
		$screen = get_current_screen();
		$is_post = in_array( $screen->base, array( 'post', 'post-new' ) ) ? true : false;
		$sanitize = new md_sanitize;
		$prefix = $this->_prefix;
		$cta_type = $this->fields->module( 'page_cta' );

		include md_template( 'admin/page-title', true );
	}

	/**
	 * Callback option for Link group admin fields, which is a
	 * repeatable field group.
	 *
	 * @since 6.0
	 */

	public function links_fields( $group, $field ) {
		$this->fields->link_fields( array( 'group' => array( $group, $field ) ) );
	}

}

new md_page_title;
