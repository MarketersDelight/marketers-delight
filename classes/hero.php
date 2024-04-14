<?php
/**
 * Construct the admin settings and frontend templates for the
 * Page Cover concept, which is an evolution of MD's classic
 * Featured Image Position controls that allows for custom design
 * of Page Title's of any kind of page throughout WordPress.
 *
 * @since 6.0
 */

class md_hero extends md_api {

	/**
	 * Load featured image in various positions across templates.
	 *
	 * @since 4.8.3
	 */

	public function template() {
		$cover = $this->cover();
		$featured_image = md_get_featured_image( 'page' );

		if ( ! empty( $featured_image['id'] ) ) {
			$page_image_hook = 'md_hook_page_headline_bottom';

			if ( md_post_type_field( array( 'layout', 'content', 'hero_inline' ) ) && md_has_sidebar() )
				$page_image_hook = 'md_hook_page_headline_wrap_bottom';

			if ( $featured_image['position'] == 'above_headline' )
				$page_image_hook = 'md_hook_page_headline_top';
			elseif ( $featured_image['position'] == 'below_headline' )
				$page_image_hook = 'md_hook_after_page_title';

			add_action( $page_image_hook, 'md_page_featured_image' );
		}

		if ( $cover['position'] == 'header_cover' ) {
			add_action( 'md_hook_post_headline_top', array( $this, 'inner' ), 5 );
			add_action( 'md_hook_page_headline_top', array( $this, 'inner' ), 5 );
			add_action( 'md_hook_post_headline_bottom', array( $this, 'close_div' ), 100 );
			add_action( 'md_hook_page_headline_top', array( $this, 'close_div' ), 100 );

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

			if ( md_has_sidebar() && ! md_post_type_field( array( 'layout', 'content', 'hero_inline' ) ) )
				$hook = 'md_hook_content_top';

			add_action( $hook, array( $this, 'html' ) );
		}
	}

	/**
 	 * Call the Headline template within the Loop.
 	 *
	 * @since 6.0
 	 */

	public function html() {
		if ( ( is_singular() || is_404() ) && have_posts() )
			while ( have_posts() ) {
				the_post();
				md_headline();
			}
		else {
			$args['context'] = 'page';
			$featured_image = md_get_featured_image( 'page' );

			if ( md_module( array( 'layout', 'content', 'hero_inline' ) ) && md_has_sidebar() )
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
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		$this->name = __( 'Hero', 'md' );

		return array(
			'meta_box' => array(
				'name' => $this->name,
				'page_settings' => true,
				'fields' => $this->fields()
			),
			'term' => array(
				'name' => $this->name,
				'fields' => $this->fields(),
				'position' => 20,
				'callback' => array( $this, 'admin_fields' )
			)
		);
	}

	/**
	 * Set options for save.
	 *
	 * @since 4.3.5
	 */

	public function fields() {
		$sanitize = $this->_data( 'sanitize' );
		$fields = array(
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
				'options' => array( 'alternate', 'disable_cover', 'bg_repeat' )
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
			'custom_html' => array( 'type' => 'code' )
		);

		$fields['link_primary'] = $this->fields->data->links( array( 'sort' => 'save' ) );
		$fields['link_secondary'] = $this->fields->data->links( array( 'sort' => 'save' ) );

		return $fields;
	}

	/**
	 * Add header wrap classes when needed.
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
	 * General fields admin template.
	 *
	 * @since 4.7
	 */

	public function admin_template( $group = null ) {
		$screen = get_current_screen();
		$has_tabs = ! in_array( $screen->base, array( 'post', 'post-new', 'term' ) ) ? true : false;
		$prefix = $this->_prefix;
		$values = $this->_data( 'values' );
		$sanitize = $this->_data( 'sanitize' );
		$cta_type = $this->fields->module( 'page_cta' );

		include( md_template( 'admin/hero', true ) );
	}

	/**
	 * Meta box template callback.
	 *
	 * @since 5.0
	 */

	public function meta_box() {
		echo "<div class=\"md-$this->_clean_id md-tab-content\">";
		$this->admin_template();
		echo '</div>';
	}

	/**
	 * Add settings template and script to Page Settings sections.
	 *
	 * @since 6.0
	 */

	public function admin_fields() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo esc_html( $this->name ); ?></h3>
			<div class="md-widget-item">
				<?php $this->admin_template(); ?>
			</div>
		</div>
	<?php }

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

}

new md_hero;
