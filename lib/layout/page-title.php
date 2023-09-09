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
		$image_order = 10;
		$hook = 'md_hook_content';
		$image = $this->get( 'image' );
		$cover = md_cover();

		if ( ! empty( $image['size'] ) )
			add_action( 'wp_head', array( $this, 'inline_css' ) );

		if ( ! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
			$hook = 'md_hook_page_cover_headline';

		if ( $image['position'] == 'above_headline' )
			$image_order = 5;
		elseif ( $image['position'] == 'below_headline' )
			$image_order = 15;

		if ( $this->get( 'title' ) || $this->get( 'description' ) )
			add_action( $hook, array( $this, 'html' ), 20 );

		if ( $this->get( 'title' ) )
			add_action( 'md_hook_page_title', array( $this, 'title' ) );

		if ( ! empty( $image['id'] ) && $image['position'] !== 'remove' )
			add_action( 'md_hook_page_title', array( $this, 'image' ), $image_order );

		if ( $this->get( 'description' ) )
			add_action( 'md_hook_page_title', array( $this, 'description' ) );
	}

	/**
	 * Print inline CSS to resize featured image across devices.
	 *
	 * @since 5.6
	 */

	public function inline_css() {
		$image = $this->get( 'image' );
		$devices = array( 'tablet' => 900, 'mobile' => 700 );
		$selector = '.page-title .page-image';

		echo "<style type=\"text/css\">\n";

		if ( ! empty( $image['size']['desktop'] ) )
			echo "$selector { flex-basis: " . esc_attr( $image['size']['desktop'] ) . "px; }\n";

		foreach ( $devices as $device => $width )
			if ( ! empty( $image['size'][$device] ) )
				echo '@media all and (max-width: ' . esc_attr( $width ) . "px) { $selector { flex-basis: " . esc_attr( $image['size'][$device] ) . "px; } }\n";

		echo "</style>\n";
	}

	/**
	 * Return key data of Page Title, including Headline, Description,
	 * and the Featured Image.
	 *
	 * @since 5.6
	 */

	public function get( $key = null ) {
		$data = array( 'title' => '', 'description' => '' );

		$data['title'] = md_page_title();

		if ( is_post_type_archive() )
			$data['description'] = md_post_type_field( 'archives_text' );
		elseif ( is_home() || is_singular( 'post' ) )
			$data['description'] = md_post_type_field( 'archives_text' );
		elseif ( is_tax() && get_queried_object() )
			$data['description'] = md_term_meta( 'archives_text' );
		elseif ( is_category() )
			$data['description'] = category_description();

		if ( has_filter( 'md_page_description' ) )
			$data['description'] = apply_filters( 'md_page_description' );

		$data['image']['position'] = md_featured_image_position();
		$data['image']['id'] = md_module( array( 'featured_image', 'image', 'id' ) );
		$data['image']['size'] = md_module( array( 'featured_image', 'image_width' ) );

		if ( isset( $key ) )
			$data = $data[$key];

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

	public function html() { ?>
		<div class="<?php echo esc_attr( $this->classes() ); ?>"<?php echo md_cover_style(); ?>>
			<?php md_hook_page_title(); ?>
		</div>
	<?php }

	/**
	 * Render the Page Headline.
	 *
	 * @since 5.6
	 */

	public function title() {
		$title = $this->get( 'title' );
		do_action( 'md_hook_before_page_title' );
	?>
		<div class="page-headline-wrap">
			<h1 class="page-headline"><?php echo md_text_field( $title ); ?></h1>
		</div>
	<?php
		do_action( 'md_hook_after_page_title' ); }

	/**
	 * Render the Page Description.
	 *
	 * @since 5.6
	 */

	public function description() {
		$description = $this->get( 'description' );
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
		$image = $this->get( 'image' );
	?>
		<div class="page-image">
			<?php echo wp_get_attachment_image( $image['id'], 'full' ); ?>
		</div>
	<?php }

}
