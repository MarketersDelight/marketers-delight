<?php

/**
 * Organize array of lists for use in options.
 *
 * @since 4.9
 */

function md_email_data($atts = null)
{
	$label = '';
	$ids = array();
	$email = md_setting(array('integrations', 'services'));
	if (empty($email))
		$email = array();
	if (!empty($email)) {
		if (isset($atts['show'])) {
			if (isset($atts['empty_label']))
				$ids[''] = __('Use default email list...', 'md');
			// Return list of IDs
			if ($atts['show'] == 'ids') {
				foreach ($email as $service => $lists)
					foreach ($lists as $list => $fields)
						$ids[] = $list;
				if (isset($atts['custom_html']))
					$ids[] = 'custom_html';
				return $ids;
			} // Return IDs by name
			elseif ($atts['show'] == 'names') {
				foreach ($email as $service => $lists)
					foreach ($lists as $list => $fields) {
						if (isset($atts['label']))
							$label = esc_html(' (' . $service . ')');
						$ids[$list] = $fields['name'] . $label;
					}
				return $ids;
			} // Return service by ID
			elseif ($atts['show'] == 'service') {
				foreach ($email as $service => $lists)
					foreach ($lists as $list => $fields)
						$ids[$list] = $service;
				return $ids;
			}
		}
		if (isset($atts['custom_html']))
			$email['other']['custom_html']['name'] = __('Custom HTML', 'md');
	}
	return $email;
}

/**
 * Compile the proper form action URL for each service.
 *
 * @since 4.9
 */

function md_email_action($service, $list)
{
	$action = '';
	$option = md_setting(array('integrations'));
	$id = !empty($option['services'][$service][$list]['id']) ? $option['services'][$service][$list]['id'] : '';
	$url = !empty($option['services'][$service][$list]['url']) ? $option['services'][$service][$list]['url'] : '';

	if (in_array($service, array('mailchimp', 'activecampaign')))
		$action = esc_url_raw($url);
	elseif ($service == 'aweber')
		$action = 'https://www.aweber.com/scripts/addlead.pl';
	elseif ($service == 'drip')
		$action = 'https://www.getdrip.com/forms/' . esc_attr($id) . '/submissions';
	elseif ($service == 'mailerlite')
		$action = 'https://app.mailerlite.com/webforms/submit/' . esc_attr($id);
	elseif ($service == 'convertkit')
		if (!empty($url)) // fallback for CK4.0
			$action = 'https://app.convertkit.com/landing_pages/' . esc_attr($id);
		else
			$action = 'https://app.convertkit.com/forms/' . esc_attr($id) . '/subscriptions';

	return $action;
}

/**
 * Generate special meta tags for each service.
 *
 * @since 4.9
 */

function md_email_inputs($fields)
{
	$data = md_setting(array('integrations'));
	$service = $fields['email_service'];
	$list = $fields['email_list'];
	$id = !empty($data['services'][$service][$list]['id']) ? $data['services'][$service][$list]['id'] : '';
	$uid = !empty($data['services'][$service][$list]['uid']) ? $data['services'][$service][$list]['uid'] : '';
	?>

	<?php if ($service == 'mailchimp') : ?>
	<input type="hidden" name="u" value="<?php echo esc_attr($uid); ?>"/>
	<input type="hidden" name="id" value="<?php echo esc_attr($id); ?>"/>
<?php endif; ?>

	<?php if ($service == 'aweber') : ?>
	<input type="hidden" name="meta_web_form_id" value="<?php echo esc_attr($id); ?>"/>
	<input type="hidden" name="listname" value="<?php echo esc_attr($uid); ?>"/>
	<?php if (!empty($fields['email_thank_you'])) : ?>
		<input type="hidden" name="redirect" value="<?php echo esc_url($fields['email_thank_you']); ?>"/>
	<?php endif; ?>

<?php endif; ?>

	<?php if ($service == 'convertkit') : ?>
	<input type="hidden" name="id" value="<?php echo esc_attr($id); ?>" id="landing_page_id"/>
<?php endif; ?>

	<?php if ($service == 'activecampaign') : ?>
	<input type="hidden" name="u" value="<?php echo esc_attr($id); ?>"/>
	<input type="hidden" name="f" value="<?php echo esc_attr($id); ?>"/>
	<input type="hidden" name="act" value="sub"/>
<?php endif; ?>

	<?php if ($service == 'mailerlite') : ?>
	<input type="hidden" name="ml-submit" value="1"/>
<?php endif; ?>

<?php }

/**
 * Construct any needed HTML attributes for each service.
 *
 * @since 4.9
 */

