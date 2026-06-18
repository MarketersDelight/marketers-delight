<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open();

md_hook_before_html();

if ( md_has_header() ) :
	$header = md_get_builder( 'header' );
	$mobile = md_setting( array( 'header', 'layout_mobile' ) );
	$layout = md_setting( array( 'header', 'layout' ), 'standard' );
	$has_elements = md_has_header_elements();
?>

<header class="<?php echo md_header_classes(); ?>">

	<?php md_hook_header_top(); ?>

	<div class="inner">

		<?php md_hook_before_header(); ?>

		<div class="header-controls"><?php // Contains logo, mobile triggers

			if ( md_has_panel() )
				md_trigger( 'panel', array(
					'parent' => 'has-panel',
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

			if ( $has_elements ) {

				echo '<div class="header-triggers">';

				if ( ! empty( $header['elements']['search'] ) )
					md_trigger( 'search', array(
						'title' => __( 'Search', 'md' ),
						'builder' => $header
					) );

				if ( ( md_has_menu() && $mobile !== 'expanded' ) || md_has_panel() )
					md_trigger( 'menu', array( 'builder' => $header ) );

				if ( ! empty( $header['elements']['link'] ) )
					foreach ( $header['elements']['link'] as $c => $link_id )
						md_link( $header['fields'][$link_id] ?? array() );

				md_hook_header_triggers();

				echo '</div>';

			}

		?></div>

		<?php if ( $has_elements ) // Render Main and Aside sections with inner elements
			foreach ( array_keys( $header['data'] ) as $section ) {
				if ( empty( $header['data'][$section] ) )
					continue;

				echo "<div class=\"header-$section\">";

				foreach ( $header['data'][$section] as $order => $items ) {
					$type = esc_attr( $items['type'] );
					$id = esc_attr( $items['id'] );
					$field = $header['fields'][$id] ?? null;

					if ( $field ) {
						$field['location'] = $section;
						$field['layout'] = $layout;
						$field['id'] = $id;

						call_user_func( "md_$type", $field );
					}
				}

				do_action( "md_hook_$section" );

				echo '</div>';
		} ?>

		<?php if ( empty( $header['data'] ) )
			md_menu( array( 'wrap' => 'header-primary' ) );

		md_hook_after_header(); ?>

	</div>

	<?php md_hook_header_bottom(); ?>

</header>

<?php endif;

if ( md_filter_template() !== false )
	md_hook_before_content_box();