<?php

$header = md_get_builder( 'header' );
$mobile = md_setting( array( 'header', 'layout_mobile' ) );

md_template( 'head' );

md_hook_before_html();

if ( md_has_header() ) : md_hook_before_header(); ?>

<header class="<?php echo md_header_classes(); ?>">

	<?php md_hook_header_top(); ?>

	<div class="inner">

		<div class="header-controls"><?php

			if ( md_has_menu() && $mobile == 'expanded' )
				md_trigger();

			if ( md_has_logo() )
				md_logo();

			echo '<div class="header-triggers">';

			if ( ! empty( $header['elements']['search'] ) )
				md_trigger( 'search', array( 'title' => __( 'Search', 'md' ) ) );

			if ( md_has_menu() && $mobile !== 'expanded' )
				md_trigger();

			if ( ! empty( $header['elements']['link'] ) )
				foreach ( $header['elements']['link'] as $c => $link_id ) {
					$link = md_setting( array( 'header', 'builder', $link_id ) );
					md_link( $link );
				}

			do_action( 'md_hook_header_triggers' );

			echo '</div>';

		?></div>

		<?php // Display Primary and Aside Header Sections
			$fields = md_setting( array( 'header', 'builder' ), array() );
			foreach ( array( 'primary', 'aside' ) as $section ) {
				if ( empty( $header['data'][$section] ) )
					continue;

				echo '<div class="header-' . esc_attr( $section ) . '">';

				foreach ( $header['data'][$section] as $order => $items ) {
					$type = esc_attr( $items['type'] );
					$id = esc_attr( $items['id'] );

					if ( ! empty( $fields[$id] ) ) {
						$fields[$id]['location'] = $section;
						$fields[$id]['layout'] = md_setting( array( 'header', 'layout' ), 'standard' );
						$fields[$id]['id'] = $id;

						call_user_func( 'md_' . esc_attr( $type ), $fields[$id] );
					}
				}

				do_action( 'md_hook_' . esc_attr( $section ) );

				echo '</div>';
		} ?>

		<?php if ( empty( $header['data'] ) ) : ?>
		<div class="header-primary">
			<?php md_menu(); ?>
		</div>
		<?php endif; ?>

		<?php md_hook_after_header(); ?>

	</div>

	<?php md_hook_header_bottom(); ?>

</header>

<?php md_hook_after_header(); endif;

if ( md_filter_template() !== false )
	md_hook_before_content_box();