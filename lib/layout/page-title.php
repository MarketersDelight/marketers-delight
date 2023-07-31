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
		$hook = 'md_hook_before_content_box';
		$image = $this->get( 'image' );
		$cover = md_cover();

		if ( ! empty( $cover['position'] ) )
			$hook = 'md_hook_page_cover_headline';

		if ( $image['position'] == 'above_headline' )
			$image_order = 5;
		elseif ( $image['position'] == 'below_headline' )
			$image_order = 15;

		add_action( $hook, array( $this, 'html' ) );
		add_action( 'md_hook_page_title', array( $this, 'title' ) );
		if ( $image['position'] !== 'remove' )
			add_action( 'md_hook_page_title', array( $this, 'image' ), $image_order );
		add_action( 'md_hook_page_title', array( $this, 'description' ) );
	}

	/**
	 * Return key data of Page Title, including Headline, Description,
	 * and the Featured Image.
	 *
	 * @since 5.6
	 */

	public function get( $key = null ) {
		$data = array( 'title' => '', 'description' => '' );

		if ( is_post_type_archive() ) {
			$data['title'] = post_type_archive_title( '', false );
			$data['description'] = md_post_type_field( 'archives_text' );
		}
		elseif ( is_home() || is_singular( 'post' ) ) {
			$data['title'] = md_post_type_field( 'archives_title' );
			$data['description'] = md_post_type_field( 'archives_text' );
		}
		elseif ( is_tax() && get_queried_object() ) {
			$data['title'] = single_term_title( '', false );
			$data['description'] = md_term_meta( 'archives_text' );
		}
		elseif ( is_category() ) {
			$data['title'] = single_cat_title( '', false );
			$data['description'] = category_description();
		}
		elseif ( is_tag() )
			$data['title'] = single_tag_title( '', false );
		elseif ( is_author() )
			$data['title'] = get_the_author();
		elseif ( is_year() )
			$data['title'] = get_the_date( _x( 'Y', 'yearly archives date format' ) );
		elseif ( is_month() )
			$data['title'] = get_the_date( _x( 'F Y', 'monthly archives date format' ) );
		elseif ( is_day() )
			$data['title'] = get_the_date( _x( 'F j, Y', 'daily archives date format' ) );

		if ( has_filter( 'md_page_title' ) )
			$data['title'] = do_action( 'md_page_title' );

		if ( has_filter( 'md_page_description' ) )
			$data['description'] = do_action( 'md_page_description' );

		$data['image']['position'] = md_featured_image_position();
		$data['image']['id'] = md_module( array( 'featured_image', 'image', 'id' ) );

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
	?>
		<h1 class="page-headline"><?php echo md_text_field( $title ); ?></h1>
	<?php }

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
