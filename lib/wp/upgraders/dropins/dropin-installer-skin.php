<?php
/**
 * The Skin that goes along with the Drop-in Installer system.
 * Meant to fill out details like after install actions, and
 * other elements of Drop-in installation.
 *
 * @since 5.4
 */

class Dropin_Installer_Skin extends WP_Upgrader_Skin {

	public $api;
	public $type;
	public $url;
	public $overwrite;
	private $is_downgrading = false;

	/**
	 * Set class defaults and properties.
	 *
	 * @since 5.4
	 */

	public function __construct( $args = array() ) {
		$defaults = array(
			'type' => 'web',
			'url' => '',
			'dropin' => '',
			'nonce' => '',
			'title' => '',
			'overwrite' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		$this->type = $args['type'];
		$this->url = $args['url'];
		$this->api = isset( $args['api'] ) ? $args['api'] : array();
		$this->overwrite = $args['overwrite'];
		parent::__construct( $args );
	}

	/**
	 * Action to perform before installing a dropin.
	 *
	 * @since 5.4
	 */

	public function before() {
		if ( ! empty( $this->api ) )
			$this->upgrader->strings['process_success'] = sprintf( $this->upgrader->strings['process_success_specific'], $this->api->name, $this->api->version );
	}

	/**
	 * Hides the `process_failed` error when updating a dropin by uploading a zip file.
	 *
	 * @since 5.4
	 */

	public function hide_process_failed( $wp_error ) {
		if ( 'upload' === $this->type && '' === $this->overwrite && $wp_error->get_error_code() === 'folder_exists' )
			return true;

		return false;
	}

	/**
	 * Action to perform following a dropin install.
	 *
	 * @since 5,4
	 */

	public function after() {
		if ( $this->do_overwrite() )
			return;

		$dropin_file = $this->upgrader->dropin_info();

		$install_actions = array();

		$from = isset( $_GET['from'] ) ? wp_unslash( $_GET['from'] ) : 'dropins';

		if ( 'import' === $from ) {
			$install_actions['activate_dropin'] = sprintf(
				'<a class="button button-primary" href="%s" target="_parent">%s</a>',
				wp_nonce_url( 'plugins.php?action=activate&amp;from=import&amp;dropin=' . urlencode( $dropin_file ), 'activate-dropin_' . $dropin_file ),
				__( 'Activate Drop-in &amp; Run Importer' )
			);
		} elseif ( 'press-this' === $from ) {
			$install_actions['activate_dropin'] = sprintf(
				'<a class="button button-primary" href="%s" target="_parent">%s</a>',
				wp_nonce_url( 'plugins.php?action=activate&amp;from=press-this&amp;dropin=' . urlencode( $dropin_file ), 'activate-dropin_' . $dropin_file ),
				__( 'Activate Drop-in &amp; Go to Press This' )
			);
		} else {
			$install_actions['activate_dropin'] = sprintf(
				'<a class="button button-primary" href="%s" target="_parent">%s</a>',
				wp_nonce_url( 'plugins.php?action=activate&amp;dropin=' . urlencode( $dropin_file ), 'activate-dropin_' . $dropin_file ),
				__( 'Activate Drop-in' )
			);
		}

		if ( is_multisite() && current_user_can( 'manage_network_plugins' ) ) {
			$install_actions['network_activate'] = sprintf(
				'<a class="button button-primary" href="%s" target="_parent">%s</a>',
				wp_nonce_url( 'plugins.php?action=activate&amp;networkwide=1&amp;dropin=' . urlencode( $dropin_file ), 'activate-dropin_' . $dropin_file ),
				__( 'Network Activate' )
			);
			unset( $install_actions['activate_dropin'] );
		}

		if ( 'import' === $from ) {
			$install_actions['importers_page'] = sprintf(
				'<a href="%s" target="_parent">%s</a>',
				admin_url( 'import.php' ),
				__( 'Go to Importers' )
			);
		} elseif ( 'web' === $this->type ) {
			$install_actions['dropins_page'] = sprintf(
				'<a href="%s" target="_parent">%s</a>',
				self_admin_url( 'admin.php?page=md_dropins' ),
				__( '&larr; Go to Drop-ins Manager' )
			);
		} elseif ( 'upload' === $this->type && 'dropins' === $from ) {
			$install_actions['dropins_page'] = sprintf(
				'<a href="%s">%s</a>',
				self_admin_url( 'admin.php?page=md_dropins' ),
				__( '&larr; Go to Drop-ins Manager' )
			);
		} else {
			$install_actions['dropins_page'] = sprintf(
				'<a href="%s" target="_parent">%s</a>',
				self_admin_url( 'admin.php?page=md_dropins' ),
				__( '&larr; Go to Drop-ins Manager' )
			);
		}

		if ( ! $this->result || is_wp_error( $this->result ) )
			unset( $install_actions['activate_dropin'], $install_actions['network_activate'] );
		elseif ( ! current_user_can( 'activate_dropin', $dropin_file ) || md_is_dropin_active( $dropin_file ) )
			unset( $install_actions['activate_dropin'] );

		$install_actions = apply_filters( 'install_dropin_complete_actions', $install_actions, $this->api, $dropin_file );

		if ( ! empty( $install_actions ) )
			$this->feedback( implode( ' ', (array) $install_actions ) );
	}

	/**
	 * Check if the dropin can be overwritten and output the HTML for overwriting a dropin on upload.
	 *
	 * @since 5.4
	 */

	private function do_overwrite() {
		if ( 'upload' !== $this->type || ! is_wp_error( $this->result ) || 'folder_exists' !== $this->result->get_error_code() )
			return false;

		$folder = $this->result->get_error_data( 'folder_exists' );
		$folder = ltrim( substr( $folder, strlen( MD_INSTALLED_DROPINS ) ), '/' );

		$current_dropin_data = false;
		$all_dropins = md_get_dropins( 'files' );

		foreach ( $all_dropins as $dropin => $dropin_data ) {
			if ( strrpos( $dropin, $folder ) !== 0 )
				continue;
			$current_dropin_data = $dropin_data;
		}

		$new_dropin_data = $this->upgrader->new_dropin_data;

		if ( ! $current_dropin_data || ! $new_dropin_data )
			return false;

		echo '<h2 class="update-from-upload-heading">' . esc_html__( 'This drop-in is already installed.' ) . '</h2>';

		$this->is_downgrading = version_compare( $current_dropin_data['Version'], $new_dropin_data['Version'], '>' );

		$rows = array(
			'Name' => __( 'Drop-in name' ),
			'Version' => __( 'Version' ),
			'Author' => __( 'Author' ),
			'RequiresWP' => __( 'Required WordPress version' ),
			'RequiresPHP' => __( 'Required PHP version' )
		);

		$table  = '<table class="update-from-upload-comparison"><tbody>';
		$table .= '<tr><th></th><th>' . esc_html_x( 'Current', 'dropin' ) . '</th>';
		$table .= '<th>' . esc_html_x( 'Uploaded', 'dropin' ) . '</th></tr>';

		$is_same_dropin = true;

		foreach ( $rows as $field => $label ) {
			$old_value = ! empty( $current_dropin_data[ $field ] ) ? (string) $current_dropin_data[ $field ] : '-';
			$new_value = ! empty( $new_dropin_data[ $field ] ) ? (string) $new_dropin_data[ $field ] : '-';
			$is_same_dropin = $is_same_dropin && ( $old_value === $new_value );
			$diff_field   = ( 'Version' !== $field && $new_value !== $old_value );
			$diff_version = ( 'Version' === $field && $this->is_downgrading );
			$table .= '<tr><td class="name-label">' . $label . '</td><td>' . wp_strip_all_tags( $old_value ) . '</td>';
			$table .= ( $diff_field || $diff_version ) ? '<td class="warning">' : '<td>';
			$table .= wp_strip_all_tags( $new_value ) . '</td></tr>';
		}

		$table .= '</tbody></table>';

		echo apply_filters( 'install_dropin_overwrite_comparison', $table, $current_dropin_data, $new_dropin_data );

		$install_actions = array();
		$can_update = true;

		$blocked_message = '<p>' . esc_html__( 'The dropin cannot be updated due to the following:' ) . '</p>';
		$blocked_message .= '<ul class="ul-disc">';

		$requires_php = isset( $new_dropin_data['RequiresPHP'] ) ? $new_dropin_data['RequiresPHP'] : null;
		$requires_wp  = isset( $new_dropin_data['RequiresWP'] ) ? $new_dropin_data['RequiresWP'] : null;

		if ( ! is_php_version_compatible( $requires_php ) ) {
			$error = sprintf( __( 'The PHP version on your server is %1$s, however the uploaded dropin requires %2$s.' ), phpversion(), $requires_php );
			$blocked_message .= '<li>' . esc_html( $error ) . '</li>';
			$can_update       = false;
		}

		if ( ! is_wp_version_compatible( $requires_wp ) ) {
			$error = sprintf( __( 'Your WordPress version is %1$s, however the uploaded dropin requires %2$s.' ), get_bloginfo( 'version' ), $requires_wp );
			$blocked_message .= '<li>' . esc_html( $error ) . '</li>';
			$can_update       = false;
		}

		$blocked_message .= '</ul>';

		if ( $can_update ) {
			if ( $this->is_downgrading ) {
				$warning = sprintf( __( 'You are uploading an older version of a current dropin. You can continue to install the older version, but be sure to <a href="%s">back up your database and files</a> first.' ), __( 'https://wordpress.org/support/article/wordpress-backups/' ) );
			} else {
				$warning = sprintf( __( 'You are updating a dropin. Be sure to <a href="%s">back up your database and files</a> first.' ), __( 'https://wordpress.org/support/article/wordpress-backups/' ) );
			}

			echo '<p class="update-from-upload-notice">' . $warning . '</p>';

			$overwrite = $this->is_downgrading ? 'downgrade-dropin' : 'update-dropin';

			$install_actions['overwrite_dropin'] = sprintf(
				'<a class="button button-primary update-from-upload-overwrite" href="%s" target="_parent">%s</a>',
				wp_nonce_url( add_query_arg( 'overwrite', $overwrite, $this->url ), 'dropin-upload' ),
				_x( 'Replace current with uploaded', 'dropin' )
			);
		} else
			echo $blocked_message;

		$cancel_url = add_query_arg( 'action', 'upload-dropin-cancel-overwrite', $this->url );

		$install_actions['dropins_page'] = sprintf( '<a class="button" href="%s">%s</a>', wp_nonce_url( $cancel_url, 'dropin-upload-cancel-overwrite' ), __( 'Cancel and go back' ) );

		$install_actions = apply_filters( 'install_dropin_overwrite_actions', $install_actions, $this->api, $new_dropin_data );

		if ( ! empty( $install_actions ) ) {
			printf( '<p class="update-from-upload-expired hidden">%s</p>', __( 'The uploaded file has expired. Please go back and upload it again.' ) );
			echo '<p class="update-from-upload-actions">' . implode( ' ', (array) $install_actions ) . '</p>';
		}

		return true;
	}
}