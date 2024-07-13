<div class="columns-4 columns-single">

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

			<?php if ( ! md_setting( array( 'logo', 'display', 'site_tagline' ) ) ) : ?>

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

		<?php
			$featured_image = array(
				'type' => 'select',
				'label' => __( 'Featured image', 'md' ),
				'wrap_classes' => 'md-sep-small',
				'options' => $sanitize->values['featured_image']
			);

			if ( $is_post )
				$featured_image['empty_label'] = __( 'Use default position', 'md' );

			$this->fields->field( 'featured_image', $featured_image );

			do_action( 'md_layout_' . ( $is_post ? 'post' : $screen_base ) . '_after_header' );

			if ( $is_admin )
				$this->footer_fields();
		?>

	</div>

	<!-- Content -->

	<div class="col">

		<p class="md-label-wrap"><label class="md-label"><?php echo __( 'Content', 'md' ); ?></label></p>

		<?php if ( ! $is_post ) : ?>
			<?php $this->fields->field( 'content', array(
				'type' => 'checkbox',
				'options' => array(
					'hero_inline' => __( 'Show <strong>Hero</strong> inline', 'md' ),
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

			<?php
				if ( $post_type !== 'page' )
					$this->fields->field( 'breadcrumbs', array(
						'type' => 'checkbox',
						'options' => $breadcrumbs_options
					) );

				if ( $is_post ) {
					$this->fields->field( 'content', array(
						'type' => 'checkbox',
						'options' => array(
							'headline' => __( 'Remove <b>Headline</b>', 'md' )
						)
					) );

					if ( $post_type !== 'page' ) {
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
				}

				$this->fields->field( 'content_box', array(
					'type' => 'select',
					'empty_label' => __( 'Content / Sidebar', 'md' ),
					'wrap_classes' => 'md-sep-micro',
					'options' => array(
						'sidebar_content' => __( 'Sidebar / Content', 'md' )
					)
				) );

				if ( ! md_setting( array( 'colors', 'design', 'box_style' ) ) )
					$this->fields->field( 'content', array(
						'type' => 'checkbox',
						'options' => array(
							'box_style' => __( 'Disable <strong>Box style</strong>', 'md' )
						)
					) );

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

		</div>

		<?php if ( $is_admin )
			$this->fields->field( 'content', array(
				'type' => 'checkbox',
				'label' => __( 'Single', 'md' ),
				'options' => array(
					'add_author_box' => __( 'Add <b>Author Box</b>', 'md' ),
					'post_nav' => __( 'Remove <b>Post Nav</b>', 'md' ),
				)
			) );
		?>

	</div>

	<!-- Sidebar -->

	<div id="sidebar_fields" class="<?php echo esc_attr( $sidebar_classes ); ?>" style="display: <?php echo empty( $content['remove'] ) ? 'block' : 'none'; ?>;">

		<?php $this->fields->label( 'sidebar', array( 'label' => __( 'Sidebar', 'md' ) ) ); ?>

		<?php if ( $is_admin ) : ?>

			<?php $this->fields->field( 'sidebar', array(
				'type' => 'checkbox',
				'wrap_classes' => 'md-sep-micro',
				'options' => array(
					'global' => __( 'Enable on all pages', 'md' )
				)
			) ); ?>

			<?php foreach ( $page_types as $type => $label ) : ?>
				<div class="columns-2 md-sep-micro">
					<div class="col">
						<?php $this->fields->field( "sidebar_{$type}_show", array(
							'type' => 'checkbox',
							'inline' => true,
							'options' => array(
								'enable' => sprintf( __( 'Enable on <strong>%s</strong>', 'md' ), $label ),
								'disable' => sprintf( __( 'Disable on <strong>%s</strong>', 'md' ), $label ),
							)
						) ); ?>
					</div>
					<div class="col">
						<?php $this->fields->field( "sidebar_$type", array(
							'type' => 'select',
							'empty_label' => __( 'Use Main sidebar', 'md' ),
							'options' => $sidebars
						) ); ?>
					</div>
				</div>
			<?php endforeach; ?>

		<?php else : ?>

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
						'empty_label' => __( 'Use default sidebar', 'md' ),
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

		<?php endif; ?>

	</div>

	<?php if ( ! $is_admin ) : ?>
		<div class="col">
			<?php $this->footer_fields(); ?>
		</div>
	<?php endif; ?>

</div>
