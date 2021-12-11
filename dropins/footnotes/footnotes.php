<?php
/**
 * Drop-in Name: Footnotes
 * Description: A simple footnotes meta box that helps you create and organize footnotes in am article. Edit post > Footnotes.
 * Author: Alex, Kolakube
 * AuthorURI: https://marketersdelight.com/
 * DropinURI: https://marketersdelight.com/dropins/footnotes/
 * Slug: footnotes
 * Version: 1.0.1
 */

/**
 * Setup MD Footnotes meta box & build various interface parts.
 *
 * @since 4.5
 */

class md_footnotes extends md_api {

	/**
	 * Run actions and filters.
	 *
	 * @since 5.3
	 */
	
	public function actions() {
		add_filter( 'post_class', array( $this, 'post_classes' ) );
		add_filter( 'the_content', array( $this, 'footnotes_list' ) );
		add_shortcode( 'fn', array( $this, 'shortcode' ) );
	}

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
	 * Add/remove post classes.
	 *
	 * @since 4.1
	 */
	
	public function post_classes( $classes ) {
		$footnotes = md_post_meta( array( 'footnotes' ) );
		if ( ( md_has_sidebar() && ! empty( $footnotes ) ) || ! empty( $footnotes['after_post']['toggle'] ) )
			$classes[] = 'toggle-footnotes';
		return $classes;
	}

	/**
	 * [fn] shortcode template.
	 *
	 * @since 4.5
	 */

	public function shortcode( $atts ) {
		extract( shortcode_atts( array(
			'id' => '',
			'align' => ''
		), $atts, 'footnote' ) );
		static $i = 1;
		$id = ! empty( $atts['id'] ) ? $atts['id'] : '';
		$footnotes = md_post_meta( array( 'footnotes' ) );

		if ( empty( $footnotes['footnotes'][$id] ) )
			return;

		$url = get_permalink();
		$align = ( $i % 2 == 0 || ( isset( $atts['align'] ) && $atts['align'] == 'right' ) ? ' right' : '' );
		ob_start();
		include( md_template( 'dropins', 'footnotes/footnote', true ) );
		return ob_get_clean();
	}

	/**
	 * Generate footnotes list after post.
	 *
	 * @since 4.5
	 */
	
	public function footnotes_list( $content ) {
		if ( in_the_loop() && is_main_query() ) {
			$footnotes = md_post_meta( array( 'footnotes' ) );
			if ( ! empty( $footnotes['after_post']['show'] ) && ! empty( $footnotes['footnotes'] ) && is_singular() ) {
				$notes = '';
				$c = 0;
				$url = get_permalink();
				foreach ( $footnotes['footnotes'] as $footnote => $fields ) {
					if ( ! empty( $fields['footnote'] ) )
						$notes .= '<li>' . $fields['footnote'] . " <a href=\"{$url}#footnote_{$footnote}\">&#8617;</a>" . '</li>';
					$c++;
				}
				$content .=
					'<div id="footnotes" class="footnotes">'.
					'<h4>' . apply_filters( 'md_footnotes_list_title', __( 'Footnotes', 'md' ) ) . '</h4>'.
					'<ol>' . $notes . '</ol>'.
					'</div>';
			}
		}
		return $content;
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