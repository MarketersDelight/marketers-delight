<?php

// Set upgrade URL
define( 'MD_UPGRADE_URL', MD_URL . 'lib/admin/settings/upgrade/' );

/**
 * A scaleable upgrade system to make sweeping updates to all
 * options including settings, post meta, and term meta.
 *
 * Used for larger MD updates and smooth update seqeuences.
 *
 * @since 4.7
 */

class md_upgrade {

	/**
	 * Common properties used throughout class.
	 */

	public $add_page;
	public $slug;
	public $utilities;
	public $processes;

	/**
	 * Setup the upgrader class.
	 *
	 * @since 4.7
	 */

	public function __construct() {
		$this->slug = 'md-upgrade';
		$this->processes = array( 'setup', 'update_option', 'post_meta', 'term_meta', 'clean' );
		$this->includes();
		add_action( 'admin_notices', array( $this, 'admin_notice' ), 1 );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'wp_ajax_run', array( $this, 'run' ) );
		add_action( 'wp_ajax_nopriv_run', array( $this, 'run' ) );
	}

	/**
	 * Include updater files.
	 *
	 * @since 4.7
	 */

	public function includes() {
		include_once( 'utilities.php' );
		$this->utilities = new md_upgrade_utilities;
	}

	/**
	 * Add admin page to menu.
	 *
	 * @since 4.7
	 */

	public function add_menu() {
		$this->add_page = add_submenu_page( 'md_settings', __( 'Marketers Delight Upgrade', 'md' ), __( '<b style="color:yellow">Upgrade MD &rarr;</b>', 'md' ), 'manage_options', $this->slug, array( $this, 'admin_page' ) );
	}

	/**
	 * Load admin scripts to Updater 4.7 admin page.
	 *
	 * @since 4.7
	 */

	public function admin_scripts() {
		$screen = get_current_screen();
		if ( $screen->base == $this->add_page )
			wp_enqueue_script( $this->slug, MD_UPGRADE_URL . 'upgrade.js', array( 'jquery' ) );
	}

	/**
	 * Add admin update notice once 4.7 upgrade confirmed.
	 *
	 * @since 4.7
	 */

	public function admin_notice() {
		global $pagenow;
		if ( $pagenow == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] == $this->slug )
			return;
	?>
		<div id="md_update_47_notice" class="notice notice-error">
			<p><?php echo __( 'Marketers Delight needs to upgrade your database for full compatibility with the newest version.', 'md' ); ?></p>
			<p><a href="<?php echo admin_url( "admin.php?page={$this->slug}" ); ?>" class="button button-primary"><?php echo __( 'Upgrade database &rarr;', 'md' ); ?></a></p>
		</div>
	<?php }

	/**
	 * Build admin updater page.
	 *
	 * @since 4.7
	 */

	public function admin_page() { ?>

		<style type="text/css">
			.md .md-spacer-med { margin-bottom: 20px; }
			.md-box { background-color: #fff; border: 1px solid #ddd; margin-bottom: 15px; padding: 30px; }
			.md-alert { background-color: #fffbcc; padding: 20px; }
			.md-hide { display: none; }
		</style>

		<div class="md wrap md-content-wrap">

			<form id="md47_upgrade" method="post" action="options.php">

				<h1><?php _e( 'Marketers Delight Upgrader', 'md' ); ?></h1>

				<div class="md-alert md-sep-small"><?php echo __( 'Your database is currently being upgraded to the latest version of Marketers Delight. Please do not leave this page until the upgrade process is complete.', 'md' ); ?></div>

				<hr class="md-spacer-med" />

				<div id="md47_updates">

					<?php foreach ( $this->processes as $process ) :
						$label = str_replace( '_', ' ', $process );
					?>

						<div id="md47_<?php echo $process; ?>" class="md-box md-hide">

							<div class="md47-before">
								<span class="spinner is-active"></span>
								Running <b><?php echo esc_html( $label ); ?></b> process...
							</div>

							<div class="md47-success md-hide">
								<b style="text-transform: capitalize"><?php echo esc_html( $label ); ?></b> complete.
								<span class="dashicons dashicons-yes" style="float: right; color: green; font-size: 27px;"></span>
								<?php if ( $process == 'clean' ) : ?>
									<br /><b><?php echo sprintf( 'Your site has successfully upgraded to Marketers Delight 5.0! <a href="%s">Click here to see what\'s new &rarr;</a>', admin_url( '/admin.php?page=md_settings' ) ); ?>
								<?php endif; ?>
							</div>

						</div>

					<?php endforeach; ?>

				</div>

			</form>

		</div>

	<?php }

	/**
	 * This function is hooked up to the AJAX updater script
	 * and fires with different POST data for different processes.
	 * Runs until update process is complete.
	 *
	 * @since 4.7
	 */

	public function run() {
		$process = $_POST['process'];
		$item = $_POST['item'];

		if ( $process == 'start' ) {
			$next = 'setup';
			$item = 'done';
		}

		if ( $process == 'setup' ) {
			$this->utilities->setup();
			$next = 'update_option';
			$item = 'done';
		}

		if ( $process == 'update_option' ) {
			$this->utilities->update_option();
			$next = 'post_meta';
			$item = 'done';
		}

		if ( $process == 'post_meta' ) {
			$meta = $this->utilities->post_meta( $item );
			$next = $meta['process'];
			$item = $meta['item'];
		}

		if ( $process == 'term_meta' ) {
			$this->utilities->term_meta();
			$next = 'clean';
			$item = 'done';
		}

		if ( $process == 'clean' ) {
			$this->utilities->clean();
			$next = 'complete';
			$item = 'done';
		}

		echo json_encode( array(
			'process' => $next,
			'item' => $item
		) );

		die();
	}

}

new md_upgrade;