<?php
/**
 * Create Intehrations admin settings and run all connection processes.
 *
 * @since 4.9
 */

class md_integrations extends md_api
{

	/**
	 * Run actions and filters.
	 *
	 * @since 5.0
	 */

	public function actions()
	{
		add_action("wp_ajax_{$this->_id}", array($this, 'connect'));
		add_action("wp_ajax_nopriv_{$this->_id}", array($this, 'connect'));
	}

	/**
	 * Create admin page.
	 *
	 * @since 5.0
	 */

	public function register()
	{
		return array(
				'admin_page' => array(
						'name' => __('Integrations', 'md'),
						'admin_header' => true
				)
		);
	}

	/**
	 * Admin page template.
	 *
	 * @since 4.9
	 */

	public function admin_page()
	{
		include('templates/integrations-settings.php');
	}

	/**
	 * Initiate Integrations script.
	 *
	 * @since 4.9
	 */

	public function admin_scripts()
	{ ?>
		<script>MD.integrations();</script>
	<?php }

	/**
	 * Run the AJAX action to connect or disconnect to integrations.
	 *
	 * @since 4.1
	 */

	public function connect()
	{
		parse_str(stripslashes($_POST['form']), $form);

		if (!wp_verify_nonce($form['_wpnonce'], $form['option_page'] . '-options'))
			die (__('Sorry, there was an error during the connection process. Please try again.', 'md'));

		$option = md_setting();
		$api_keys = $form['marketers_delight']['integrations']['api_keys'];
		$integration = esc_attr($_POST['integration']);
		$action = esc_attr($_POST['action_type']);
		$api_url = isset($api_keys[$integration]['account_url']) ? $api_keys[$integration]['account_url'] : null;

		if ($action == 'connect' || $action == 'refresh') {
			if ($integration == 'mailchimp')
				$this->mailchimp($api_keys['mailchimp']['key'], $option);
			elseif ($integration == 'aweber')
				$this->aweber($api_keys['aweber']['key'], $option);
			elseif ($integration == 'activecampaign')
				$this->activecampaign($api_keys['activecampaign']['account_url'], $api_keys['activecampaign']['key'], $option);
			elseif ($integration == 'convertkit')
				$this->convertkit($api_keys['convertkit']['key'], $option);
			elseif ($integration == 'mailerlite')
				$this->mailerlite($api_keys['mailerlite']['key'], $option);
			elseif ($integration == 'drip')
				$this->drip($api_keys['drip']['key'], $api_keys['drip']['account_id'], $option);
			elseif (in_array($integration, array('typekit', 'google_analytics', 'xenforo')))
				$this->save_api_key($integration, $option, $api_keys[$integration]['key'], $api_url);
		} elseif ($action == 'disconnect')
			$this->disconnect($integration, $option);

		$this->admin_template(array('service' => $integration));

		die();
	}

	/**
	 * Format MailChimp data.
	 *
	 * @since 4.1
	 */

	public function mailchimp($api_key, $option)
	{
		require_once('services/mailchimp.php');
		$service = 'mailchimp';
		$api = new MD_MailChimp($api_key);
		$get_lists = $api->get('lists');

		if (!is_array($get_lists['lists']))
			$this->error($service);

		$option['integrations']['api_keys']['mailchimp']['key'] = esc_attr($api_key);
		$option['integrations']['enabled'][$service] = true;

		if (isset($option['integrations']['services']['mailchimp']))
			unset($option['integrations']['services']['mailchimp']);

		foreach ($get_lists['lists'] as $list) {
			$id = esc_attr($list['id']);
			$url = $list['subscribe_url_long'];
			$parse = parse_url($url);
			parse_str($parse['query'], $form);
			$option['integrations']['services'][$service][$id] = array(
					'id' => $id,
					'uid' => esc_attr($form['u']),
					'name' => esc_html($list['name']),
					'url' => esc_url_raw('//' . $parse['host'] . $parse['path'] . '/post/'),
					'subscribers' => intval(trim($list['stats']['member_count'])),
					'service' => $service
			);
		}

		update_option('marketers_delight', $option);
	}

	/**
	 * In case of error, show this message.
	 *
	 * @since 4.1
	 */

	public function error($service)
	{
		$this->admin_template(array('service' => $service, 'error' => true));
		die();
	}

	/**
	 * Return itemplate as individial integration boxes.
	 *
	 * @since 4.9
	 */

	public function admin_template($args = null)
	{
		$integrations = $this->data($args);
		$option = md_setting(array('integrations'));
		$error = isset($args['error']) ? true : '';
		include('templates/integrations-fields.php');
	}

	/**
	 * Gather array of integrations to include in interface.
	 *
	 * @since 4.9
	 */

