<?php
/**
 * Construct the admin settings and frontend templates for the
 * Page Cover concept, which is an evolution of MD's classic
 * Featured Image Position controls that allows for custom design
 * of Page Title's of any kind of page throughout WordPress.
 *
 * @since 5.6
 */

class md_page_cover extends md_api {

	/**
	 * Include related files.
	 *
	 * @since 5.6
	 */

	public function includes() {
		include_once( 'cover-functions.php' );
	}

	/**
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		$this->name = __( 'Page Cover', 'md' );

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
	 * Run actions and filters.
	 *
	 * @since 4.3.5
	 */

	public function actions() {
		add_action( 'wp_head', array( $this, 'inline_css' ) );
	}

	/**
	 * Set options for save.
	 *
	 * @since 4.3.5
	 */

	public function fields() {
		$sanitize = $this->_data( 'sanitize' );

		return array(
			'cover_image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'cover_position' => array(
				'type' => 'select',
				'options' => array_keys( $sanitize->values['covers'] )
			),
			'bg_color' => array( 'type' => 'color' ),
			'text_color' => array(
				'type' => 'checkbox',
				'options' => array( 'alternate', 'disable_cover', 'categories', 'posts', 'category_posts' )
			)
		);
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
	 * @since 5.6
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
	 * General fields admin template.
	 *
	 * @since 4.7
	 */

	public function admin_template( $group = null ) {
		$cover_settings = array();
		$screen = get_current_screen();
		$values = $this->_data( 'values' );
		$sanitize = $this->_data( 'sanitize' );

		if ( ! in_array( $screen->base, array( 'post', 'post-new' ) ) )
			if ( $screen->base == 'term' )
				$cover_settings['category_posts'] = __( 'Apply to all Posts in this Category', 'md' );
			else {
				$cover_settings['categories'] = __( 'Apply to all Categories', 'md' );
				$cover_settings['posts'] = __( 'Apply to all Posts', 'md' );
			}

		$disable_overlay = md_setting( array( 'colors', 'page_cover', 'cover_styles', 'disable_cover' ) );
		$overlay_label = $disable_overlay ? __( 'Add overlay', 'md' ) : __( 'Remove overlay', 'md' );

		$cover_settings = array_merge( $cover_settings, array(
			'alternate' => __( 'Use alternate text color', 'md' ),
			'disable_cover' => $overlay_label
		) );

		include( 'cover-fields.php' );
	}

	/**
	 * Load featured image in various positions across templates.
	 *
	 * @since 4.8.3
	 */

	public function template() {
		$cover = md_cover();

		if ( $cover['position'] !== 'header_cover_full' )
			add_action( 'md_hook_before_headline', array( $this, 'overlay' ), 1 );

		if ( $cover['position'] == 'header_cover' ) {
			add_action( 'md_hook_before_headline', 'md_inner_html', 5 );
			add_action( 'md_hook_after_headline', 'md_html_close', 100 );

			if ( md_has_headline() )
				add_action( 'md_hook_content_box_top', array( $this, 'headline' ) );
		}
		elseif ( $cover['position'] == 'header_cover_full' ) {
			add_action( 'md_hook_header_top', array( $this, 'overlay' ) );
			add_filter( 'md_filter_header_classes', array( $this, 'header_classes' ) );

			if ( md_has_headline() )
				add_action( 'md_hook_after_header', array( $this, 'headline' ) );
		}
	}

	/**
	 * Print inline CSS to wp_head when needed.
	 *
	 * @since 4.8.3
	 */

	public function inline_css() {
		$cover = md_cover();

		if ( $cover['position'] == 'header_cover_full' ) {
			if ( ! empty( $cover['image'][0] ) )
				echo
					"\n<style type=\"text/css\">\n".
					"\t.header.has-cover {\n".
					"\t\tbackground-image: url('" . esc_url( $cover['image'][0] ) . "');\n".
					"\t\tbackground-size: " . ( $cover['image'][1] < 500 ? 'auto' : 'cover' ) . ";\n".
					"\t}\n".
					"</style>\n";
		}
	}

	/**
	 * Add header wrap classes when needed.
	 *
	 * @since 4.8.3
	 */

	public function header_classes( $classes ) {
		$classes[] = 'has-cover';
		$cover = md_cover();

		if ( ! empty( $cover['text'] ) )
			$classes[] = 'alt';

		return $classes;
	}

	/**
 	 * Call the Headline template within the Loop.
 	 *
	 * @since 5.6
 	 */

	public function headline() {
		if ( ( is_singular() || is_404() ) && have_posts() )
			while ( have_posts() ) {
				the_post();
				md_headline();
			}
		else
			do_action( 'md_hook_page_cover_headline' );
	}

	/**
	 * Add Overlay HTML to covers.
	 *
	 * @since 4.8.6
	 */

	public function overlay() {
		$style = array();
		$cover = md_cover();

		if ( ! empty( $cover['color'] ) )
			$style['bg_color'] = $cover['color'];

		if ( ! empty( $cover['position'] ) && empty( $cover['disable_overlay'] ) )
			echo '<div class="overlay"' . md_style( $style ) . '></div>';
	}

}

new md_page_cover;
