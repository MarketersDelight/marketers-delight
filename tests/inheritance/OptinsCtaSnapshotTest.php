<?php
/**
 * Snapshot tests for the Optins drop-in's md_optins_cta(): the full CTA box
 * markup across positions, image positions, colors, backgrounds and CTA
 * types. Guards the move of CTA boxes onto md_render_title() (Phase 5 of
 * MD/deploy/title-sections/PLAN.md).
 *
 * A missing snapshot is recorded from the current output and the test is
 * marked incomplete. Delete a snapshot file to record it again. Skipped when
 * the Optins drop-in isn't installed beside the theme.
 *
 * @since 6.0.4
 */

if ( ! function_exists( 'add_shortcode' ) ) {
	function add_shortcode() {
		return true;
	}
}

if ( ! function_exists( 'wp_get_attachment_url' ) ) {
	function wp_get_attachment_url( $id ) {
		return "https://example.test/image-$id.jpg";
	}
}

if ( ! function_exists( 'md_text_field' ) ) {
	function md_text_field( $string ) {
		return wp_kses_post( $string );
	}
}

if ( ! function_exists( 'md_get_link' ) ) {
	function md_get_link( $fields ) {
		$classes = ! empty( $fields['classes'] ) ? implode( ' ', $fields['classes'] ) : '';

		return '<a class="' . $classes . '" href="' . $fields['url'] . '">' . $fields['text'] . '</a>';
	}
}

if ( ! function_exists( 'sanitize_html_class' ) ) {
	function sanitize_html_class( $class ) {
		return preg_replace( '/[^A-Za-z0-9_-]/', '', (string) $class );
	}
}

class OptinsCtaSnapshotTest extends MD_InheritanceTestCase {

	private $dir;

	protected function setUp(): void {
		parent::setUp();

		$optins = dirname( __DIR__, 4 ) . '/md-dropins/optins/functions.php';

		if ( ! file_exists( $optins ) )
			$this->markTestSkipped( 'Optins drop-in not installed' );

		require_once $optins;

		$this->dir = __DIR__ . '/fixtures/optins-cta-snapshots';

		md_test_use_real_templates();
		md_test_set_query( array( 'is_singular' => true, 'post_type' => 'post', 'queried_object_id' => 1 ) );
		md_test_set_option( 'marketers_delight', array() );
		md_test_set_filter( 'md_color_palette', array(
			'primary' => array( 'hex' => '#1e5bd8', 'name' => 'Primary' ),
			'ink' => array( 'hex' => '#14141a', 'name' => 'Ink' ),
			'cream' => array( 'hex' => '#f6f0e1', 'name' => 'Cream' )
		) );
		md_test_set_filter( 'md_cta_email', '<form class="email-form"></form>' );
	}

	/**
	 * @dataProvider boxes
	 */

	public function test_cta_box_markup_is_unchanged( $name, $fields ) {
		ob_start();

		try {
			md_optins_cta( 'x1', $fields );
		}
		finally {
			$html = ob_get_clean();
		}

		$this->assertSnapshot( $name, $html );
	}

	public static function boxes() {
		$text = array(
			'title' => 'Get the <em>weekly</em> letter',
			'text' => 'One email every Sunday.',
			'cta_type' => 'custom',
			'custom_html' => '<a class="button" href="#join">Join</a>'
		);
		$image = array( 'image' => array( 'id' => 12 ), 'image_width' => '240' );
		$cases = array(
			array( 'wide-defaults', $text ),
			array( 'inline-lede-email-foot', array(
				'position' => 'content',
				'lede' => 'Newsletter',
				'title' => 'Join 4,000 readers',
				'text' => 'No spam.',
				'cta_type' => 'email',
				'email_form_footer' => 'Unsubscribe anytime.'
			) ),
			array( 'inline-email-no-title', array( 'position' => 'content', 'cta_type' => 'email' ) ),
			array( 'inline-links', array(
				'position' => 'after_first_p',
				'title' => 'Read next',
				'cta_type' => 'links',
				'links' => array(
					array( 'text' => 'Start here', 'url' => '#start', 'style' => 'button' ),
					array( 'text' => 'Archive', 'url' => '#archive' )
				)
			) ),
			array( 'content-item-classes', $text + array( 'position' => 'content_item', 'classes' => 'box block-single' ) ),
			array( 'bg-color-hex-dark', $text + array( 'bg_color' => '#2a1938' ) ),
			array( 'bg-color-palette-light', $text + array( 'bg_color' => 'cream' ) ),
			array( 'bg-image-overlay', $text + array(
				'bg_image' => array( 'id' => 30 ),
				'bg_style' => 'cover',
				'overlay' => 'primary',
				'overlay_opacity' => '0.6'
			) ),
			array( 'bg-image-no-overlay', $text + array( 'position' => 'content', 'bg_image' => array( 'id' => 30 ) ) ),
			array( 'colors-close', $text + array(
				'text_color' => 'ink',
				'title_color' => '#c0392b',
				'close_color' => 'primary',
				'display' => array( 'close' => 1 ),
				'cookie' => 14
			) ),
			array( 'colors-close-hex', $text + array(
				'position' => 'content',
				'text_color' => '#333333',
				'title_color' => 'primary',
				'close_color' => '#ae2525',
				'display' => array( 'close' => 1 )
			) ),
			array( 'title-image-no-title', array( 'position' => 'content', 'image' => array( 'id' => 12 ), 'image_position' => 'title_left', 'cta_type' => 'email' ) )
		);

		foreach ( array( 'left', 'right', 'title_left', 'title_right', 'title_center', 'center', 'above_headline', 'below_headline' ) as $position ) {
			$slug = str_replace( '_', '-', $position );
			$cases[] = array( "wide-image-$slug", $text + $image + array( 'image_position' => $position, 'lede' => 'Lede' ) );
		}

		foreach ( array( 'left', 'title_right' ) as $position ) {
			$slug = str_replace( '_', '-', $position );
			$cases[] = array( "inline-image-$slug", $text + $image + array( 'position' => 'content', 'image_position' => $position, 'email_form_footer' => 'Foot' ) );
		}

		$named = array();

		foreach ( $cases as $case )
			$named[$case[0]] = $case;

		return $named;
	}

	private function assertSnapshot( $name, $html ) {
		$file = "{$this->dir}/$name.html";

		if ( ! file_exists( $file ) ) {
			if ( ! is_dir( $this->dir ) )
				mkdir( $this->dir, 0755, true );

			file_put_contents( $file, $html );

			$this->markTestIncomplete( "Recorded snapshot $name.html" );
		}

		$this->assertSame( file_get_contents( $file ), $html, "CTA box markup changed: $name" );
	}

}
