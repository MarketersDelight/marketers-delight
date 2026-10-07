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
		$this->name = __( 'Page Title', 'md' );
		$term_fields = array_merge( $this->fields->data->page_fields(), array(
			'archives_icon' => array(
				'type' => 'select',
				'options' => md_get_icons( 'ids' )
			),
			'archives_color' => array( 'type' => 'color' )
		) );

		return array(
			'admin_page' => array(
				'name' => $this->name,
				'group' => 'page_settings',
				'position' => 5
			),
			'term' => array(
				'name' => $this->name,
				'position' => 10,
				'fields' => $term_fields
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
				<div class="md-field-row md-sep-small">
					<p class="md-label-wrap"><label class="md-label"><?php echo __( 'Identity', 'md' ); ?></label></p>
					<div class="md-field columns-2 columns-single md-full-select">
						<div class="col">
						<?php $this->fields->field( 'archives_color', array(
							'type' => 'color',
							'label' => __( 'Color', 'md' ),
							'description' => __( 'Set the category icon color.', 'md' )
						) ); ?>
						</div>

						<div class="col">
						<?php $this->fields->field( 'archives_icon', array(
							'type' => 'select',
							'label' => __( 'Icon', 'md' ),
							'description' => __( 'Show beside the category title.', 'md' ),
							'empty_label' => __( 'No icon', 'md' ),
							'options' => md_get_icons( 'options' )
						) ); ?>
						</div>
					</div>
				</div>
				<?php $this->fields->page_fields(); ?>
				<?php $this->fields->settings_group( 'term_meta' ); ?>
			</div>
		</div>
	<?php }

}

new md_hero;
