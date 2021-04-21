<?php
/**
 * Setup MD Footnotes meta box & build various interface parts.
 *
 * @since 4.5
 */

class md_footnotes extends md_api {

	/**
	 * Register meta box.
	 *
	 * @since 4.5
	 */

	public function register() {
		return array(
			'meta_box' => array(
				'name' => __( 'Footnotes', 'md' ),
				'fields' => array(
					'after_post' => array(
						'type'    => 'checkbox',
						'options' => array( 'show', 'toggle' )
					),
					'footnotes' => array(
						'type' => 'group',
						'fields' => array(
							'footnote' => array( 'type' => 'textarea' )
						)
					)
				)
			)
		);
	}

	/**
	 * Add dynamic CSS to style.css generation.
	 *
	 * @since 5.2
	 */

	public function css( $templates ) {
		$templates['footnotes'] = md_css( 'dropins', 'footnotes/css', true );
		return $templates;
	}
	/**
	 * Build admin fields for use in meta box.
	 *
	 * @since 4.5
	 */

	public function meta_box() { ?>
		<div class="md-footnotes-fields">
			<div class="md-sep-small">
				<?php $this->fields->field( 'after_post', array(
					'type' => 'checkbox',
					'options' => array(
						'show' => __( 'Show footnotes list (after post)', 'md' ),
						'toggle' => __( 'Always toggle footnotes', 'md' )
					)
				) ); ?>
			</div>
			<?php $this->fields->field( 'footnotes', array(
				'type' => 'group',
				'label' => __( 'Footnotes', 'md' ),
				'callback' => array( $this, 'footnote_field' ),
				'button_text' => __( 'New Footnote', 'md' )
			) ); ?>
		</div>
	<?php }

	/**
	 * Footnote repeater field data.
	 *
	 * @since 4.5
	 */

	public function footnote_field( $group, $field ) {
		$this->fields->field( array( $group, $field, 'footnote' ), array(
			'type' => 'textarea',
			'label' => __( 'Footnote', 'md' ) . ' <span class="md-code">[fn id="<span class="md-clone-label">' . $field . '</span>"]</span>',
			'rows' => 2
		) );
	}

}

new md_footnotes;