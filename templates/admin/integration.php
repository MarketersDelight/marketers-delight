<div class="md-integration <?php echo esc_attr( $id ); ?> md-widget md-toggle md-sep-small type-<?php echo esc_attr( $fields['type'] ) . ( $error ? ' invalid' : '' ) . ( ! empty( $option['enabled'][$id] ) ? ' valid' : ' inactive' ); ?>">

	<h3 class="md-widget-title">

		<img src="<?php echo esc_url( "$icon_path/{$id}.png" ); ?>" alt="<?php echo esc_html( $fields['name'] ); ?>" class="mr-half" width="25" />

		<?php echo esc_html( $fields['name'] ); ?>

		<?php if ( ! empty( $option['enabled'][$id] ) ) : ?>
		<span class="green"><i class="dashicons dashicons-yes"></i></span>
		<?php endif; ?>

	</h3>

	<div class="md-widget-item clear">

		<div class="step step-1<?php echo ( empty( $option['enabled'][$id] ) ? ' is-active' : '' ); ?>">

			<?php if ( isset( $fields['manual_refresh'] ) || empty( $option['api_keys'][$id]['key'] ) ) : ?>
			<p class="md-alert md-alert-manual-refresh"><?php echo sprintf( __( '<b>Note:</b> To refresh %s data, please <a href="%2s" target="_blank">enter a new %s</a> below.', 'md' ), $fields['name'], esc_url( $fields['url'] ), $api_key_label ); ?></p>
			<?php endif; ?>

			<p class="md-alert md-alert-error red"><?php echo __( '<b>Connection failed.</b> Please enter valid API credentials or try again in a few minutes.', 'md' ); ?></p>

			<?php if ( isset( $fields['fields'] ) && in_array( 'account_url', $fields['fields'] ) ) : ?>
			<p><label class="md-label" for="marketers_delight_integrations_api_keys_<?php echo esc_attr( $id ); ?>_account_url"><?php echo $account_url_label; ?></label></p>
			<p><input type="text" name="marketers_delight[integrations][api_keys][<?php echo esc_attr( $id ); ?>][account_url]" id="marketers_delight_integrations_api_keys_<?php echo esc_attr( $id ); ?>_account_url" placeholder="<?php echo sprintf( __( 'Enter %s URL...', 'md' ), $fields['name'] ); ?>" value="<?php echo ( ! empty( $option['api_keys'][$id]['account_url'] ) ? esc_attr( $option['api_keys'][$id]['account_url'] ) : '' ); ?>" class="md-input regular-text" /></p>
			<?php endif; ?>

			<p><label class="md-label" for="marketers_delight_integrations_api_keys_<?php echo esc_attr( $id ); ?>_key"><?php echo $api_key_label; ?></label></p>

			<p><input type="text" name="marketers_delight[integrations][api_keys][<?php echo esc_attr( $id ); ?>][key]" id="marketers_delight_integrations_api_keys_<?php echo esc_attr( $id ); ?>_key" class="md-input" placeholder="<?php echo sprintf( __( 'Enter %s %2s...', 'md' ), $fields['name'], $api_key_label ); ?>" value="<?php echo ( ! empty( $option['api_keys'][$id]['key'] ) && $id != 'aweber' ? esc_attr( $option['api_keys'][$id]['key'] ) : '' ); ?>" class="regular-text" /></p>

			<?php if ( isset( $fields['fields'] ) && in_array( 'account_id', $fields['fields'] ) ) : ?>
			<p><label class="md-label" for="marketers_delight_integrations_api_keys_<?php echo esc_attr( $id ); ?>_account_id"><?php echo __( 'Account ID', 'md' ); ?></label></p>
			<p><input type="text" name="marketers_delight[integrations][api_keys][<?php echo esc_attr( $id ); ?>][account_id]" id="marketers_delight_integrations_api_keys_<?php echo esc_attr( $id ); ?>_account_id" placeholder="<?php echo sprintf( __( 'Enter %s Account ID...', 'md' ), $fields['name'] ); ?>" value="<?php echo ( ! empty( $option['api_keys'][$id]['account_id'] ) ? esc_attr( $option['api_keys'][$id]['account_id'] ) : '' ); ?>" class="md-input regular-text" /></p>
			<?php endif; ?>

			<div class="md-sep-small">
				<button class="md-integration-button md-button-med button button-primary" data-md-integration="<?php echo esc_attr( $id ); ?>" data-md-integration-action="connect"><?php echo sprintf( __( 'Connect to %s', 'md' ), $fields['name'] ); ?></button>
				<span class="md-loading dashicons dashicons-update"></span>
			</div>

			<hr />

			<p class="description"><?php echo sprintf( __( 'Enter the %s from your %2s account into the text field above then click the "Connect" button. <a href="%3s" target="_blank">Click here to get your API key &rarr;</a>', 'md' ), $api_key_label, $fields['name'], $fields['url'] ); ?></p>

		</div>

		<?php if ( ! empty( $option['enabled'][$id] ) ) : ?>

		<div class="step step-2 is-active">

			<?php if ( $fields['type'] == 'email' ) : ?>

			<p class="green"><?php echo sprintf( __( 'Your site is connected to <b>%s</b>!', 'md' ), esc_html( $fields['name'] ) ); ?></b></p>
			<ul class="md-bullet-list">
				<?php foreach ( $option['services'][$id] as $list ) : ?>
					<li><?php echo esc_html( $list['name'] ) . ( isset( $list['subscribers'] ) ? sprintf( __( ' (%s subscribers)', 'md' ), intval( $list['subscribers'] ) ) : '' ); ?></li>
				<?php endforeach; ?>
			</ul>

			<?php elseif ( $fields['type'] == 'site' && ! empty( $fields['message'] ) ) :
				echo wpautop( $fields['message'] ) . '<hr />';

			endif; ?>

			<p>
				<?php if ( ! isset( $fields['refresh'] ) ) : ?>
				<button class="md-integration-button md-button-med button" data-md-integration="<?php echo esc_attr( $id ); ?>" data-md-integration-action="<?php echo ( isset( $fields['manual_refresh'] ) || empty( $option['api_keys'][$id]['key'] ) ? 'manual_refresh' : 'refresh' ); ?>"><?php echo sprintf( __( 'Refresh %s', 'md' ), $fields['name'] ); ?></button> &nbsp;
				<?php endif; ?>

				<a href="#" class="md-integration-button red" data-md-integration="<?php echo esc_attr( $id ); ?>" data-md-integration-action="disconnect"><?php echo sprintf( __( 'Disconnect from %s', 'md' ), $fields['name'] ); ?></a>

				<span class="md-loading dashicons dashicons-update"></span>
			</p>

		</div>

		<?php endif; ?>

	</div>

</div>