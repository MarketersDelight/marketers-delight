<?php

$has_wrap = isset( $args['wrap'] ) || $lede;

if ( $has_category_icon ) {
	echo '<div class="category-title-wrap fl items-start">'.
		 '<span class="category-title-icon square-icon mid" aria-hidden="true">' . md_icon( $args['category_icon'] ) . '</span>'.
		 '<div class="title-wrap">';
}

elseif ( $has_wrap )
	echo '<div class="title-wrap">';

if ( isset( $args['regions'] ) )
	echo '<div class="title-main">';

if ( $lede )
	echo '<p class="lede">' . $lede . '</p>';

md_title_position( 'before_title', $context, $args );

do_action( "md_hook_before_{$context}_title" );

if ( $has_inside )
	echo '<div class="title-inline">';

if ( ! empty( $title ) )
	echo "<{$h} class=\"title{$h_class}\"{$h_style}>" . $title . "</{$h}>";

if ( $has_inside ) {
	md_title_meta( 'inside_title', $context, $args );

	echo '</div>';
}

do_action( "md_hook_after_{$context}_title" );

if ( isset( $args['description'] ) ) {
	md_description( $context, isset( $args['data'] ) ? array( 'description' => $args['data']['description'] ) : array() );

	md_title_position( 'after_title', $context, $args );
}

if ( isset( $args['regions'] ) ) {
	echo '</div><div class="title-aside">';

	md_title_position( 'byline_aside', $context, $args );

	echo '</div>';
}

if ( $has_category_icon )
	echo '</div></div>';

elseif ( $has_wrap )
	echo '</div>';
