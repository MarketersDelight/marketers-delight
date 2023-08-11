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
				'page_settings' => true,
				'fields' => $this->fields()
			)
		);
	}

	/**
	 * Run actions and filters.
	 *
	 * @since 4.3.5
	 */

	public function actions() {
		$this->sanitize = $this->_data( 'sanitize' );
		add_action( 'wp_head', array( $this, 'inline_css' ) );
	}

	/**
	 * Set options for save.
	 *
	 * @since 4.3.5
	 */

	public function fields() {
		return array(
			'cover_image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'cover_position' => array(
				'type' => 'select',
				'options' => array_keys( $this->sanitize->values['covers'] )
			),
			'bg_color' => array( 'type' => 'color' ),
			'text_color' => array(
				'type' => 'checkbox',
				'options' => array( 'alternate', 'disable_cover', 'categories', 'posts' )
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
		$values = $this->_data( 'values' );
		$sanitize = $this->sanitize;
		$default_position = md_setting( array( 'colors', 'page_cover', 'cover_position' ) );
		$show_on_posts = md_setting( array( $screen->post_type, 'page_cover', 'text_color', 'posts' ) );
		$show_on_categories = md_setting( array( $screen->post_type, 'page_cover', 'text_color', 'categories' ) );

		if ( ( in_array( $screen->base, array( 'post', 'post-new' ) ) && $show_on_posts ) || $screen->base == 'term' && $show_on_categories )
			$cover_position = md_setting( array( $screen->post_type, 'page_cover', 'cover_position' ), $default_position );
		else
			$cover_position = $this->fields->module( 'cover_position', $default_position );

		$disable_overlay = md_setting( array( 'colors', 'page_cover', 'cover_styles', 'disable_cover' ) );
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
		$prefix = $this->_prefix();
		$disable_overlay = md_setting( array( 'colors', 'page_cover', 'cover_styles', 'disable_cover' ) );
	?>
		<script>
			( function() {
				document.getElementById( '<?php echo $prefix; ?>_cover_position' ).onchange = function() {
					document.getElementById( 'md_cover_settings' ).style.display = this.value !== '' ? 'block' : 'none';
				}
				document.getElementById( '<?php echo $prefix; ?>_text_color_disable_cover' ).onchange = function() {
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
		add_action( 'md_hook_before_headline', array( $this, 'overlay' ), 1 );

		$cover = md_cover();

		if ( $cover['position'] == 'headline_cover' ) {
			add_action( 'md_hook_page_title', array( $this, 'overlay' ), 1 );
			add_action( 'md_hook_content', 'md_page_title' );
		}
		elseif ( $cover['position'] == 'header_cover' ) {
			add_action( 'md_hook_page_title', array( $this, 'overlay' ), 1 );
			if ( md_has_headline() )
				add_action( 'md_hook_header_bottom', array( $this, 'header_cover' ) );
			if ( is_singular() || is_404() ) {
				add_action( 'md_hook_before_headline', 'md_inner_html', 5 );
				add_action( 'md_hook_after_headline', 'md_html_close', 90 );
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

		if ( empty( $cover['position'] ) || $cover['position'] == 'header_cover' ) {
			add_action( 'md_hook_page_title', 'md_inner_html', 5 );
			add_action( 'md_hook_page_title', 'md_html_close', 100 );
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
			do_action( 'md_hook_page_cover_headline' );
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

new md_page_cover;
