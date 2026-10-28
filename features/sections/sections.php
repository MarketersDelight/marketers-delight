<?php
/**
 * Group the Sections box: Meta for page titles, and Sections for
 * archives and singles. The class keeps its archive name, which is the
 * group id its tabs register into.
 *
 * @since 6.0
 */

class md_archive extends md_api {

	/**
	 * Register the shared Sections settings group.
	 *
	 * @since 6.0
	 */

	public function register() {
		$this->name = __( 'Sections', 'md' );

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'group' => 'page_settings',
				'position' => 7
			),
			'term' => array(
				'name' => $this->name,
				'position' => 20
			)
		);
	}

	/**
	 * Render the Sections group on post type settings pages.
	 *
	 * @since 6.0
	 */

	public function admin_page() {
		$this->group( 'admin_page' );
	}

	/**
	 * Render the Sections group on taxonomy term screens.
	 *
	 * @since 6.0
	 */

	public function term() {
		$this->group( 'term_meta' );
	}

	/**
	 * Render the shared group wrapper for an admin context.
	 *
	 * @since 6.0
	 */

	private function group( $context ) {
		echo '<div class="md-widget md-toggle md-sep-small">'.
			 '<h3 class="md-widget-title">' . esc_html( $this->name ) . '</h3>'.
			 '<div class="md-widget-item">';

		$this->fields->settings_group( $context );

		echo '</div></div>';
	}
}

new md_archive;
