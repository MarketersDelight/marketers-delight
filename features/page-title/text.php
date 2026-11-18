<?php
/**
 * Register the post editor's Text tab: the title's text fields, gathered
 * from the features and Drop-ins that own them.
 *
 * @since 6.1
 */

class md_page_text extends md_api {

	/**
	 * Register the Text tab, first in the Featured tab, with the page's own
	 * Page Title and Title size.
	 *
	 * @since 6.1
	 */

	public function register() {
		$this->name = __( 'Text', 'md' );

		return array(
			'meta_box' => array(
				'name' => $this->name,
				'edit_post' => true,
				'child_of' => 'hero',
				'fields' => array(
					'title' => array( 'type' => 'text' ),
					'title_size' => array( 'type' => 'select', 'options' => array( 'huge' ) )
				)
			)
		);
	}

	/**
	 * Render the Text tab. Its other fields save with the features that print
	 * them, like the Page Cover's Description.
	 *
	 * @since 6.1
	 */

	public function admin_fields() {
		echo "<div class=\"md-$this->_clean_id md-tab-content\">";

		$this->fields->title_field( array(
			'title' => 'title',
			'size' => 'title_size',
			'description' => __( 'The main <code>h1</code> title on this page. Leave blank to use the post title.', 'md' ),
			'placeholder' => wp_strip_all_tags( get_the_title() )
		) );

		do_action( 'md_post_meta_page_text' );

		echo '</div>';
	}

}

new md_page_text;