	public function data($args = null)
	{
		$integrations = array_merge(array(
				'mailchimp' => array(
						'name' => __('MailChimp', 'md'),
						'url' => 'http://admin.mailchimp.com/account/api-key-popup',
						'type' => 'email'
				),
				'aweber' => array(
						'name' => __('AWeber', 'md'),
						'url' => 'https://auth.aweber.com/1.0/oauth/authorize_app/e5957609',
						'type' => 'email',
						'manual_refresh' => true,
						'labels' => array(
								'api_key' => __('Authorization Key', 'md')
						)
				),
				'convertkit' => array(
						'name' => __('ConvertKit', 'md'),
						'url' => 'https://app.convertkit.com/account/edit',
						'type' => 'email'
				),
				'activecampaign' => array(
						'name' => __('ActiveCampaign', 'md'),
						'url' => 'https://marketersdelight.com/connect-email-activecampaign/',
						'type' => 'email',
						'fields' => array('account_url'),
						'labels' => array(
								'account_url' => __('API URL', 'md')
						)
				),
				'mailerlite' => array(
						'name' => __('MailerLite', 'md'),
						'url' => 'https://app.mailerlite.com/integrations/api/',
						'type' => 'email'
				),
				'drip' => array(
						'name' => __('Drip', 'md'),
						'url' => 'https://www.getdrip.com/user/edit',
						'type' => 'email',
						'fields' => array('account_id'),
						'labels' => array(
								'api_key' => __('API Token', 'md')
						)
				),
				'typekit' => array(
						'name' => __('Typekit', 'md'),
						'url' => '#',
						'type' => 'site',
						'refresh' => false,
						'labels' => array(
								'api_key' => __('Kit ID', 'md')
						),
						'message' => sprintf(__('Your Font Kit is now loading on your site! Go to the <a href="%s">Fonts and Typography</a> section in the Site Design panel to assign fonts to your design.', 'md'), admin_url('customize.php?autofocus[section]=md_design[typography]'))
				),
				'google_analytics' => array(
						'name' => __('Google Analytics', 'md'),
						'url' => 'https://support.google.com/analytics/answer/1008080#trackingID',
						'type' => 'site',
						'refresh' => false,
						'labels' => array(
								'api_key' => __('Tracking ID', 'md')
						),
						'message' => __('The Google Analytics tracking code is now loading on your site! For best performance the script has been placed at the bottom of every page.', 'md')
				)
		), apply_filters($this->_id, array()));
		return empty($args) ? $integrations : $this->sort($integrations, $args);
	}

	/**
	 * Return integrations data in various ways.
	 *
	 * @since 4.9
	 */

	public function sort($integrations, $args)
	{
		$show = array();
		// Show services by 'type'
		if (isset($args['key']))
			foreach ($integrations as $id => $fields)
				if ($fields['type'] == $args['key'])
					$show[$id] = $fields;
		// Show single service
		if (isset($args['service']))
			foreach ($integrations as $id => $fields)
				if ($args['service'] == $id)
					$show[$id] = $fields;
		return $show;
	}

	/**
	 * Pull latest AWeber data to option.
	 *
	 * @since 4.1
	 */

	public function aweber($api_key, $option)
	{
		require_once('services/aweber/aweber_api.php');
		$service = 'aweber';

		try {
			list($keys['consumer_key'], $keys['consumer_secret'], $keys['access_key'], $keys['access_secret']) = AWeberAPI::getDataFromAweberID($api_key);
		} catch (AWeberAPIException $e) {
			$this->error($service);
		}

		$aweber = new AWeberAPI($keys['consumer_key'], $keys['consumer_secret']);
		$account = $aweber->getAccount($keys['access_key'], $keys['access_secret']);

		$option['integrations']['enabled'][$service] = true;

		foreach ($account->lists->data['entries'] as $list) {
			$id = esc_attr($list['id']);
			$option['integrations']['services'][$service][$id] = array(
					'id' => esc_attr($id),
					'uid' => esc_attr($list['unique_list_id']),
					'name' => esc_html($list['name']),
					'subscribers' => intval(trim($list['total_subscribers'])),
					'service' => $service
			);
		}

		update_option('marketers_delight', $option);
	}

	/**
	 * Pull latest ActiveCampaign data to option.
	 *
	 * @since 4.3.1
	 */

