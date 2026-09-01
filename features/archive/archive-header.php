<?php

/**
 * Group archive title details and navigation controls.
 *
 * @since 6.0
 */

class md_archive_header extends md_api {

	public function register() {
		$this->name = __( 'Archive Header', 'md' );

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

	public function admin_page() {
		$this->group( 'admin_page' );
	}

	public function term() {
		$this->group( 'term_meta' );
	}

	private function group( $context ) {
		echo '<div class="md-widget md-toggle md-sep-small">'.
			 '<h3 class="md-widget-title">' . esc_html( $this->name ) . '</h3>'.
			 '<div class="md-widget-item">';

		$this->fields->settings_group( $context );

		echo '</div></div>';
	}
}

new md_archive_header;
