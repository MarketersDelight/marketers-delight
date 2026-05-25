<div id="md_layout" class="columns-<?php echo $is_post ? 4 : 3; ?> columns-single<?php echo ( ! empty( $content['remove'] ) ? ' remove-content-box' : '' ) . ( $is_admin ? ' md-layout-admin' : '' ); ?>">

	<div class="col col1">

		<!-- Header -->

		<?php $this->fields->field( 'header', array(
			'type' => 'checkbox',
			'label' => __( 'Header', 'md' ),
			'options' => array(
				'remove' => __( 'Remove <b>Header</b>', 'md' )
			)
		) ); ?>

		<div id="header_options" class="md-sep-small" style="display: <?php echo ! empty( $header['remove'] ) ? 'none' : 'block'; ?>;">

			<?php $this->fields->field( 'header', array(
				'type' => 'checkbox',
				'options' => array(
					'logo' => __( 'Remove <b>Logo</b>', 'md' ),
				)
			) ); ?>

			<?php if ( ! md_setting( array( 'header', 'display', 'site_tagline' ) ) ) : ?>

				<?php $this->fields->field( 'header', array(
					'type' => 'checkbox',
					'options' => array(
						'tagline' => __( 'Remove <b>Tagline</b>', 'md' ),
					)
				) ); ?>

			<?php endif; ?>

			<?php if ( md_has_header_elements() )
				$this->fields->field( 'header', array(
					'type' => 'checkbox',
					'options' => array(
						'elements' => __( 'Remove <b>Elements</b>', 'md' )
					)
				)
			); ?>

			<?php if ( md_has_menu() ) : ?>

				<?php $this->fields->field( 'header', array(
					'type' => 'checkbox',
					'options' => array(
						'menu' => __( 'Remove <b>Menu</b>', 'md' )
					)
				) ); ?>

				<div id="header_menu_options" style="display: <?php echo empty( $header['menu'] ) ? 'block' : 'none'; ?>;">
					<?php $this->fields->field( 'header_menu', array(
						'type' => 'select',
						'empty_label' => __( 'Use default menu', 'md' ),
						'options' => $menus
					) ); ?>
				</div>

			<?php endif; ?>

		</div>

		<?php $this->fields->field( 'footer', array(
			'type' => 'checkbox',
			'label' => __( 'Footer', 'md' ),
			'options' => array(
				'remove' => __( 'Remove <b>Footer</b>', 'md' )
			)
		) ); ?>

		<div id="footer_options" style="display: <?php echo ! empty( $footer['remove'] ) ? 'none' : 'block'; ?>;">
			<?php $this->fields->field( 'footer', array(
				'type' => 'checkbox',
				'options' => array(
					'columns' => __( 'Remove <b>Columns</b>', 'md' )
				)
			) ); ?>
		</div>

	</div>

	<!-- Content -->

	<div class="col col2">

		<?php if ( $is_admin ) {

			$this->fields->field( 'content', array(
				'type' => 'checkbox',
				'label' => __( 'Single', 'md' ),
				'wrap_classes' => 'md-sep-micro',
				'options' => array(
					'add_author_box' => __( 'Add <b>Author Box</b>', 'md' ),
					'post_nav' => __( 'Remove <b>Post Nav</b>', 'md' )
				)
			) );

			$this->fields->field( 'featured_image', array(
				'type' => 'select',
				'label' => __( 'Featured image', 'md' ),
				'empty_label' => __( 'Use default position', 'md' ),
				'wrap_classes' => 'md-sep-small',
				'options' => $this->fields->data->values['featured_image']
			) );

		} ?>

		<p class="md-label-wrap"><label class="md-label"><?php echo __( 'Content', 'md' ); ?></label></p>

		<?php $this->fields->field( 'content', array(
			'type' => 'checkbox',
			'options' => array(
				'remove' => __( 'Remove <b>Content Box</b>', 'md' ),
				'the_content' => __( 'Remove <b>Content</b>', 'md' ),
				'builder' => __( 'Enable <b>Builder</b>', 'md' )
			)
		) ); ?>

		<div id="content_options" class="md-sep-small" style="display: <?php echo empty( $content['remove'] ) ? 'block' : 'none'; ?>;">

			<?php
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

			if ( $post_type !== 'page' && ! $is_term ) {
				if ( ! $is_admin ) {
					if ( $author_box ) $this->fields->field( 'content', array(
						'type' => 'checkbox',
						'options' => array(
							'author_box' => __( 'Remove <b>Author Box</b>', 'md' )
						)
					) );
					else $this->fields->field( 'content', array(
						'type' => 'checkbox',
						'options' => array(
							'add_author_box' => __( 'Add <b>Author Box</b>', 'md' )
						)
					) );

					$post_nav_options = array( 'post_nav' => __( 'Remove <b>Post Nav</b>', 'md' ) );

					if ( md_post_type_field( array( 'layout', 'content', 'post_nav' ), null, $post_type ) )
						$post_nav_options = array( 'add_post_nav' => __( 'Add <b>Post Nav</b>', 'md' ) );

					$this->fields->field( 'content', array(
						'type' => 'checkbox',
						'options' => $post_nav_options
					) );
				}
			}

			if ( $is_post )
				$this->fields->field( 'content', array(
					'type' => 'checkbox',
					'options' => array(
						'wpautop' => __( 'Disable <strong>WP formatting</strong>', 'md' )
					)
				) );
			?>

			<div class="md-sep-micro">
				<?php do_action( 'md_post_layout_content_options' ); ?>
			</div>

			<?php $this->fields->field( 'content_style', array(
				'type' => 'select',
				'empty_label' => __( 'Use default style', 'md' ),
				'wrap_classes' => 'md-sep-micro',
				'options' => md_filter_loop_styles()
			) ); ?>

		</div>

	</div>

	<!-- Sidebar / Panels -->

	<div class="col col3">

		<div id="layout_fields_tabs" class="md-tabs" style="display: <?php echo empty( $content['remove'] ) ? 'block' : 'none'; ?>;">

			<div class="nav-tab-wrapper">
				<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="md-layout-sidebar"><?php echo esc_html( $toggles['sidebar']['title'] ); ?></a>
				<a href="#" class="md-tab nav-tab" data-md-tab="md-layout-panel"><?php echo esc_html( $toggles['panel']['title'] ); ?></a>
			</div>

			<div id="sidebar_fields" class="md-layout-sidebar md-tab-content active <?php echo esc_attr( $sidebar['classes'] ); ?>">
				<?php $this->toggle_fields( 'sidebar', $sidebar, $post_type, $is_admin, $is_term ); ?>
			</div>

			<div id="panel_fields" class="md-layout-panel md-tab-content <?php echo esc_attr( $panel['classes'] ); ?>">
				<?php $this->toggle_fields( 'panel', $panel, $post_type, $is_admin, $is_term ); ?>
			</div>

		</div>

	</div>

</div>