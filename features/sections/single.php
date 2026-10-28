<?php
/**
 * Sections around single posts and pages: Archive Sections' builder in
 * a second context. A post type's Single Sections are the defaults; a
 * post's own Sections replace the areas it saves.
 *
 * md_api hooks a class's methods by name (actions, template, css,
 * enqueue, setting_defaults...), and a subclass inherits them. So
 * actions() and template() are overridden on purpose, and anything
 * hook-bearing added to Archive Sections must be checked here too.
 *
 * @since 6.0.4
 */

class md_single_sections extends md_archive_sections {

	/**
	 * Hooks. The archive query variable is registered by Archive Sections.
	 *
	 * @since 6.0.4
	 */

	public function actions() {
		add_filter( 'md_admin_page_archive_fields', array( $this, 'tabs' ), 11 );
	}

	/**
	 * Only post types have singles, so skip the tab on taxonomy and
	 * author archive settings.
	 *
	 * @since 6.0.4
	 */

	public function tabs( $fields ) {
		if ( ! empty( $this->_get_screen['is_taxonomy'] ) || ( $this->_get_screen['page'] ?? '' ) === 'md_author' )
			unset( $fields[$this->_clean_id] );

		return $fields;
	}

	/**
	 * Register Single Sections on post type settings and the post editor.
	 *
	 * @since 6.0.4
	 */

	public function register() {
		$this->name = __( 'Single', 'md' );

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'child_of' => array( 'archive', 'page_settings' ),
				'fields' => $this->fields()
			),
			'meta_box' => array(
				'name' => __( 'Sections', 'md' ),
				'edit_post' => true,
				'child_of' => 'page_settings',
				'fields' => $this->fields()
			)
		);
	}

	/**
	 * Render the Sections builder for single posts.
	 *
	 * @since 6.0.4
	 */

	public function admin_fields() {
		$post_type = $this->fields->screen_post_type( true );
		$is_post = $this->_get_screen['is_post'];

		echo '<div class="md-' . esc_attr( $this->_clean_id ) . ' md-tab-content">';

		$this->fields->field( 'builder', $this->builder_args( $post_type, array(
			'title' => __( 'Add Sections', 'md' ),
			'description' => $is_post ? __( 'Replaces this post type\'s Single sections in the areas you fill. Empty areas keep them.', 'md' ) : __( 'Add reusable content before or after each post\'s content.', 'md' ),
			'before_content' => __( 'Shown before the post content.', 'md' ),
			'after_content' => __( 'Shown after the post content.', 'md' )
		) ) );

		echo '</div>';
	}

	/**
	 * Attach Single Sections inside the entry: below the title, and after
	 * the content and footer byline, before the author box and comments.
	 * With Remove Post Content there's no entry, so they take its place.
	 *
	 * @since 6.0.4
	 */

	public function template() {
		if ( md_has_post_content() ) {
			add_action( 'md_hook_before_the_content', array( $this, 'before_content' ) );
			add_action( 'md_hook_content_item', array( $this, 'after_content' ), 10 );
		}
		else
			add_action( 'md_hook_content', array( $this, 'without_content' ), 20 );

		add_action( 'md_hook_before_sidebar', array( $this, 'before_sidebar' ) );
		add_action( 'md_hook_after_sidebar', array( $this, 'after_sidebar' ) );
	}

	/**
	 * Render both content areas where the entry would be.
	 *
	 * @since 6.0.4
	 */

	public function without_content() {
		$this->before_content();
		$this->after_content();
	}

	/**
	 * Render the Before Content area.
	 *
	 * @since 6.0.4
	 */

	public function before_content() {
		$this->html( 'before_content', array( 'loop' => array( 'post_type' => get_post_type() ) ) );
	}

	/**
	 * Render the After Content area.
	 *
	 * @since 6.0.4
	 */

	public function after_content() {
		$this->html( 'after_content', array( 'loop' => array( 'post_type' => get_post_type() ) ) );
	}

	/**
	 * Confirm the entry is the single post being viewed, not a post in a
	 * Query Loop on its page.
	 *
	 * @since 6.0.4
	 */

	protected function in_context( $args ) {
		return md_is_queried_post();
	}
}

new md_single_sections;
