<?php
/**
 * Render and manage registered Collections in post editors.
 *
 * @since 6.0
 */

class md_collection_admin {

	protected $collection;

	public function __construct( $collection ) {
		$this->collection = $collection;
	}

	/**
	 * Register editor hooks for one Collection.
	 *
	 * @since 6.0
	 */

	public function register() {
		add_action( "add_meta_boxes_{$this->collection->parent}", array( $this, 'register_parent_meta_box' ) );

		if ( $this->collection->meta_fields ) {
			add_action( "add_meta_boxes_{$this->collection->post_type}", array( $this, 'register_item_meta_box' ) );
			add_action( "save_post_{$this->collection->post_type}", array( $this, 'save_item_meta' ) );
		}

		add_action( 'pre_get_posts', array( $this, 'filter_items' ) );
	}

	/**
	 * Register the Collection manager on its parent editor.
	 *
	 * @since 6.0
	 */

	public function register_parent_meta_box() {
		$labels = $this->labels();

		add_meta_box( "md-collection-{$this->collection->id}", $labels->name, array( $this, 'parent_meta_box' ), $this->collection->parent, 'normal', 'default' );
	}

	/**
	 * Render Quick Add and saved Collection items.
	 *
	 * @since 6.0
	 */

	public function parent_meta_box( $post ) {
		$collection = $this->collection;
		$labels = $this->labels();
		$query = $collection->query( $post->ID );
		$can_add = $post->post_status === 'publish';
		$singular = $labels->singular_name;
		$plural = $labels->name;
		$endpoint = rest_url( "md/v1/collections/{$collection->id}/{$post->ID}" );
		$manage_url = add_query_arg( array(
			'post_type' => $collection->post_type,
			'md_collection' => $collection->id,
			'md_collection_parent' => $post->ID
		), admin_url( 'edit.php' ) );

		include md_template( 'admin/fields/collections', true );
	}

	/**
	 * Register standalone meta fields on Collection item editors.
	 *
	 * @since 6.0
	 */

	public function register_item_meta_box() {
		$labels = $this->labels();

		add_meta_box( "md-collection-item-{$this->collection->id}", sprintf( __( '%s Details', 'md' ), $labels->singular_name ), array( $this, 'item_meta_box' ), $this->collection->post_type, 'normal', 'default' );
	}

	/**
	 * Render standalone Collection metadata and its parent link.
	 *
	 * @since 6.0
	 */

	public function item_meta_box( $post ) {
		$collection = $this->collection;
		$parent_id = wp_get_post_parent_id( $post->ID );

		wp_nonce_field( "md_collection_{$collection->id}", "md_collection_{$collection->id}_nonce" );

		if ( $parent_id ) {
			$parent_labels = get_post_type_object( $collection->parent )->labels;

			echo '<p><b>' . esc_html( $parent_labels->singular_name ) . '</b><br>';
			echo '<a href="' . esc_url( get_edit_post_link( $parent_id ) ) . '">' . esc_html( get_the_title( $parent_id ) ) . '</a></p>';
		}

		echo $collection->render_fields( $post->ID, true );
	}

	/**
	 * Save standalone meta fields from a Collection item editor.
	 *
	 * @since 6.0
	 */

	public function save_item_meta( $post_id ) {
		$nonce = "md_collection_{$this->collection->id}_nonce";

		if ( ! isset( $_POST[$nonce] ) || ! wp_verify_nonce( $_POST[$nonce], "md_collection_{$this->collection->id}" ) )
			return;

		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) || ! current_user_can( 'edit_post', $post_id ) )
			return;

		$values = wp_unslash( $_POST['md_collection'][$this->collection->id] ?? array() );

		$this->collection->save( $post_id, $values );
	}

	/**
	 * Filter an item list table to one Collection parent.
	 *
	 * @since 6.0
	 */

	public function filter_items( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() || $query->get( 'post_type' ) !== $this->collection->post_type )
			return;

		if ( empty( $_GET['md_collection_parent'] ) || ( $_GET['md_collection'] ?? '' ) !== $this->collection->id )
			return;

		$query->set( 'post_parent', absint( $_GET['md_collection_parent'] ) );
	}

	/**
	 * Get native labels from the Collection item post type.
	 *
	 * @since 6.0
	 */

	protected function labels() {
		return get_post_type_object( $this->collection->post_type )->labels;
	}
}
