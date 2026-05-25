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
			<a href="#" class="md-tab nav-tab nav-tab-active" data-md-tab="md-layout-sidebar"><?php echo __( 'Sidebar', 'md' ); ?></a>
			<a href="#" class="md-tab nav-tab" data-md-tab="md-layout-panel"><?php echo __( 'Panel', 'md' ); ?></a>
		</div>

		<div id="sidebar_fields" class="md-layout-sidebar md-tab-content active <?php echo esc_attr( $sidebar['classes'] ); ?>">

			<?php if ( $is_admin ) : ?>

				<?php $this->fields->field( 'sidebar', array(
					'type' => 'checkbox',
					'wrap_classes' => 'md-sep-micro md-right',
					'options' => array(
						'alt' => __( 'Flip direction', 'md' )
					)
				) ); ?>

				<?php $this->fields->field( 'sidebar', array(
					'type' => 'checkbox',
					'wrap_classes' => 'md-sep-micro',
					'options' => array(
						'global' => __( 'Enable on all pages', 'md' )
					)
				) ); ?>

				<?php foreach ( $page_types as $type => $label ) : ?>
				<div class="md-flex-columns md-sep-micro">
					<div class="md-flex-column">
						<?php $this->fields->field( "sidebar_{$type}_show", array(
							'type' => 'checkbox',
							'inline' => true,
							'options' => array(
								'enable' => sprintf( __( 'Enable on <strong>%s</strong>', 'md' ), $label ),
								'disable' => sprintf( __( 'Disable on <strong>%s</strong>', 'md' ), $label ),
							)
						) ); ?>
					</div>
					<div class="md-flex-column md-full-select">
						<?php $this->fields->field( "sidebar_$type", array(
							'type' => 'select',
							'empty_label' => __( 'Use Main sidebar', 'md' ),
							'options' => $sidebar['areas']
						) ); ?>
					</div>
				</div>
				<?php endforeach; ?>

			<?php else :
				if ( $sidebar['has'] )
					$this->fields->field( 'sidebar', array(
						'type' => 'checkbox',
						'options' => array(
							'remove' => __( 'Remove <b>Sidebar</b>', 'md' )
						)
					) );
				else
					$this->fields->field( 'sidebar', array(
						'type' => 'checkbox',
						'options' => array(
							'add' => __( 'Add <b>Main Sidebar</b>', 'md' )
						)
					) );
				?>

				<?php if ( ! empty( $sidebar['areas'] ) ) : ?>

				<div id="sidebar_options" style="display: <?php echo $sidebar['display']; ?>;">

					<?php $this->fields->field( 'sidebar', array(
						'type' => 'checkbox',
						'wrap_classes' => 'md-sep-micro',
						'options' => array(
							'alt' => __( 'Flip direction', 'md' )
						)
					) ); ?>

					<div id="<?php echo $this->_id; ?>_custom_sidebar_option">
						<?php $this->fields->field( 'custom_sidebar', array(
							'type' => 'select',
							'empty_label' => __( 'Use Main sidebar', 'md' ),
							'wrap_classes' => 'md-sep-micro',
							'options' => $sidebar['areas']
						) ); ?>
					</div>

					<?php if ( $is_term )
						$this->fields->field( 'entries_sidebar', array(
							'type' => 'select',
							'empty_label' => __( 'Posts in this category...', 'md' ),
							'wrap_classes' => 'md-sep-micro',
							'options' => $sidebar['areas']
						) ); ?>

					<p class="description"><?php echo sprintf( __( '<a href="%s" target="_blank">Edit Custom Sidebars</a>', 'md' ), admin_url( 'admin.php?page=md_settings' ) ); ?></p>

				</div>

				<?php endif; ?>

			<?php endif; ?>

		</div>

		<div id="panel_fields" class="md-layout-panel md-tab-content <?php echo esc_attr( $panel['classes'] ); ?>">

			<?php if ( $is_admin ) : ?>

				<?php $this->fields->field( 'panel', array(
					'type' => 'checkbox',
					'wrap_classes' => 'md-sep-micro md-right',
					'options' => array(
						'alt' => __( 'Flip direction', 'md' )
					)
				) ); ?>

				<?php $this->fields->field( 'panel', array(
					'type' => 'checkbox',
					'wrap_classes' => 'md-sep-micro',
					'options' => array(
						'global' => __( 'Enable on all pages', 'md' )
					)
				) ); ?>

				<?php foreach ( $page_types as $type => $label ) : ?>
				<div class="md-flex-columns md-sep-micro">
					<div class="md-flex-column">
						<?php $this->fields->field( "panel_{$type}_show", array(
							'type' => 'checkbox',
							'inline' => true,
							'options' => array(
								'enable' => sprintf( __( 'Enable on <strong>%s</strong>', 'md' ), $label ),
								'disable' => sprintf( __( 'Disable on <strong>%s</strong>', 'md' ), $label ),
							)
						) ); ?>
					</div>
					<div class="md-flex-column md-full-select">
						<?php $this->fields->field( "panel_$type", array(
							'type' => 'select',
							'empty_label' => __( 'Use Main panel', 'md' ),
							'options' => $panel['areas']
						) ); ?>
					</div>
				</div>
				<?php endforeach; ?>

			<?php else :
				if ( $panel['has'] )
					$this->fields->field( 'panel', array(
						'type' => 'checkbox',
						'options' => array(
							'remove' => __( 'Remove <b>Panel</b>', 'md' )
						)
					) );
				else
					$this->fields->field( 'panel', array(
						'type' => 'checkbox',
						'options' => array(
							'add' => __( 'Add <b>Main Panel</b>', 'md' )
						)
					) );
				?>

				<?php if ( ! empty( $panel['areas'] ) ) : ?>

				<div id="panel_options" style="display: <?php echo $panel['display']; ?>;">

					<?php $this->fields->field( 'panel', array(
						'type' => 'checkbox',
						'wrap_classes' => 'md-sep-micro',
						'options' => array(
							'alt' => __( 'Flip direction', 'md' )
						)
					) ); ?>

					<div id="<?php echo $this->_id; ?>_custom_panel_option">
						<?php $this->fields->field( 'custom_panel', array(
							'type' => 'select',
							'empty_label' => __( 'Use Main Panel', 'md' ),
							'wrap_classes' => 'md-sep-micro',
							'options' => $panel['areas']
						) ); ?>
					</div>

					<?php if ( $is_term )
						$this->fields->field( 'entries_panel', array(
							'type' => 'select',
							'empty_label' => __( 'Posts in this category...', 'md' ),
							'wrap_classes' => 'md-sep-micro',
							'options' => $panel['areas']
						) ); ?>

					<p class="description"><?php echo sprintf( __( '<a href="%s" target="_blank">Edit Custom Panels</a>', 'md' ), admin_url( 'admin.php?page=md_settings' ) ); ?></p>

				</div>

				<?php endif; ?>

			<?php endif; ?>

		</div>

		</div>







	</div>

</div>