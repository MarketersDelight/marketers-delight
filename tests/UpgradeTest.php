<?php
/**
 * Tests the data upgrade runner: version checks, step order, failures, repeat runs and the compile flag.
 *
 * @since 6.2.2
 */

require_once dirname( __DIR__ ) . '/api/upgrade.php';

class MD_Test_Upgrade extends md_upgrade {

	public $ran = array();
	public $fail = '';

	public function steps() {
		return array(
			'5.9' => 'step_old',
			MD_VERSION => 'step_current',
			'99.0' => 'step_future'
		);
	}

	public function step_old() {
		$this->ran[] = '5.9';

		return true;
	}

	public function step_current() {
		$this->ran[] = MD_VERSION;

		if ( $this->fail === MD_VERSION )
			return new WP_Error( 'test', 'Step failed.' );

		$settings = get_option( 'marketers_delight', array() );

		if ( isset( $settings['colors']['width'] ) && ! isset( $settings['settings']['width'] ) ) {
			$settings['settings']['width'] = $settings['colors']['width'];
			update_option( 'marketers_delight', $settings );
		}

		return true;
	}

	public function step_future() {
		$this->ran[] = '99.0';

		return true;
	}
}

class UpgradeTest extends MD_TestCase {

	private function version() {
		$settings = get_option( 'marketers_delight' );

		return isset( $settings['version'] ) ? $settings['version'] : null;
	}

	public function test_current_version_does_nothing() {
		md_test_set_option( 'marketers_delight', array( 'version' => MD_VERSION, 'colors' => array( 'width' => 900 ) ) );
		$before = $GLOBALS['__test_options'];
		$upgrade = new MD_Test_Upgrade;

		$upgrade->maybe_run();

		$this->assertSame( array(), $upgrade->ran );
		$this->assertSame( $before, $GLOBALS['__test_options'] );
	}

	public function test_missing_version_runs_every_step_up_to_the_running_code() {
		md_test_set_option( 'marketers_delight', array( 'colors' => array( 'width' => 900 ) ) );
		$upgrade = new MD_Test_Upgrade;

		$this->assertTrue( $upgrade->maybe_run() );
		$this->assertSame( array( '5.9', MD_VERSION ), $upgrade->ran );
		$this->assertSame( MD_VERSION, $this->version() );
	}

	public function test_only_steps_newer_than_the_saved_version_run() {
		md_test_set_option( 'marketers_delight', array( 'version' => '5.9' ) );
		$upgrade = new MD_Test_Upgrade;

		$upgrade->maybe_run();

		$this->assertSame( array( MD_VERSION ), $upgrade->ran );
	}

	public function test_steps_copy_old_keys_and_keep_them() {
		md_test_set_option( 'marketers_delight', array( 'colors' => array( 'width' => 900 ) ) );

		( new MD_Test_Upgrade )->maybe_run();
		$settings = get_option( 'marketers_delight' );

		$this->assertSame( 900, $settings['colors']['width'] );
		$this->assertSame( 900, $settings['settings']['width'] );
	}

	public function test_any_version_change_flags_a_compile() {
		md_test_set_option( 'marketers_delight', array( 'version' => '99.1' ) );

		( new MD_Test_Upgrade )->maybe_run();

		$this->assertTrue( get_option( 'md_compile_after_upgrade' ) );
		$this->assertSame( MD_VERSION, $this->version() );
	}

	public function test_fresh_install_records_version_and_flags_a_compile() {
		( new MD_Test_Upgrade )->maybe_run();

		$this->assertSame( array( 'version' => MD_VERSION ), get_option( 'marketers_delight' ) );
		$this->assertTrue( get_option( 'md_compile_after_upgrade' ) );
	}

