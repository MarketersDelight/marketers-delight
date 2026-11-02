<?php
/**
 * Featured Media abilities for apps and AI assistants: set any post's
 * featured image and where Marketers Delight shows it.
 *
 * @since 6.2
 */

class md_featured_media_abilities extends md_abilities {

	/**
	 * Describe the Featured Media abilities.
	 *
	 * @since 6.2
	 */

	public function abilities() {
		return array(
			'set-featured-media' => array(
				'label' => __( 'Set featured media', 'md' ),
				'description' => __( 'Sets a post\'s featured image from an image already in the Media Library, and where Marketers Delight shows it. The position can also be changed on its own.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'post_id' => array(
							'type' => 'integer',
							'description' => __( 'The post to set the featured media on.', 'md' )
						),
						'image_id' => array(
							'type' => 'integer',
							'description' => __( 'The ID of an image in the Media Library.', 'md' )
						),
						'position' => array(
							'type' => 'string',
							'enum' => $this->image_positions(),
							'description' => __( 'Where the image shows: title_right, title_left or title_center sit beside the title; right, left or center sit beside the title and text; above_headline or below_headline span the full width; remove hides it. Leave out to use the site\'s default.', 'md' )
						)
					),
					'required' => array( 'post_id' ),
					'additionalProperties' => false
				),
				'execute' => 'set_featured_media',
				'permission' => 'can_set_featured_media'
			)
		);
	}

	/**
	 * Check the user can edit the post.
	 *
	 * @since 6.2
	 */

	public function can_set_featured_media( $input = array() ) {
		return current_user_can( 'edit_post', absint( $input['post_id'] ?? 0 ) );
	}

	/**
	 * Set the featured image and save its Featured Media position.
	 *
	 * @since 6.2
	 */

	public function set_featured_media( $input = array() ) {
		$post_id = absint( $input['post_id'] );

		if ( ! empty( $input['image_id'] ) ) {
			if ( ! wp_attachment_is_image( $input['image_id'] ) )
				return new WP_Error( 'md_featured_media_image', __( 'That ID isn\'t an image in the Media Library.', 'md' ) );

			set_post_thumbnail( $post_id, $input['image_id'] );
		}

		$meta = md_post_meta( null, $post_id, array() );

		if ( isset( $input['position'] ) ) {
			$meta['featured_media']['position'] = $input['position'];
			update_post_meta( $post_id, 'marketers_delight', $meta );
		}

		return array(
			'post_id' => $post_id,
			'image_id' => (int) get_post_thumbnail_id( $post_id ),
			'position' => $meta['featured_media']['position'] ?? ''
		);
	}

}

new md_featured_media_abilities;