function md_email_attrs($tag, $fields)
{
	$atts = '';
	$data = md_setting(array('integrations'));
	$service = $fields['email_service'];
	$list = $fields['email_list'];
	$uid = !empty($data['services'][$service][$list]['uid']) ? $data['services'][$service][$list]['uid'] : '';
	$id = !empty($data['services'][$service][$list]['id']) ? $data['services'][$service][$list]['id'] : '';
	$url = !empty($data['services'][$service][$list]['url']) ? $data['services'][$service][$list]['url'] : '';

	if ($service == 'drip' && $tag == 'form')
		$atts = ' data-drip-embedded-form="' . esc_attr($id) . '"';
	if ($service == 'convertkit' && $tag == 'form' && !empty($url))
		$atts = ' id="ck_subscribe_form"';
	if ($service == 'mailerlite')
		if ($tag == 'form')
			$atts = ' data-code="' . esc_attr($id) . '"';
		elseif ($tag == 'uid')
			$atts = ' id="mlb2-' . esc_attr($uid) . '"';

	return $atts;
}

/**
 * Get names of First Name and Email field for each service.
 *
 * @since 4.9
 */

function md_email_input($field, $service)
{
	$input = '';

	if ($service == 'mailchimp')
		if ($field == 'name')
			$input = 'MERGE1';
		elseif ($field == 'email')
			$input = 'MERGE0';

	if ($service == 'aweber')
		if ($field == 'name')
			$input = 'name';
		elseif ($field == 'email')
			$input = 'email';

	if ($service == 'activecampaign')
		if ($field == 'name')
			$input = 'firstname';
		elseif ($field == 'email')
			$input = 'email';

	if ($service == 'convertkit')
		if ($field == 'name')
			$input = 'first_name';
		elseif ($field == 'email')
			$input = 'email_address';

	if (in_array($service, array('drip', 'mailerlite')))
		if ($field == 'name')
			$input = 'fields[name]';
		elseif ($field == 'email')
			$input = 'fields[email]';

	return $input;
}

/**
 * Checks if single instance of an email option is found, loads
 * global email form if none.
 *
 * @since 4.0
 * @deprecated md_email_form 4.9, now md_email_fields
 */

function md_email_fields($options = null)
{
	$services = md_email_data(array('show' => 'service'));
	$list = md_setting(array('cta', 'email_list'));
	$name_label = __('Enter your name&hellip;', 'md');
	$email_label = __('Enter your email&hellip;', 'md');
	$submit_text = __('Join Now!', 'md');
	if ($options == null) {
		$fields = array(
				'email_service' => !empty($services[$list]) ? $services[$list] : '',
				'email_list' => $list,
				'email_code' => md_setting(array('email', 'email_code')),
				'email_title' => md_setting(array('email', 'email_title')),
				'email_desc' => md_setting(array('email', 'email_desc')),
				'email_input' => array(
						'name' => md_setting(array('email', 'email_input', 'name'))
				),
				'email_name_label' => md_setting(array('email', 'email_name_label'), $name_label),
				'email_email_label' => md_setting(array('email', 'email_email_label'), $email_label),
				'email_submit_text' => md_setting(array('email', 'email_submit_text'), $submit_text),
				'email_image' => md_setting(array('email', 'email_image')),
				'email_form_style' => array(
						'attached' => md_setting(array('email', 'email_form_style', 'attached'))
				),
				'email_form_title' => md_setting(array('email', 'email_form_title')),
				'email_form_footer' => md_setting(array('email', 'email_form_footer')),
				'email_thank_you' => md_setting(array('email', 'email_thank_you')),
				'email_bg_color' => md_setting(array('email', 'bg_color')),
				'email_text_color' => md_setting(array('email', 'text_color_scheme')),
				'email_image' => md_setting(array('email', 'bg_image')),
				'email_submit_classes' => md_setting(array('email', 'email_submit_classes')),
				'email_classes' => md_setting(array('email', 'email_classes'))
		);
	} else {
		$email_list = !empty($options['email_list']) ? $options['email_list'] : $list;
		$fields = array(
				'email_service' => !empty($services[$email_list]) ? $services[$email_list] : '',
				'email_list' => !empty($options['email_list']) ? $options['email_list'] : $email_list,
				'email_code' => !empty($options['email_code']) ? $options['email_code'] : '',
				'email_title' => !empty($options['email_title']) ? $options['email_title'] : '',
				'email_desc' => !empty($options['email_desc']) ? $options['email_desc'] : '',
				'email_input' => array(
						'name' => !empty($options['email_input']['name']) ? $options['email_input']['name'] : ''
				),
				'email_name_label' => isset($options['email_name_label']) ? $options['email_name_label'] : $name_label,
				'email_email_label' => isset($options['email_email_label']) ? $options['email_email_label'] : $email_label,
				'email_submit_text' => isset($options['email_submit_text']) ? $options['email_submit_text'] : $submit_text,
				'email_image' => !empty($options['email_image']) ? $options['email_image'] : '',
				'email_form_style' => array(
						'attached' => !empty($options['email_form_style']['attached']) ? $options['email_form_style']['attached'] : ''
				),
				'email_form_title' => !empty($options['email_form_title']) ? $options['email_form_title'] : '',
				'email_form_footer' => !empty($options['email_form_footer']) ? $options['email_form_footer'] : '',
				'email_thank_you' => !empty($options['email_thank_you']) ? $options['email_thank_you'] : '',
				'email_bg_color' => !empty($options['email_bg_color']) ? $options['email_bg_color'] : '',
				'email_text_color' => !empty($options['email_text_color']) ? $options['email_text_color'] : '',
				'email_image' => !empty($options['email_image']) ? $options['email_image'] : '',
				'email_submit_classes' => !empty($options['email_submit_classes']) ? $options['email_submit_classes'] : '',
				'email_classes' => !empty($options['email_classes']) ? $options['email_classes'] : ''
		);
	}
	return $fields;
}

