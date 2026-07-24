<div class="md-header-settings md-content-wrap">

	<h2 class="md-title"><?php echo __( 'Header', 'md' ); ?></h2>

	<p><?php echo __( 'Customize your header with the header builder and layout options.', 'md' ); ?></p>

	<hr class="md-sep-small" />

	<div class="md-widget md-toggle">

		<h3 class="md-widget-title"><?php echo __( 'Settings', 'md' ); ?></h3>

		<div class="md-widget-item">

			<div class="md-sep-small">
			<?php $this->fields->field( 'display', array(
				'type' => 'checkbox',
				'label' => __( 'Display', 'md' ),
				'multi' => true,
				'options' => array(
					'sticky' => __( 'Use <strong>Sticky</strong> Header', 'md' ),
					'site_title' => __( 'Remove <strong>Title</strong>', 'md' ),
					'site_tagline' => __( 'Remove <strong>Tagline</strong>', 'md' ),
					'hide_title_mobile' => __( 'Hide <strong>Title</strong> on mobile', 'md' ),
					'hide_tagline_mobile' => __( 'Hide <strong>Tagline</strong> on mobile', 'md' ),
					'align_tagline' => __( 'Show Title and Tagline on one line', 'md' ),
					'align_logo' => __( 'Show Logo on one line', 'md' )
				)
			) ); ?>
			</div>

			<div class="md-header-layout md-radio-fields md-clear md-sep-small">
			<?php $this->fields->field( 'layout', array(
				'type' => 'radio',
				'label' => __( 'Layout', 'md' ),
				'label_icon' => 'dashicons dashicons-desktop',
				'layout' => 'banner',
				'options' => array(
					'left' => array(
						'name' => __( 'Header Left', 'md' ),
						'description' => __( 'The default header layout with a left-aligned logo and nav menu to the right.', 'md' ),
						'image' => MD_URL . 'admin/images/header-standard.gif'
					),
					'right' => array(
						'name' => __( 'Header Right', 'md' ),
						'description' => __( 'A reversed header layout with a right-aligned logo and nav menu to the left.', 'md' ),
						'image' => MD_URL . 'admin/images/header-rtl.gif'
					),
					'center' => array(
						'name' => __( 'Header Center', 'md' ),
						'description' => __( 'A logo aligned to the center between two outer nav areas.', 'md' ),
						'image' => MD_URL . 'admin/images/header-flyer.gif'
					)
				)
			) ); ?>
			</div>

			<div class="md-radio-fields md-clear md-sep-micro">
			<?php $this->fields->field( 'layout_mobile', array(
				'type' => 'radio',
				'label' => __( 'Mobile Layout', 'md' ),
				'label_icon' => 'dashicons dashicons-smartphone',
				'layout' => 'banner',
				'options' => array(
					'standard' => array(
						'name' => __( 'Default', 'md' ),
						'description' => __( 'The default layout with the logo on the left and controls to the right.', 'md' ),
						'image' => MD_URL . 'admin/images/header-mobile-standard.gif'
					),
					'expanded' => array(
						'name' => __( 'Expanded', 'md' ),
						'description' => __( 'Moves the main nav control before the Logo, and other controls to the right.', 'md' ),
						'image' => MD_URL . 'admin/images/header-mobile-expanded.gif'
					)
				)
			) ); ?>
			</div>

		</div>

	</div>

	<?php $this->fields->field( 'builder', array(
		'type' => 'builder',
		'wrap_classes' => 'md-tabs',
		'tabs' => array(
			'header' => __( 'Header', 'md' )
		),
		'active_tab' => 'header',
		'areas' => array(
			'primary' => array(
				'title' => __( 'Header', 'md' ),
				'description' => __( 'The main branding and navigation area at the top of every page.', 'md' ),
				'tab' => 'header'
			),
			'aside' => array(
				'title' => __( 'Header Aside', 'md' ),
				'description' => __( 'A secondary content area for the header area.', 'md' ),
				'tab' => 'header'
			)
		),
		'elements' => apply_filters( 'md_header_builder_elements', array(
			'link' => array(
				'title' => __( 'Link', 'md' ),
				'subtitle' => true,
				'color' => '#2772af',
				'icon' => 'admin-links',
				'callback' => array( $this->fields, 'builder_link' )
			),
			'search' => array(
				'title' => __( 'Search', 'md' ),
				'placeholder' => __( 'Search', 'md' ),
				'color' => '#41b141',
				'icon' => 'search',
				'callback' => array( $this->fields, 'builder_search' )
			),
			'menu' => array(
				'title' => __( 'Menu', 'md' ),
				'icon' => 'menu',
				'callback' => array( $this->fields, 'builder_menu' )
			)
		) )
	) );

	$this->fields->save(); ?>

</div>
