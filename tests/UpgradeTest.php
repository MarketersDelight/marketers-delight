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

		if ( isset( $settings['colors']['width'] ) && ! isset( $settings['layout']['width'] ) ) {
			$settings['layout']['width'] = $settings['colors']['width'];
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
		$this->assertSame( 900, $settings['layout']['width'] );
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
		$this->assertSame( array( 'colors' => array() ), get_option( 'marketers_delight' ) );
	}

	public function test_622_is_registered_for_its_release() {
		$this->assertSame( 'upgrade_622', ( new md_upgrade )->steps()['6.2.2'] );
	}

	public function test_622_moves_widths_and_leaves_no_old_keys() {
		md_test_set_option( 'marketers_delight', array( 'colors' => array( 'width' => array( 'content' => 700, 'site' => 1300 ), 'site' => array( 'bg_color' => 'x' ) ) ) );

		( new md_upgrade )->upgrade_622();
		$settings = get_option( 'marketers_delight' );

		$this->assertSame( array( 'content' => 700, 'site' => 1300 ), $settings['layout']['width'] );
		$this->assertSame( array( 'site' => array( 'bg_color' => 'x' ) ), $settings['colors'] );
	}

	public function test_moving_widths_removes_colors_when_left_empty() {
		$moved = ( new md_upgrade )->move_widths( array( 'colors' => array( 'width' => array( 'sidebar' => 300 ) ) ) );

		$this->assertSame( array( 'layout' => array( 'width' => array( 'sidebar' => 300 ) ) ), $moved );
	}

	public function test_moving_widths_never_overwrites_a_layout_width() {
		$moved = ( new md_upgrade )->move_widths( array( 'colors' => array( 'width' => array( 'content' => 700 ) ), 'layout' => array( 'width' => array( 'content' => 640 ) ) ) );

		$this->assertSame( array( 'layout' => array( 'width' => array( 'content' => 640 ) ) ), $moved );
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
