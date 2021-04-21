<?php
/**
 * Build meta box and taxonomy controls to add MD Featured Image
 * upload fields where needed, and extend onto WordPress' controls.
 *
 * @since 4.3.5
 */

class md_featured_image extends md_api {

	/**
	 * Run actions and filters.
	 *
	 * @since 4.3.5
	 */

	public function actions() {
		$this->sanitize = new md_sanitize;
		$this->design = new md_design;
		$this->values = $this->design->values();
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
				'name' => sprintf( __( '%s Extras', 'md' ), $this->name ),
				'context' => 'side',
				'priority' => 'high',
				'fields' => $this->fields()
			),
			'term' => array(
				'name' => $this->name,
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
		return array(
			'image' => array(
				'type' => 'upload',
				'upload_type' => 'media'
			),
			'position' => array(
				'type' => 'select',
				'options' => array_keys( $this->sanitize->values['featured_image'] )
			),
			'caption' => array(
				'type' => 'checkbox',
				'options' => array( 'add' )
			),
			'bg_color' => array( 'type' => 'color' ),
			'text_color' => array(
				'type' => 'checkbox',
				'options' => array( 'alternate' )
			)
		);
	}

	/**
	 * Meta box template and scripts callback.
	 *
	 * @since 5.0
	 */

	public function meta_box() {
		$this->admin_template();
	}

	public function meta_scripts() {
		$this->scripts();
	}

	/**
	 * Term fields template and scripts callback.
	 *
	 * @since 5.0
	 */

	public function term() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo $this->name; ?></h3>
			<div class="md-widget-item">
				<?php $this->admin_template(); ?>
			</div>
		</div>
	<?php }

	public function term_scripts() {
		$this->scripts();
	}

	/**
	 * Build additional Featured Image controls.
	 *
	 * @since 4.7
	 */

	public function admin_template() {
		$screen = get_current_screen();
		$covers = array( 'headline_cover', 'header_cover', 'header_cover_full' );
		$position = $this->fields->module( 'position' );
		$pos = ! empty( $position ) ? $position : md_setting( array( 'content', 'featured_image', 'position' ) );
		$cover_display = in_array( $pos, $covers ) ? 'block' : 'none';
		include( 'admin-fields.php' );
	}

	/**
	 * Add inline scripts to Featured Image controls.
	 *
	 * @since 4.8.3
	 */

	public function scripts() { ?>
		<script>
			( function() {
				document.getElementById( '<?php echo $this->_prefix; ?>_position' ).onchange = function() {
					document.getElementById( 'featured_image_cover_field' ).style.display = this.value == 'headline_cover' || this.value == 'header_cover' || this.value == 'header_cover_full' ? 'block' : 'none';
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
		$position = md_featured_image_position();

		add_action( 'md_hook_headline_top', array( $this, 'overlay_single' ), 1 );

		if ( ( is_singular() || is_category() || is_tax() || is_home() || is_404() ) && $position == 'header_cover_full' ) {
			add_action( 'md_hook_header_top', array( $this, 'overlay' ) );
			add_filter( 'md_filter_header_classes', array( $this, 'header_classes' ) );
		}

		if ( is_category() || is_tax() ) {
			$image = md_term_meta( array( 'featured_image', 'image' ) );

			if ( in_array( $position, array( 'header_cover', 'header_cover_full' ) ) )
				remove_action( 'md_hook_content', 'md_archives_title' );

			if ( ! empty( $image['id'] ) )
				if ( $position == 'below_headline' ) {
					remove_action( 'md_hook_content', 'md_archives_title' ); // silly
					add_action( 'md_hook_content', 'md_archives_title', 5 );
					add_action( 'md_hook_content', 'md_featured_image_tax', 7 );
				}
				elseif ( $position == 'above_headline' )
					add_action( 'md_hook_content', 'md_featured_image_tax', 3 );
		}
	}

	/**
	 * Add header wrap classes when needed.
	 *
	 * @since 4.8.3
	 */

	public function header_classes( $classes ) {
		$position = md_featured_image_position();
		$classes[] = 'featured-image-cover';

		if ( md_meta( array( 'featured_image', 'text_color', 'alternate' ) ) )
			$classes[] = 'text-alt';

		return $classes;
	}

	/**
	 * Add Overlay HTML to covers.
	 *
	 * @since 4.8.6
	 */

	public function overlay() {
		$bg_color = md_meta( array( 'featured_image', 'bg_color' ) );
		$style = ! empty( $bg_color ) ? ' style="background-color: ' . esc_attr( $bg_color ) . ';"' : '';
		echo '<div class="overlay"' . $style . '></div>';
	}

	public function overlay_single() {
		$position = md_featured_image_position();
		if (
			( ! is_singular() && in_the_loop() && in_array( $position, array( 'headline_cover', 'header_cover', 'header_cover_full' ) ) ) ||
			( ( is_singular() || is_category() || is_tax() || is_404() ) && in_array( $position, array( 'headline_cover', 'header_cover' ) ) )
		)
			$this->overlay();
	}

	/**
	 * Print inline CSS to wp_head when needed.
	 *
	 * @since 4.8.3
	 */

	public function inline_css() {
		if ( is_singular() || is_category() || is_tax() ) {
			$position = md_featured_image_position();
			$wp_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
			$wp_image_url = ! empty( $wp_image[0] ) ? $wp_image[0] : '';
			$image = is_singular() ? $wp_image_url : md_term_meta( array( 'featured_image', 'image', 'url' ) );
			if ( $position == 'header_cover_full' && ! empty( $image ) ) {
				echo "\n<style type=\"text/css\">\n";
				if ( $position == 'header_cover_full' ) {
					echo "\t.header {\n".
						 	"\t\tbackground-image: url('" . esc_url( $image ) . "');\n".
						 "\t}\n";
				}
				echo "</style>\n";
			}
		}
	}

}

new md_featured_image;