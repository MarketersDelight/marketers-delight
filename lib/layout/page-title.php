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
		$cover = md_cover();
		$image = $this->get( 'image' );
		$image_order = 10;
		$hook = 'md_hook_before_content_box';

		if ( ! empty( $cover['position'] ) )
			$hook = 'md_hook_page_cover_headline';

		if ( $image['position'] == 'above_headline' )
			$image_order = 5;
		elseif ( $image['position'] == 'below_headline' )
			$image_order = 15;

		add_action( $hook, array( $this, 'html' ) );

		add_action( 'md_hook_page_title', array( $this, 'title' ) );
		add_action( 'md_hook_page_title', array( $this, 'image' ), $image_order );
		add_action( 'md_hook_page_title', array( $this, 'description' ) );

/*
		$cover = md_cover();
		$image_position = md_featured_image_position();
		$html_hook = empty( $cover['position'] ) ? 'md_hook_before_content_box' : 'md_hook_page_cover_headline';
		$image_hook = in_array( $image_position, array( 'right', 'left', 'center', 'above_headline' ) ) ? 'before' : 'after';

		add_action( $html_hook, array( $this, 'html' ) );
		add_action( "md_hook_{$image_hook}_page_title", array( $this, 'image' ) );
*/
	}

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
	 * Render Page/Archives Title text and description.
	 *
	 * @since 5.6
	 */

	public function html() { ?>
		<div class="<?php echo md_cover_classes( 'page-title format' ); ?>"<?php echo md_cover_style(); ?>>
			<?php md_hook_page_title(); ?>
		</div>
	<?php }

	public function title() {
		$title = $this->get( 'title' );
	?>
		<h1 class="page-headline"><?php echo md_text_field( $title ); ?></h1>
	<?php }

	public function description() {
		$description = $this->get( 'description' );
	?>
		<div class="page-description">
			<?php echo wpautop( $description ); ?>
		</div>
	<?php }

	/**
	 * Page Title Featured Image template.
	 *
	 * @since 5.6
	 */

	public function image() {
		$image = $this->get( 'image' );
	?>
		<div class="page-image page-image-<?php echo esc_attr( $image['position'] ); ?>">
			<?php echo wp_get_attachment_image( $image['id'], 'medium' ); ?>
		</div>
	<?php }

}