/**
 * Loads email form template using data from function above.
 *
 * @since 4.0
 */

function md_email_form($options = null, $atts = null)
{
	$classes = array();
	$data = md_setting('integrations');
	$fields = md_email_fields($options);
	$list = $fields['email_list'];
	$id = !empty($data['services']['mailerlite'][$list]['id']) ? $data['services']['mailerlite'][$list]['id'] : '';
	$uid = !empty($data['services']['mailerlite'][$list]['uid']) ? $data['services']['mailerlite'][$list]['uid'] : '';
	$fields['uid'] = $id;
	$code = $fields['email_code'];
	$service = $fields['email_service'];
	$title = $fields['email_title'];
	$desc = $fields['email_desc'];

	$form_classes[] = 'clear';

	if (!empty($fields['email_form_style']['attached']))
		$classes[] = 'form-attached' . (!empty($fields['email_input']['name']) ? '-2' : '');
	else
		$classes[] = 'form-full';
	if (!empty($fields['email_input']['name']))
		$classes[] = 'form-multi-fields';
	if (!empty($fields['email_image']))
		$classes[] = 'image-overlay';
	if (!empty($atts['block_classes']))
		$classes[] = $atts['block_classes'];
	elseif (!empty($fields['email_bg_color']) || !empty($fields['email_image']))
		$classes[] = 'block-single';
	if (isset($atts['classes']))
		$classes[] = $atts['classes'];
	if (isset($fields['email_classes']))
		$classes[] = $fields['email_classes'];
	if ($service == 'mailerlite') {
		$classes[] = 'ml-subscribe-form';
		$classes[] = 'ml-subscribe-form-' . esc_attr($uid);
		$form_classes[] = 'ml-block-form';
	}

	$inner_classes = isset($atts['inner_classes']) ? ' ' . $atts['inner_classes'] : '';
	$submit_classes = isset($fields['email_submit_classes']) ? ' ' . $fields['email_submit_classes'] : '';
	$classes = join(' ', $classes);
	$form_classes = join(' ', $form_classes);
	$submit_bg_color = isset($atts['email_submit_bg_color']) ? 'background-color: ' . $atts['email_submit_bg_color'] . ';' : '';
	$submit_color = isset($atts['email_submit_color']) ? 'color: ' . $atts['email_submit_color'] . ';' : '';
	$submit_style = !empty($submit_bg_color) || !empty($submit_color) ? " style=\"{$submit_bg_color}{$submit_color}\"" : '';
	$before_title = isset($atts['before_title']) ? $atts['before_title'] : '<div class="email-form-intro-title med-title mb-half">';
	$after_title = isset($atts['after_title']) ? $atts['after_title'] : '</div>';

	if ((!empty($list) && in_array($list, md_email_data(array('show' => 'ids')))) || !empty($code))
		if ($template = md_template('email-form', true))
			include($template);
		else
			md_email_connect_notice();

	$id++;
}

/**
 * If no service is connected, display this message.
 *
 * @since 4.0
 */

function md_email_connect_notice()
{
	if (is_admin())
		echo '<p class="description"><em>' . sprintf(__('To easily embed an email list from your mail provider, please first connect your lists to the <a href="%s">Integrations panel</a>, then come back here to quickly embed it to your site.', 'md'), admin_url('admin.php?page=md_integrations')) . '</em></p>';
	else
		echo '<p class="alert shadow">' . __('<b>Attention</b>: please select an email list or enter a custom form code to show an email form here!', 'md') . '</p>';
}