	public function test_failed_step_keeps_the_old_version_and_skips_the_compile() {
		md_test_set_option( 'marketers_delight', array( 'version' => '5.9' ) );
		$upgrade = new MD_Test_Upgrade;
		$upgrade->fail = MD_VERSION;

		$this->assertTrue( is_wp_error( $upgrade->maybe_run() ) );
		$this->assertSame( '5.9', $this->version() );
		$this->assertFalse( get_option( 'md_compile_after_upgrade' ) );

		$upgrade->fail = '';
		$upgrade->ran = array();

		$this->assertTrue( $upgrade->maybe_run() );
		$this->assertSame( array( MD_VERSION ), $upgrade->ran );
	}

	public function test_running_twice_changes_nothing() {
		md_test_set_option( 'marketers_delight', array( 'colors' => array( 'width' => 900 ) ) );
		$upgrade = new MD_Test_Upgrade;
		$upgrade->maybe_run();
		$after = $GLOBALS['__test_options'];

		$upgrade->ran = array();
		$upgrade->maybe_run();

		$this->assertSame( array(), $upgrade->ran );
		$this->assertSame( $after, $GLOBALS['__test_options'] );
	}

	public function test_adds_no_options() {
		md_test_set_option( 'marketers_delight', array( 'version' => '5.6' ) );

		( new MD_Test_Upgrade )->maybe_run();
		delete_option( 'md_compile_after_upgrade' );

		$this->assertSame( array( 'marketers_delight' ), array_keys( $GLOBALS['__test_options'] ) );
	}

	public function test_622_removes_the_old_storage_counter() {
		md_test_set_option( 'marketers_delight_storage_version', '4' );
		md_test_set_option( 'marketers_delight', array( 'colors' => array() ) );

		$this->assertTrue( ( new md_upgrade )->upgrade_622() );
		$this->assertFalse( get_option( 'marketers_delight_storage_version' ) );
		$this->assertSame( array(), get_option( 'marketers_delight' ) );
	}

	public function test_622_is_registered_for_its_release() {
		$this->assertSame( 'upgrade_622', ( new md_upgrade )->steps()['6.2.2'] );
	}

	public function test_622_moves_widths_and_leaves_no_old_keys() {
		md_test_set_option( 'marketers_delight', array( 'colors' => array( 'width' => array( 'content' => 700, 'site' => 1300 ), 'site' => array( 'bg_color' => 'x' ) ) ) );

		( new md_upgrade )->upgrade_622();
		$settings = get_option( 'marketers_delight' );

		$this->assertSame( array( 'content' => 700, 'site' => 1300 ), $settings['settings']['width'] );
		$this->assertSame( array( 'site' => array( 'bg_color' => 'x' ) ), $settings['colors'] );
	}

	public function test_no_style_site_removes_the_box_in_effects() {
		$moved = ( new md_upgrade )->move_box_style( array( 'colors' => array( 'design' => 'plain', 'site' => array( 'bg_color' => 'x' ) ) ) );

		$this->assertSame( array( 'colors' => array( 'site' => array( 'bg_color' => 'x' ) ), 'effects' => array( 'box_style' => array( 'remove' => true ) ) ), $moved );
	}

	public function test_border_site_removes_the_box_and_keeps_its_dividers() {
		$moved = ( new md_upgrade )->move_box_style( array( 'colors' => array( 'design' => 'border' ), 'effects' => array( 'shadow_style' => 'hard' ) ) );

		$this->assertSame( array( 'shadow_style' => 'hard', 'box_style' => array( 'remove' => true ), 'dividers' => array( 'divide' => true ) ), $moved['effects'] );
		$this->assertArrayNotHasKey( 'colors', $moved );
	}

	public function test_border_site_keeps_box_choices_it_already_saved() {
		$moved = ( new md_upgrade )->move_box_style( array( 'colors' => array( 'design' => 'border' ), 'effects' => array( 'box_style' => array( 'remove' => 0 ) ) ) );

		$this->assertSame( array( 'remove' => 0 ), $moved['effects']['box_style'] );
		$this->assertSame( array( 'divide' => true ), $moved['effects']['dividers'] );
	}

