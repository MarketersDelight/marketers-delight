<div id="md_layout" class="<?php echo esc_attr( $classes ); ?>">

	<?php if ( ! $is_taxonomy ) : ?>

	<div class="col col1">

		<!-- Header -->

		<div class="md-conditional md-conditional-invert md-sep-small">

			<?php $this->fields->field( 'header', array(
				'type' => 'checkbox',
				'label' => __( 'Header', 'md' ),
				'classes' => 'md-conditional-option',
				'options' => array(
					'remove' => __( 'Remove <b>Header</b>', 'md' )
				)
			) ); ?>

			<div id="header_options" class="md-conditional-item md-conditional-1 md-sep-small<?php echo empty( $header['remove'] ) ? ' is-condition' : ''; ?>">

				<?php
				$this->fields->field( 'header', array(
					'type' => 'checkbox',
					'options' => array(
						'logo' => __( 'Remove <b>Logo</b>', 'md' ),
					)
				) );

				if ( ! md_setting( array( 'header', 'display', 'site_tagline' ) ) )
					$this->fields->field( 'header', array(
						'type' => 'checkbox',
						'options' => array(
							'tagline' => __( 'Remove <b>Tagline</b>', 'md' )
						)
					) );

				if ( md_has_header_elements() )
					$this->fields->field( 'header', array(
						'type' => 'checkbox',
						'options' => array(
							'elements' => __( 'Remove <b>Elements</b>', 'md' )
						)
					) );

				if ( md_has_menu() ) {

					echo '<div class="md-conditional md-conditional-invert">';

					$this->fields->field( 'header', array(
						'type' => 'checkbox',
						'classes' => 'md-conditional-option',
						'options' => array(
							'menu' => __( 'Remove <b>Menu</b>', 'md' )
						)
					) );

					echo '<div class="md-conditional-item md-conditional-1' . ( empty( $header['menu'] ) ? ' is-condition' : '' ) . '">';

					$this->fields->field( 'header_menu', array(
						'type' => 'select',
						'empty_label' => __( 'Use default menu', 'md' ),
						'options' => $menus
					) );

					echo '</div></div>';

				} ?>

			</div>

		</div>

		<div class="md-conditional md-conditional-invert">

			<?php $this->fields->field( 'footer', array(
				'type' => 'checkbox',
				'label' => __( 'Footer', 'md' ),
				'classes' => 'md-conditional-option',
				'options' => array(
					'remove' => __( 'Remove <b>Footer</b>', 'md' )
				)
			) ); ?>

			<div id="footer_options" class="md-conditional-item md-conditional-1<?php echo empty( $footer['remove'] ) ? ' is-condition' : ''; ?>">
				<?php $this->fields->field( 'footer', array(
					'type' => 'checkbox',
					'options' => array(
						'columns' => __( 'Remove <b>Columns</b>', 'md' )
					)
				) ); ?>
			</div>

		</div>

	</div>

	<?php endif; ?>

	<!-- Content -->

	<div class="<?php echo $is_taxonomy ? 'md-taxonomy-col' : 'col col2'; ?>">

		<?php if ( $is_admin ) {

		echo '<div class="md-sep-micro">';

		$featured_image_options = $this->fields->data->values['featured_image'];

		$this->fields->field( 'featured_image', array(
			'type' => 'select',
			'label' => __( 'Featured image', 'md' ),
			'empty_label' => $this->fields->inherit_label( 'featured_image', __( 'Use default position', 'md' ), $featured_image_options ),
			'options' => $featured_image_options
		) );

		echo '</div>';

		echo '<div class="md-sep-micro">';

		do_action( 'md_hook_layout_admin_single_fields', $this->fields );

		echo '</div>';

		} ?>

		<div class="md-sep-micro">

		<p class="md-label-wrap"><label class="md-label"><?php echo __( 'Content', 'md' ); ?></label></p>

		<?php if ( ! $is_taxonomy ) :

		$content_options = array(
			'remove' => __( 'Remove <b>Content Box</b>', 'md' ),
			'the_content' => __( 'Remove <b>Post Content</b>', 'md' )
		);

		if ( $is_post ) {
			$content_options['builder'] = __( 'Enable <b>Builder</b>', 'md' );
			$content_options['wpautop'] = __( 'Disable <strong>WP format</strong>', 'md' );
		}

		$this->fields->field( 'content', array(
			'type' => 'checkbox',
			'options' => $content_options
		) );

		endif; ?>

		<div id="content_options" class="md-sep-small" style="display: <?php echo empty( $content['remove'] ) ? 'block' : 'none'; ?>;">

			<?php if ( ! $is_taxonomy ) {
				$this->fields->field( 'breadcrumbs', array(
					'type' => 'checkbox',
					'options' => $breadcrumbs_options
				) );

				$this->fields->field( 'content', array(
					'type' => 'checkbox',
					'options' => array(
						'headline' => __( 'Remove <b>Title</b>', 'md' )
					)
				) );
			}

			if ( $post_type !== 'page' && $is_post ) {
				if ( $author_box )
					$this->fields->field( 'content', array(
						'type' => 'checkbox',
						'options' => array(
							'author_box' => __( 'Remove <b>Author Box</b>', 'md' )
						)
					) );
				else
					$this->fields->field( 'content', array(
						'type' => 'checkbox',
						'options' => array(
							'add_author_box' => __( 'Add <b>Author Box</b>', 'md' )
						)
					) );

				$this->fields->field( 'content', array(
					'type' => 'checkbox',
					'options' => $post_nav_options
				) );
			}

			if ( ! $is_taxonomy )
				$this->fields->field( 'content', array(
					'type' => 'checkbox',
					'options' => array(
						'add_author_box' => __( 'Add <b>Author Box</b>', 'md' ),
						'post_nav' => __( 'Remove <b>Post Nav</b>', 'md' )
					)
				) );

			$content_style_options = md_filter_loop_styles();

			$this->fields->field( 'content_style', array(
				'type' => 'select',
				'empty_label' => $this->fields->inherit_label( 'content_style', __( 'Use default style', 'md' ), $content_style_options ),
				'wrap_classes' => 'md-sep-micro',
				'options' => $content_style_options
			) ); ?>

		</div>

	</div>

	</div>

	<!-- Sidebar / Panels -->

	<?php if ( ! $is_taxonomy ) : ?>

	<div class="col col3">

		<div id="layout_fields_tabs" class="md-tabs" style="display: <?php echo empty( $content['remove'] ) ? 'block' : 'none'; ?>;">

			<div class="nav-tab-wrapper">
				<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="md-layout-sidebar"><?php echo esc_html( $context['toggles']['sidebar']['title'] ); ?></a>
				<a href="#" class="md-tab nav-tab" data-md-tab="md-layout-panel"><?php echo esc_html( $context['toggles']['panel']['title'] ); ?></a>
			</div>

			<div id="sidebar_fields" class="md-layout-sidebar md-tab-content active <?php echo esc_attr( $sidebar['classes'] ); ?>">
				<?php $this->toggle_fields( 'sidebar', $sidebar, $context ); ?>
			</div>

			<div id="panel_fields" class="md-layout-panel md-tab-content <?php echo esc_attr( $panel['classes'] ); ?>">
				<?php $this->toggle_fields( 'panel', $panel, $context ); ?>
			</div>

		</div>

	</div>

	<?php endif; ?>

</div>