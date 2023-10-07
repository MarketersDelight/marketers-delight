<?php
/**
 * Build the frontend Page Title template, consisting of
 * a main Page Headline, Page Description, and Featured Image
 * with various locations within the markup.
 *
 * @since 5.6
 */

class md_page_title {

	/**
	 * Load Page Title templates.
	 *
	 * @since 5.6
	 */

	public function templates() {
		$hook = 'md_hook_content';
		$image = $this->get( 'image' );
		$cover = md_cover();

		if ( ! empty( $image['size'] ) )
			add_action( 'wp_head', array( $this, 'inline_css' ) );

		if ( ! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
			$hook = 'md_hook_page_cover_headline';

		if ( $this->get( 'title' ) || $this->get( 'description' ) )
			add_action( $hook, array( $this, 'html' ), 20 );

		if ( $this->get( 'description' ) )
			add_action( 'md_hook_after_headline', array( $this, 'description' ) );

		$image_hook = 'md_hook_before_headline';

		if ( $image['position'] == 'below_headline' )
			$image_hook = 'md_hook_after_headline';

		if ( ! empty( $image['id'] ) && $image['position'] !== 'remove' )
			add_action( $image_hook, array( $this, 'image' ) );
	}

	/**
	 * Print inline CSS to resize featured image across devices.
	 *
	 * @since 5.6
	 */

	public function inline_css() {
		$image = $this->get( 'image' );

		md_inline_image_css( $image['size'] );
	}

	/**
	 * Return key data of Page Title, including Headline, Description,
	 * and the Featured Image.
	 *
	 * @since 5.6
	 */

	public function get( $key = null, $group = null ) {
		$data = array( 'title' => md_page_title() );

		if ( ! in_the_loop() ) {

			if ( is_post_type_archive() || is_home() || is_singular( 'post' ) )
				$description = md_post_type_field( 'archives_text' );
			elseif ( ( is_category() || is_tax() ) && get_queried_object() )
				$description = category_description();

			if ( has_filter( 'md_page_description' ) )
				$description = apply_filters( 'md_page_description' );

			if ( $description )
				$data['description'] = $description;

			$data['image']['position'] = md_featured_image_position();
			$data['image']['id'] = md_module( array( 'featured_image', 'image', 'id' ) );
			$data['image']['size'] = md_module( array( 'featured_image', 'image_width' ) );

		}

		if ( isset( $key ) )
			if ( isset( $group ) )
				$data = ! empty( $data[$key][$group] ) ? $data[$key][$group] : '';
			else
				$data = ! empty( $data[$key] ) ? $data[$key] : '';

		return $data;
	}

	/**
	 * Populate HTML classes for main HTML wrapper.
	 *
	 * @since 5.6
	 */

	public function classes() {
		$image = $this->get( 'image' );
		$classes = array( 'page-title' );

		if ( ! empty( $image['id'] ) )
			$classes[] = 'layout-' . $image['position'];

		return md_cover_classes( $classes );
	}

	/**
	 * Render Page Title HTML wrapper.
	 *
	 * @since 5.6
	 */

	public function html() {
		$title = $this->get( 'title' );

		md_headline( array(
			'title' => $title
		) );
	}

	/**
	 * Render the Page Description.
	 *
	 * @since 5.6
	 */

	public function description() {
		$description = $this->get( 'description' );

		if ( ! $description )
			return;
	?>
		<div class="page-description">
			<?php echo wpautop( $description ); ?>
		</div>
	<?php }

	/**
	 * Render the Page Featured Image.
	 *
	 * @since 5.6
	 */

	public function image() {
		$image_id = $this->get( 'image', 'id' );
	?>
		<div class="page-image">
			<?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
		</div>
	<?php }

}
