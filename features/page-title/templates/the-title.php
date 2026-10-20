<?php

$has_wrap = isset( $args['wrap'] );

if ( $has_category_icon ) {
	echo '<div class="category-title-wrap fl items-start">'.
		 '<span class="category-title-icon square-icon mid" aria-hidden="true">' . md_icon( $args['category_icon'] ) . '</span>'.
		 '<div class="title-wrap' . ( $has_inside && ! $has_wrap ? ' title-inline' : '' ) . '">';
}
elseif ( $has_wrap || $has_inside )
	echo '<div class="title-wrap' . ( $has_inside && ! $has_wrap ? ' title-inline' : '' ) . '">';

// Title images stack the description, byline and CTA under the title, so an inline byline gets its own row

if ( $has_wrap && $has_inside )
	echo '<div class="title-inline fl">';

if ( isset( $args['byline'] ) )
	md_byline( 'before_title', $args );

do_action( "md_hook_before_{$context}_title" );

if ( ! empty( $title ) )
	echo "<{$h} class=\"title{$h_class}\">" . $title . "</{$h}>";

do_action( "md_hook_after_{$context}_title" );

if ( isset( $args['byline'] ) )
	md_byline( 'inside_title', $args );

if ( $has_wrap && $has_inside )
	echo '</div>';

if ( isset( $args['description'] ) ) {
	md_description( $context );

	if ( isset( $args['byline'] ) )
		md_byline( 'after_title', $args );
}

if ( isset( $args['cta'] ) )
	md_cta( $context );

if ( $has_category_icon )
	echo '</div>'.
		 '</div>';

elseif ( $has_wrap || $has_inside )
	echo '</div>';
