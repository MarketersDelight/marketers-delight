<?php
/**
 * Start the Popups Engine. This class needs organizational work.
 *
 * @since 4.5
 */

class md_popups extends md_api {

	public $dir = 'dropins/optins';

	/**
	 * Load required files for Popups environment.
	 *
	 * @since 4.5
	 */

	public function includes() {
		require_once( 'popup.php' );
		require_once( 'hotspots.php' );
		require_once( 'customize/customize.php' );
	}

	/**
	 * Run hooks and filters.
	 *
	 * @since 4.5
	 */

	public function actions() {
		add_action( 'wp_footer', array( $this, 'html' ) );
		add_action( 'md_hook_after_footer', array( $this, 'load_template' ) );
	}

	/**
	 * Build MD Popups admin page.
	 *
	 * @since 4.5
	 */

	public function register() {
		$name = __( 'Popups', 'md' );
		$options = md_get_popups( 'ids' );
		return array(
			'admin_page' => array(
				'name' => $name,
				'parent' => 'md_optins',
				'fields' => array(
					'popups' => array(
						'type' => 'group',
						'fields' => array(
							'name' => array( 'type' => 'text' ),
							'locations' => array(
								'type' => 'checkbox',
								'options' => array_keys( md_optins_locations( 'ids' ) )
							),
							'show' => array(
								'type' => 'select',
								'options' => array( 'exit', 'percent' )
							),
							'rules' => array(
								'type' => 'select',
								'options' => array( 'logged_in', 'logged_out' )
							),
							'delay' => array( 'type' => 'number' ),
							'cookie' => array( 'type' => 'number' )
						)
					),
					'main_menu' => array(
						'type' => 'select',
						'options' => $options
					),
					'byline' => array(
						'type' => 'select',
						'options' => $options
					),
					'byline_text' => array( 'type' => 'text' ),
					'header_menu' => array(
						'type' => 'select',
						'options' => $options
					),
					'header_menu_text' => array( 'type' => 'text' ),
					'header_menu_button' => array(
						'type' => 'checkbox',
						'options' => array( 'enable' )
					)
				)
			)
		);
	}

	/**
	 * Create Popups admin page.
	 *
	 * @since 4.5
	 */

	public function admin_page() {
		$options = md_get_popups( 'options' );
		$header_menu = $this->fields->module( 'header_menu' );
		$byline_text = $this->fields->module( 'byline' );
		include( md_template( $this->dir, 'popups/admin/popups-settings', true ) );
	}

	/**
	 * Build out repeatable fields for Popups Manager.
	 *
	 * @since 4.5
	 */

	public function fields( $group, $field ) {
		$show = md_setting( array( 'popups', 'popups', $field, 'show' ) );
		include( md_template( $this->dir, 'popups/admin/popups-fields', true ) );
	}

	/**
	 * Loads popup HTML + scripts if any popups have
	 * been added to the page.
	 *
	 * @since 4.5
	 */

	public function html() {
		if ( has_action( 'md_popups' ) )
			md_template( $this->dir, 'popups/popups' );
	}

	/**
	 * Load Popups template across site.
	 *
	 * @since 5.0
	 */

	public function load_template() {
		$js = array();
		$popups = md_setting( array( 'popups', 'popups' ) );
		$popups = ! empty( $popups ) ? $popups : array();
		$global = md_optins_locations( 'ids' );
		$post_type = get_post_type();
		$popups_remove = md_meta( array( 'optins', 'popups_remove' ), true );
		$remove = ! empty( $popups_remove ) ? array_keys( $popups_remove ) : array();
		$popups_add = md_meta( array( 'optins', 'popups' ), true );
		$add = ! empty( $popups_add ) ? $popups_add : array();

		if ( $remove )
			foreach ( $remove as $id )
				unset( $popups[$id] );

		if ( $add )
			foreach ( $add as $group => $fields )
				if ( isset( $fields['popup'] ) )
					$add[$group]['locations']['current'] = true;

		$popups = array_merge( $popups, $add );

		if ( $popups ) {
			foreach ( $popups as $id => $fields ) {
				$locations = ! empty( $fields['locations'] ) ? array_keys( $fields['locations'] ) : array();
				$rules = ! empty( $fields['rules'] ) ? $fields['rules'] : '';
				$id = isset( $fields['popup'] ) ? $fields['popup'] : $id;
				if (
					( empty( $rules ) || ( $rules == 'logged_in' && is_user_logged_in() ) || ( $rules == 'logged_out' && ! is_user_logged_in() ) ) &&
					(
						! empty( $fields['locations']['sitewide'] ) ||
						! empty( $fields['locations']['current'] ) ||
						( is_front_page() && ! empty( $fields['locations']['front'] ) ) ||
						( is_home() && ! empty( $fields['locations']['home'] ) ) ||
						( is_singular( 'post' ) && ! empty( $fields['locations']['post'] ) ) ||
						( is_category() && ! empty( $fields['locations']['category'] ) ) ||
						( is_page() && ! empty( $fields['locations']['page'] ) ) ||
						( is_author() && ! empty( $fields['locations']['author'] ) ) ||
						( is_search() && ! empty( $fields['locations']['search'] ) ) ||
						(
							( is_post_type_archive( $post_type ) && ! empty( $fields['locations']["{$post_type}_archive"] ) ) ||
							( is_singular( $post_type ) && ! empty( $fields['locations']["{$post_type}_single"] ) ) ||
							( is_tax( get_query_var( 'taxonomy' ) ) && in_array( get_query_var( 'taxonomy' ), $locations ) )
						)
					)
				) {
					if ( empty( $_COOKIE["md_popup_$id"] ) ) {
						md_popup( array( 'id' => $id ) );
						$js["md_popup_$id"]['id'] = "md_popup_$id";
						$js["md_popup_$id"]['show'] = ! empty( $fields['show'] ) ? $fields['show'] : 'seconds';
						$js["md_popup_$id"]['delay'] = ! empty( $fields['delay'] ) ? $fields['delay'] : 5;
						$js["md_popup_$id"]['cookieExp'] = isset( $fields['cookie'] ) ? $fields['cookie'] : '0';
					}
				}
			}
		}

		if ( has_action( 'md_popups' ) )
			wp_add_inline_script( 'marketers-delight', "\tMD.popups.init({" . md_js_object( $js ) . "});" );
	}

}

new md_popups;