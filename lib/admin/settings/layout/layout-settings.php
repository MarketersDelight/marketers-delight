<!-- Header -->

<div class="md-sep-small">
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
				'tagline' => __( 'Remove <b>Tagline</b>', 'md' ),
				'menu' => __( 'Remove <b>Menu</b>', 'md' )
			)
		) ); ?>
		<div id="header_menu_options" style="display: <?php echo empty( $header['menu'] ) ? 'block' : 'none'; ?>; margin-top: 10px;">
			<?php $this->fields->field( 'header_menu', array(
				'type' => 'select',
				'empty_label' => __( 'Select custom menu...', 'md' ),
				'options' => $menus
			) ); ?>
		</div>
	</div>
</div>

<?php if ( has_nav_menu( 'main' ) ) : ?>

	<!-- Main Menu -->

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

<!-- Content Box -->

<div class="md-sep-small">
	<?php $this->fields->field( 'content', array(
		'type' => 'checkbox',
		'label' => __( 'Content Box', 'md' ),
		'options' => array(
			'remove' => __( 'Remove <b>Content Box</b>', 'md' ),
		)
	) ); ?>
	<div id="content_options" style="display: <?php echo empty( $content['remove'] ) ? 'block' : 'none'; ?>;">
		<?php if ( md_setting( array( 'content', 'post', 'breadcrumbs' ) ) ) : ?>
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
		<?php if ( $screen->base == 'term' && md_setting( array( 'loop' ) ) !== 'teasers' ) : ?>
			<?php $this->fields->field( 'content', array(
				'type' => 'checkbox',
				'options' => array(
					'teasers' => __( 'Enable <b>Teasers</b>', 'md' )
				)
			) ); ?>
		<?php endif; ?>
		<?php if ( $screen->base !== 'term' ) : ?>
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
				<?php if ( is_singular( 'post' ) && ! empty( $author_box ) ) : ?>
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
			'options' => array(
				'' => __( 'Select content box layout&hellip;', 'md' ),
				'content_sidebar' => __( 'Content / Sidebar (default)', 'md' ),
				'sidebar_content' => __( 'Sidebar / Content', 'md' ),
			)
		) ); ?>
	</div>
</div>

<!-- Sidebar -->

<div class="md-sep-small">
	<?php if ( ( $screen->base == 'term' && ! empty( $sidebar_category ) ) || ( $screen->post_type != 'page' && ! empty( $sidebar_single ) ) ) : ?>
		<?php $this->fields->field( 'sidebar', array(
			'type' => 'checkbox',
			'label' => __( 'Sidebar', 'md' ),
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
	<div id="sidebar_options" style="display: <?php echo $sidebar_display; ?>;">
		<?php if ( ! empty( $sidebars ) ) : ?>
			<p id="<?php echo $this->_id; ?>_custom_sidebar_option">
				<?php $this->fields->field( 'custom_sidebar', array(
					'type' => 'select',
					'empty_label' => __( 'Select custom sidebar&hellip;', 'md' ),
					'options' => $sidebars
				) ); ?>
			</p>
		<?php else : ?>
			<p class="description"><?php echo sprintf( __( '<b>Tip:</b> to reuse sidebars across pages, <a href="%s" target="_blank">add new Sidebars here</a> and come back and add it to this page.', 'md' ), admin_url( 'widgets.php' ) ); ?></p>
		<?php endif; ?>
	</div>
	<?php if ( $screen->base == 'term' && ! empty( $sidebars ) ) : ?>
		<p>
			<?php $this->fields->field( 'entries_sidebar', array(
				'type' => 'select',
				'empty_label' => __( 'Select sidebar for posts in this category&hellip;', 'md' ),
				'options' => $sidebars
			) ); ?>
		</p>
	<?php endif; ?>
</div>

<!-- Footer -->

<div class="md-sep-small">
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