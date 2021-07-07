<?php
/**
 * Load and manipulate frontend WooCommerce templates.
 *
 * @since 5.0
 */

class md_woocommerce_templates extends md_api {

	/**
	 * Run actions and filters.
	 *
	 * @since 4.9.4
	 */

	public function actions() {
		$this->woo = md_setting( array( 'woocommerce' ) );
		add_action( 'woocommerce_before_main_content', array( $this, 'before_main_content' ), 5 );
		add_action( 'woocommerce_after_main_content', array( $this, 'after_main_content' ) );
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'add_to_cart_fragment' ) );
		add_filter( 'woocommerce_checkout_fields' , array( $this, 'checkout_fields' ) );
		if ( ! empty( $this->woo['sale_label'] ) )
			add_filter( 'woocommerce_sale_flash', array( $this, 'sale_label' ), 10, 3 );
	}

	/**
	 * Modify Woo checkout fields.
	 *
	 * @since 4.9.4
	 */

	public function checkout_fields( $fields ) {
		if ( ! empty( $this->woo['settings']['phone_optional'] ) )
			$fields['billing']['billing_phone']['required'] = false;
		if ( ! empty( $this->woo['settings']['minimal_checkout'] ) ) {
			unset( $fields['billing']['billing_address_1']);
			unset( $fields['billing']['billing_country'] );
			unset( $fields['billing']['billing_company'] );
			unset( $fields['billing']['billing_address_2'] );
			unset( $fields['billing']['billing_city'] );
			unset( $fields['billing']['billing_state'] );
			unset( $fields['billing']['billing_postcode'] );
		}
		if ( ! empty( $this->woo['settings']['remove_order_comments'] ) )
			unset( $fields['order']['order_comments'] );
		return $fields;
	}

	/**
	 * Modify Sale text label.
	 *
	 * @since 4.9.4
	 */

	function sale_label( $text, $post, $_product ) {
	    return '<span class="onsale">' . esc_html( $this->woo['sale_label'] ) . '</span>';
	}

	/**
	 * Manipulate templates.
	 *
	 * @since 4.9.4
	 */

	public function template() {
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );

		if ( ! empty( $this->woo['settings']['header_cart'] ) && md_has_menu() )
			add_action( 'md_hook_header_triggers', array( $this, 'cart_icon' ), 5 );

		if ( is_post_type_archive( 'product' ) ) {
			if ( ! empty( $this->woo['settings']['enable_archives_sidebar'] ) )
				add_filter( 'md_filter_has_sidebar', '__return_true' );
			else
				add_filter( 'md_filter_has_sidebar', '__return_false' );
			add_filter( 'md_filter_loop_type', array( $this, 'loop_type' ) );
		}

		if ( is_singular( 'product' ) )
			if ( ! empty( $this->woo['settings']['enable_single_sidebar'] ) )
				add_filter( 'md_filter_has_sidebar', '__return_true' );
			else
				add_filter( 'md_filter_has_sidebar', '__return_false' );
	}

	/**
	 * Add loop type.
	 *
	 * @since 5.1
	 */

	public function loop_type() {
		return 'woo';
	}

	/**
	 * Insert custom HTML before WooCommerce main content.
	 *
	 * @since 4.9.4
	 */

	public function before_main_content() {
		ob_start();
	?>
		<div id="content_box" class="<?php echo md_content_box_classes(); ?>">
			<div class="inner">
				<div id="content" class="<?php echo md_content_classes(); ?> format">
					<?php md_hook_before_content(); ?>
	<?php return ob_get_contents(); }

	/**
	 * Insert custom HTML after WooCommerce main content.
	 *
	 * @since 4.9.4
	 */

	public function after_main_content() {
		ob_start();
	?>
					<?php md_hook_after_content(); ?>
				</div>
				<?php get_sidebar(); ?>
			</div>
		</div>
	<?php return ob_get_contents(); }

	/**
	 * Load Cart icon HTML into header.
	 *
	 * @since 4.9.4
	 */

	public function cart_icon() {
		$count = WC()->cart->cart_contents_count;
	?>
		<a class="header-cart cart-contents" href="<?php echo wc_get_cart_url(); ?>" title="<?php echo __( 'View your shopping cart', 'md' ); ?>">
			<?php echo md_icon( 'cart' ); ?>
			<span class="cart-contents-count">
				<?php echo esc_html( $count ); ?>
			</span>
		</a>
	<?php }

	/**
	 * Target shopping cart icon for instant update.
	 *
	 * @since 4.9.4
	 */

	public function add_to_cart_fragment( $fragments ) {
	    ob_start();
		$this->cart_icon();
	    $fragments['a.cart-contents'] = ob_get_clean();
	    return $fragments;
	}

}

new md_woocommerce_templates;