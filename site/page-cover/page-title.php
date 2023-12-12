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
		$description_hook = $cta_hook = 'md_hook_after_page_title';
		$image_hook = 'md_hook_page_header_bottom';
		$inline = md_module( array( 'layout', 'content', 'page_title' ) );
		$image_id = $this->get( 'image_id' );
		$cover = md_cover( 'page' );

		if ( ! empty( $cover['position'] ) && in_array( $cover['position'], array( 'header_cover', 'header_cover_full' ) ) )
			$hook = 'md_hook_page_cover_title';
		elseif ( $inline && md_has_sidebar() ) {
			$description_hook = 'md_hook_page_header_bottom';
			$cta_hook = 'md_hook_after_description';
		}
		elseif ( ! $inline )
			$hook = 'md_hook_content_top';

		if ( $this->get( 'title' ) || $this->get( 'description' ) )
			add_action( $hook, array( $this, 'html' ), 20 );

		if ( $this->get( 'description' ) )
			add_action( $description_hook, array( $this, 'description' ) );

		if ( $image_id ) {
			$image_order = 10;
			$image_position = $this->get( 'image_position' );

			if ( $image_position == 'center' ) {
//				$image_order = 20;
			}
			elseif ( $image_position == 'above_headline' )
				$image_hook = 'md_hook_page_header_top';
			elseif ( $image_position == 'below_headline' ) {
				$image_hook = 'md_hook_after_page_title';
				$image_order = 5;
			}

			if ( $image_position !== 'remove' )
				add_action( $image_hook, array( $this, 'image' ), $image_order );
		}

		add_action( $cta_hook, array( $this, 'cta' ) );
	}

	/**
	 * Return key data of Page Title, including Headline, Description,
	 * and the Featured Image.
	 *
	 * @since 5.6
	 */

	public function get( $key = null ) {
		$description = '';
		$data = array();

		if ( has_filter( 'md_page_description' ) )
			$description = apply_filters( 'md_page_description' );
		elseif ( is_post_type_archive() || is_home() || is_singular( 'post' ) )
			$description = md_post_type_field( 'archives_text' );
		elseif ( ( is_category() || is_tax() ) && get_queried_object() )
			$description = category_description();
		elseif ( is_author() )
			$description = get_the_author_meta( 'description' );

		if ( $description )
			$data['description'] = $description;

		$data['image_position'] = md_featured_image_position();
		$data['image_id'] = md_module( array( 'featured_image', 'image', 'id' ) );
		$data['image_size'] = md_module( array( 'featured_image', 'image_width' ) );
		$data['page_cta'] = md_module( 'page_cta' );
		$data['link_primary'] = md_module( 'link_primary' );
		$data['link_secondary'] = md_module( 'link_secondary' );

		if ( ! empty( $data['image_id'] ) ) {
			if ( in_array( $data['image_position'], array( 'left', 'right' ) ) )
				$data['classes'][] = 'layout-columns';
			else
				$data['classes'][] = 'layout-slim';

			$data['classes'][] = 'image-' . str_replace( '_', '-', $data['image_position'] );
		}

		$inline = md_module( array( 'layout', 'content', 'page_title' ) );

		if ( $inline && md_has_sidebar() )
			$data['classes'][] = 'inline';
		else
			$data['classes'][] = 'outer';

		if ( isset( $key ) )
			$data = ! empty( $data[$key] ) ? $data[$key] : '';

		return $data;
	}

	/**
	 * Render Page Title HTML wrapper.
	 *
	 * @since 5.6
	 */

	public function html() {
		$classes = $this->get( 'classes' );

		md_headline( array(
			'title' => md_page_title(),
			'context' => 'page',
			'classes' => $classes
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
		<div class="description">
			<?php echo wpautop( $description ); ?>
			<?php do_action( 'md_hook_after_description' ); ?>
		</div>
	<?php }

	/**
	 * Render the Page Featured Image.
	 *
	 * @since 5.6
	 */

	public function image() {
		$image_id = $this->get( 'image_id' );

		if ( ! $image_id )
			return;

		$image_size = $this->get( 'image_size' );
	?>
		<div class="page-image">
			<?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
			<?php if ( $image_size ) :
				md_inline_css( array(
					'.page-header .page-image' => array(
						'flex-basis' => array(
							'query' => $image_size,
							'unit' => 'px',
							'fallback' => 'max-width'
						)
					)
				) );
			endif; ?>
		</div>
	<?php }

	/**
	 * Render the Page CTA.
	 *
	 * @since 5.6
	 */

	public function cta() {
		$cta = $this->get( 'page_cta' );

		if ( ! $cta )
			return;

		echo '<div class="page-cta">';

		if ( $cta == 'links' ) {
			$secondary = $this->get( 'link_secondary' );
			$primary = $this->get( 'link_primary' );

			if ( $secondary ) {
				$secondary['classes'] = 'page-cta-link';
				echo md_link( $secondary );
			}

			if ( $primary ) {
				$primary['classes'] = 'page-cta-link';
				echo md_link( $primary );
			}
		}
		elseif ( $cta == 'custom' )
			echo md_module( 'custom_html' );

		echo '</div>';
	}

}
