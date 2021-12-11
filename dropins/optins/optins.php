<?php
/**
 * Drop-in Name: Optins
 * Description: Design and deploy custom popups, floating bars, and inline optin forms with smart precision tools and display features to tastefully capture more leads around your website.
 * Author: Alex, Kolakube
 * AuthorURI: https://marketersdelight.com/
 * DropinURI: https://marketersdelight.com/dropins/optins/
 * Slug: optins
 * Version: 1.0.3
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Create Optins admin page.
 *
 * @since 5.0
 */

class md_optins extends md_api {

	public $dir = 'dropins/optins';

	/**
	 * Include other design settings pages.
	 *
	 * @since 5.0
	 */

	public function includes() {
		require_once( 'lib/integrations/integrations.php' );
		require_once( 'lib/template-functions.php' );
		require_once( 'cta/cta.php' );
		require_once( 'floating-bars/floating-bars.php' );
		require_once( 'popups/popups.php' );
		require_once( 'lib/shortcodes.php' );
		require_once( 'lib/widget-email-form.php' );
	}

	/**
	 * Set properties.
	 *
	 * @since 5.0
	 */

	public function actions() {
		$this->floating_bars = new md_floating_bars_data;
		$this->cta = new md_cta_data;
		if ( isset( $_GET['page'] ) && ! isset( $_GET['tab'] ) && $_GET['page'] == $this->_id )
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'md_integrations_actions', array( $this, 'integration_actions' ), 10, 2 );
		add_filter( 'md_integrations', array( $this, 'integrations' ) );
		add_filter( 'md_optins_locations', array( $this, 'locations' ) );
		add_filter( 'md_filter_blocks', array( $this, 'register_blocks' ) );
		add_filter( 'md_filter_blocks_scripts', array( $this, 'blocks_scripts' ), 10, 2 );
	}

	/**
	 * Add email marketing services to MD Integrations.
	 *
	 * @since 5.3
	 */
	
	public function integrations( $integrations ) {
		$integrations['mailchimp'] = array(
			'name' => __( 'MailChimp', 'md' ),
			'url'  => 'http://admin.mailchimp.com/account/api-key-popup',
			'type' => 'email'
		);
		$integrations['aweber'] = array(
			'name' => __( 'AWeber', 'md' ),
			'url' => 'https://auth.aweber.com/1.0/oauth/authorize_app/e5957609',
			'type' => 'email',
			'manual_refresh' => true,
			'labels' => array(
				'api_key' => __( 'Authorization Key', 'md' )
			)
		);
		$integrations['convertkit'] = array(
			'name' => __( 'ConvertKit', 'md' ),
			'url' => 'https://app.convertkit.com/account/edit',
			'type' => 'email'
		);
		$integrations['activecampaign'] = array(
			'name' => __( 'ActiveCampaign', 'md' ),
			'url' => 'https://marketersdelight.com/connect-email-activecampaign/',
			'type' => 'email',
			'fields' => array( 'account_url' ),
			'labels' => array(
				'account_url' => __( 'API URL', 'md' )
			)
		);
		$integrations['mailerlite'] = array(
			'name' => __( 'MailerLite', 'md' ),
			'url' => 'https://app.mailerlite.com/integrations/api/',
			'type' => 'email'
		);
		$integrations['drip'] = array(
			'name' => __( 'Drip', 'md' ),
			'url' => 'https://www.getdrip.com/user/edit',
			'type' => 'email',
			'fields' => array( 'account_id' ),
			'labels' => array(
				'api_key' => __( 'API Token', 'md' )
			)
		);
		return $integrations;		
	}

	/**
	 * Run email service actions on connection.
	 *
	 * @since 5.3
	 */

	public function integration_actions( $integration, $api_keys ) {
		$integrations = new md_optins_integrations;
		$option = md_setting();
		if ( $integration == 'mailchimp' )
			$integrations->mailchimp( $api_keys['mailchimp']['key'], $option );
		elseif ( $integration == 'aweber' )
			$integrations->aweber( $api_keys['aweber']['key'], $option );
		elseif ( $integration == 'activecampaign' )
			$integrations->activecampaign( $api_keys['activecampaign']['account_url'], $api_keys['activecampaign']['key'], $option );
		elseif ( $integration == 'convertkit' )
			$integrations->convertkit( $api_keys['convertkit']['key'], $option );
		elseif ( $integration == 'mailerlite' )
			$integrations->mailerlite( $api_keys['mailerlite']['key'], $option );
		elseif ( $integration == 'drip' )
			$integrations->drip( $api_keys['drip']['key'], $api_keys['drip']['account_id'], $option );
	}
	
	/**
	 * Filter Optins Blocks into MD Blocks system.
	 *
	 * @since 5.3
	 */
	
	public function register_blocks( $blocks ) {
		$blocks['email'] = array(
			'dropins' => true,
			'path' => 'optins/lib/block-email.js',
			'callback' => array( $this, 'block_email_template' ),
			'localize' => array( 'colors', 'email' )
		);
		return $blocks;
	}
	
	/**
	 * Add custom parameters to Block localized script.
	 *
	 * @since 5.3
	 */
	
	public function blocks_scripts( $scripts, $data ) {
		$email = md_email_data( array( 'show' => 'names', 'label' => true, 'empty_label' => true ) );
		$popups = md_setting( array( 'popups', 'popups' ) );
	
		if ( in_array( 'email', $data ) && ! empty( $email ) )
			foreach ( $email as $list => $name )
				$scripts['email'][] = array( 'label' => $name, 'value' => $list );
	
		if ( in_array( 'popups', $data ) && ! empty( $popups ) )
			foreach ( $popups as $popup => $fields )
				$scripts['popups'][] = array( 'label' => $fields['name'], 'value' => $popup );
	
		return $scripts;
	}
	
	/**
	 * Email Block template
	 *
	 * @since 4.9
	 */
	
	public function block_email_template( $attributes, $content ) {
		ob_start();
		include( md_template( 'dropins', 'optins/block-email', true ) );
		return ob_get_clean();
	}

	/**
	 * Register Optins widgets.
	 *
	 * @since 5.3
	 */

	public function widgets() {
		register_widget( 'md_email_form' );
	}

	/**
	 * Enabled Optins around various post types.
	 *
	 * @since 5.0
	 */

	public function locations( $locations ) {
		if ( ! md_has( 'stream' ) )
			$locations['stream'] = array(
				'archive' => __( 'Stream Page', 'md' ),
				'single' => __( 'Stream Posts', 'md' ),
				'stream_categories' => __( 'Stream Categories', 'md' )
			);
		if ( ! md_has( 'bookshelf' ) )
			$locations['bookshelf'] = array(
				'archive' => __( 'Books Page', 'md' ),
				'single' => __( 'Books Posts', 'md' ),
				'bookshelf_categories' => __( 'Books Categories', 'md' )
			);
		if ( ! md_has( 'woocommerce' ) )
			$locations['product'] = array(
				'archive' => __( 'Products Page', 'md' ),
				'single' => __( 'Single Products', 'md' ),
				'product_cat' => __( 'Products Categories', 'md' )
			);
		return $locations;
	}

	/**
	 * Until Optins dashboard is made, redirect to CTA.
	 *
	 * @since 5.0
	 */

	public function admin_init() {
		wp_redirect( admin_url( "admin.php?page={$this->_id}&tab=md_cta" ) );
		exit;
	}

	/**
	 * Register admin page.
	 *
	 * @since 5.0
	 */

	public function register() {
		$this->name = __( 'Optins', 'md' );
		$popups = md_get_popups( 'ids' );
		$floating_bars = $cta =array();

		$bars = md_setting( array( 'floating_bars', 'bars' ) );
		if ( $bars )
			foreach ( $bars as $bar => $fields )
				$floating_bars[] = $bar;

		$forms = md_setting( array( 'cta', 'forms' ) );
		if ( $forms )
			foreach ( $forms as $form => $fields )
				$cta[] = $form;

		$meta = array(
			'cta' => array(
				'type' => 'group',
				'fields' => $this->cta->fields()
			),
			'cta_remove' => array(
				'type' => 'checkbox',
				'options' => $cta
			),
			'popups' => array(
				'type' => 'group',
				'fields' => array(
					'popup' => array(
						'type' => 'select',
						'options' => $popups
					),
					'show' => array(
						'type' => 'select',
						'options' => array( 'exit', 'percent' )
					),
					'delay' => array( 'type' => 'number' ),
					'cookie' => array( 'type' => 'number' )
				)
			),
			'popups_remove' => array(
				'type' => 'checkbox',
				'options' => $popups
			),
			'floating_bars' => array(
				'type' => 'group',
				'fields' => $this->floating_bars->fields()
			),
			'floating_bars_remove' => array(
				'type' => 'checkbox',
				'options' => $floating_bars
			)
		);

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'admin_header' => true
			),
			'meta_box' => array(
				'name' => $this->name,
				'priority' => 'high',
				'fields' => $meta
			),
			'term' => array(
				'name' => $this->name,
				'fields' => $meta
			)
		);
	}

	/**
	 * Retrieve active Optins on current page.
	 * $optin = array( 'popups', 'popups' ) || ( 'floating_bars', 'bars' ) || ( 'cta', 'forms' )
	 *
	 * @since 5.0
	 */

	public function active_optins( $optin ) {
		$active = array();
		$screen = get_current_screen();
		$taxonomy = ! empty( $screen->taxonomy ) ? $screen->taxonomy : '';
		$post_type = get_post_type();
		$optins = md_setting( $optin );
		if ( $optins )
			foreach ( $optins as $optin_id => $fields ) {
				$locations = ! empty( $fields['locations'] ) ? $fields['locations'] : array();
				if ( ! empty( $locations['sitewide'] ) || ! empty( $locations[$post_type] ) || ! empty( $locations[$taxonomy] ) )
					$active[$optin_id] = $fields['name'];
			}
		return $active;
	}

	/**
	 * Generic template for post meta and terms.
	 *
	 * @since 5.0
	 */

	public function meta_template() {
		$active_popups = $this->active_optins( array( 'popups', 'popups' ) );
		$active_floating_bars = $this->active_optins( array( 'floating_bars', 'bars' ) );
		$active_cta = $this->active_optins( array( 'cta', 'forms' ) );
		include( md_template( 'dropins', 'optins/admin/meta-box', true ) );
	}

	/**
	 * Popups Group fields.
	 *
	 * @since 5.0
	 */

	public function popups_meta( $group, $field ) {
		$show = md_meta( array( 'optins', 'popups', $field, 'show' ) );
		include( md_template( 'dropins', 'optins/admin/popups-meta', true ) );
	}

	/**
	 * CTA fields.
	 *
	 * @since 5.0
	 */

	public function cta_meta( $group, $field ) {
		$screen = get_current_screen();
		$colors = $this->cta->colors();
		$cta_type = md_meta( array( 'optins', 'cta', $field, 'cta_type' ) );
		$button_type = md_meta( array( 'optins', 'cta', $field, 'button_type' ) );
		include( md_template( 'dropins', 'optins/admin/cta-fields', true ) );
	}

	/**
	 * Floating Bars Group fields.
	 *
	 * @since 5.0
	 */

	public function floating_bars_meta( $group, $field ) {
		$screen = get_current_screen();
		$media_type = md_meta( array( 'optins', 'floating_bars', $field, 'media_type' ) );
		$cta_type = md_meta( array( 'optins', 'floating_bars', $field, 'cta_type' ) );
		$button_type = md_meta( array( 'optins', 'floating_bars', $field, 'button_type' ) );
		$position = md_meta( array( 'optins', 'floating_bars', $field, 'position' ) );
		$show = md_meta( array( 'optins', 'floating_bars', $field, 'show' ) );
		$colors = $this->floating_bars->colors();
		$icons = md_get_icons( 'options', null, 'md-icon-' );
		include( md_template( 'dropins', 'optins/admin/floating-bar-fields', true ) );
	}

	/**
	 * Terms fields.
	 *
	 * @since 5.0
	 */

	public function term() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo $this->name; ?></h3>
			<div class="md-widget-item">
				<?php $this->meta_template(); ?>
			</div>
		</div>
	<?php }

	/**
	 * Load meta box template.
	 *
	 * @since 5.0
	 */

	public function meta_box() {
		$this->meta_template();
	}

	/**
	 * Popups admin scripts.
	 *
	 * @since 5.0
	 */

	public function admin_scripts() { ?>
		<script>
			jQuery( document ).ready( function( $ ) {
				$( document ).on( 'change', '.md-optins-show-field', function( e ) {
					var val = $( this ).val(),
						parent = $( this ).parents( '.md-optins-show-fields' );
					if ( val == 'percent' )
						var text = 'percent';
					else
						var text = 'seconds';
					parent.find( '.md-optins-delay label.description' ).html( text );
				});
			});
		</script>
	<?php }

	/**
	 * Floating Bar meta box scripts.
	 *
	 * @since 5.0
	 */

	public function meta_scripts() {
		$this->floating_bars->admin_scripts();
		$this->cta->admin_scripts();
		$this->admin_scripts();
	}

	/**
	 * Load CSS template to style.css.
	 *
	 * @since 5.0
	 */

	public function css( $templates ) {
		$templates['optins'] = md_css( 'dropins', 'optins/css', true );
		return $templates;
	}

}

new md_optins;