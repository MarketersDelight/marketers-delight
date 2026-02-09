<?php
/**
 * Register Page Hero controls across admin interfaces.
 *
 * @since 6.0
 */

class md_hero extends md_api {

	/**
	 * Register custom meta box and terms.
	 *
	 * @since 6.0
	 */

	public function register() {
		$this->name = __( 'Hero', 'md' );
		return array(
			'admin_page' => array(
				'name' => $this->name,
				'parent_group' => 'page_settings',
				'position' => 5
			),
			'term' => array(
				'name' => $this->name,
				'position' => 10,
				'fields' => $this->fields->data->page_fields()
			)
		);
	}

	/**
	 * Render Post meta box template.
	 *
	 * @since 6.0
	 */

	public function admin_page() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo $this->name; ?></h3>
			<div class="md-widget-item">
				<?php $this->fields->settings_group( 'admin_page' ); ?>
			</div>
		</div>
	<?php }

	/**
	 * Render meta box template.
	 *
	 * @since 6.0
	 */

	public function term() { ?>
		<div class="md-widget md-toggle md-sep-small">
			<h3 class="md-widget-title"><?php echo $this->name; ?></h3>
			<div class="md-widget-item">
				<?php $this->fields->page_fields(); ?>
				<?php $this->fields->settings_group( 'term_meta' ); ?>
			</div>
		</div>
	<?php }

}

new md_hero;