	public function activecampaign($account_url, $api_key, $option)
	{
		$service = 'activecampaign';

		foreach (array('form_getforms', 'account_view') as $action) {
			$request = wp_remote_get("{$account_url}/admin/api.php?api_key={$api_key}&api_action={$action}&api_output=json");
			if (is_wp_error($request))
				$this->error($service);
			$response = wp_remote_retrieve_body($request);
			$data[$action] = json_decode($response);
		}

		$forms = $data['form_getforms'];
		$account = $data['account_view'];

		$option['integrations']['api_keys'][$service]['account_url'] = esc_attr($account_url);
		$option['integrations']['api_keys'][$service]['key'] = esc_attr($api_key);
		$option['integrations']['enabled'][$service] = true;

		if (isset($option['integrations']['services'][$service]))
			unset($option['integrations']['services'][$service]);

		foreach ($forms as $key => $list) {
			if (is_numeric($key)) {
				$id = esc_attr($list->id);
				$url = 'https://' . $account->account . '/proc.php';
				$option['integrations']['services'][$service][$id] = array(
						'id' => $id,
						'name' => esc_html($list->name),
						'url' => esc_url_raw($url),
						'subscribers' => intval(trim($list->subscriptions)),
						'service' => $service
				);
			}
		}

		update_option('marketers_delight', $option);
	}

	/**
	 * Pull latest ConvertKit data to option.
	 *
	 * @since 4.3.3
	 */

	public function convertkit($api_key, $option)
	{
		$request = wp_remote_get("https://api.convertkit.com/v3/forms?api_key=$api_key");
		$service = 'convertkit';

		if (is_wp_error($request))
			$this->error($service);

		$body = wp_remote_retrieve_body($request);
		$api_data = json_decode($body);

		$option['integrations']['api_keys'][$service]['key'] = esc_attr($api_key);
		$option['integrations']['enabled'][$service] = true;

		if (isset($option['integrations']['services'][$service]))
			unset($option['integrations']['services'][$service]);

		foreach ($api_data->forms as $form) {
			$id = esc_attr($form->id);
			$option['integrations']['services'][$service][$id] = array(
					'id' => $id,
					'name' => esc_html($form->name),
					'service' => $service
			);
			if (!empty($form->url))
				$option['services'][$service][$id]['url'] = esc_url($form->url);
		}

		update_option('marketers_delight', $option);
	}

	/**
	 * Pull latest MailerLite data to option.
	 *
	 * @since 4.9.5
	 */

	public function mailerlite($api_key, $option)
	{
		require_once('services/mailerlite/forms.php');
		$service = 'mailerlite';
		$api = new MD_MailerLite_Forms($api_key);
		$forms = $api->getAllJson();

		if (empty($forms))
			$this->error($service);

		$option['integrations']['api_keys'][$service]['key'] = esc_attr($api_key);
		$option['integrations']['enabled'][$service] = true;

		if (isset($option['integrations']['services'][$service]))
			unset($option['integrations']['services'][$service]);

		foreach ($forms as $form => $fields) {
			$id = esc_attr($fields->code);
			$option['integrations']['services'][$service][$id] = array(
					'id' => $id,
					'uid' => esc_attr($fields->id),
					'name' => esc_html($fields->name),
					'service' => $service,
					'subscribers' => $fields->total
			);
		}

		update_option('marketers_delight', $option);
	}

	/**
	 * Pull latest Drip data to option.
	 *
	 * @since 4.6.3
	 */

	public function drip($api_key, $account_id, $option)
	{
		require_once('services/drip.php');

		$drip = new MD_Drip($api_key, $account_id);
		$forms = $drip->get_forms(array('account_id' => $account_id));
		$service = 'drip';

		if (empty($forms))
			$this->error($service);

		$option['integrations']['api_keys'][$service]['key'] = esc_attr($api_key);
		$option['integrations']['api_keys'][$service]['account_id'] = esc_attr($account_id);
		$option['integrations']['enabled'][$service] = true;

		if (isset($option['integrations']['services'][$service]))
			unset($option['integrations']['services'][$service]);

		foreach ($forms as $form => $fields) {
			$id = esc_attr($fields['id']);
			$option['integrations']['services'][$service][$id] = array(
					'id' => $id,
					'name' => esc_html($fields['headline']),
					'service' => $service
			);
		}

		update_option('marketers_delight', $option);
	}

	/**
	 * Save API Key only.
	 *
	 * @since 4.9
	 */

	public function save_api_key($service, $option, $api_key, $api_url = null)
	{
		if (empty($api_key))
			$this->error($service);

		$option['integrations']['api_keys'][$service]['key'] = esc_attr($api_key);
		if ($api_url)
			$option['integrations']['api_keys'][$service]['url'] = esc_attr($api_url);
		$option['integrations']['enabled'][$service] = true;
		update_option('marketers_delight', $option);
	}

	/**
	 * Disconnect integration from site and delete all data.
	 *
	 * @since 4.1
	 */

	public function disconnect($integration, $option)
	{
		unset($option['integrations']['services'][$integration]);
		unset($option['integrations']['api_keys'][$integration]);
		unset($option['integrations']['enabled'][$integration]);
		update_option('marketers_delight', $option);
	}

}

new md_integrations;