	public function test_box_site_only_loses_the_old_select() {
		$moved = ( new md_upgrade )->move_box_style( array( 'colors' => array( 'design' => 'box' ) ) );

		$this->assertSame( array(), $moved );
	}

	public function test_post_type_and_taxonomy_no_style_become_checkboxes() {
		$moved = ( new md_upgrade )->move_box_style( array(
			'post' => array(
				'layout' => array( 'content_style' => 'plain', 'sidebar' => array( 'global' => true ) ),
				'category' => array( 'layout' => array( 'content_style' => 'plain' ) )
			),
			'docs' => array( 'layout' => array( 'content_style' => 'border' ) ),
			'stream' => array( 'layout' => array( 'content_style' => 'box', 'content' => array( 'add_author_box' => true ) ) )
		) );

		$this->assertSame( array( 'sidebar' => array( 'global' => true ), 'content' => array( 'box_style' => true ) ), $moved['post']['layout'] );
		$this->assertSame( array( 'content' => array( 'box_style' => true ) ), $moved['post']['category']['layout'] );
		$this->assertArrayNotHasKey( 'docs', $moved );
		$this->assertSame( array( 'content' => array( 'add_author_box' => true ) ), $moved['stream']['layout'] );
	}

	public function test_box_style_move_keeps_empty_arrays_it_did_not_touch() {
		$settings = array( 'settings' => array( 'sidebars' => array() ), 'post' => array( 'layout' => array( 'content_style' => 'box' ) ) );

		$this->assertSame( array( 'settings' => array( 'sidebars' => array() ) ), ( new md_upgrade )->move_box_style( $settings ) );
	}

	public function test_moving_box_style_twice_changes_nothing() {
		$upgrade = new md_upgrade;
		$once = $upgrade->move_box_style( array( 'colors' => array( 'design' => 'border' ), 'post' => array( 'layout' => array( 'content_style' => 'plain' ) ) ) );

		$this->assertSame( $once, $upgrade->move_box_style( $once ) );
	}

	public function test_moving_widths_removes_colors_when_left_empty() {
		$moved = ( new md_upgrade )->move_widths( array( 'colors' => array( 'width' => array( 'sidebar' => 300 ) ) ) );

		$this->assertSame( array( 'settings' => array( 'width' => array( 'sidebar' => 300 ) ) ), $moved );
	}

	public function test_moving_widths_never_overwrites_a_saved_width() {
		$moved = ( new md_upgrade )->move_widths( array( 'colors' => array( 'width' => array( 'content' => 700 ) ), 'settings' => array( 'width' => array( 'content' => 640 ) ) ) );

		$this->assertSame( array( 'settings' => array( 'width' => array( 'content' => 640 ) ) ), $moved );
	}

	public function test_moving_widths_twice_changes_nothing() {
		$upgrade = new md_upgrade;
		$once = $upgrade->move_widths( array( 'colors' => array( 'width' => array( 'content' => 700 ) ) ) );

		$this->assertSame( $once, $upgrade->move_widths( $once ) );
	}

	public function test_622_leaves_sites_without_widths_alone() {
		$settings = array( 'colors' => array( 'site' => array( 'bg_color' => 'x' ) ) );
		md_test_set_option( 'marketers_delight', $settings );

		( new md_upgrade )->upgrade_622();

		$this->assertSame( $settings, get_option( 'marketers_delight' ) );
	}

	public function test_622_removes_the_md5_site_loop() {
		md_test_set_option( 'marketers_delight', array( 'loop' => array( 'columns' => '2' ), 'post' => array( 'loop' => array( 'columns' => 3 ) ) ) );

		( new md_upgrade )->upgrade_622();

		$this->assertSame( array( 'post' => array( 'loop' => array( 'columns' => 3 ) ) ), get_option( 'marketers_delight' ) );
	}
}
