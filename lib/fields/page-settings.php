<?php
/**
 * A unified settings group to compile various page settings into a joined interface.
 *
 * @since 5.6
 */

class md_page_settings extends md_api {

	/**
	 * Register custom meta box and terms.
	 *
	 * @since 5.6
	 */

	public function register() {
		$this->name = __( 'Page Settings', 'md' );

		return array(
			'meta_box' => array( 'name' => $this->name )
		);
	}

	/**
	 * Render Post meta box template.
	 *
	 * @since 5.6
	 */

	public function meta_box() {
		$this->admin_template( 'post_meta' );
	}

	/**
	 * Render meta box template.
	 *
	 * @since 5.6
	 */

	public function term() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo $this->name; ?></h3>
			<div class="md-widget-item">
				<?php $this->admin_template( 'term_meta' ); ?>
			</div>
		</div>
	<?php }

	/**
	 * Render admin fields when registered as Page Settings.
	 *
	 * @since 5.6
	 */

	public function admin_fields() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo $this->name; ?></h3>
			<div class="md-widget-item">
				<?php $this->admin_template( 'admin_fields' ); ?>
			</div>
		</div>
	<?php }

	/**
	 * Generic template for Meta Box & Term Meta.
	 *
	 * @since 5.6
	 */

	public function admin_template( $hook ) {
		$c = 0;
		$page_order = array();
		$page_settings = md_register( "{$hook}_page_settings" );

		foreach ( $page_settings as $id => $fields )
			$page_order[$id] = $fields['order'];

		asort( $page_order );
	 ?>
		<div class="md-tabs">
			<div class="nav-tab-wrapper">
				<?php foreach ( $page_order as $id => $order ) :
					$fields = $page_settings[$id];
				?>
					<a href="#" class="md-tab nav-tab<?php echo $c == 0 ? ' nav-tab-active' : ''; ?>" data-md-tab="md-<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $fields['name'] ); ?></a>
				<?php $c++; endforeach; ?>
			</div>
			<?php do_action( "md_{$hook}_page_settings" ); ?>
		</div>
	<?php }

}

new md_page_settings;
