<?php

$item_loop = ! empty( $loop_base['own_look'] ) ? md_loop_own_look( $loop_base ) : $loop_base;
$item_c = $c;
$item_template = $loop_template ?? '';
$own_wrap = ! empty( $item_loop['own_wrap'] );

if ( $own_wrap ) {
	$item_c = 1;
	$item_template = $item_loop['loop'];
	$item_args = array(
		'loop' => $item_loop,
		'query' => $item_loop['query']
	);

	md_hook_loop_before( $item_args );

	echo '<div class="' . esc_attr( $item_loop['loop_classes'] ) . '"' . md_loop_columns_style( $item_loop ) . '>';
}

$loop = $args['loop'] = md_loop_item( $item_loop, $item_c );
$classes = md_post_class( $loop, $item_c );

if ( $item_template && ! empty( $loops[$item_template]['template'] ) )
	include esc_attr( $loops[$item_template]['template'] );
else
	include md_template( 'features', 'loop/loop', true );

if ( $own_wrap ) {
	echo '</div>';

	md_hook_loop_after( $item_args );
}

if ( $loop['loop_type'] !== 'category_posts' )
	md_hook_x_loop( $loop, $c );

$c++;
