<?php
/**
 * Abilities for managing Drop-ins: what's installed, turning them on and off,
 * installing and updating. Drop-ins are code, so each takes the capability
 * the same action on a plugin does.
 *
 * @since 6.2
 */

class md_dropins_abilities extends md_abilities {

	/**
	 * Describe the Drop-in abilities.
	 *
	 * @since 6.2
	 */

	public function abilities() {
		return array(
			'set-dropin-status' => array(
				'label' => __( 'Set Drop-in status', 'md' ),
				'description' => __( 'Turns an installed Drop-in on or off. Describe site lists them. Its settings, post types and Collections appear on your next call.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'dropin' => array( 'type' => 'string' ),
						'active' => array( 'type' => 'boolean' )
					),
					'required' => array( 'dropin', 'active' ),
					'additionalProperties' => false
				),
				'execute' => 'set_dropin_status',
				'permission' => 'can_manage_dropins'
			),
			'find-dropins' => array(
				'label' => __( 'Find Drop-ins', 'md' ),
				'description' => __( 'Lists installed Drop-ins with their versions and any waiting updates, and Drop-ins this site\'s license can install. From the last update check.', 'md' ),
				'execute' => 'find_dropins',
				'permission' => 'can_read_dropins',
				'readonly' => true
			),
			'update-dropins' => array(
				'label' => __( 'Update Drop-ins', 'md' ),
				'description' => __( 'Installs waiting Drop-in updates. Give slugs, or leave them out to update everything waiting.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'dropins' => array( 'type' => 'array', 'items' => array( 'type' => 'string' ) )
					),
					'additionalProperties' => false,
					'default' => array()
				),
				'execute' => 'update_dropins',
				'permission' => 'can_update_dropins'
			),
			'install-dropin' => array(
				'label' => __( 'Install Drop-in', 'md' ),
				'description' => __( 'Installs a Drop-in this license has already claimed, from Find Drop-ins, without turning it on. Drop-ins not yet claimed are claimed by a person, under Theme > Drop-ins.', 'md' ),
				'input' => array(
					'type' => 'object',
					'properties' => array(
						'dropin' => array( 'type' => 'string' )
					),
					'required' => array( 'dropin' ),
					'additionalProperties' => false
				),
				'execute' => 'install_dropin',
				'permission' => 'can_install_dropins'
			)
		);
	}

	/**
	 * Drop-ins are code, so they take the capability plugins do.
	 *
	 * @since 6.2
	 */

	public function can_manage_dropins() {
		return current_user_can( 'activate_plugins' );
	}

	/**
	 * Seeing what's installed and waiting is for anyone who may change it.
	 *
	 * @since 6.2
	 */

	public function can_read_dropins() {
		return current_user_can( 'activate_plugins' ) || current_user_can( 'update_plugins' );
	}

	/**
	 * Updating Drop-ins takes the capability updating plugins does.
	 *
	 * @since 6.2
	 */

	public function can_update_dropins() {
		return current_user_can( 'update_plugins' );
	}

	/**
	 * Installing Drop-ins takes the capability installing plugins does.
	 *
	 * @since 6.2
	 */

	public function can_install_dropins() {
		return current_user_can( 'install_plugins' );
	}

	/**
	 * Installed Drop-ins, waiting updates and what can be installed.
	 *
	 * @since 6.2
	 */

	public function find_dropins() {
		$updates = array();

		foreach ( md_dropin_updates() as $update )
			$updates[$update['slug']] = $update['new_version'];

		$installed = $available = array();

		foreach ( md_get_dropin_details() as $slug => $details )
			$installed[] = array(
				'slug' => $slug,
				'name' => $details['Name'],
				'version' => $details['Version'],
				'active' => md_has( $slug ),
				'update' => isset( $updates[$slug] ) ? $updates[$slug] : ''
			);

		foreach ( md_available_dropins() as $slug => $dropin )
			$available[] = array(
				'slug' => $slug,
				'name' => isset( $dropin['name'] ) ? $dropin['name'] : $slug,
				'status' => $dropin['status']
			);

		return array( 'installed' => $installed, 'available' => $available );
	}

	/**
	 * Turn a Drop-in on or off.
	 *
	 * @since 6.2
	 */

	public function set_dropin_status( $input = array() ) {
		$slug = $input['dropin'];

		if ( ! md_get_dropin_details( $slug ) )
			return new WP_Error( 'md_abilities_dropin', sprintf( __( '%s isn\'t installed. Describe site lists the installed Drop-ins.', 'md' ), $slug ) );

		$results = $input['active'] ? md_activate_dropins( array( $slug ) ) : md_deactivate_dropins( array( $slug ) );

		if ( isset( $results[$slug] ) && is_wp_error( $results[$slug] ) )
			return $this->result( $results[$slug] );

		return array( 'dropin' => $slug, 'active' => (bool) $input['active'] );
	}

	/**
	 * Install waiting updates. Each is reported, so one failure doesn't hide
	 * the rest.
	 *
	 * @since 6.2
	 */

	public function update_dropins( $input = array() ) {
		$waiting = wp_list_pluck( md_dropin_updates(), 'slug' );
		$slugs = isset( $input['dropins'] ) ? array_intersect( (array) $input['dropins'], $waiting ) : $waiting;

		if ( ! $slugs )
			return array( 'updated' => array(), 'failed' => array() );

		$results = md_upgrade_dropins( array_values( $slugs ) );

		if ( is_wp_error( $results ) )
			return $this->result( $results );

		$report = array( 'updated' => array(), 'failed' => array() );

		foreach ( $results as $slug => $result ) {
			if ( ! $result || is_wp_error( $result ) )
				$report['failed'][$slug] = is_wp_error( $result ) ? $this->plain( $result->get_error_message() ) : __( 'The update failed.', 'md' );
			else
				$report['updated'][] = $slug;
		}

		return $report;
	}

	/**
	 * Install a Drop-in the license has claimed. Claiming one uses up part
	 * of a plan, so that stays a person's choice.
	 *
	 * @since 6.2
	 */

	public function install_dropin( $input = array() ) {
		$slug = $input['dropin'];
		$available = md_available_dropins( array( 'claimed' ) );

		if ( empty( $available[$slug] ) )
			return new WP_Error( 'md_abilities_dropin', sprintf( __( '%s can\'t be installed here. Find Drop-ins lists what can; one not yet claimed is claimed under Theme > Drop-ins.', 'md' ), $slug ) );

		$installed = md_install_available_dropin( $slug );

		if ( is_wp_error( $installed ) )
			return $this->result( $installed );

		return array( 'dropin' => $slug, 'installed' => true, 'active' => md_has( $slug ) );
	}

}

new md_dropins_abilities;
