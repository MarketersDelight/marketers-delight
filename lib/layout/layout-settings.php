<div class="columns-3 columns-half">

	<div class="col">

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

		<?php if ( has_nav_menu( 'main' ) ) : ?>

			<div class="md-sep-small">
				<?php $this->fields->field( 'main_menu', array(
					'type' => 'checkbox',
					'label' => __( 'Main Menu', 'md' ),
					'options' => array(
						'remove' => __( 'Remove <b>Main Menu</b>', 'md' )
					)
				) ); ?>
				<div id="main_menu_options" style="display: <?php echo empty( $main_menu['remove'] ) ? 'block' : 'none'; ?>; margin-top: 10px;">
					<?php $this->fields->field( 'main_menu_menu', array(
						'type' => 'select',
						'empty_label' => __( 'Select a custom menu...', 'md' ),
						'options' => $menus
					) ); ?>
				</div>
			</div>

		<?php endif; ?>

	</div>

	<!-- Content Box -->

	<div class="col md-sep-small">

		<?php do_action( "md_layout_{$post_base}_before_content_options" ); ?>

		<?php $this->fields->field( 'content', array(
			'type' => 'checkbox',
			'label' => __( 'Content Box', 'md' ),
			'options' => array(
				'remove' => __( 'Remove <b>Content Box</b>', 'md' ),
			)
		) ); ?>

		<div id="content_options" style="display: <?php echo empty( $content['remove'] ) ? 'block' : 'none'; ?>;">

			<?php if ( md_setting( array( 'colors', 'breadcrumbs', 'position' ) ) ) : ?>
				<?php $this->fields->field( 'breadcrumbs', array(
					'type' => 'checkbox',
					'options' => array( 'remove' => __( 'Remove <b>Breadcrumbs</b>', 'md' ) )
				) ); ?>
			<?php else : ?>
				<?php $this->fields->field( 'breadcrumbs', array(
					'type' => 'checkbox',
					'options' => array( 'add' => __( 'Add <b>Breadcrumbs</b>', 'md' ) )
				) ); ?>
			<?php endif; ?>

			<?php do_action( 'md_layout_content_options' ); ?>

			<?php if ( in_array( $screen_base, array( 'post', 'post-new' ) ) ) : ?>

				<?php $this->fields->field( 'content', array(
					'type' => 'checkbox',
					'options' => array(
						'headline' => __( 'Remove <b>Headline</b>', 'md' )
					)
				) ); ?>

				<?php if ( $screen->post_type !== 'page' ) : ?>
					<div id="headline_options" style="display: <?php echo empty( $content['headline'] ) ? 'block' : 'none'; ?>;">
						<?php $this->fields->field( 'content', array(
							'type' => 'checkbox',
							'options' => array(
								'byline' => __( 'Remove <b>Byline</b>', 'md' ),
							)
						) ); ?>
					</div>
					<?php if ( ! empty( $author_box ) ) : ?>
						<?php $this->fields->field( 'content', array(
							'type' => 'checkbox',
							'options' => array(
								'author_box' => __( 'Remove <b>Author Box</b>', 'md' )
							)
						) ); ?>
					<?php else : ?>
						<?php $this->fields->field( 'content', array(
							'type' => 'checkbox',
							'options' => array(
								'add_author_box' => __( 'Add <b>Author Box</b>', 'md' )
							)
						) ); ?>
					<?php endif; ?>

				<?php else : ?>

					<div id="headline_options" style="display: <?php echo empty( $content['headline'] ) ? 'block' : 'none'; ?>;">
						<?php $this->fields->field( 'content', array(
							'type' => 'checkbox',
							'options' => array(
								'add_byline' => __( 'Add <b>Byline</b>', 'md' ),
							)
						) ); ?>
					</div>

				<?php endif; ?>
			<?php endif; ?>

			<?php $this->fields->field( 'content_box', array(
				'type' => 'select',
				'empty_label' => __( 'Content / Sidebar', 'md' ),
				'options' => array(
					'sidebar_content' => __( 'Sidebar / Content', 'md' ),
				)
			) ); ?>

		</div>

	</div>

	<!-- Sidebar -->

	<div class="col md-sep-small">

		<?php $this->fields->label( 'sidebar', array( 'label' => __( 'Sidebar', 'md' ) ) ); ?>

		<?php if ( $has_sidebar ) : ?>
			<?php $this->fields->field( 'sidebar', array(
				'type' => 'checkbox',
				'options' => array(
					'remove' => __( 'Remove <b>Sidebar</b>', 'md' ),
				)
			) ); ?>
		<?php else : ?>
			<?php $this->fields->field( 'sidebar', array(
				'type' => 'checkbox',
				'options' => array(
					'add' => __( 'Add <b>Main Sidebar</b>', 'md' )
				)
			) ); ?>
		<?php endif; ?>

		<?php if ( ! empty( $sidebars ) ) : ?>

			<div id="sidebar_options" style="display: <?php echo $sidebar_display; ?>;">

				<p id="<?php echo $this->_id; ?>_custom_sidebar_option">
					<?php $this->fields->field( 'custom_sidebar', array(
						'type' => 'select',
						'empty_label' => __( 'Choose a sidebar&hellip;', 'md' ),
						'options' => $sidebars
					) ); ?>
				</p>

				<?php if ( $screen_base == 'term' ) : ?>
					<p>
						<?php $this->fields->field( 'entries_sidebar', array(
							'type' => 'select',
							'empty_label' => __( 'Posts in this category...', 'md' ),
							'options' => $sidebars
						) ); ?>
					</p>
				<?php endif; ?>

				<p class="description"><?php echo sprintf( __( '<a href="%s" target="_blank">Edit Custom Sidebars</a>', 'md' ), admin_url( 'admin.php?page=md_settings&tab=md_sidebars' ) ); ?></p>

			</div>

		<?php endif; ?>

	</div>

	<!-- Footer -->

	<div class="col">

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
					'columns' => __( 'Remove <b>Footer Columns</b>', 'md' )
				)
			) ); ?>
		</div>

	</div>

</div>
