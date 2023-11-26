<?php
/**
 * Adds the Featured Video meta box to various post types.
 * Integrates with YouTube, Vimeo, and custom embed codes.
 *
 * @since 4.6.4
 */

class md_featured_video extends md_api {

	/**
	 * Register meta box.
	 *
	 * @since 4.6.4
	 */

	public function register() {
		return array(
			'meta_box' => array(
				'name' => __( 'Featured Video', 'md' ),
				'page_settings' => true,
				'fields' => array(
					'service' => array(
						'type'    => 'select',
						'options' => array( 'youtube', 'vimeo', 'embed' )
					),
					'position' => array(
						'type' => 'select',
						'options' => array(
							'before_headline',
							'after_headline'
						)
					),
					'youtube' => array( 'type' => 'text' ),
					'vimeo' => array( 'type' => 'text' ),
					'thumbnail' => array( 'type' => 'media' ),
					'embed' => array( 'type' => 'code' )
				)
			)
		);
	}

	/**
	 * A collection of meta fields to use in Video templates.
	 *
	 * @since 4.6.4
	 */

	public function meta() {
		return array(
			'service' => md_post_meta( array( 'featured_video', 'service' ) ),
			'position' => md_post_meta( array( 'featured_video', 'position' ) ),
			'youtube' => md_post_meta( array( 'featured_video', 'youtube' ) ),
			'vimeo' => md_post_meta( array( 'featured_video', 'vimeo' ) ),
			'embed' => md_post_meta( array( 'featured_video', 'embed' ) )
		);
	}

	/**
	 * Build default meta box that shows on post edit screens.
	 *
	 * @since 4.6.4
	 */

	public function meta_box() {
		$meta = $this->meta();
		$videos = array(
			'youtube' => array(
				'label' => __( 'YouTube Video ID', 'md' ),
				'desc'  => __( 'Example: <code>https://www.youtube.com/watch?v=<b style="color: red;">XXXXXXXXXXX</b></code>', 'md-videos' )
			),
			'vimeo' => array(
				'label' => __( 'Vimeo Video ID', 'md' ),
				'desc'  => __( 'Example: <code>https://vimeo.com/<b style="color: red;">XXXXXXXXX</b></code>', 'md-videos' )
			)
		);
		echo "<div class=\"md-$this->_clean_id md-tab-content\">";
		include( 'meta-box.php' );
		echo '</div>';
	}

	/**
	 * Load video scripts to admin page footer.
	 *
	 * @since 4.6.4
	 */

	public function meta_scripts() { ?>
		<script>
			( function() {
				document.getElementById( '<?php echo $this->_prefix; ?>_service' ).onchange = function() {
					document.getElementById( 'featured_video_options' ).style.display = this.value != '' ? 'block' : 'none';
					document.getElementById( 'featured_video_youtube_row' ).style.display = this.value == 'youtube' ? 'block' : 'none';
					document.getElementById( 'featured_video_vimeo_row' ).style.display = this.value == 'vimeo' ? 'block' : 'none';
					document.getElementById( 'featured_video_embed_row' ).style.display = this.value == 'embed' ? 'block' : 'none';
				}
			})();
		</script>
	<?php }

	/**
	 * Manipulate pages, fire hooks to template_redirect.
	 *
	 * @since 4.6.4
	 */

	public function template() {
		if ( is_singular() ) {
			$meta = $this->meta();
			$id = $this->get_video_id();

			if ( ! empty( $meta['service'] ) && ! empty( $id ) ) {
				if ( in_array( $meta['position'], array( '', 'before_headline' ) ) )
					$order = 10;
				else
					$order = 30;

				add_action( 'md_hook_content_item', array( $this, 'video' ), $order );
			}
		}
	}

	/**
	 * Get the Video ID and show video if active.
	 *
	 * @since 4.6.4
	 */

	public function get_video_id() {
		$meta = $this->meta();

		if ( ! empty( $meta['service'] ) )
			if ( $meta['service'] == 'youtube' && ! empty( $meta['youtube'] ) )
				return $meta['youtube'];
			if ( $meta['service'] == 'vimeo' && ! empty( $meta['vimeo'] ) )
				return $meta['vimeo'];
			if ( $meta['service'] == 'embed' && ! empty( $meta['embed'] ) )
				return 'embed';

		return false;
	}

	/**
	 * Get the Video ID and show video if active.
	 *
	 * @since 4.6.4
	 */

	public function get_video_url() {
		$meta = $this->meta();
		$url = '';

		if ( $meta['service'] == 'youtube' && ! empty( $meta['youtube'] ) )
			$url = 'https://www.youtube.com/embed/' . $meta['youtube'];
		elseif ( $meta['service'] == 'vimeo' && ! empty( $meta['vimeo'] ) )
			$url = 'https://player.vimeo.com/video/' . $meta['vimeo'];

		return $url;
	}

	/**
	 * Display featured video at the top of the content box.
	 *
	 * @since 4.6.4
	 */

	public function video() {
		$meta = $this->meta();
		include( md_template( 'featured-video', true ) );
	}

}

new md_featured_video;