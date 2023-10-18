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

		if ( in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
			$hook = 'md_hook_page_cover_headline';

		if ( $this->get( 'title' ) || $this->get( 'description' ) )
			add_action( $hook, array( $this, 'html' ), 20 );

		if ( $this->get( 'description' ) )
			add_action( 'md_hook_after_title', array( $this, 'description' ) );

		$image_hook = 'md_hook_before_headline';

		if ( in_array( $image['position'], array( 'right', 'center' ) ) )
			$image_hook = 'md_hook_after_headline';

		if ( $image['position'] == 'below_headline' )
			$image_hook = 'md_hook_after_title';

		if ( ! empty( $image['id'] ) && $image['position'] !== 'remove' )
			add_action( $image_hook, array( $this, 'image' ) );
	}

	/**
	 * Return the Page Title of the current page/type/term.
	 * Used in md_page_title() in layout-functions.php
	 *
	 * @since 5.6
	 */

	public function page_title() {
		$title = '';

		if ( is_post_type_archive() ) {
			$post_type_title = post_type_archive_title( '', false );
			$title = md_post_type_field( 'archives_title', $post_type_title );
		}
		elseif ( is_home() || is_singular( 'post' ) )
			$title = md_post_type_field( 'archives_title' );
		elseif ( is_tax() && get_queried_object() ) {
			$term_title = single_term_title( '', false );
			$title = md_term_meta( array( get_post_type(), 'archives_title' ), null, $term_title );
		}
		elseif ( is_category() )
			$title = single_cat_title( '', false );
		elseif ( is_tag() )
			$title = single_tag_title( '', false );
		elseif ( is_author() )
			$title = get_the_author();
		elseif ( is_year() )
			$title = get_the_date( 'Y' );
		elseif ( is_month() )
			$title = get_the_date( 'F Y' );
		elseif ( is_day() )
			$title = get_the_date( 'F j, Y' );

		return $title;
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
	 * Render Page Title HTML wrapper.
	 *
	 * @since 5.6
	 */

	public function html() {
		$css = array();
		$title = $this->get( 'title' );
		$image = $this->get( 'image' );

		if ( $image['size'] )
			$css['image']['size'] = $image['size'];

		if ( $css ) {
			$css['selector'] = '.page-header.headline-image .page-image';

			wp_register_style( 'md-page-header', false );
			wp_enqueue_style( 'md-page-header' );
			wp_add_inline_style( 'md-page-header', md_post_css( $css ) );
		}

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

		if ( ! $image_id )
			return;
	?>
		<div class="page-image">
			<?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
		</div>
	<?php }

}
