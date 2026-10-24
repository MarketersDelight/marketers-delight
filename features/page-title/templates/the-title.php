<?php

$has_wrap = isset( $args['wrap'] ) || $lede;

if ( $has_category_icon ) {
	echo '<div class="category-title-wrap fl items-start">'.
		 '<span class="category-title-icon square-icon mid" aria-hidden="true">' . md_icon( $args['category_icon'] ) . '</span>'.
		 '<div class="title-wrap">';
}
elseif ( $has_wrap )
	echo '<div class="title-wrap">';

if ( $lede )
	echo '<p class="lede">' . $lede . '</p>';

if ( isset( $args['byline'] ) )
	md_byline( 'before_title', $args );

do_action( "md_hook_before_{$context}_title" );

// Inside Title items follow the title's last word and wrap with it

if ( $has_inside )
	echo '<div class="title-inline">';

if ( ! empty( $title ) )
	echo "<{$h} class=\"title{$h_class}\"{$h_style}>" . $title . "</{$h}>";

if ( $has_inside ) {
	md_byline( 'inside_title', $args );

	echo '</div>';
}

do_action( "md_hook_after_{$context}_title" );

if ( isset( $args['description'] ) ) {
	md_description( $context, isset( $args['data'] ) ? array( 'description' => $args['data']['description'] ) : array() );

	if ( isset( $args['byline'] ) )
		md_byline( 'after_title', $args );
}

if ( isset( $args['cta'] ) )
	md_cta( $context, isset( $args['data'] ) ? $args['data']['cta'] : array() );

if ( $has_category_icon )
	echo '</div>'.
		 '</div>';

elseif ( $has_wrap )
	echo '</div>';
