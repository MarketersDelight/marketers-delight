<?php
/**
 * Create Post Settings admin pages.
 *
 * @since 5.6
 */

class md_post extends md_api {

	public function actions() {
		add_action( "md_layout_{$this->_id}_after_settings", array( $this, 'post_settings' ) );
	}

	/**
	 * Create admin page and meta box.
	 *
	 * @since 5.6
	 */

	public function register() {
		$fields = array(
			'author_box' => array(
				'type' => 'checkbox',
				'options' => array( 'enable', 'all_posts' )
			),
			'byline' => array(
				'type' => 'checkbox',
				'options' => md_byline_items( 'ids' )
			),
			'byline_position' => array(
				'type' => 'select',
				'options' => array( 'before_headline', 'after_headline' )
			)
		);
		$page_settings = md_page_settings_fields();
		return array(
			'admin_page' => array(
				'name' => __( 'Settings', 'md' ),
				'parent_slug' => 'edit.php',
				'fields' => array_merge( $fields, $page_settings )
			)
		);
	}

	/**
	 * Pull admin settings from various parts of MD for use
	 * on this settings page.
	 *
	 * @since 5.6
	 */

	public function admin_settings( $settings ) {
		$settings[$this->_id] = array( 'page_cover', 'featured_image', 'layout', 'loop', 'share', 'optins', 'scripts' );
		return $settings;
	}

	/**
	 * Admin page template.
	 *
	 * @since 5.6
	 */

	public function admin_page() {
		include( 'blog-settings.php' );
	}

	public function post_settings() { ?>
		<div class="md-widget md-toggle md-sep-small">

			<h3 class="md-widget-title"><?php echo __( 'Post Settings', 'md' ); ?></h3>

			<div class="md-widget-item">

				<p class="description md-sep-micro"><span class="dashicons dashicons-editor-help"></span> <?php echo __( 'The settings below will apply to single blog posts only.', 'md' ); ?></p>

				<hr class="md-sep-small" />

				<h4><?php echo __( 'Byline', 'md' ); ?></h4>

				<div class="md-sep-small">
					<?php $this->fields->field( 'byline_position', array(
						'type' => 'select',
						'empty_label' => __( 'Select byline position...', 'md' ),
						'options' => array(
							'before_headline' => __( 'Show before headline', 'md' ),
							'after_headline' => __( 'Show after headline', 'md' )
						)
					) ); ?>
				</div>

				<div class="md-sep-small">
					<?php $this->fields->field( 'byline', array(
						'type' => 'checkbox',
						'multi' => true,
						'options' => md_byline_items()
					) ); ?>
				</div>

				<hr class="md-sep-small" />

				<h4><?php echo __( 'Author Box', 'md' ); ?></h4>

				<div class="md-sep-small">
					<?php $this->fields->field( 'author_box', array(
						'type' => 'checkbox',
						'options' => array(
							'enable' => __( 'Enable author box after blog posts', 'md' ),
							'all_posts' => __( 'Link to author archives page', 'md' )
						)
					) ); ?>
				</div>

			</div>

		</div>
	<?php }

}

new md_post;
