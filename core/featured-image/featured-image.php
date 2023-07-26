<?php
/**
 * Build meta box and taxonomy controls to add MD Featured Image
 * upload fields where needed, and extend onto WordPress' controls.
 *
 * @since 4.3.5
 */

class md_featured_image extends md_api {

	/**
	 * Include related files.
	 *
	 * @since 5.6
	 */

	public function includes() {
		include_once( 'featured-image-functions.php' );
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
	 * Register meta box and term.
	 *
	 * @since 4.3.5
	 */

	public function register() {
		$this->name = __( 'Featured Image', 'md' );
		return array(
			'meta_box' => array(
				'name' => $this->name,
				'page_settings' => true,
				'fields' => $this->fields()
			),
			'term' => array(
				'name' => $this->name,
				'page_settings' => true,
				'fields' => $this->fields()
			)
		);
	}

	/**
	 * Set options for save.
	 *
	 * @since 4.3.5
	 */

	public function fields() {
		$sanitize = new md_sanitize;
		return array(
			'image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'position' => array(
				'type' => 'select',
				'options' => array_keys( $sanitize->values['featured_image'] )
			),
			'cover_position' => array(
				'type' => 'select',
				'options' => array_keys( $sanitize->values['covers'] )
			),
			'cover_image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'bg_color' => array( 'type' => 'color' ),
			'text_color' => array(
				'type' => 'checkbox',
				'options' => array( 'alternate', 'disable_cover' )
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
	 * Term fields template callback.
	 *
	 * @since 5.0
	 */

	public function term() {
		echo "<div class=\"md-$this->_clean_id md-tab-content\">";
		$this->admin_template();
		echo '</div>';	}

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
		$screen = get_current_screen();
		$data = $this->_data();
		$sanitize = new md_sanitize;
		$default_position = md_setting( array( 'colors', 'featured_image', 'cover_position' ) );
		$cover_position = $this->fields->module(  'cover_position', $default_position );
		$disable_overlay = md_setting( array( 'colors', 'featured_image', 'cover_styles', 'disable_cover' ) );
		$disable_overlay_single = $this->fields->module( array( 'text_color', 'disable_cover' ) );
		$overlay_label = $disable_overlay ? __( 'Add overlay', 'md' ) : __( 'Remove overlay', 'md' );
		include( 'admin-fields.php' );
		$this->scripts();
	}

	/**
	 * Toggle scripts for Cover Image admin controls.
	 *
	 * @since 4.7
	 */

	public function scripts() {
		$disable_overlay = md_setting( array( 'colors', 'featured_image', 'cover_styles', 'disable_cover' ) );
	?>
		<script>
			( function() {
				document.getElementById( '<?php echo $this->_prefix; ?>_cover_position' ).onchange = function() {
					document.getElementById( 'md_cover_settings' ).style.display = this.value !== '' ? 'block' : 'none';
				}
				document.getElementById( '<?php echo $this->_prefix; ?>_text_color_disable_cover' ).onchange = function() {
					<?php if ( ! empty( $disable_overlay ) ) : ?>
					document.getElementById( 'md_cover_overlay' ).style.display = this.checked ? 'block' : 'none';
					<?php else : ?>
					document.getElementById( 'md_cover_overlay' ).style.display = this.checked ? 'none' : 'block';
					<?php endif; ?>
				}
			})();
		</script>
	<?php }

	/**
	 * Load featured image in various positions across templates.
	 *
	 * @since 4.8.3
	 */

	public function template() {
		add_action( 'md_hook_content_item', array( $this, 'above_headline' ) );
		add_action( 'md_hook_content_item', array( $this, 'below_headline' ), 30 );
		add_action( 'md_hook_before_headline', array( $this, 'overlay' ), 1 );
		add_action( 'md_hook_before_page_title', array( $this, 'overlay' ), 1 );

		$cover = md_cover();

		if ( $cover['position'] == 'header_cover' ) {
			if ( md_has_headline() )
				add_action( 'md_hook_before_content_box', array( $this, 'header_cover' ) );
			if ( is_singular() || is_404() ) {
				add_action( 'md_hook_before_headline', 'md_inner_html', 5 );
				add_action( 'md_hook_after_headline', 'md_html_close' );
			}
		}
		elseif ( $cover['position'] == 'header_cover_full' ) {
			add_action( 'md_hook_header_top', array( $this, 'overlay' ) );
			add_filter( 'md_filter_header_classes', array( $this, 'header_classes' ) );
			if ( md_has_headline() )
				add_action( 'md_hook_after_header', array( $this, 'headline' ) );
			if ( is_singular() || is_404() )
				remove_action( 'md_hook_before_headline', array( $this, 'overlay' ), 1 );
		}
	}

	/**
	 * Print inline CSS to wp_head when needed.
	 *
	 * @since 4.8.3
	 */

	public function inline_css() {
//		if ( is_singular() || is_category() || is_tax() ) {
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
//		}
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
			$classes[] = 'text-alt';

		return $classes;
	}

	/**
 	 * Dedicated template for Header Cover Cover position.
 	 *
 	 * @since 4.1
	 * @moved 5.6
 	 */

	public function header_cover() {
		echo '<div class="header-cover has-cover">';
		$this->headline();
		echo '</div>';
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
			md_page_title();
	}

	/**
	 * Insert featured image above/below headline with in-post check.
	 *
	 * @since 4.1
	 * @since 5.6
	 */

	public function above_headline() {
		$position = md_featured_image_position();
		if ( has_post_thumbnail() && $position == 'above_headline' )
			md_featured_image();
	}

	public function below_headline() {
		$position = md_featured_image_position();
		if ( has_post_thumbnail() && $position == 'below_headline' )
			md_featured_image();
	}

	/**
	 * Add Overlay HTML to covers.
	 *
	 * @since 4.8.6
	 */

	public function overlay() {
		$cover = md_cover();

		if ( ! empty( $cover['position'] ) && empty( $cover['disable_overlay'] ) )
			echo '<div class="overlay"' . md_style( array( 'bg_color' => $cover['color'] ) ) . '></div>';
	}

}

new md_featured_image;
