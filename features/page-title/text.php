<?php
/**
 * Register the post editor's Text tab: the title's text fields, gathered
 * from the features and Drop-ins that own them.
 *
 * @since 6.0.4
 */

class md_page_text extends md_api {

	/**
	 * Register the Text tab, first in the Featured tab.
	 *
	 * @since 6.0.4
	 */

	public function register() {
		$this->name = __( 'Text', 'md' );

		return array(
			'meta_box' => array(
				'name' => $this->name,
				'edit_post' => true,
				'child_of' => 'hero'
			)
		);
	}

	/**
	 * Render the Text tab. Its fields save with the features that print
	 * them, like the Page Cover's Description.
	 *
	 * @since 6.0.4
	 */

	public function admin_fields() {
		echo "<div class=\"md-$this->_clean_id md-tab-content\">";

		do_action( 'md_post_meta_page_text' );

		echo '</div>';
	}

}

new md_page_text;
