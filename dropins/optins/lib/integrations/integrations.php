<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Hold all of MD Optins integrations AJAX calls.
 *
 * @since 1.0
 * @since MD5.3
 */

class md_optins_integrations {

	/**
	 * Format MailChimp data.
	 *
	 * @since 4.1
	 */

	public function mailchimp( $api_key, $option ) {
		require_once( 'mailchimp.php' );
		$service = 'mailchimp';
		$api = new MD_MailChimp( $api_key );
		$get_lists = $api->get( 'lists' );

		if ( ! is_array( $get_lists['lists'] ) )
			$this->error( $service );

		$option['integrations']['api_keys']['mailchimp']['key'] = esc_attr( $api_key );
		$option['integrations']['enabled'][$service] = true;

		if ( isset( $option['integrations']['services']['mailchimp'] ) )
			unset( $option['integrations']['services']['mailchimp'] );

		foreach ( $get_lists['lists'] as $list ) {
			$id = esc_attr( $list['id'] );
			$url = $list['subscribe_url_long'];
			$parse = parse_url( $url );
			parse_str( $parse['query'], $form );
			$option['integrations']['services'][$service][$id] = array(
				'id' => $id,
				'uid' => esc_attr( $form['u'] ),
				'name' => esc_html( $list['name'] ),
				'url' => esc_url_raw( '//' . $parse['host'] . $parse['path'] . '/post/' ),
				'subscribers' => intval( trim( $list['stats']['member_count'] ) ),
				'service' => $service
			);
		}

		update_option( 'marketers_delight', $option );
	}

	/**
	 * Pull latest AWeber data to option.
	 *
	 * @since 4.1
	 */

	public function aweber( $api_key, $option ) {
		require_once( 'aweber/aweber_api.php');
		$service = 'aweber';

		try {
			list( $keys['consumer_key'], $keys['consumer_secret'], $keys['access_key'], $keys['access_secret'] ) = AWeberAPI::getDataFromAweberID( $api_key );
		}
		catch( AWeberAPIException $e ) {
			$this->error( $service );
		}

		$aweber = new AWeberAPI( $keys['consumer_key'], $keys['consumer_secret'] );
		$account = $aweber->getAccount( $keys['access_key'], $keys['access_secret'] );

		$option['integrations']['enabled'][$service] = true;

		foreach ( $account->lists->data['entries'] as $list ) {
			$id = esc_attr( $list['id'] );
			$option['integrations']['services'][$service][$id] = array(
				'id' => esc_attr( $id ),
				'uid' => esc_attr( $list['unique_list_id'] ),
				'name' => esc_html( $list['name'] ),
				'subscribers' => intval( trim( $list['total_subscribers'] ) ),
				'service' => $service
			);
		}

		update_option( 'marketers_delight', $option );
	}

	/**
	 * Pull latest ActiveCampaign data to option.
	 *
	 * @since 4.3.1
	 */

	public function activecampaign( $account_url, $api_key, $option ) {
		$service = 'activecampaign';

		foreach ( array( 'form_getforms', 'account_view' ) as $action ) {
			$request = wp_remote_get( "{$account_url}/admin/api.php?api_key={$api_key}&api_action={$action}&api_output=json" );
			if ( is_wp_error( $request ) )
				$this->error( $service );
			$response = wp_remote_retrieve_body( $request );
			$data[$action] = json_decode( $response );
		}

		$forms = $data['form_getforms'];
		$account = $data['account_view'];

		$option['integrations']['api_keys'][$service]['account_url'] = esc_attr( $account_url );
		$option['integrations']['api_keys'][$service]['key'] = esc_attr( $api_key );
		$option['integrations']['enabled'][$service] = true;

		if ( isset( $option['integrations']['services'][$service] ) )
			unset( $option['integrations']['services'][$service] );

		foreach ( $forms as $key => $list ) {
			if ( is_numeric( $key ) ) {
				$id = esc_attr( $list->id );
				$url = 'https://' . $account->account . '/proc.php';
				$option['integrations']['services'][$service][$id] = array(
					'id' => $id,
					'name' => esc_html( $list->name ),
					'url' => esc_url_raw( $url ),
					'subscribers' => intval( trim( $list->subscriptions ) ),
					'service' => $service
				);
			}
		}

		update_option( 'marketers_delight', $option );
	}

	/**
	 * Pull latest ConvertKit data to option.
	 *
	 * @since 4.3.3
	 */

	public function convertkit( $api_key, $option ) {
		$request = wp_remote_get( "https://api.convertkit.com/v3/forms?api_key=$api_key" );
		$service = 'convertkit';

		if ( is_wp_error( $request ) )
			$this->error( $service );

		$body = wp_remote_retrieve_body( $request );
		$api_data = json_decode( $body );

		$option['integrations']['api_keys'][$service]['key'] = esc_attr( $api_key );
		$option['integrations']['enabled'][$service] = true;

		if ( isset( $option['integrations']['services'][$service] ) )
			unset( $option['integrations']['services'][$service] );

		foreach ( $api_data->forms as $form ) {
			$id = esc_attr( $form->id );
			$option['integrations']['services'][$service][$id] = array(
				'id' => $id,
				'name' => esc_html( $form->name ),
				'service' => $service
			);
			if ( ! empty( $form->url ) )
				$option['services'][$service][$id]['url'] = esc_url( $form->url );
		}

		update_option( 'marketers_delight', $option );
	}

	/**
	 * Pull latest MailerLite data to option.
	 *
	 * @since 4.9.5
	 */

	public function mailerlite( $api_key, $option ) {
		require_once( 'mailerlite/forms.php' );
		$service = 'mailerlite';
		$api = new MD_MailerLite_Forms( $api_key );
		$forms = $api->getAllJson();

		if ( empty( $forms ) )
			$this->error( $service );

		$option['integrations']['api_keys'][$service]['key'] = esc_attr( $api_key );
		$option['integrations']['enabled'][$service] = true;

		if ( isset( $option['integrations']['services'][$service] ) )
			unset( $option['integrations']['services'][$service] );

		foreach ( $forms as $form => $fields ) {
			$id = esc_attr( $fields->code );
			$option['integrations']['services'][$service][$id] = array(
				'id' => $id,
				'uid' => esc_attr( $fields->id ),
				'name' => esc_html( $fields->name ),
				'service' => $service,
				'subscribers' => $fields->total
			);
		}

		update_option( 'marketers_delight', $option );
	}

	/**
	 * Pull latest Drip data to option.
	 *
	 * @since 4.6.3
	 */

	public function drip( $api_key, $account_id, $option ) {
		require_once( 'drip.php' );

		$drip = new MD_Drip( $api_key, $account_id );
		$forms = $drip->get_forms( array( 'account_id' => $account_id ) );
		$service = 'drip';

		if ( empty( $forms ) )
			$this->error( $service );

		$option['integrations']['api_keys'][$service]['key'] = esc_attr( $api_key );
		$option['integrations']['api_keys'][$service]['account_id'] = esc_attr( $account_id );
		$option['integrations']['enabled'][$service] = true;

		if ( isset( $option['integrations']['services'][$service] ) )
			unset( $option['integrations']['services'][$service] );

		foreach ( $forms as $form => $fields ) {
			$id = esc_attr( $fields['id'] );
			$option['integrations']['services'][$service][$id] = array(
				'id' => $id,
				'name' => esc_html( $fields['headline'] ),
				'service' => $service
			);
		}

		update_option( 'marketers_delight', $option );
	}

}