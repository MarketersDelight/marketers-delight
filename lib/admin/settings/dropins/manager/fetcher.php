<?php
/**
 * This file represents an example of the code that themes would use to register
 * the required plugins.
 *
 * It is expected that theme authors would copy and paste this code into their
 * functions.php file, and amend to suit.
 *
 * @see http://tgmpluginactivation.com/configuration/ for detailed documentation.
 *
 * @package    TGM-Dropin-Activation
 * @subpackage Example
 * @version    2.6.1 for plugin MD_Dropins
 * @author     Thomas Griffin, Gary Jones, Juliette Reinders Folmer
 * @copyright  Copyright (c) 2011, Thomas Griffin
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       https://github.com/TGMPA/TGM-Dropin-Activation
 */

/**
 * Include the TGM_Dropin_Activation class.
 *
 * Depending on your implementation, you may want to change the include call:
 *
 * Parent Theme:
 * require_once get_template_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Child Theme:
 * require_once get_stylesheet_directory() . '/path/to/class-tgm-plugin-activation.php';
 *
 * Dropin:
 * require_once dirname( __FILE__ ) . '/path/to/class-tgm-plugin-activation.php';
 */
require_once dirname( __FILE__ ) . '/class-dropin-activation.php';
add_action( 'tgmpa_register', 'md__register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register five plugins:
 * - one included with the TGMPA library
 * - two from an external source, one from an arbitrary source, one from a GitHub repository
 * - two from the .org repo, where one demonstrates the use of the `is_callable` argument
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
function md__register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$dropins = external_dropin_list();

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 */
	$config = array(
		'id'           => 'md',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'dropin_manager', // Menu slug.
		'parent_slug'  => 'md_settings',            // Parent menu slug.
		'capability'   => 'manage_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.

		
		'strings'      => array(
			'page_title'                      => __( 'Install Required Dropins', 'md' ),
			'menu_title'                      => __( 'Dropins Manager', 'md' ),
			/* translators: %s: plugin name. */
			'installing'                      => __( 'Installing Dropin: %s', 'md' ),
			/* translators: %s: plugin name. */
			'updating'                        => __( 'Updating Dropin: %s', 'md' ),
			'oops'                            => __( 'Something went wrong with the plugin API.', 'md' ),
			'notice_can_install_required'     => _n_noop(
				/* translators: 1: plugin name(s). */
				'This theme requires the following plugin: %1$s.',
				'This theme requires the following plugins: %1$s.',
				'md'
			),
			'notice_can_install_recommended'  => _n_noop(
				/* translators: 1: plugin name(s). */
				'This theme recommends the following plugin: %1$s.',
				'This theme recommends the following plugins: %1$s.',
				'md'
			),
			'notice_ask_to_update'            => _n_noop(
				/* translators: 1: plugin name(s). */
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'md'
			),
			'notice_ask_to_update_maybe'      => _n_noop(
				/* translators: 1: plugin name(s). */
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'md'
			),
			'notice_can_activate_required'    => _n_noop(
				/* translators: 1: plugin name(s). */
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'md'
			),
			'notice_can_activate_recommended' => _n_noop(
				/* translators: 1: plugin name(s). */
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'md'
			),
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'md'
			),
			'update_link' 					  => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'md'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'md'
			),
			'return'                          => __( 'Return to Required Dropins Installer', 'md' ),
			'plugin_activated'                => __( 'Dropin activated successfully.', 'md' ),
			'activated_successfully'          => __( 'The following plugin was activated successfully:', 'md' ),
			/* translators: 1: plugin name. */
			'plugin_already_active'           => __( 'No action taken. Dropin %1$s was already active.', 'md' ),
			/* translators: 1: plugin name. */
			'plugin_needs_higher_version'     => __( 'Dropin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'md' ),
			/* translators: 1: dashboard link. */
			'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'md' ),
			'dismiss'                         => __( 'Dismiss this notice', 'md' ),
			'notice_cannot_install_activate'  => __( 'There are one or more required or recommended plugins to install, update or activate.', 'md' ),
			'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'md' ),

			'nag_type'                        => '', // Determines admin notice type - can only be one of the typical WP notice classes, such as 'updated', 'update-nag', 'notice-warning', 'notice-info' or 'error'. Some of which may not work as expected in older WP versions.
		),
		
	);

	tgmpa( $dropins, $config );
}

function download_dropin_url( $dropin ) {
	if (empty($dropin)) {
		return;
	}

	return $dropin['external_url'];
}

function download_dropin_package( $dropin ) {
	if (empty($dropin)) {
		return;
	}

	// If the function it's not available, require it.
	if ( ! function_exists( 'download_url' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	WP_Filesystem();
	global $wp_filesystem;

	$tmp_file = download_url( download_dropin_url($dropin) );
	
	// Sets file final destination.
	$filepath = MD_INSTALLED_DROPINS . '/' . $dropin['slug'] . '.zip';

	copy( $tmp_file, $filepath );
	@unlink( $tmp_file );

	unzip_file( $filepath, MD_INSTALLED_DROPINS . '/' . $dropin['slug'] );

	// Copies the file to the final destination and deletes temporary file.
	
}

function external_dropin_list() {
	$license = md_setting( array( 'settings', 'license_key' ) );

	$body = wp_remote_retrieve_body( wp_remote_get( 'https://marketersdelight.com/edd-api/v2/?edd_action=get_version&item_id=63289&license=' . $license ) );

	$response = json_decode( $body, true, 4 );
	$response = $response['dropins'];
	$dropins = [];

	foreach( $response as $key => $value) {
		if( $key == 'error' ) {
			return false;
		}

		$slug = sanitize_title_with_dashes( $value['name'] );

		$dropins[ $slug ] = [
			'name'               => $value['name'], // The plugin name.
			'slug'               => $slug, // The plugin slug (typically the folder name).
			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
			'version'            => $value['version'], // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'external_url'       => $value['package'], // If set, overrides default API URL and points to an external URL.
		];
	}
	//var_dump($dropins); die;
	return $dropins;
}

function flatten(array $array) {
    $return = array();
    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
    return $return;
}
