<div class="columns-4 columns-single">

	<div class="col md-sep-small">

		<!-- Header -->

		<?php $this->fields->field( 'header', array(
			'type' => 'checkbox',
			'label' => __( 'Header', 'md' ),
			'options' => array(
				'remove' => __( 'Remove <b>Header</b>', 'md' )
			)
		) ); ?>

		<div id="header_options" style="display: <?php echo ! empty( $header['remove'] ) ? 'none' : 'block'; ?>;">

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

		<?php do_action( "md_layout_{$hook}_after_header" ); ?>

	</div>

	<!-- Content Box -->

	<div class="col">

		<p class="md-label-wrap"><label class="md-label"><?php echo __( 'Content Box', 'md' ); ?></label></p>

		<?php if ( $is_admin ) : ?>
			<?php $this->fields->field( 'content', array(
				'type' => 'checkbox',
				'options' => array(
					'page_title' => __( 'Show <strong>Page Title</strong> inline', 'md' ),
				)
			) ); ?>
		<?php endif; ?>

		<?php $this->fields->field( 'content', array(
			'type' => 'checkbox',
			'options' => array(
				'remove' => __( 'Remove <b>Content Box</b>', 'md' ),
			)
		) ); ?>

		<div id="content_options" style="display: <?php echo empty( $content['remove'] ) ? 'block' : 'none'; ?>;">

			<?php if ( $post_type !== 'page' ) : ?>
				<?php $this->fields->field( 'breadcrumbs', array(
					'type' => 'checkbox',
					'options' => $breadcrumbs_options
				) ); ?>
			<?php endif; ?>

			<?php if ( $is_edit ) : ?>

				<?php $this->fields->field( 'content', array(
					'type' => 'checkbox',
					'options' => array(
						'headline' => __( 'Remove <b>Headline</b>', 'md' )
					)
				) ); ?>

				<?php if ( $post_type !== 'page' ) : ?>

					<div id="headline_options" style="display: <?php echo empty( $content['headline'] ) ? 'block' : 'none'; ?>;">
						<?php $this->fields->field( 'content', array(
							'type' => 'checkbox',
							'options' => array(
								'byline' => __( 'Remove <b>Byline</b>', 'md' ),
							)
						) ); ?>
					</div>

					<?php if ( $author_box ) : ?>

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

					<?php $this->fields->field( 'content', array(
						'type' => 'checkbox',
						'options' => $post_nav_options
					) ); ?>

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

			<div class="md-sep-micro">
				<?php do_action( 'md_post_layout_content_options' ); ?>
			</div>

			<?php $this->fields->field( 'content_box', array(
				'type' => 'select',
				'empty_label' => __( 'Content / Sidebar', 'md' ),
				'wrap_classes' => 'md-sep-micro',
				'options' => array(
					'sidebar_content' => __( 'Sidebar / Content', 'md' )
				)
			) ); ?>

			<?php $this->fields->field( 'content_box_style', array(
				'type' => 'select',
				'empty_label' => __( 'Use default style', 'md' ),
				'wrap_classes' => 'md-sep-micro',
				'options' => array(
					'box_style' => __( 'Box style', 'md' ),
					'minimal' => __( 'Simple style', 'md' )
				)
			) ); ?>

			<?php if ( $is_edit ) : ?>
				<?php $this->fields->field( 'content', array(
					'type' => 'checkbox',
					'options' => array(
						'full' => __( 'Show Full-Width', 'md' ),
					)
				) ); ?>
			<?php endif; ?>

		</div>

	</div>

	<?php if ( ! $is_admin ) : ?>

	<!-- Sidebar -->

	<div id="sidebar_fields" class="col md-sep-small" style="display: <?php echo empty( $content['remove'] ) ? 'block' : 'none'; ?>;">

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

				<div id="<?php echo $this->_id; ?>_custom_sidebar_option">
					<?php $this->fields->field( 'custom_sidebar', array(
						'type' => 'select',
						'empty_label' => __( 'Choose a sidebar&hellip;', 'md' ),
						'wrap_classes' => 'md-sep-micro',
						'options' => $sidebars
					) ); ?>
				</div>

				<?php if ( $screen_base == 'term' ) : ?>
					<?php $this->fields->field( 'entries_sidebar', array(
						'type' => 'select',
						'empty_label' => __( 'Posts in this category...', 'md' ),
						'wrap_classes' => 'md-sep-micro',
						'options' => $sidebars
					) ); ?>
				<?php endif; ?>

				<p class="description"><?php echo sprintf( __( '<a href="%s" target="_blank">Edit Custom Sidebars</a>', 'md' ), admin_url( 'admin.php?page=md_settings&tab=md_sidebars' ) ); ?></p>

			</div>

		<?php endif; ?>

	</div>

	<?php endif; ?>

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
					'columns' => __( 'Remove <b>Columns</b>', 'md' )
				)
			) ); ?>

		</div>

	</div>

</div>
