<?php

$lede = $cta['cta_lede'] ?? '';
$heading = $cta['cta_title'] ?? '';
$text = $cta['cta_text'] ?? '';
$foot = $cta['cta_foot'] ?? '';
$is_box = $lede || $heading || $text || $foot;

do_action( "md_hook_before_{$context}_cta" );

echo '<div class="' . esc_attr( $is_box ? 'cta cta-box' : "cta cta-$type" ) . '">';

do_action( "md_hook_{$context}_cta_top" );

$lede_html = $lede ? '<p class="lede">' . wp_kses_post( $lede ) . '</p>' : '';
$heading_html = $heading ? '<p class="title h5">' . wp_kses_post( $heading ) . '</p>' : '';

if ( $lede && $heading )
	echo '<div class="title-wrap">' . $lede_html . $heading_html . '</div>';
else
	echo $lede_html . $heading_html;

if ( $text )
	echo '<div class="cta-text">' . wpautop( wp_kses_post( $text ) ) . '</div>';

echo $is_box ? '<div class="' . esc_attr( "cta-$type" ) . '">' . $html . '</div>' : $html;

if ( $foot )
	echo '<p class="foot">' . wp_kses_post( $foot ) . '</p>';

do_action( "md_hook_{$context}_cta_bottom" );

echo '</div>';

do_action( "md_hook_after_{$context}_cta" );
