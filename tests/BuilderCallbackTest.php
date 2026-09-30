<?php
/**
 * Builder item callbacks receive the row's saved values.
 *
 * @since 6.0
 */

if ( ! function_exists( 'md_style' ) ) {
	function md_style( $fields ) {
		return '';
	}
}

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $text ) {
		return htmlspecialchars( (string) $text, ENT_QUOTES );
	}
}

class MD_Builder_Callback_Probe {

	public $row;

	public function field( $id, $args ) {}

	public function render( $args ) {
		extract( $args );

		include MD_DIR . 'admin/templates/fields/builder-field.php';
	}

}

class BuilderCallbackTest extends MD_TestCase {

	public function test_admin_callback_receives_saved_builder_row() {
		$probe = new MD_Builder_Callback_Probe;
		$row = array( 'source' => 'bookshelf', 'items' => 3 );
		$fields = array(
			'title' => 'Query Loop',
			'icon' => 'list-view',
			'color' => '',
			'admin_callback' => function( $group, $type, $fields, $saved_row ) use ( $probe ) {
				$probe->row = $saved_row;
			}
		);

		ob_start();

		try {
			$probe->render( array(
				'key' => 'builder',
			'group' => 'author_books',
			'type' => 'query_loop',
			'fields' => $fields,
			'icon' => 'list-view',
			'color' => '',
			'row' => $row
			) );
		}
		finally {
			ob_end_clean();
		}

		$this->assertSame( $row, $probe->row );
	}

}
