<?php

$has_category_icon = ! empty( $args['category_icon'] );

if ( $has_category_icon ) {
	echo '<div class="category-title-wrap fl items-start">';
	echo '<span class="category-title-icon square-icon mid" aria-hidden="true">' . md_icon( $args['category_icon'] ) . '</span>';
	echo '<div class="title-wrap' . ( $has_inside ? ' title-inline' : '' ) . '">';
}
elseif ( isset( $args['wrap'] ) || $has_inside )
	echo '<div class="title-wrap' . ( $has_inside ? ' title-inline' : '' ) . '">';

if ( isset( $args['byline'] ) )
	md_byline( 'before_title', $args );

do_action( "md_hook_before_{$context}_title" );

if ( ! empty( $title ) )
	echo "<{$h} class=\"title\">" . $title . "</{$h}>";

do_action( "md_hook_after_{$context}_title" );

if ( isset( $args['byline'] ) ) {
	md_byline( 'after_title', $args );
	md_byline( 'inside_title', $args );
}

if ( $has_category_icon )
	echo '</div></div>';
elseif ( isset( $args['wrap'] ) || $has_inside )
	echo '</div>';

if ( isset( $args['description'] ) )
	md_description( $context );

if ( isset( $args['cta'] ) )
	md_cta( $context );
