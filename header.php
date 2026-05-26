<?php md_template( 'head' );

md_hook_before_html();

if ( md_has_header() ) :
	$header = md_get_builder( 'header' );
	$mobile = md_setting( array( 'header', 'layout_mobile' ) );

	md_hook_before_header();
?>

<header class="<?php echo md_header_classes(); ?>">

	<?php md_hook_header_top(); ?>

	<div class="inner">

		<div class="header-controls"><?php

			if ( md_has_panel() )
				md_trigger( 'panel', array(
					'parent'  => 'has-panel',
					'location' => 'header',
					'icon' => 'sidebar',
					'hide_label' => true,
					'title' => __( 'Toggle Panel', 'md' ),
					'classes' => ! md_get_layout_toggle( array( 'sidebar', 'close' ) ) ?? 'toggled'
				) );
			elseif ( md_has_menu() && $mobile == 'expanded' )
				md_trigger( 'menu', array( 'builder' => $header ) );

			if ( md_has_logo() )
				md_logo();

			if ( md_has_header_elements() ) {

				echo '<div class="header-triggers">';

				if ( ! empty( $header['elements']['search'] ) )
					md_trigger( 'search', array(
						'title' => __( 'Search', 'md' ),
						'builder' => $header
					) );

				if ( ( md_has_menu() && $mobile !== 'expanded' ) || md_has_panel() )
					md_trigger( 'menu', array( 'builder' => $header ) );

				if ( ! empty( $header['elements']['link'] ) )
					foreach ( $header['elements']['link'] as $c => $link_id ) {
						$link = $header['fields'][$link_id] ?? array();
						md_link( $link );
					}

				md_hook_header_triggers();

				echo '</div>';

			}

		?></div>

		<?php if ( md_has_header_elements() )
			foreach ( array_keys( $header['data'] ) as $section ) {
				if ( empty( $header['data'][$section] ) )
					continue;

				echo '<div class="header-' . esc_attr( $section ) . '">';

				foreach ( $header['data'][$section] as $order => $items ) {
					$type = esc_attr( $items['type'] );
					$id = esc_attr( $items['id'] );

					if ( ! empty( $header['fields'][$id] ) ) {
						$header['fields'][$id]['location'] = $section;
						$header['fields'][$id]['layout'] = md_setting( array( 'header', 'layout' ), 'standard' );
						$header['fields'][$id]['id'] = $id;

						call_user_func( 'md_' . esc_attr( $type ), $header['fields'][$id] );
					}
				}

				do_action( 'md_hook_' . esc_attr( $section ) );

				echo '</div>';
		} ?>

		<?php if ( empty( $header['data'] ) )
			md_menu( array( 'wrap' => 'header-primary' ) );

		md_hook_after_header(); ?>

	</div>

	<?php md_hook_header_bottom(); ?>

</header>

<?php md_hook_after_header(); endif;

if ( md_filter_template() !== false )
	md_hook_before_content_box();